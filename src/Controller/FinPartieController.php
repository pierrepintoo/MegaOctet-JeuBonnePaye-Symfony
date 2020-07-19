<?php

namespace App\Controller;

use App\Entity\Jouer;
use App\Repository\JouerRepository;
use App\Repository\PartieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FinPartieController extends AbstractController
{
    /**
     * @Route("/fin/partie/{codePartie}", name="fin_partie")
     */
    public function index($codePartie, JouerRepository $jouerRepository, PartieRepository $partieRepository)
    {
        $idPartie = $partieRepository->findOneBy(['id' => $codePartie]);
        $partie = $partieRepository->findOneBy(['id' => $idPartie]);
        $partie->setPartieEtat('T');
        $em = $this->getDoctrine()->getManager();
        $em->persist($partie);
        $em->flush();
        $user = $this->getUser();
        $jouer = $jouerRepository->findByUserAndPartie($partie, $user);
        if($jouer->getClassement() == 1){
            $nbVictoires = $user->getVictoires();
            $nbVictoires += 1;
            $user->setVictoires($nbVictoires);
        }
        $nbParties = $user->getNbPartie();
        $nbParties += 1;
        $user->setNbPartie($nbParties);

        $argentPartie = $jouer->getArgent();
        $argentTotal = $user->getArgentTotal();
        $argentTotal += $argentPartie;
        $user->setArgentTotal($argentTotal);


        $classement = $jouerRepository->findBy(['partie' => $idPartie],['argent' => 'DESC']);
        //dump($classement);
        return $this->render('fin_partie/index.html.twig', [
            'controller_name' => 'FinPartieController',
            'classement' => $classement,
            'codePartie' => $partie
        ]);
    }
}
