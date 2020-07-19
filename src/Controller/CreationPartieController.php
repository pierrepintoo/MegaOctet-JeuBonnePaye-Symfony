<?php

namespace App\Controller;

use App\Entity\Partie;
use App\Form\NombreDeToursType;
use App\Repository\CarteRepository;
use App\Repository\PartieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreationPartieController extends AbstractController
{
    /**
     * @Route("/creation/partie", name="creation_partie")
     * @param Request $request
     * @param CarteRepository $carteRepository
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request, CarteRepository $carteRepository, PartieRepository $partieRepository)
    {
        $user = new Partie();
        $form = $this->createForm(NombreDeToursType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $cartes = $carteRepository->findAll();
            $tableauDeCartes = ['tp' => [], 'action' => [], 'notif' => []];
            foreach ($cartes as $carte)
            {
                $tableauDeCartes[$carte->getCarteType()][] = $carte->getId();
            }
            shuffle($tableauDeCartes['tp']);
            shuffle($tableauDeCartes['action']);
            shuffle($tableauDeCartes['notif']);
            $partie = new Partie();
            $nbTours = $form->get('nbTours')->getData();
            $partie->setNbTours($nbTours);
            $partie->setNbToursRestants($nbTours);
            $partie->setPartieDateDebut(new \DateTime('now'));
            $partie->setPartiePioche($tableauDeCartes);
            $partie->setPartieQuiJoue($this->getUser()->getId());
            $codeRejoindre = rand(10000, 500000);
            $parties = $partieRepository->findAll();
            foreach ($parties as $part)
            {
                if($codeRejoindre == $part->getCodeRejoindre()){
                    $codeRejoindre = rand(10000, 500000);
                }
            }
            $partie->setCodeRejoindre($codeRejoindre);
            $em = $this->getDoctrine()->getManager();
            $em->persist($partie);
            $em->flush();
            return $this->redirectToRoute('partie_new-partie',
                [
                    'codePartie' => $partie->getId(),
                ]);
        }
        return $this->render('creation_partie/index.html.twig', [
            'NombreDeToursType' => $form->createView(),
        ]);
    }
}
