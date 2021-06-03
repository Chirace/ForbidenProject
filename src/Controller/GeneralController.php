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
use Symfony\Component\Security\Core\User\UserInterface;
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

   public function setting(){
       return $this->render('responsableUE/setting.html.twig');
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

   public function assignTutor(){
        $listeEtudiants = $this->getDoctrine()->getRepository(Etudiant::Class)->findAll();
        $listeTuteurs = $this->getDoctrine()->getRepository(Tuteur::Class)->findAll();

       return $this->render('responsableUE/assignTutor.html.twig', array(
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