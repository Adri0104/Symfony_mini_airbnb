<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingFormType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings", name="admin.booking.index")
     */
    public function index(BookingRepository $repo)
    {
        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/admin/bookings/{id}/edit", name="admin.booking.edit")
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $em) : Response
    {
        $form = $this->createForm(AdminBookingFormType::class, $booking);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $booking->setAmount(0);
            $em->persist($booking);
            $em->flush();
            $this->addFlash('success', "La réservation n°" . $booking->getId() . "a bien été modifié");
            return $this->redirectToRoute('admin.booking.index');
        }
        return $this->render('admin/booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/bookings/{id}/delete", name="admin.booking.delete")
     */
    public function delete(Booking $booking, EntityManagerInterface $em) : Response
    {
        $em->remove($booking);
        $em->flush();
        $this->addFlash('success', "La réservation de°" . $booking->getBooker()->getFullName() . "a bien été supprimé");
        return $this->redirectToRoute('admin.booking.index');
    }
}
