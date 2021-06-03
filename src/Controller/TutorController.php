<?php
namespace App\Controller;
use App\Entity\Tuteur;
use App\Entity\Document;
use App\Entity\Etudiant;
use App\Entity\Personne;
use App\Form\DocumentType;
use App\Entity\TypeDocument;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TutorController extends AbstractController {
    public function listStudents(UserInterface $user){
        $personne = $this->getDoctrine()->getManager()->getRepository(Personne::class)
                        ->findOneByUsername($user->getUsername());
        $tuteur = $this->getDoctrine()->getManager()->getRepository(Tuteur::class)
                        ->findOneByPersonne($personne->getId());

        $listeEtudiants = $this->getDoctrine()->getRepository(Etudiant::Class)
            ->findByTuteur($tuteur->getId());

        return $this->render('tutor/students.html.twig', array(
            'etudiants' => $listeEtudiants
        ));
    }

    public function editStudent(Request $request, UserInterface $user, EntityManagerInterface $manager, $id){
        $etudiant = $this->getDoctrine()->getManager()->getRepository(Etudiant::Class)
            ->find($id);
        
        if(!$etudiant)
            throw $this->createNotFoundException('Etudiant[id='.$id.'] inexistant');

        $form = $this->createFormBuilder($etudiant)
            ->setAction($this->generateUrl('tutor_student',array('id' => $id)))
            ->add('noteSuivi')
            ->add('valider', SubmitType::class, array('label'=> 'modifier'))
            ->getForm();
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($etudiant);
            $manager->flush();

            $listeEtudiants = $this->getDoctrine()->getRepository(Etudiant::Class)->findAll();

            return $this->render('tutor/students.html.twig', array(
                'etudiants' => $listeEtudiants,
                'form' => $form->createView()
                )
            );
        }

        return $this->render('tutor/editStudent.html.twig', array(
            'etudiant' => $etudiant,
            'form' => $form->createView()
        ));
    }

    public function listDocuments(Request $request, UserInterface $user){
        $personne = $this->getDoctrine()->getManager()->getRepository(Personne::class)
            ->findOneByUsername($user->getUsername());
        $tuteur = $this->getDoctrine()->getManager()->getRepository(Tuteur::class)
            ->findOneByPersonne($personne);
        $documents = $this->getDoctrine()->getRepository(Document::class)
            ->findByTuteur($tuteur);

        /*$documents = [];

        foreach($etudiants as $etudiant) {
            $documentsEtudiant = $this->getDoctrine()->getRepository(Document::class)
                ->findByEtudiant($etudiant->getId());
            array_push($documents, $documentsEtudiant);
            die(print_r($documentsEtudiant));
        }*/

        //die(print_r($documents));

        /*$documents = $this->getDoctrine()->getManager()->getRepository(Document::class)
            ->findByEtudiant($etudiant->getId());*/

        return $this->render('tutor/documents.html.twig', array(
            'documents' => $documents
        ));
    }

    public function downloadDocument($id) {
        $document = $this->getDoctrine ()->getRepository (Document::class)->find($id);

        $fileName = $document->getIntitule().".pdf";
        $file_with_path = $this->getParameter('upload_directory')."/".$fileName;
        $response = new BinaryFileResponse($file_with_path);
        return $response;
        
        return $this->render('tutor/documents.html.twig', array(
            'documents' => $documents
        ));
    }

    public function addDocument(Request $request, EntityManagerInterface $manager, UserInterface $user){
        $personne = $this->getDoctrine()->getManager()->getRepository(Personne::class)
                        ->findOneByUsername($user->getUsername());
        $etudiant = $this->getDoctrine()->getManager()->getRepository(Etudiant::class)
                        ->findOneByPersonne($personne->getId());
    
        $document = new Document();
        $formDocument = $this->createForm(DocumentType::class, $document);
    
        $formDocument->handleRequest($request);
    
        if ($formDocument->isSubmitted() && $formDocument->isValid()) {
            $date = date('Y-m-d H:i:s');
    
            $type_document_texte = $formDocument->get('typeDocument2')->getData();
            if($type_document_texte == 1) {
                 $type_document = $this->getDoctrine()->getManager()->getRepository(TypeDocument::class)
                    ->findOneByIntitule('Fiche faisabilité');
                $intitule = "Fiche_faisabilite";
            } elseif ($type_document_texte == 2) {
                $type_document = $this->getDoctrine()->getManager()->getRepository(TypeDocument::class)
                    ->findOneByIntitule('Rapport');
                $intitule = "Rapport";
            } elseif ($type_document_texte == 3) {
                $type_document = $this->getDoctrine()->getManager()->getRepository(TypeDocument::class)
                    ->findOneByIntitule('Poster');
                $intitule = "Poster";
            } else {
                $type_document = $this->getDoctrine()->getManager()->getRepository(TypeDocument::class)
                    ->findOneByIntitule('Fiche d\'appréciation');
                $intitule = "Fiche_appreciation";
            }

            $etudiant_select = $formDocument->get('etudiant')->getData()->getPersonne()->getId();

            $etudiant2 = $this->getDoctrine()->getManager()->getRepository(Etudiant::class)
                        ->findOneByPersonne($etudiant_select);
    
            $intitule = $intitule."_".$etudiant2->getPersonne()->getNom()."_".$etudiant2->getPersonne()->getPrenom();
    
            $document->setTypeDocument($type_document);
            $document->setEtatDocument("à valider");
            $document->setIntitule($intitule);
            $document->setDepositaire($personne);
            $document->setDateDepot($date);
    
            $file = $formDocument->get('intitule')->getData();
    
            $fileName = $document->getIntitule().".".$file->guessExtension();
    
            $file->move($this->getParameter('upload_directory'), $fileName);
    
            $manager->persist($document);
            $manager->flush();
            return $this->redirectToRoute('tutor_document');
        }

        return $this->render('tutor/addDocument.html.twig', array(
            'formDocument' => $formDocument->createView()));
    }

    public function rateStudent(Request $request, EntityManagerInterface $manager, UserInterface $user){
        $personne = $this->getDoctrine()->getManager()->getRepository(Personne::class)
                        ->findOneByUsername($user->getUsername());
        $tuteur = $this->getDoctrine()->getManager()->getRepository(Tuteur::class)
                        ->findOneByPersonne($personne->getId());

        $listeEtudiants = $this->getDoctrine()->getRepository(Etudiant::Class)
            ->findByTuteur($tuteur->getId());

        $form = $this->createFormBuilder($tuteur)
            //->setAction($this->generateUrl('tutor_affect',array('id' => $id)))
            ->add('etudiants', EntityType::class, array(
                'class' => Etudiant::class,
                'choice_label' => function ($etudiant) {
                    return $etudiant->getPersonne()->getNom() . ' ' . $etudiant->getPersonne()->getPrenom();
                }
            ))
            ->add('noteTempo')
            ->add('valider', SubmitType::class, array('label'=> 'attribuer'))
            ->getForm();

        if ($form->isSubmitted() && $form->isValid()) {

            
            $manager->persist($tuteur);
            $manager->flush();
        }

        return $this->render('tutor/rate.html.twig', array(
            'form' => $form->createView(),
            'etudiants' => $listeEtudiants
        ));
    }
}