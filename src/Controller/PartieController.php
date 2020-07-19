<?php

namespace App\Controller;

use App\Entity\Box;
use App\Entity\Carte;
use App\Entity\Jouer;
use App\Entity\Partie;
use App\Repository\BoxRepository;
use App\Repository\CarteRepository;
use App\Repository\JouerRepository;
use App\Repository\PartieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
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
    public function creerPartie(Request $request, UserRepository $userRepository, CarteRepository $carteRepository, JouerRepository $JouerRepository, PartieRepository $partieRepository)
    {

        if ($request->isMethod('POST'))
        {
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

        return $this->render('partie/creerpartie.html.twig');
    }

    /**
     * @Route("/rejoindre-partie", name="rejoindre")
     * @param Request $request
     * @param JouerRepository $JouerRepository
     * @param PartieRepository $partieRepository
     * @return RedirectResponse|Response
     */
    public function rejoindrePartie(Request $request, JouerRepository $JouerRepository, PartieRepository $partieRepository)
    {
        if($request->isMethod('POST'))
        {
            $codeRecupere = $request->request->get('codeRecupere');
            $partieJoueurs = $JouerRepository->findBy
            (
                ['partie'=>$codeRecupere]
            );
            $parties = $partieRepository->findAll();
            foreach ($parties as $partie){
                if($partie->getCodeRejoindre() == $codeRecupere) {
                    return $this->redirectToRoute('partie_new-partie',
                        [
                            'codePartie' => $partie->getId()
                        ]
                );
                }
                }
            return $this->render('partie/rejoindre-partie.html.twig', ['messageErreur' => 'Le code que tu as inséré n\'existe pas.']);
        }

            /*if (!empty($partieJoueurs))
            {
                return $this->redirectToRoute('partie_new-partie',
                    [
                        'codePartie' => $codeRecupere
                    ]
                );
            } else {
                echo 'La code partie que tu as inséré n\'éxiste pas';
            }*/

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
            $jouer->setNbToursRestants($partieEnCoursId->getNbTours());
            $jouer->setUser($userConnecte);
            $joueurs = $codePartie->getJouers();
                $classement = array();
                foreach ($joueurs as $joueur){
                    $classement[] = $joueur->getClassement();
                }
            $futurClassement = count($classement) + 1;
            $jouer->setClassement($futurClassement);
            $jouer->setDeLance(false);
            $jouer->setQuiMiseLoterie(false);
            $jouer->setJaiPioche(false);
            $jouer->setArgent(1000);
            $em = $this->getDoctrine()->getManager();
            $em->persist($jouer);
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

        if($codePartie->getPartieEtat() == 'NC')
        {
            $this->addUserToParty($JouerRepository, $codePartie);
            $usersPartie = $JouerRepository->findBy
            (
                ['partie'=> $codePartie],
                ['user'=> 'ASC']
            );
            $nbUsers = count($usersPartie);
            if($nbUsers >= 6){
                return $this->redirectToRoute('partie_app_partie',
                    [
                        'codePartie' => $codePartie->getId()
                    ]);
            }

            if($request->isMethod('POST'))
            {
                $codePartie->setPartieEtat('EC');
                $em = $this->getDoctrine()->getManager();
                $em->persist($codePartie);
                $em->flush();
                return $this->redirectToRoute('partie_app_partie',
                    [
                        'codePartie' => $codePartie->getId(),
                    ]
                );
            }
            return $this->render('partie/maPartie.html.twig', array(
                'codeRejoindre' => $codePartie->getCodeRejoindre(),
                'code' => $codePartie->getId(),
                'codePartie' => $codePartie,
                'usersPartie' => $usersPartie
            ));
        } elseif (!empty($JouerRepository->findByUserAndPartie($codePartie, $this->getUser()))) {
            return $this->redirectToRoute('partie_app_partie',
                [
                    'codePartie' => $codePartie->getId(),
                ]
            );
        } elseif ($codePartie->getPartieEtat() == 'EC')
        {
            return $this->redirectToRoute('partie_app_partie',
                [
                    'codePartie' => $codePartie->getId(),
                ]
            );
        } else
            {
            return $this->render('partie/dejaCo.html.twig');
            }

    }

    /**
     * @Route("/{codePartie}", name="app_partie")
     * @param $codePartie
     */
    public function jouerPartie(EntityManagerInterface $entityManager, Partie $codePartie, CarteRepository $carteRepository, JouerRepository $JouerRepository,Request $request){
        $jouer = $JouerRepository->findByUserAndPartie($codePartie, $this->getUser());

        if($request->isMethod('POST')){
            $deMiser = $request->request->get('deMiser');
            $jouer->setDeMiserLoterie($deMiser);
            $jouer->setQuiMiseLoterie(false);
            $entityManager->flush();

        }
        if(!empty($jouer)){

            return $this->render('partie/partieEnCours.html.twig',
                [
                    'partie' => $codePartie,

                ]
            );
        } else {
            return $this->render('partie/dejaCo.html.twig');
        }

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
            $tableauDeCartes = ['acquisition' => [], 'action' => [], 'courrier' => []];
            foreach ($cartes as $carte)
            {
                $tableauDeCartes[$carte->getCarteType()][] = $carte->getId();
            }
            shuffle($tableauDeCartes['acquisition']);
            shuffle($tableauDeCartes['action']);
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
     * @Route("/update-new-partie/data/{codePartie}", name="update_new_game")
     * @param Partie $codePartie
     *
     * @return Response
     */
    public function updateNewGame(BoxRepository $casesRepository, Partie $codePartie, JouerRepository $jouerRepository, UserRepository $userRepository)
    {
        $jouers = $codePartie->getJouers();
        $usernameQuiJoue = $userRepository->findOneBy(['id' => $codePartie->getPartieQuiJoue()])->getUsername();
        foreach ($jouers as $joueur)
        {
            $tabJoueursId[] = $joueur->getUser()->getId();
        }

        $jouer = $jouerRepository->findByUserAndPartie($codePartie, $this->getUser());
        $positions = [];

        foreach ($jouers as $jouer)
        {
            if ($jouer->getUser() !== null) {
                $positions[$jouer->getUser()->getId()]['username'] = $jouer->getUser()->getUsername();
                $positions[$jouer->getUser()->getId()]['position'] = $jouer->getBox();
                $positions[$jouer->getUser()->getId()]['argent'] = $jouer->getArgent();
                $positions[$jouer->getUser()->getId()]['de'] = $jouer->getDe();
                $positions[$jouer->getUser()->getId()]['classement'] = $jouer->getClassement();
                $positions[$jouer->getUser()->getId()]['jaiPioche'] = $jouer->getJaiPioche();

            }
        }
        foreach ($positions as $position){
            $noms[] = $position['username'];
        }
            $array = [

                'tabJoueursId' => $tabJoueursId,
                'joueurEnCours' => $codePartie->getPartieQuiJoue(),
                'positionsJoueurs' => $positions,
                'idJoueur' => $this->getUser()->getId(),
                'noms' => $noms,
                'etatPartie' => $codePartie->getPartieEtat(),
                'usernameQuiJoue' => $usernameQuiJoue,

            ];

            return $this->json($array);


    }




    /**
     * @Route("/update-partie/data/{codePartie}", name="update_game")
     * @param Partie $codePartie
     *
     * @return Response
     */
    public function updateGame(BoxRepository $casesRepository, Partie $codePartie, JouerRepository $jouerRepository, UserRepository $userRepository)
    {
        $jouer = $jouerRepository->findByUserAndPartie($codePartie, $this->getUser());
        $jouers = $codePartie->getJouers();
        foreach ($jouers as $joueur)
        {
            $tabJoueursId[] = $joueur->getUser()->getId();

        }

        // Mon petit script pour réaliser le classement des joueurs en temps réel (rappel: j'ai galéré)
        $tabClassement = $jouerRepository->findBy(['partie' => $codePartie], ['argent' => 'DESC']);
        foreach ($tabClassement as $joueur)
        {
            $classement[] = $joueur->getUser()->getId();
        }
        foreach ($jouers as $joueur)
        {
            for ($i = 0; $i<count($classement);$i++){
                if ($classement[$i] == $joueur->getUser()->getId()){
                    $joueur->setClassement($i+1);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($joueur);
                    $em->flush();
                }
            }
        }
        //Fin script du classement

            $deLance = $jouer->getDeLance();
        $monTour = false;
        $monTourLoterie = false;
        $positions = [];
        $positionsLoterie = [];
        $quiMiseLoterie = $jouer->getQuiMiseLoterie();
        $cartes = $jouer->getCartes();
        $usernameQuiJoue = $userRepository->findOneBy(['id' => $codePartie->getPartieQuiJoue()])->getUsername();
        $positionJoueur = $jouer->getBox();
        $cases = $casesRepository->findAll();
        $de = $jouer->getDe();

        if($positionJoueur == 22) {
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => $positionJoueur]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => $positionJoueur + 1]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => $positionJoueur + 2]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => 1]
            );
        } elseif ($positionJoueur == 23) {
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => $positionJoueur]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => $positionJoueur + 1]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => 1]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => 2]
            );
        } elseif ($positionJoueur == 24) {
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => $positionJoueur]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => 1]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => 2]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => 3]
            );
        } elseif ($positionJoueur < 22) {
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => $positionJoueur]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => $positionJoueur + 1]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => $positionJoueur + 2]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => $positionJoueur + 3]
            );
        };
        for($i=0;$i<count($caseJoueur);$i++){
            $casesId[$i] = $caseJoueur[$i]->getId();
        }
        for($i=0;$i<count($caseJoueur);$i++){
            $casesImage[$i] = $caseJoueur[$i]->getBoxImage();
        }
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
                $positions[$jouer->getUser()->getId()]['jaiPioche'] = $jouer->getJaiPioche();
                $positions[$jouer->getUser()->getId()]['cartes'] = $jouer->getCartes();
                $positions[$jouer->getUser()->getId()]['argentEnAttente'] = $jouer->getArgentEnAttente();

            }
        }
        foreach ($positions as $position){
            $noms[] = $position['username'];
        }
        //Système de tours pour la loterie
        if(!empty($codePartie->getQuiJoueLoterie())){
            foreach ($jouers as $jouer) {
                if ($codePartie->getQuiJoueLoterie() === $this->getUser()->getId())
                {
                    $monTourLoterie = true;
                }
                if ($jouer->getUser() !== null) {
                    $positionsLoterie[$jouer->getUser()->getId()]['username'] = $jouer->getUser()->getUsername();
                    $positionsLoterie[$jouer->getUser()->getId()]['position'] = $jouer->getBox();
                    $positionsLoterie[$jouer->getUser()->getId()]['argent'] = $jouer->getArgent();
                    $positionsLoterie[$jouer->getUser()->getId()]['de'] = $jouer->getDe();
                    $positionsLoterie[$jouer->getUser()->getId()]['classement'] = $jouer->getClassement();
                    $positionsLoterie[$jouer->getUser()->getId()]['jaiPioche'] = $jouer->getJaiPioche();
                }
            }
        };


        $array = [
            'de' => $de,
            'casesId' => $casesId,
            'casesImage' => $casesImage,
            'usernameQuiJoue' => $usernameQuiJoue,
            'tabJoueursId' => $tabJoueursId,
            'joueurEnCours' => $codePartie->getPartieQuiJoue(),
            'monTour' => $monTour,
            'monTourLoterie' => $monTourLoterie,
            'positionsJoueurs' => $positions,
            'cartes' => $cartes,
            'idJoueur' => $this->getUser()->getId(),
            'deLance' => $deLance,
            'quiMiseLoterie' => $quiMiseLoterie,
            'noms' => $noms,
            'cagnotte' => $codePartie->getPartieCagnotte(),
            'etatPartie' => $codePartie->getPartieEtat(),
            'joueur' => $jouer
        ];

        return $this->json($array);
    }


    /**
     * @Route("/update-partie/fin-tour/{codePartie}", name="fin_de_tour")
     * @param Partie $codePartie
     *
     * @return Response
     */
    public function finTour(EntityManagerInterface $entityManager, Partie $codePartie, JouerRepository $jouerRepository)
    {
        $jouers = $codePartie->getJouers();
        $positions = [];
        $jouer = $jouerRepository->findByUserAndPartie($codePartie, $this->getUser());
        $jouer->setDeLance(false);
        $jouer->setJaiPioche(false);

        foreach ($jouers as $jouer)
        {
            $jouer->setQuiMiseLoterie(false);
            if ($jouer->getUser()->getId() === $this->getUser()->getId())
            {
                $monOrdre = $jouer->getClassement();
            }

            if ($jouer->getUser() !== null) {
                $positions[$jouer->getUser()->getId()]['username'] = $jouer->getUser()->getUsername();
                $positions[$jouer->getUser()->getId()]['position'] = $jouer->getClassement();
                $positions[$jouer->getUser()->getId()]['argent'] = $jouer->getArgent();
                $positions[$jouer->getUser()->getId()]['de'] = $jouer->getDe();
                $positions[$jouer->getUser()->getId()]['classement'] = $jouer->getClassement();
            }
            $ordre[$jouer->getClassement()] = $jouer->getUser()->getId();
        }
        if ($monOrdre >= count($ordre))
        {
            $joueurSuivant = $ordre[1];
        } else {
            $joueurSuivant = $ordre[$monOrdre+1];
        }
        $codePartie->setPartieQuiJoue($joueurSuivant);
        $entityManager->persist($codePartie);
        $entityManager->persist($jouer);
        $entityManager->flush();//sauvegarde de l'entité partie


        $array = [
            'joueurEnCours' => $codePartie->getPartieQuiJoue(),
            'monTour' => false,
            'positionsJoueurs' => $positions,
        ];

        return $this->json($array);
    }

    /**
     * @Route("/tirerCarte/{codePartie}", name="tirer_carte")
     * @param Partie $codePartie
     *
     * @return Response
     */
    public function tirerUneCarte($codePartie, $typeDeCarte, $argentEnCours, CarteRepository $carteRepository, EntityManagerInterface $entityManager, JouerRepository $jouerRepository, $nbPioches){
        /*$jouer = $this->getDoctrine()
            ->getRepository(Jouer::class)
            ->createQueryBuilder('j')
            ->where('j.partie = :partie')
            ->andWhere('j.user = :user')
            ->setParameter('partie', $codePartie->getId())
            ->setParameter('user', $this->getUser()->getId())
            ->getQuery()
            ->getOneOrNullResult();
        */
        //$jouerRepository = $this->getDoctrine()->getRepository(Jouer::class);
        //$carteRepository = $this->getDoctrine()->getRepository(Carte::class);
        for($i = 0; $i<$nbPioches; $i++){
            $jouer = $jouerRepository->findByUserAndPartie($codePartie, $this->getUser());
            $pioche = $codePartie->getPartiePioche();
            $carteId = $pioche[$typeDeCarte][$i];
            $objetCarte = $carteRepository->findOneBy(['id' => $carteId]);
            $objetCarteJson = $objetCarte->getJson();
            $carteEffet = $objetCarte->getCarteEffet();
            $carteMontant = $objetCarte->getCarteMontant();
            //dump($argentEnCours);
            if($carteEffet == 'negatif'){
                $argentEnCours -= $carteMontant;
            } elseif ($carteEffet == 'positif'){
                $argentEnCours += $carteMontant;
            }
            $defausse = $codePartie->getPartieDefausse();
            if($defausse == null){
                $defausse = ['tp' => [], 'action' => [], 'notif' => []];
                $defausse[$objetCarte->getCarteType()][] = $objetCarte->getId();
            } else {
                $defausse[$typeDeCarte][] = $objetCarte->getId();
            }

            //dump($argentEnCours);
            $jouer->setArgent($argentEnCours);

            $mesCartes = $jouer->getCartes();
            $mesCartes[] = $objetCarteJson;
            $jouer->setCartes($mesCartes);
            $codePartie->setPartieDefausse($defausse);
            array_shift($pioche[$typeDeCarte]);
            $codePartie -> setPartiePioche($pioche);
            $entityManager->persist($jouer);
            $entityManager->persist($codePartie);
            $entityManager->flush();
            //dump($jouer->getArgent());
            //dump($jouer);
        }

    }

    /**
     * @Route("/tirerTp/{codePartie}", name="tirer_tp")
     * @param Partie $codePartie
     *
     * @return Response
     */
    public function tirerCarteTp(EntityManagerInterface $entityManager, Partie $codePartie, CarteRepository $carteRepository, JouerRepository $jouerRepository)
    {
        $pioche = $codePartie->getPartiePioche();
        $carteId = $pioche['tp'][0];
        $objetCarte = $carteRepository->findOneBy(['id' => $carteId]);
        $objetCarteJson = $objetCarte->getJson();
        //$carteEffet = $objetCarte->getCarteEffet();
        //$carteMontant = $objetCarteDecode->montant;
        //$carteGainMise = $objetCarteDecode->montant;
        $jouer = $jouerRepository->findByUserAndPartie($codePartie, $this->getUser());
        $defausse = $codePartie->getPartieDefausse();
        if($defausse == null){
            $defausse = ['tp' => [], 'action' => [], 'notif' => []];
            $defausse[$objetCarte->getCarteType()][] = $objetCarte->getId();
        } else {
            $defausse['tp'][] = $objetCarte->getId();
        }

        $mesCartes = $jouer->getCartes();
        $mesCartes[] = $objetCarteJson;
        $jouer->setCartes($mesCartes);
        $entityManager->persist($jouer);
        $entityManager->flush();
    }
    /**
     * @Route("/update-partie/miser-carte-tp/{codePartie}", name="miser_carte_tp")
     * @param Partie $codePartie
     *
     * @return Response
     */
    public function miserCarteTp(EntityManagerInterface $entityManager, Partie $codePartie, JouerRepository $jouerRepository, CarteRepository $carteRepository)
    {
        $pioche = $codePartie->getPartiePioche();
        $carteId = $pioche['tp'][0];
        $objetCarte = $carteRepository->findOneBy(['id' => $carteId]);
        $gainMise = $objetCarte->getGainMise();
        $jouer = $jouerRepository->findByUserAndPartie($codePartie, $this->getUser());
        $argentDejaEnAttente = $jouer->getArgentEnAttente();
        $gainMise += $argentDejaEnAttente;
        $jouer->setArgentEnAttente($gainMise);
        $argent = $jouer->getArgent();
        $argent -= $objetCarte->getCarteMontant();
        array_shift($pioche['tp']);
        $codePartie->setPartiePioche($pioche);
        $jouer->setArgent($argent);
        $jouer->setJaiPioche(true);
        $codePartie->setQuiJoueLoterie($jouer->getId());
        $entityManager->persist($jouer);
        $entityManager->flush();
        $array = [
            'argent' => $gainMise
        ];
        return $this->json($array);
    }


    /**
     * @Route("/update-partie/miser-loterie/{codePartie}", name="miser_loterie")
     * @param Partie $codePartie
     *
     * @return Response
     */
    public function miserLoterie(EntityManagerInterface $entityManager, Partie $codePartie, JouerRepository $jouerRepository)
    {
        $joueurs = $codePartie->getJouers();
        foreach($joueurs as $joueur)
        {
            $joueur->setQuiMiseLoterie(true);
        }
        $jouer = $jouerRepository->findByUserAndPartie($codePartie, $this->getUser());
        $codePartie->setQuiJoueLoterie($jouer->getId());
        $entityManager->flush();
    }


    /*    public function tirerUneCarteTp($codePartie, $argentEnCours, $carteRepository, $entityManager, $jouer)
    {

        $pioche = $codePartie->getPartiePioche();
        $carteId = $pioche['tp'][0];
        $objetCarte = $carteRepository->findOneBy(['id' => $carteId]);
        //$carteEffet = $objetCarte->getCarteEffet();
        $carteMontant = $objetCarte->getCarteMontant();
        $carteGainMise = $objetCarte->getGainMise();
        $jouer->setCarte($objetCarte);
        $entityManager->persist($jouer);
        $entityManager->flush();
    }
    */

    /**
     * @Route("/update-partie/lance_de/{codePartie}", name="lance_de")
     * @param Partie $codePartie
     *
     * @return Response
     * @throws NonUniqueResultException
     */
    public function lanceDe(EntityManagerInterface $entityManager, JouerRepository $jouerRepository, Partie $codePartie, CarteRepository $carteRepository)
    {
        $jouer = $jouerRepository->findByUserAndPartie($codePartie, $this->getUser());
        $codePartie->setPartieEtat('EC');
        $em = $this->getDoctrine()->getManager();
        $em->persist($codePartie);
        $em->flush();
        $de = rand(1, 6);
        $position = $jouer->getBox()+$de;
        $finTour=false;
        $tourDejaIncr = false;
        if ($position >= 24) {
            $position = 1; //fin du tour
            $nbTours = $jouer->getTour();
            $nbTours += 1;
            $nbToursRestants = $jouer->getNbToursRestants();
            $nbToursRestants -= 1;
            $jouer->setNbToursRestants($nbToursRestants);
            $jouer->setTour($nbTours);
            if($nbTours == $codePartie->getNbTours()){
                $codePartie->setPartieEtat('T');
                $codePartie->setPartieGagnant($this->getUser()->getId());
            }
        }
        $finTour=true;


        $user = $this->getUser();
        //$de = 0;
        $argentEnCours = $jouer->getArgent();
        switch ($position){
            case 1:
                //case rendu
                $argentGagner = $jouer->getArgentEnAttente();
                $argentEnCours += $argentGagner;
                $argentEnCours -= 100;
                $cagnotte = $codePartie->getPartieCagnotte();
                $cagnotte += 100;
                $codePartie->setPartieCagnotte($cagnotte);
                $jouer->setArgent($argentEnCours);
                $jouer->setArgentEnAttente(0);
            break;
            case 2:
                $this->tirerUneCarte($codePartie, 'notif', $argentEnCours, $carteRepository, $entityManager, $jouerRepository, 2);
                break;
            case 3:
                $this->tirerUneCarte($codePartie, 'action', $argentEnCours, $carteRepository, $entityManager, $jouerRepository, 1);
            break;
            case 4:
                // Tous les joueurs reculent d'une case
                $joueurs = $codePartie->getJouers();
                foreach ($joueurs as $joueur)
                {
                    $caseEnCours = $joueur->getBox();
                    $caseEnCours -= 1;
                    $joueur->setBox($caseEnCours);
                    $entityManager->persist($joueur);
                    $entityManager->flush();
                }
                break;
            case 5:
                // Tu viens seulement de te coucher
                $argentEnCours -= 100;
                $jouer->setArgent($argentEnCours);

                break;
            case 6:
                $argentEnCours += 2000;
                $jouer->setArgent($argentEnCours);

                break;
            case 7:
                $argentEnCours  -= 50; // Tu écoutes de la musique : -50Mo
                $jouer->setArgent($argentEnCours);

                break;
            case 8:
                $this->tirerUneCarte($codePartie, 'notif', $argentEnCours, $carteRepository, $entityManager, $jouerRepository, 3);
                break;
            case 9:
                $joueurs = $codePartie->getJouers();
                foreach ($joueurs as $joueur)
                {
                    if($joueur->getUser()->getId() === $this->getUser()->getId())
                    {
                        $argentEnCours += 200*(count($joueurs)-1);
                        $joueur->setArgent($argentEnCours);
                    } else
                    {
                        $argent = $joueur->getArgent();
                        $argent -= 200;
                        $joueur->setArgent($argent);
                        $entityManager->persist($joueur);
                        $entityManager->flush();
                     }
                }

                break;
            case 10:
                //case tp
                $this->tirerCarteTp($entityManager, $codePartie, $carteRepository, $jouerRepository);
                break;
            case 11:
                //case action
                $this->tirerUneCarte($codePartie, 'action', $argentEnCours, $carteRepository, $entityManager, $jouerRepository, 1);
                break;
            case 13:
                //case rendu
                $argentGagner = $jouer->getArgentEnAttente();
                $argentEnCours += $argentGagner;
                $jouer->setArgent($argentEnCours);
                $jouer->setArgentEnAttente(0);
                break;
            case 14:
                // case notif x1
                $this->tirerUneCarte($codePartie, 'notif', $argentEnCours, $carteRepository, $entityManager, $jouerRepository, 1);
            break;
            case 15:
                $this->tirerCarteTp($entityManager, $codePartie, $carteRepository, $jouerRepository);
            break;
            case 16:
                $joueurs = $codePartie->getJouers();
                foreach ($joueurs as $joueur)
                {
                    $argentJoueur = $joueur->getArgent();
                    $argentJoueur -= 100;
                    $joueur->setArgent($argentJoueur);
                }
                $cagnotte = $codePartie->getPartieCagnotte();
                $cagnotte += 100*(count($joueurs));
                $codePartie->setPartieCagnotte($cagnotte);
                break;
            case 17:
                $this->tirerUneCarte($codePartie, 'action', $argentEnCours, $carteRepository, $entityManager, $jouerRepository, 1);
            break;
            case 19:
                // case notif x2
                $this->tirerUneCarte($codePartie, 'notif', $argentEnCours, $carteRepository, $entityManager, $jouerRepository, 2);
                break;
            case 20:
                $this->tirerCarteTp($entityManager, $codePartie, $carteRepository, $jouerRepository);
            break;
            case 21:
                //case rendu
                $argentGagner = $jouer->getArgentEnAttente();
                $argentEnCours += $argentGagner;
                $jouer->setArgent($argentEnCours);
                $jouer->setArgentEnAttente(0);
            break;
            case 22:
                // Tu regardes netflix avec la connexion de ton voisin !
                $argentEnCours += 50;
                $jouer->setArgent($argentEnCours);

                break;
            case 23:
                $this->tirerUneCarte($codePartie, 'action', $argentEnCours, $carteRepository, $entityManager, $jouerRepository, 1);
                /*$banque = $codePartie->getBanque();
                $banque += 200;
                $this->miserLoterie($entityManager, $codePartie, $jouerRepository);*/
                break;
        }
        //$argentEnCours = $jouer->getArgent();
        ;
        /*if($anciennePosition > 24){
            $argentEnCours +=2000;
            $anciennePosition = 1;
            $objetJouerDeUser->setBox($anciennePosition);
        } else {
            $objetJouerDeUser->setBox($anciennePosition);
        }*/
        $jouer->setDeLance(true);
        $jouer->setDe($de);
        $jouer->setBox($position);
        $entityManager->persist($user);
        $entityManager->persist($jouer);
        $entityManager->flush();//sauvegarde de l'entité partie
        $array = [
            'de' => $de,
            'finTour' => $finTour,
            'position' => $position
        ];

        return $this->json($array);
    }

    /**
     * @Route("/affiche-plateau/{codePartie}", name="affiche_plateau")
     * @param BoxRepository $casesRepository
     * @param Partie $codePartie
     *
     * @return Response
     * @throws NonUniqueResultException
     */
    public function affichePlateau(BoxRepository $casesRepository, Partie $codePartie, JouerRepository $jouerRepository, EntityManagerInterface $entityManager)
    {
        $jouers = $codePartie->getJouers();
        $positions = [];
        foreach ($jouers as $jouer) {
            if (!array_key_exists($jouer->getBox(), $positions)) {
                $positions[$jouer->getBox()] = [];
            }
            $positions[$jouer->getBox()][] = $jouer;
        }
        $jouer = $jouerRepository->findByUserAndPartie($codePartie, $this->getUser());
        $positionJoueur = $jouer->getBox();


        if($positionJoueur == 22) {
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => $positionJoueur]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => $positionJoueur + 1]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => $positionJoueur + 2]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => 1]
            );
        } elseif ($positionJoueur == 23) {
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => $positionJoueur]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => $positionJoueur + 1]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => 1]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => 2]
            );
        } elseif ($positionJoueur == 24) {
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => $positionJoueur]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => 1]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => 2]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => 3]
            );
        } elseif ($positionJoueur < 22) {
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => $positionJoueur]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => $positionJoueur + 1]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => $positionJoueur + 2]
            );
            $caseJoueur[] = $casesRepository->findOneBy(
                ['box_position' => $positionJoueur + 3]
            );
        };


        $allCases = $casesRepository->findAll();
        return $this->render('partie/index2.html.twig',
            [
                'allCases' => $allCases,
                'joueurs' => $jouers,
                'cases' => $caseJoueur,
                'partie' => $codePartie,
                'positions' => $positions,
                'cartes' => $jouer->getCartes(),
                'jouer' => $jouer
            ]);
    }





}