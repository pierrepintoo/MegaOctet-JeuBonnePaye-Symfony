<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CGUController extends AbstractController
{
    /**
     * @Route("/c/g/u", name="c_g_u")
     */
    public function index()
    {
        return $this->render('cgu/index.html.twig', [
            'controller_name' => 'CGUController',
        ]);
    }
}
