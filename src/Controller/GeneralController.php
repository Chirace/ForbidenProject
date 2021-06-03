<?php
namespace App\Controller;
use App\Entity\Tuteur;
use App\Entity\Document;
use App\Entity\Etudiant;
use App\Entity\Personne;
use App\Form\DocumentType;
use App\Entity\TypeDocument;
use App\Form\TypeDocumentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GeneralController extends AbstractController {
   public function accueil() {
       return $this->render('general/accueil.html.twig');
   }

   public function login(){
        return $this->render('responsableUE/accueil.html.twig');
   }

   public function logout() {}

   public function homeAdmin(){
       return $this->render('admin/accueil.html.twig');
   }

   public function homeExternalSpeaker(){
        return $this->render('externalSpeaker/accueil.html.twig');
    }

    public function homeRue(){
        return $this->render('responsableUE/accueil.html.twig');
    }

    public function homeScolarite(){
        return $this->render('scolarite/accueil.html.twig');
    }

    public function homeStudents(){
        return $this->render('student/accueil.html.twig');
    }

    public function homeTutors(){
        return $this->render('tutor/accueil.html.twig');
    }

    public function RUEFeasibility(){
        $listeEtudiants = $this->getDoctrine()->getRepository(Etudiant::Class)->findAll();

        return $this->render('responsableUE/feasibility.html.twig', array(
            'etudiants' => $listeEtudiants));
    }

    public function setting(Request $request, EntityManagerInterface $manager) {
        $listeTypeDocuments = $this->getDoctrine()->getRepository(TypeDocument::Class)->findAll();

        return $this->render('responsableUE/setting.html.twig', array(
            'typeDocuments' => $listeTypeDocuments
            )
        );
    }

    public function editSetting(Request $request, EntityManagerInterface $manager, $id){
        $TypeDocument = $this->getDoctrine()->getManager()->getRepository(TypeDocument::Class)
            ->find($id);

        if(!$TypeDocument)
            throw $this->createNotFoundException('TypeDocument[id='.$id.'] inexistant');

        $form = $this->createFormBuilder($TypeDocument)
            ->setAction($this->generateUrl('RUE_setting',array('id' => $id)))
            ->add('dateLimite', DateTimeType::class)
            ->add('delaiRelance')
            ->add('valider', SubmitType::class, array('label'=> 'modifier'))
            ->getForm();
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($TypeDocument);
            $manager->flush();

            $listeTypeDocuments = $this->getDoctrine()->getRepository(TypeDocument::Class)->findAll();

            return $this->render('responsableUE/setting.html.twig', array(
                'typeDocument' => $TypeDocument,
                'typeDocuments' => $listeTypeDocuments,
                'form' => $form->createView()
                )
            );
        }

        return $this->render('responsableUE/editSetting.html.twig', array(
            'typeDocument' => $TypeDocument,
            'form' => $form->createView()
            )
        );
    }

   public function listStudents(){
        $listeEtudiants = $this->getDoctrine()->getRepository(Etudiant::Class)->findAll();

        return $this->render('responsableUE/students.html.twig', array(
            'etudiants' => $listeEtudiants));
   }

   public function listTutors(){
        $listeTuteurs = $this->getDoctrine()->getRepository(Tuteur::Class)->findAll();
        
        return $this->render('responsableUE/tutors.html.twig', array(
        'tuteurs' => $listeTuteurs));
   }

   public function downloadDocument($id) {
        $document = $this->getDoctrine ()->getRepository (Document::class)->find($id);

        $fileName = $document->getIntitule().".pdf";
        $file_with_path = $this->getParameter('upload_directory')."/".$fileName;
        $response = new BinaryFileResponse($file_with_path);
        return $response;
        
        return $this->render('responsableUE/documents.html.twig', array(
            'documents' => $documents
        ));
    }

   public function commentTutor(Request $request, EntityManagerInterface $manager, $id){
        $tuteur = $this->getDoctrine()->getManager()->getRepository(Tuteur::Class)
            ->find($id);

        if(!$tuteur)
            throw $this->createNotFoundException('Tuteur[id='.$id.'] inexistant');

        $form = $this->createFormBuilder($tuteur)
            ->setAction($this->generateUrl('tutor_comment',array('id' => $id)))
            ->add('commentaire')
            ->add('valider', SubmitType::class, array('label'=> 'modifier'))
            ->getForm();
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($tuteur);
            $manager->flush();

            $listeTuteurs = $this->getDoctrine()->getRepository(Tuteur::Class)->findAll();

            return $this->render('responsableUE/tutors.html.twig', array(
                'tuteur' => $tuteur,
                'tuteurs' => $listeTuteurs,
                'form' => $form->createView()
                )
            );
        }
        
        return $this->render('responsableUE/commentTutor.html.twig', array(
            'tuteur' => $tuteur,
            'form' => $form->createView()
        ));
    }

   public function assignTutor(){
        $listeEtudiants = $this->getDoctrine()->getRepository(Etudiant::Class)->findAll();
        $listeTuteurs = $this->getDoctrine()->getRepository(Tuteur::Class)->findAll();

       return $this->render('responsableUE/assignTutor.html.twig', array(
        'etudiants' => $listeEtudiants,
        'tuteurs' => $listeTuteurs));
   }

    public function affectTutor(Request $request, EntityManagerInterface $manager, $id) {
        $etudiant = $this->getDoctrine()->getManager()->getRepository(Etudiant::class)
            ->find($id);

        $listeEtudiants = $this->getDoctrine()->getRepository(Etudiant::Class)->findAll();
        $listeTuteurs = $this->getDoctrine()->getRepository(Tuteur::Class)->findAll();

        $form = $this->createFormBuilder($etudiant)
            ->setAction($this->generateUrl('tutor_affect',array('id' => $id)))
            ->add('tuteur', EntityType::class, array(
                'class' => Tuteur::class,
                'choice_label' => function ($personne) {
                    return $personne->getPersonne()->getNom() . ' ' . $personne->getPersonne()->getPrenom();
                }
            ))
            ->add('valider', SubmitType::class, array('label'=> 'Affecter'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $etudiant->setTuteur($form->get('tuteur')->getData());

            $documents = $this->getDoctrine()->getRepository(Document::class)
                ->findByEtudiant($etudiant);
            
            foreach($documents as $document) {
                $document->setTuteur($etudiant->getTuteur());
                $manager->persist($document); 
            }
        
            //$etudiant->setTuteur($tuteur);
            $manager->persist($etudiant); 
            $manager->flush();

            return $this->render('responsableUE/assignTutor.html.twig', array(
                'form' => $form->createView(),
                'etudiants' => $listeEtudiants,
                'tuteurs' => $listeTuteurs));
        }

        return $this->render('responsableUE/affectTutor.html.twig', array(
            'form' => $form->createView(),
            'etudiant' => $etudiant,
            'etudiants' => $listeEtudiants,
            'tuteurs' => $listeTuteurs));
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
            return $this->redirectToRoute('RUE_add_document');
        }

        return $this->render('responsableUE/addDocument.html.twig', array(
            'formDocument' => $formDocument->createView()));
    }



    public function listDocuments(Request $request){
        $documents = $this->getDoctrine()->getManager()->getRepository(Document::class)
            ->findAll();

        return $this->render('responsableUE/documents.html.twig', array(
            'documents' => $documents
        ));
    }

    public function refuseDocument(EntityManagerInterface $manager, $id) {
        $document = $this->getDoctrine()->getManager()->getRepository(Document::class)
            ->find($id);

        if(!$document) 
            throw $this->createNotFoundException('Document[id='.$id.'] inexistant');
        
        $document->setEtatDocument('refusé');
        $manager->persist($document); 
        $manager->flush();

        $documents = $this->getDoctrine()->getManager()->getRepository(Document::class)
            ->findAll();

        return $this->render('responsableUE/documents.html.twig', array(
            'documents' => $documents
        )); 
    }

    public function admitDocument(EntityManagerInterface $manager, $id) {
        $document = $this->getDoctrine()->getManager()->getRepository(Document::class)
            ->find($id);

        if(!$document) 
            throw $this->createNotFoundException('Document[id='.$id.'] inexistant');
        
        $document->setEtatDocument('Validé');
        $manager->persist($document); 
        $manager->flush();

        $documents = $this->getDoctrine()->getManager()->getRepository(Document::class)
            ->findAll();

        return $this->render('responsableUE/documents.html.twig', array(
            'documents' => $documents
        )); 
    }

    public function addComment(Request $request, EntityManagerInterface $manager, $id) {
        $document = $this->getDoctrine()->getManager()->getRepository(Document::class)
            ->find($id);

        if(!$document) 
            throw $this->createNotFoundException('Document[id='.$id.'] inexistant');

        //$form = $this->createForm(DocumentType::class, $document);

        $form = $this->createFormBuilder($document)
            ->setAction($this->generateUrl('RUE_add_comment',array('id' => $id)))
            ->add('commentaire', TextType::class)
            ->add('valider', SubmitType::class, array('label'=> 'Commenter'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $document->setCommentaire($form->get('commentaire')->getData());

            $manager->persist($document); 
            $manager->flush();

            $documents = $this->getDoctrine()->getManager()->getRepository(Document::class)
            ->findAll();

            return $this->render('responsableUE/documents.html.twig', array(
                'documents' => $documents
            )); 
        }

        /*$form = $this->createForm(DocumentType::class, $document,
                 ['action' => $this->generateUrl('RUE_add_comment_suite',
                 array('id' => $document->getId()))]);*/

        //$form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        return $this->render('responsableUE/addComment.html.twig',
            array('form' => $form->createView(),
            'document' => $document
        ));
    }

    /*public function addCommentSuite(Request $request, $id){
        $document = $this->getDoctrine()->getManager()->getRepository(Document::class)
            ->find($id);

        if(!$document) 
            throw $this->createNotFoundException('Document[id='.$id.'] inexistant');

        $form = $this->createForm(DocumentType::class, $document,
                 ['action' => $this->generateUrl('RUE_add_comment_suite',
                 array('id' => $document->getId()))]);

        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($document); 
            $entityManager->flush();

            $documents = $this->getDoctrine()->getManager()->getRepository(Document::class)
            ->findAll();

            return $this->render('responsableUE/documents.html.twig', array(
                'documents' => $documents
            )); 
        } 

        //$form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        return $this->render('responsableUE/addComment.html.twig',
            array('form' => $form->createView()));
    }*/
   
   
    public function addSheet(){
       return $this->render('secretariat/addSheet.html.twig');
   }

   public function addListStudent(){
    return $this->render('secretariat/addListStudent.html.twig');
   }

   public function erreur($url) {
        throw $this->createNotFoundException('Erreur 404 - Page introuvable');
        return $this->render('general/error404.html.twig', array('url' => $url));
    } 
}