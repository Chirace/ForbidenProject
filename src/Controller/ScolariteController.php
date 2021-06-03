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
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ScolariteController extends AbstractController {
    public function listStudents(){
        $listeEtudiants = $this->getDoctrine()->getRepository(Etudiant::Class)->findAll();

        return $this->render('scolarite/students.html.twig', array(
            'etudiants' => $listeEtudiants));
   }

    public function listDocuments(Request $request){
        $documents = $this->getDoctrine()->getManager()->getRepository(Document::class)
            ->findAll();

        return $this->render('scolarite/documents.html.twig', array(
            'documents' => $documents
        ));
    }

    public function downloadDocument($id) {
        $document = $this->getDoctrine ()->getRepository (Document::class)->find($id);

        $fileName = $document->getIntitule().".pdf";
        $file_with_path = $this->getParameter('upload_directory')."/".$fileName;
        $response = new BinaryFileResponse($file_with_path);
        return $response;
        
        return $this->render('scolarite/documents.html.twig', array(
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
            return $this->redirectToRoute('scolarite_document');
        }

        return $this->render('scolarite/addDocument.html.twig', array(
            'formDocument' => $formDocument->createView()));
    }
}