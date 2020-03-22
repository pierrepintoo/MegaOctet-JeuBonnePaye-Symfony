<?php

namespace App\Controller;

use App\Entity\Jouer;
use App\Entity\Partie;
use App\Repository\BoxRepository;
use App\Repository\CarteRepository;
use App\Repository\JouerRepository;
use App\Repository\PartieRepository;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/partie", name="partie_")
 */
class PartieController extends AbstractController
{
    /**
     * @Route("/test", name="partie_test")
     */
    public function testAfficherPlateau(BoxRepository $boxesRepository)
    {
        $boxes = $boxesRepository->findBy([], ['box_position' => 'ASC']);
        return $this->render('partie/test.html.twig',
            [
                'boxes' => $boxes
            ]);
    }

    /**
     * @Route("/creer-partie", name="creer")
     */
    public function creerPartie(Request $request, UserRepository $userRepository, CarteRepository $carteRepository, JouerRepository $JouerRepository)
    {

        if ($request->isMethod('POST'))
        {
            /*$joueur1 = $userRepository->find($request->request->get('joueur1'));
            $joueur2 = $userRepository->find($request->request->get('joueur2'));

            $cartes = $carteRepository->findAll();
            $tableauDeCartes = ['acquisition' => [], 'evenement' => [], 'courrier' => []];
            foreach ($cartes as $carte)
            {
                $tableauDeCartes[$carte->getCarteType()][] = $carte->getId();
            }
            shuffle($tableauDeCartes['acquisition']);
            shuffle($tableauDeCartes['evenement']);
            shuffle($tableauDeCartes['courrier']);

            $partie = new Partie();
            $partie->setPartiePioche($tableauDeCartes);
            $em = $this->getDoctrine()->getManager();
            $em->persist($partie);

            $jouer = new Jouer();
            $jouer->setPartie($partie);
            $jouer->setClassement(1);
            $jouer->setUser($joueur1);
            $em->persist($jouer);

            $jouer = new Jouer();
            $jouer->setPartie($partie);
            $jouer->setClassement(2);
            $jouer->setUser($joueur2);
            $em->persist($jouer);

            $em->flush();*/
            $partieId = mt_rand(1000, 100000);
            //$codePartie = 3000;
            /*$code = $JouerRepository->findBy(array('code_partie'=>$codePartie));
            if(!empty($code))
            {
                $codePartie = mt_rand(1000, 100000);
            }
            */
            $cartes = $carteRepository->findAll();
            $tableauDeCartes = ['acquisition' => [], 'evenement' => [], 'courrier' => []];
            foreach ($cartes as $carte)
            {
                $tableauDeCartes[$carte->getCarteType()][] = $carte->getId();
            }
            shuffle($tableauDeCartes['acquisition']);
            shuffle($tableauDeCartes['evenement']);
            shuffle($tableauDeCartes['courrier']);
            $partie = new Partie();
            $partie->setPartieDateDebut(new \DateTime('now'));
            $partie->setPartiePioche($tableauDeCartes);
            $em = $this->getDoctrine()->getManager();
            $em->persist($partie);
            $em->flush();
            return $this->redirectToRoute('partie_new-partie',
                [
                'codePartie' => $partie->getId(),
                ]);
        }

        return $this->render('partie/creerpartie.html.twig');
    }

