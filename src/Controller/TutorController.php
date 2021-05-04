<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TutorController extends AbstractController {
    public function listStudents(){
        return $this->render('tutor/students.html.twig');
    }

    public function addDocument(){
        return $this->render('tutor/addDocument.html.twig');
    }

    public function rateStudent(){
        return $this->render('tutor/rate.html.twig');
    }
}