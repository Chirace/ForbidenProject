<?php
namespace App\Controller;
use App\Entity\Document;
use App\Entity\Etudiant;
use App\Entity\Personne;
use App\Form\DocumentType;
use App\Entity\TypeDocument;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TutorController extends AbstractController {
    public function listStudents(){
        return $this->render('tutor/students.html.twig');
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

    public function rateStudent(){
        return $this->render('tutor/rate.html.twig');
    }
}