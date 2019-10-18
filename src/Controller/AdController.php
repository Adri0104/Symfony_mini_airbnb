<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdFormType;
use App\Repository\AdRepository;
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
     */
    public function create(Request $request) : Response
    {
        $ad = new Ad();
        $form = $this->createForm(AdFormType::class, $ad);
        $form->handleRequest($request);
        return $this->render('ad/create.html.twig', [
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
}
