<?php

namespace App\Controller;

use App\Entity\Reduction;
use App\Form\Reduction1Type;
use App\Form\ReductionType;
use App\Repository\ReductionRepository;
use DateInterval;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reduction")
 */
class ReductionController extends AbstractController
{
    /**
     * @Route("/", name="reduction_index", methods={"GET","POST"})
     */
    public function index(ReductionRepository $reductionRepository, Request $request): Response
    {
        $reduction = new Reduction();
        $form = $this->createForm(Reduction1Type::class, $reduction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $csv = array_map('str_getcsv', file($form['csv']->getData()));
            foreach ($csv as $value) {
                $x = new Reduction();
                $x->setCodeReduction($value[1]);
                $x->setDateDebut(new \DateTime($value[2]));

                $x->setDateFin(new \DateTime($value[3]));
                $x->setTaux($value[4]);
                $x->setIsexpired(false);
                dump($value);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($x);
            }


            $entityManager->flush();

            return $this->redirectToRoute('reduction_index');
        }
        return $this->render('reduction/index.html.twig', [
            'reductions' => $reductionRepository->findAll(),
            'form' => $form->createView(),
            'reduction' => $reduction,

        ]);
    }

    /**
     * @Route("/new", name="reduction_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $reduction = new Reduction();
        $form = $this->createForm(ReductionType::class, $reduction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reduction);
            $entityManager->flush();

            return $this->redirectToRoute('reduction_index');
        }

        return $this->render('reduction/new.html.twig', [
            'reduction' => $reduction,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reduction_show", methods={"GET"})
     */
    public function show(Reduction $reduction): Response
    {
        return $this->render('reduction/show.html.twig', [
            'reduction' => $reduction,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reduction_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reduction $reduction): Response
    {
        $form = $this->createForm(ReductionType::class, $reduction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reduction_index');
        }

        return $this->render('reduction/edit.html.twig', [
            'reduction' => $reduction,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reduction_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Reduction $reduction): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reduction->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reduction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reduction_index');
    }


    /**
     * @Route("/newRed", name="reduction_new", methods={"GET","POST"})
     */
    public function new1(Request $request): Response
    {
        $reduction = new Reduction();
        $form = $this->createForm(Reduction1Type::class, $reduction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reduction);
            $entityManager->flush();

            return $this->redirectToRoute('reduction_index');
        }

        return $this->render('reduction/new.html.twig', [
            'reduction' => $reduction,
            'form' => $form->createView(),
        ]);
    }
}
