<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StudentController extends AbstractController {
    public function marks(){
        return $this->render('student/marks.html.twig');
    }

    public function addDocument(){
        return $this->render('student/addDocument.html.twig');
    }
}