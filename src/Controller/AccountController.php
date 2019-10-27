<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountFormType;
use App\Form\PasswordUpdateFormType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
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

    /**
     * @Route("account/profile", name="account.profile")
     * @IsGranted("ROLE_USER")
     */
    public function profile(Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountFormType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', "Votre compte a bien été modifié");
//            return $this->redirectToRoute('account.login');
        }
        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/update-password", name="account.password")
     * @IsGranted("ROLE_USER")
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
    {
        $pwdUpdate = new PasswordUpdate();
        $user = $this->getUser();
        $form = $this->createForm(PasswordUpdateFormType::class, $pwdUpdate);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if(!password_verify($pwdUpdate->getOldPassword(), $user->getHash())) {
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé ne correspond pas à l'ancien"));
            } else {
                $newPwd = $pwdUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPwd);
                $user->setHash($hash);
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', "Votre mot de passe a bien été modifié");
                return $this->redirectToRoute('index.home');
            }
        }
        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account", name="account.index")
     * @IsGranted("ROLE_USER")
     */
    public function myAccount()
    {
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/account/booking", name="account.booking")
     */
    public function bookingd()
    {
        return $this->render('account/bookings.html.twig');
    }
}