    /**
     * @Route("/new-partie/{codePartie}", name="new-partie")
     * @param $codePartie
     * @return Response
     * @throws Exception
     */
    public function newPartie($codePartie, Request $request, UserRepository $userRepository, CarteRepository $carteRepository, JouerRepository $JouerRepository, PartieRepository $PartieRepository){





        $usersPartie = $JouerRepository->findBy
        (
            ['partie'=> $codePartie],
            ['user'=> 'ASC']
        );


        $userConnecte = $this->getUser();

        $testUserDejaDansLaPartie = $JouerRepository->findBy
        (
            [
                'user' => $userConnecte,
                'partie' => $codePartie
            ]
        );
        /*$testClassement = $JouerRepository->findBy
        (
            [
                'user' => $userConnecte,
                'classement' => $classement
            ]
        );
        switch ($classement){
                case 1:
                    $classement = 2;
                    break;
                case 2:
                    $classement = 3;
                    break;
                case 3:
                    $classement = 4;
                    break;
                case 4:
                    $classement = 5;
                    break;
                case 5:
                    $classement = 6;
                    break;
            }
        ;*/

        $nbUsers = count($usersPartie);
        if($nbUsers >= 6){
            $this->redirectToRoute('app_partie',
                [
                    'users' => $usersPartie,
                    'code_partie' => $codePartie
                ]);
        }




        if(empty($testUserDejaDansLaPartie) && $nbUsers < 7)
        {
            $code = $JouerRepository->findBy(array('partie'=>$codePartie));



            $jouer = new Jouer();
            $partieEnCours = $PartieRepository->findBy(['id' => $codePartie]);
            $partieEnCoursId = $partieEnCours[0]->getId();
            $jouer->setPartie($partieEnCoursId);
            $jouer->setUser($userConnecte);
            $em = $this->getDoctrine()->getManager();
            $em->persist($jouer);
            $em->flush();

        }
        return $this->render('partie/maPartie.html.twig', array(
            'codePartie' => $codePartie,
            'usersPartie' => $usersPartie
        ));
    }

    /**
     * @Route("/partie/{codePartie}", name="app_partie")
     */
    public function jouerPartie($codePartie, JouerRepository $JouerRepository){

        $usersPartie = $JouerRepository->findBy
        (
            ['code_partie'=>$codePartie],
            ['classement'=> 'ASC']
        );



        return $this->render('partie/partieEnCours.html.twig');
    }

    /**
     * @Route("/maPartie", name="creer-partie")
     */
    public function maPartie(Request $request, UserRepository $userRepository, CarteRepository $carteRepository)
    {

        if ($request->isMethod('POST')) {
            $joueur1 = $userRepository->find($request->request->get('joueur1'));
            $joueur2 = $userRepository->find($request->request->get('joueur2'));

            $cartes = $carteRepository->findAll();
            $tableauDeCartes = ['acquisition' => [], 'evenement' => [], 'courrier' => []];
            foreach ($cartes as $carte)
            {
                $tableauDeCartes[$carte->getCarteType()][] = $carte->getId();
            }
            shuffle($tableauDeCartes['acquisition']);
            shuffle($tableauDeCartes['evenement']);
            shuffle($tableauDeCartes['courrier']);

            $partie = new Partie();
            $partie->setPartiePioche($tableauDeCartes);
            $em = $this->getDoctrine()->getManager();
            $em->persist($partie);

            $jouer = new Jouer();
            $jouer->setPartie($partie);
            $jouer->setClassement(1);
            $jouer->setUser($joueur1);
            $em->persist($jouer);

            $jouer = new Jouer();
            $jouer->setPartie($partie);
            $jouer->setClassement(2);
            $jouer->setUser($joueur2);
            $em->persist($jouer);

            $em->flush();
            return $this->redirectToRoute('affiche_code_partie', ['partie' => $partie->getId()]);
        }

        return $this->render('partie/creerpartie.html.twig',
            [
                'joueurs' => $userRepository->findAll()
            ]);
    }

    /**
     * @Route("/affiche-code-partie/{partie}", name="affiche_code_partie")
     * @param Partie $partie
     *
     * @return Response
     */
    public function afficheCodePartie(Partie $partie)
    {
        return $this->render('partie/afficheCodePartie.html.twig',
            [
                'partie' => $partie
            ]);
    }

    /**
     * @Route("/rejoindre-partie", name="rejoindre")
     */
    public function rejoindrePartie(Request $request, JouerRepository $JouerRepository)
    {
        if($request->isMethod('POST'))
        {
            $codeRecupere = $request->request->get('codeRecupere');
            $partieJoueurs = $JouerRepository->findBy
            (
                ['code_partie'=>$codeRecupere],
                ['user'=> 'ASC']
            );
            if (!empty($partieJoueurs))
            {
                $this->redirectToRoute('partie_new-partie',
                    [
                        'codePartie' => $codeRecupere
                    ]
                );
            }
        }
        return $this->render('partie/rejoindre-partie.html.twig');
    }

}
