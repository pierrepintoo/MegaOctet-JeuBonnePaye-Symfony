<?php
// src/Controller/LuckyController.php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RandomController extends AbstractController
{
    /**
     * @Route("/random/number", name="app_random_number")
     */
    public function number()
    {
        $number = mt_rand(1, 6);
        return $this->render('random/number.html.twig', array(
            'number' => $number,
        ));
    }
}
?>