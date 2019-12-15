<?php

namespace App\Controller;

use App\Entity\Recherche;
use App\Entity\Reduction;
use App\Entity\Reservation;
use App\Form\Recherche1Type;
use App\Form\Reservation1Type;
use App\Form\ReservationType;
use App\Repository\HotelRepository;
use App\Repository\ReductionRepository;
use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Exception\InvalidPathException;
use Endroid\QrCode\QrCode;
use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reservation")
 */
class ReservationController extends AbstractController
{
    /**
     * @Route("/", name="reservation_index", methods={"GET","POST"})
     */
    public function index(Request $request, ReservationRepository $reservationRepository): Response
    {
        $search = new Recherche();
        // ...

        $form = $this->createForm(Recherche1Type::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $search = $form->getData();
            return $this->redirectToRoute('reservation_show1', ['voucher' => $search->getCode()]);
        }
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/new", name="reservation_new", methods={"GET","POST"})
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @param ReductionRepository $reductionRepository
     * @param HotelRepository $hotelRepository
     * @return Response
     * @throws InvalidPathException
     */
    public function new(Request $request, Swift_Mailer $mailer, ReductionRepository $reductionRepository, HotelRepository $hotelRepository): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newReservation = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $interval = $newReservation->getDateDepart()->diff($newReservation->getDateArrivee());
            $date = new \DateTime();
             $date->format('Y-m-d');
            if ($newReservation->getDateArrivee() >$date || $interval->format("%a") > 10){
                return $this->render('index/404.html.twig');
            }
            if (!$newReservation->getReduction() instanceof ArrayCollection) {
                $remiseEctrat = $reductionRepository->findBy(['codeReduction' => $newReservation->getReduction()->getcodeReduction()]);
                if ($remiseEctrat) {
                    $remise = array_values($remiseEctrat)[0];
                    $newPrice = $reservation->getFacture()->getMontant() - ($reservation->getFacture()->getMontant() * $remise->getTaux() / 100);
                    $reservation->getFacture()->setMontant($newPrice);
                    $remise->setIsexpired(true);

                    $reservation->setReduction($remise);
                } else {
                    $referer = $request->headers->get('referer');
                    return $this->redirect($referer);
                }
            } else {
                $reservation->setReduction(new Reduction());
            }
            $reservation->addCreatedby($this->getUser());

            $entityManager->persist($reservation);
            $entityManager->flush();


            $qrCode = new QrCode($reservation->getVoucher());
            $qrCode->setSize(300);
            $qrCode->setWriterByName('png');
            $qrCode->setMargin(10);
            $qrCode->setEncoding('UTF-8');
            $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH());
            $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
            $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);

            $qrCode->setLogoSize(150, 200);
            $qrCode->setRoundBlockSize(true);
            $qrCode->setValidateResult(false);
            $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
            $qrCode->writeFile(__DIR__ . '/../../public/QR/x.png');

            $message = (new Swift_Message('Hello Email'))
                ->attach(Swift_Attachment::fromPath(__DIR__ . '/../../public/QR/x.png')
                    ->setDisposition('inline'))
                ->setFrom('testagence6@gmail.com')
                ->setTo('malek.laatiri73@gmail.com')
                ->setBody($this->render('emails/confirmation.html.twig', ['reservation' => $reservation, 'qrcode' => $qrCode->getContentType()])
                    , 'text/html');

            $mailer->send($message);
            return $this->redirectToRoute('reservation_index');
        }


        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reservation_show", methods={"GET"})
     */
    public function show(Reservation $reservation): Response
    {
        $qrCode = new QrCode($reservation->getVoucher());
        $qrCode->setSize(300);
        $qrCode->setWriterByName('png');
        $qrCode->setMargin(10);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH());
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        $qrCode->setLogoSize(150, 200);
        $qrCode->setRoundBlockSize(true);
        $qrCode->setValidateResult(false);
        $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
        $qrCode->writeFile(__DIR__ . '/../../public/QR/x.png');

        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
            'qr' => $response = new Response($qrCode->writeString(), Response::HTTP_OK, ['Content-Type' => $qrCode->getContentType()])
            ,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reservation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reservation $reservation): Response
    {
        $form = $this->createForm(Reservation1Type::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/download", name="reservation_download", methods={"GET"})
     */
    public function pdfAction(Reservation $reservation)
    {
        $pdfOptions = new Options();
        $pdfOptions->isHtml5ParserEnabled(true);
        $pdfOptions->isRemoteEnabled(true);
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        $html = $this->renderView('emails/confirmation.html.twig', [
            'reservation' => $reservation
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
    }

    /**
     * @Route("/{id}", name="reservation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Reservation $reservation): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reservation_index');
    }

    /**
     * @Route("/{voucher}/show", name="reservation_show1", methods={"GET"})
     */
    public function show1(Reservation $reservation): Response
    {
        $qrCode = new QrCode($reservation->getVoucher());
        $qrCode->setSize(300);
        $qrCode->setWriterByName('png');
        $qrCode->setMargin(10);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH());
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        $qrCode->setLogoSize(150, 200);
        $qrCode->setRoundBlockSize(true);
        $qrCode->setValidateResult(false);
        $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
        $qrCode->writeFile(__DIR__ . '/../../public/QR/x.png');

        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
            'qr' => $response = new Response($qrCode->writeString(), Response::HTTP_OK, ['Content-Type' => $qrCode->getContentType()])
            ,
        ]);
    }
}
