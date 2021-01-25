<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
class GeneralController {
   public function accueil() {
    return new Response("Un accueil de qualité !");
   }
}