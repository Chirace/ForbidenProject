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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StudentController extends AbstractController {
    public function marks(Request $request, UserInterface $user){
        $personne = $this->getDoctrine()->getManager()->getRepository(Personne::class)
            ->findOneByUsername($user->getUsername());
        $etudiant = $this->getDoctrine()->getManager()->getRepository(Etudiant::class)
            ->findOneByPersonne($personne->getId());
        
        return $this->render('student/marks.html.twig', array(
            'etudiant' => $etudiant));
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

            $intitule = $intitule."_".$personne->getNom()."_".$personne->getPrenom();

            $document->setTypeDocument($type_document);

            $document->setEtatDocument("à valider");
            $document->setIntitule($intitule);
            $document->setDepositaire($personne);
            $document->setEtudiant($etudiant);
            $document->setDateDepot($date);

            $file = $formDocument->get('intitule')->getData();

            $fileName = $document->getIntitule().".".$file->guessExtension();

            $file->move($this->getParameter('upload_directory'), $fileName);

            $manager->persist($document);
            $manager->flush();
            return $this->redirectToRoute('student_document');
        }

        return $this->render('student/addDocument.html.twig', array(
            'formDocument' => $formDocument->createView()));
    }

    public function listDocuments(Request $request, UserInterface $user){
        $personne = $this->getDoctrine()->getManager()->getRepository(Personne::class)
            ->findOneByUsername($user->getUsername());
        $etudiant = $this->getDoctrine()->getManager()->getRepository(Etudiant::class)
            ->findOneByPersonne($personne->getId());
        $documents = $this->getDoctrine()->getManager()->getRepository(Document::class)
            ->findByEtudiant($etudiant->getId());

        return $this->render('student/documents.html.twig', array(
            'documents' => $documents,
            'etudiant' => $etudiant
        ));
    }

    /**
     * @Route("/download/{id}",name="pdf_download")
     */
    /*public function downloadAction($id) {
        try {
            $file = $this->getDoctrine()->getRepository(Document::class)->findById($id);
            if (! $file) {
                $array = array (
                    'status' => 0,
                    'message' => 'File does not exist' 
                );
                $response = new JsonResponse ( $array, 200 );
                return $response;
            }
            $displayName = $file->getIntitule();
            $fileName = $document->getIntitule().".".$file->guessExtension()
            $response = new BinaryFileResponse($this->getParameter('uploads')."/".$fileName);
            $response->headers->set('Content-Type', 'text/plain');
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $displayName);
            return $response;
        } catch ( Exception $e ) {
            $array = array (
                'status' => 0,
                'message' => 'Download error' 
            );
            $response = new JsonResponse ( $array, 400 );
            return $response;
        }
    }*/
}