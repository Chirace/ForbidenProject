<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GeneralController extends AbstractController {
   public function accueil() {
       return $this->render('general/accueil.html.twig');
   }

   public function erreur($url) {
        throw $this->createNotFoundException('Erreur 404 - Page introuvable');
        return $this->render('general/error404.html.twig', array('url' => $url));
    } 
}