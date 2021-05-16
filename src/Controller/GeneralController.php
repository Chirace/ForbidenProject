<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
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
        return $this->render('responsableUE/feasibility.html.twig');
    }

   public function setting(){
       return $this->render('responsableUE/setting.html.twig');
   }

   public function listStudents(){
       return $this->render('responsableUE/students.html.twig');
   }

   public function listTutors(){
       return $this->render('responsableUE/tutors.html.twig');
   }

   public function assignTutor(){
       return $this->render('responsableUE/assignTutor.html.twig');
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