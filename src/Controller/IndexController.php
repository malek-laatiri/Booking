<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    /**
     * @Route("/begin/{first_day}", name="scroll_calendar")
     */
    public function home($first_day)
    {
        return $this->render('index/index.html.twig', ['first_day' => $first_day,]);
    }

    /**
     * @Route("/", name="change")
     */
    public function index()
    {
        $firstDay =  new \DateTime();
        return $this->render('index/index.html.twig', [
            'controller_name' => 'HomeController',
            'first_day' => $firstDay,

        ]);
    }
}
