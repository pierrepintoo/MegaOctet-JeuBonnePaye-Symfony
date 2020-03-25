<?php

namespace App\Controller;

use App\Entity\Jouer;
use App\Entity\Partie;
use App\Repository\BoxRepository;
use App\Repository\CarteRepository;
use App\Repository\JouerRepository;
use App\Repository\PartieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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
            $cartes = $carteRepository->findAll();
            $tableauDeCartes = ['tp' => [], 'evenement' => [], 'notif' => []];
            foreach ($cartes as $carte)
            {
                $tableauDeCartes[$carte->getCarteType()][] = $carte->getId();
            }
            shuffle($tableauDeCartes['tp']);
            shuffle($tableauDeCartes['evenement']);
            shuffle($tableauDeCartes['notif']);
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
     * @Route("/rejoindre-partie", name="rejoindre")
     */
    public function rejoindrePartie(Request $request, JouerRepository $JouerRepository)
    {
        if($request->isMethod('POST'))
        {
            $codeRecupere = $request->request->get('codeRecupere');
            $partieJoueurs = $JouerRepository->findBy
            (
                ['partie'=>$codeRecupere]
            );
            if (!empty($partieJoueurs))
            {
                return $this->redirectToRoute('partie_new-partie',
                    [
                        'codePartie' => $codeRecupere
                    ]
                );
            } else {
                echo 'La code partie que tu as inséré n\'éxiste pas';
            }
        }
        return $this->render('partie/rejoindre-partie.html.twig');
    }

    /**
     * @param $codePartie
     */
    public function addUserToParty(JouerRepository $JouerRepository, $codePartie){
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

        ;

        $nbUsers = count($usersPartie);

        if(empty($testUserDejaDansLaPartie) && $nbUsers < 7)
        {
            $jouer = new Jouer();
            $partieEnCoursId = $this->getDoctrine()
                ->getRepository(Partie::class)
                ->find($codePartie);
            $jouer->setPartie($partieEnCoursId);
            $jouer->setUser($userConnecte);
            $jouer->setClassement(1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($jouer);
            $em->flush();
            $user = $this->getUser();
            $Joueur = $JouerRepository->findOneBy(['partie' => $codePartie, 'user' => $user]);
            dump($Joueur);
            $classementJoueur = $Joueur->getClassement();
            switch ($classementJoueur){
                case 1:
                    $classementJoueur = 2;
                    break;
                case 2:
                    $classementJoueur  = 3;
                    break;
                case 3:
                    $classementJoueur  = 4;
                    break;
                case 4:
                    $classementJoueur  = 5;
                    break;
                case 5:
                    $classementJoueur  = 6;
                    break;
            }

            $Joueur->setClassement($classementJoueur);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

    }

    /**
     * @Route("/new-partie/{codePartie}", name="new-partie")
     * @param $codePartie
     * @return Response
     * @throws Exception
     */
    public function newPartie(Partie $codePartie, Request $request, UserRepository $userRepository, CarteRepository $carteRepository, JouerRepository $JouerRepository, PartieRepository $PartieRepository){

        $this->addUserToParty($JouerRepository, $codePartie);
        $usersPartie = $JouerRepository->findBy
        (
            ['partie'=> $codePartie],
            ['user'=> 'ASC']
        );
        $nbUsers = count($usersPartie);
        $codePartie = $codePartie->getId();
        if($nbUsers >= 6){
            return $this->redirectToRoute('partie_app_partie',
                [
                    'codePartie' => $codePartie
                ]);
        }

        if($request->isMethod('POST'))
        {
                return $this->redirectToRoute('partie_new-partie',
                    [
                        'codePartie' => $codePartie,
                        'usersPartie' => $usersPartie
                    ]
                );
        }

        return $this->render('partie/maPartie.html.twig', array(
            'codePartie' => $codePartie,
            'usersPartie' => $usersPartie
        ));
    }

    /**
     * @Route("/{codePartie}", name="app_partie")
     * @param $codePartie
     */
    public function jouerPartie(Partie $codePartie, CarteRepository $carteRepository, JouerRepository $JouerRepository,Request $request){


        if ($request->isMethod('GET'))
        {

            $user = $this->getUser();
            $objetJouerDeUser = $JouerRepository->findOneBy(['partie' => $codePartie, 'user' => $user]);
            $de = 1;
            $anciennePosition = $objetJouerDeUser->getBox();
            $anciennePosition += $de;
            $argentEnCours = $objetJouerDeUser->getArgent();
            switch ($anciennePosition){
                case 2:
                    $argentEnCours  += 500;
                    break;
                case 3:
                    $argentEnCours += 700;
                    break;
                case 4:
                    $pioche = $codePartie->getPartiePioche();
                    $carteId = $pioche['tp'][0];
                    $objetCarte = $carteRepository->findOneBy(['id' => $carteId]);
                    $carteEffet = $objetCarte->getCarteEffet();
                    $carteMontant = $objetCarte->getCarteMontant();
                    if($carteEffet == 'negatif'){
                        $argentEnCours -= $carteMontant;
                    } elseif ($carteEffet == 'positif'){
                        $argentEnCours += $carteMontant;
                    }
                    if($codePartie->getPartieDefausse() == null){
                        $tableauDeCartes = ['tp' => [], 'evenement' => [], 'notif' => []];
                    }
                    $tableauDeCartes[$objetCarte->getCarteType()][] = $objetCarte->getId();
                    $codePartie->setPartieDefausse($tableauDeCartes);
                    array_shift($pioche['tp']);

                    $codePartie -> setPartiePioche($pioche);
                    break;
                case 5:
                    $argentEnCours += 5000;
                    break;
            }

            ;
            $objetJouerDeUser->setDe($de);
            if($anciennePosition > 24){
                $argentEnCours +=2000;
                $anciennePosition = 1;
                $objetJouerDeUser->setBox($anciennePosition);
            } else {
                $objetJouerDeUser->setBox($anciennePosition);
            }
            $objetJouerDeUser->setArgent($argentEnCours);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $em->flush();
        }
        return $this->render('partie/partieEnCours.html.twig',
            [
            'partie' => $codePartie
            ]
        );
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
     * @Route("/update-partie/data/{codePartie}", name="update_game")
     * @param Partie $codePartie
     *
     * @return Response
     */
    public function updateGame(Partie $codePartie)
    {
        $jouers = $codePartie->getJouers();
        $monTour = false;
        $positions = [];
        foreach ($jouers as $jouer) {
            if ($codePartie->getPartieQuiJoue() === $this->getUser()->getId())
            {
                //quiJoue contient l'id du joueur en train de jouer.
                $monTour = true;
            }
            if ($jouer->getUser() !== null) {
                $positions[$jouer->getUser()->getId()]['username'] = $jouer->getUser()->getUsername();
                $positions[$jouer->getUser()->getId()]['position'] = $jouer->getBox();
                $positions[$jouer->getUser()->getId()]['argent'] = $jouer->getArgent();
                $positions[$jouer->getUser()->getId()]['de'] = $jouer->getDe();
                $positions[$jouer->getUser()->getId()]['classement'] = $jouer->getClassement();
            }
        }

        $array = [
            'joueurEnCours' => $codePartie->getPartieQuiJoue(),
            'monTour' => $monTour,
            'positionsJoueurs' => $positions,
        ];

        return $this->json($array);
    }


    /**
     * @Route("/update-partie/fin-tour/{codePartie}", name="fin_de_tour")
     * @param Partie $codePartie
     *
     * @return Response
     */
    public function finTour(EntityManagerInterface $entityManager, Partie $codePartie)
    {
        $jouers = $codePartie->getJouers();
        $positions = [];
        foreach ($jouers as $jouer) {
            if ($jouer->getUser()->getId() === $this->getUser()->getId())
            {
                $monOrdre = $jouer->getClassement();
            }

            if ($jouer->getUser() !== null) {
                $positions[$jouer->getUser()->getId()]['username'] = $jouer->getUser()->getUsername();
                $positions[$jouer->getUser()->getId()]['position'] = $jouer->getPosition();
                $positions[$jouer->getUser()->getId()]['argent'] = $jouer->getArgent();
                $positions[$jouer->getUser()->getId()]['de'] = $jouer->getDe();
                $positions[$jouer->getUser()->getId()]['classement'] = $jouer->getClassement();
            }
            $ordre[$jouer->getClassement()] = $jouer->getUser()->getId();
        }

        if ($monOrdre >= count($ordre)) {
            $joueurSuivant = $ordre[1];
        } else {
            $joueurSuivant = $ordre[$monOrdre+1];
        }

        $codePartie->setPartieQuiJoue($joueurSuivant);
        $entityManager->persist($codePartie);
        $entityManager->flush();//sauvegarde de l'entité partie
        $array = [
            'joueurEnCours' => $codePartie->getPartieQuiJoue(),
            'monTour' => false,
            'positionsJoueurs' => $positions
        ];

        return $this->json($array);
    }


}
