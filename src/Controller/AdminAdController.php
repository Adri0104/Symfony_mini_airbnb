<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdFormType;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin.ads.index")
     */
    public function index(AdRepository $repo, $page, PaginationService $paginationService)
    {
        $paginationService->setEntityClass(Ad::class)
            ->setCurrentPage($page);
        return $this->render('admin/ad/index.html.twig', [
            'pagination' => $paginationService
        ]);
    }

    /**
     * @Route("/admin/ads/{id}/edit", name="admin.ads.edit")
     * @param Ad $ad
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AdFormType::class, $ad);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($ad);
            $em->flush();
            $this->addFlash('success', "L'annonce " . $ad->getTitle() . " a bien été modifié");
        }
        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/ads/{id}/delete", name="admin.ads.delete")
     */
    public function delete(Ad $ad, EntityManagerInterface $em) : Response
    {
        if(count($ad->getBookings()) > 0) {
            $this->addFlash('warning', "Vous ne pouvez pas supprimer cette annonce car elle possède des réservations");
        } else {
            $em->remove($ad);
            $em->flush();
            $this->addFlash('success', "L'annonce " . $ad->getTitle() . " a bien été supprimée");
        }
        return $this->redirectToRoute('admin.ads.index');
    }
}
