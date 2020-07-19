<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/joueur", name="joueur_")
 */

class JoueurController extends AbstractController
{

    /**
     * @Route("/profil", name="profil")
     */
    public function pageJoueur()
    {
        return $this->render('joueur/index.html.twig');
    }

}
?>