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
        $Joueur = $this->getDoctrine()
            ->getRepository(Jouer::class)
            ->findBy(['user' => $codePartie]);
        /*$classementJoueur = $Joueur->getClassement();
        dump($classementJoueur);
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
        ;*/

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
        if($nbUsers >= 6){
            return $this->redirectToRoute('partie_app_partie',
                [
                    'codePartie' => $codePartie
                ]);
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
    public function jouerPartie(Partie $codePartie, JouerRepository $JouerRepository, Request $request, $queryBuilder){


        if ($request->isMethod('POST'))
        {
            $user = $this->getUser();
            $objetJouerDeUser = $JouerRepository->findBy(['partie'=>$codePartie, 'user' => $user]);
            dump($objetJouerDeUser);
            $de = mt_rand(1, 6);
            /*$queryBuilder
                ->insert('jouer')
                ->values(
                    array(
                        'de' => '?',
                    )
                )
                ->setParameter(0, $de)
            ;
            $objetJouerDeUser['de'] = $de;
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();*/
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

}
