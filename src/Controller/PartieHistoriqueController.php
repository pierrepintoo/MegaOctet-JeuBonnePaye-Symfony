<?php

namespace App\Controller;

use App\Repository\JouerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PartieHistoriqueController extends AbstractController
{
    /**
     * @Route("/historique", name="partie_historique")
     */
    public function index(JouerRepository $jouerRepository)
    {
        $parties = $jouerRepository->findBy(['user' => $this->getUser()->getId()]);
        //dump($parties);
        return $this->render('partie_historique/index.html.twig', [
            'parties' => $parties,
        ]);
    }
}
