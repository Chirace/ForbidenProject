<?php

namespace App\Controller;

use App\Entity\Tuteur;
use App\Entity\Etudiant;
use App\Entity\Personne;

use App\Entity\Formation;
use App\Entity\Scolarite;
use App\Entity\ResponsableUE;
use App\Entity\Administrateur;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface ;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){
        $personne = new Personne();
        $form = $this->createForm(RegistrationType::class, $personne);

        /*$formations = $this->getDoctrine()
                   ->getRepository(Formation::Class);*/

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($personne, $personne->getPassword());
            $personne->setPassword($hash);

            $role = $form->get('role')->getData();

            if($role == "Étudiant") {
                $personne->setRoles(['ROLE_ETUDIANT']);
                $personne->setEntreprise(5);
            }
            if($role == "ResponsableUE" ) {
                $personne->setRoles(['ROLE_RESPONSABLE']);
            }

            if($role == "Scolarite" ) {
                $personne->setRoles(['ROLE_SCOLARITE']);
            }

            if($role == "Tuteur" ) {
                $personne->setRoles(['ROLE_TUTEUR']);
            }

            if($role == "Administrateur" ) {
                $personne->setRoles(['ROLE_ADMIN']);
            }
            
            $manager->persist($personne);
            $manager->flush();

            if($role == "Étudiant") {
                $etudiant = new Etudiant();
                $etudiant->setPersonne($personne);

                


                $formation_texte = $form->get('formation')->getData();
                if($formation_texte == "M1 MIAGE") {
                    $formation = $this->getDoctrine()->getManager()->getRepository(Formation::class)
                    ->findOneByNom('M1 MIAGE');
                } else {
                    $formation = $this->getDoctrine()->getManager()->getRepository(Formation::class)
                    ->findOneByNom('M2 MIAGE');
                }

                $etudiant->setFormation($formation);

                $manager->persist($etudiant);
            }

            if($role == "ResponsableUE" ) {
                $responsableUE = new ResponsableUE();
                $responsableUE->setPersonne($personne);
                
                $manager->persist($responsableUE);
            }

            if($role == "Scolarite" ) {
                $scolarite = new Scolarite();
                $scolarite->setPersonne($personne);
                
                $manager->persist($scolarite);
            }

            if($role == "Tuteur" ) {
                $tuteur = new Tuteur();
                $tuteur->setPersonne($personne);
                
                $manager->persist($tuteur);
            }

            if($role == "Administrateur" ) {
                $administrateur = new Administrateur();
                $administrateur->setPersonne($personne);
                
                $manager->persist($administrateur);
            }
            $manager->flush();
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login() {
        return $this->render('security/index.html.twig');
    }

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }
    
    /**
     * @Route("/connexion_success", name="security_login_success")
     */
    public function onAuthenticationSuccess(Request $request, UserInterface $user) {
        $personne = $this->getDoctrine()->getManager()->getRepository(Personne::class)
                    ->findOneByUsername($user->getUsername());
        $role = $personne->getRoles();

        // si rôle admin
        if ($role[0] == "ROLE_ADMIN")
            //return $this->render('security_registration');
            return $this->redirectToRoute('security_registration');
        // si rôle étudiant
        elseif ($role[0] == "ROLE_ETUDIANT")
            return $this->render('student/accueil.html.twig');
        // si rôle responsable d'UE
        elseif ($role[0] == "ROLE_RESPONSABLE")
            return $this->render('responsableUE/accueil.html.twig');
        // si rôle scolarite
        elseif ($role[0] == "ROLE_SCOLARITE")
            return $this->render('scolarite/accueil.html.twig');
        // si rôle tuteur
        elseif ($role[0] == "ROLE_TUTEUR")
            return $this->render('tutor/accueil.html.twig');

        return $this->render('security/index.html.twig');
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout() {}
}