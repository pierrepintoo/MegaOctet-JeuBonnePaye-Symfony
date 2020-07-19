<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReglesController extends AbstractController
{
    /**
     * @Route("/regles", name="regles")
     */
    public function index()
    {
        return $this->render('regles/index.html.twig', [
            'controller_name' => 'ReglesController',
        ]);
    }
}
