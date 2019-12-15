<?php

namespace App\Controller;

use App\Entity\Extras;
use App\Form\ExtrasType;
use App\Repository\ExtrasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/extras")
 */
class ExtrasController extends AbstractController
{
    /**
     * @Route("/", name="extras_index", methods={"GET"})
     */
    public function index(ExtrasRepository $extrasRepository): Response
    {
        return $this->render('extras/index.html.twig', [
            'extras' => $extrasRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="extras_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $extra = new Extras();
        $form = $this->createForm(ExtrasType::class, $extra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($extra);
            $entityManager->flush();

            return $this->redirectToRoute('extras_index');
        }

        return $this->render('extras/new.html.twig', [
            'extra' => $extra,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="extras_show", methods={"GET"})
     */
    public function show(Extras $extra): Response
    {
        return $this->render('extras/show.html.twig', [
            'extra' => $extra,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="extras_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Extras $extra): Response
    {
        $form = $this->createForm(ExtrasType::class, $extra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('extras_index');
        }

        return $this->render('extras/edit.html.twig', [
            'extra' => $extra,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="extras_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Extras $extra): Response
    {
        if ($this->isCsrfTokenValid('delete'.$extra->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($extra);
            $entityManager->flush();
        }

        return $this->redirectToRoute('extras_index');
    }
}
