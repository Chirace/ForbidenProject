<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExternalController extends AbstractController {
    public function rateStudent(){
        return $this->render('externalSpeaker/rate.html.twig');
    }
}