<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Client;
use App\Entity\ProprietaireSalle;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // Last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
    #[Route('/login/check', name: 'login_check')]
    public function loginCheck(): Response
    {
        // This route will be intercepted by Symfony's security system.
        return new Response('', Response::HTTP_FORBIDDEN); // Placeholder response
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Symfony handles the logout automatically; this function can be left empty.
    }
}
