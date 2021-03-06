<?php

namespace App\Controller;

use App\Entity\Chambre;
use App\Entity\Hotel;
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
    /**
     * @Route("/insertroom", name="insertroom")
     */
    public function insertroom()
    {           $entityManager = $this->getDoctrine()->getManager();

        $hotel=new Hotel();
        $hotel->setNom("plaza hotel");
        $hotel->setTVA(25);
        $entityManager->persist($hotel);

        for ($i=1;$i<200;$i++){
            $chambre=new Chambre();
            $chambre->setHotel($hotel);
            $chambre->setNumChambre($i);
            $entityManager->persist($chambre);
        }
        $entityManager->flush();
        return $this->render('index/index.html.twig');

    }

}
