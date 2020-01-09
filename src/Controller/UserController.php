<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\FactureRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Swift_Attachment;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(ReservationRepository $reservationRepository,FactureRepository $factureRepository): Response
    {
        $facture=$factureRepository->findAll();
        $reservation=$reservationRepository->findAll();
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'facture'=>$facture,
            'reservation'=>$reservation
        ]);
    }
    /**
     * @Route("/log", name="log", methods={"GET"})
     */
    public function log(ReservationRepository $reservationRepository,FactureRepository $factureRepository,UserRepository $userRepository): Response
    {
        $facture=$factureRepository->findAll();
        $reservation=$reservationRepository->findAll();
        $user=$userRepository->findAll();


        return $this->render('user/log.html.twig', [
            'user'=>$user,
            'facture'=>$facture,
            'reservation'=>$reservation
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request,\Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            /** @var  $user */
            $userDATA = $form->getData();

            $user =new User();
            $user->setUsername($userDATA->getUsername());
            $user->setEmail($userDATA->getEmail());
            $user->setEmailCanonical($userDATA->getEmail());
            $user->setEnabled(1); // enable the user or enable it later with a confirmation token in the email
            // this method will encrypt the password with the default settings :)
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $count = mb_strlen($chars);

            for ($i = 0, $result = ''; $i < 8; $i++) {
                $index = rand(0, $count - 1);
                $result .= mb_substr($chars, $index, 1);
            }
            $user->setPlainPassword($result);
            $user->setPassword($result);
            $user->setUsernameCanonical($userDATA->getUsername());
            $user->setRoles(['ROLE_USER']);

            $message = (new Swift_Message('account activated'))

                ->setFrom('testagence6@gmail.com')
                ->setTo($user->getEmail())
                ->setBody("Username: ".$user->getUsername()." Password:  ".$user->getPassword())
            ;

            $mailer->send($message);

            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
