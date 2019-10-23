<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AdFormType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="index.ads")
     */
    public function index(AdRepository $repo) : Response
    {
        $ads = $repo->findAll();
        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    /**
     * @Route("/ads/new", name="ads.create")
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request, EntityManagerInterface $em) : Response
    {
        $ad = new Ad();
        $form = $this->createForm(AdFormType::class, $ad);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $em->persist($image);
            }
            $ad->setAuthor($this->getUser());
            $em->persist($ad);
            $em->flush();
            $this->addFlash('success', "L'annonce " . $ad->getTitle() . " a bien été crée");
            return $this->redirectToRoute('show.ads', ['slug' => $ad->getSlug()]);
        }
        return $this->render('ad/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/ads/{slug}/edit", name="ads.edit")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Erreur")
     *
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AdFormType::class, $ad);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $em->persist($image);
            }
            $em->persist($ad);
            $em->flush();
            $this->addFlash('success', "L'annonce " . $ad->getTitle() . " a bien été modifié");
            return $this->redirectToRoute('show.ads', ['slug' => $ad->getSlug()]);
        }
        return $this->render('ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/ads/{slug}", name="show.ads")
     */
    public function show(Ad $ad) : Response
    {
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }

    /**
     * @Route("ads/{slug}/delete", name="ads.delete")
     * @Security("is_granted('ROLE_USER') and user == ad.getAuthor()", message="Erreur delete")
     */
    public function delete(Ad $ad, EntityManagerInterface $em)
    {
        $em->remove($ad);
        $em->flush();
        $this->addFlash('success', "L'annonce " . $ad->getTitle() . " a bien été supprimée");
        return $this->redirectToRoute('index.ads');
    }
}
