<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ScolariteController extends AbstractController {
    public function listStudents(){
        return $this->render('scolarite/students.html.twig');
    }

    public function addDocument(){
        return $this->render('scolarite/addDocument.html.twig');
    }
}