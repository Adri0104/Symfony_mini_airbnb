<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin.account.login")
     */
    public function index(AuthenticationUtils $authenticationUtils)
    {
        $last_username = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('admin/account/login.html.twig', [
            'last_username' => $last_username,
            'error' => $error
        ]);
    }

    /**
     * @Route("/admin/logout", name="admin.account.logout")
     */
    public function logout() {}
}
