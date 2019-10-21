<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account.login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $last_username = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('account/login.html.twig', [
            'last_username' => $last_username,
            'error' => $error
        ]);
    }

    /**
     * @Route("/logout", name="account.logout")
     */
    public function logout() {}

    /**
     * @Route("/register", name="account.register")
     */
    public function register(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', "Votre compte a bien été créé");
            return $this->redirectToRoute('account.login');
        }
        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
