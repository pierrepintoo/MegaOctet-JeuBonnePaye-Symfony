<?php
// src/Controller/LuckyController.php
namespace App\Controller;
use App\Entity\User;
use App\Form\Type\InscriptionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/user", name="partie")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/add", name="user")
     */
    public function addUser(){
        $user = new User();
        $user->setUsername('Akitopi');
        $user->setRoles(["ROLE_Admin"]);
        $user->setPassword('123');

        $em = $this->getDoctrine()->getManager(); // on récupère le gestionnaire d'entité
        $em->persist( $user ); // on déclare une modification de type persist et la génération des différents liens entre entité
        $em->flush(); // on effectue les différentes modifications sur la base de données
        // réelle
        return new Response('Sauvegarde OK sur : ' . $user->getId() );
    }


    /**
     * @Route("/inscription", name="app_inscription")
     */
    public function new(Request $request)
    {
        $user = new User();
        $user->setUsername('Akitopi');
        //$user->setRoles(["ROLE_Admin"]);
        $user->setPassword('123');
        $usernameIsRequired = true;

        $form = $this->createForm(InscriptionType::class, $user, [
            'require_username' => $usernameIsRequired,
            'action' => $this->generateUrl('target_route'),
        ]);



        return $this->render('partie/inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
?>