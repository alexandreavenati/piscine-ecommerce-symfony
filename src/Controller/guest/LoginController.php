<?php

namespace App\Controller\guest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{

    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function displayLogin(AuthenticationUtils $authenticationUtils): Response
    {

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('guest/login.html.twig', ['error' => $error]);
    }

    #[Route('/redirect-after-login', name: 'redirect_after_login', methods: ['GET'])]
    public function redirectAfterLogin(): Response
    {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('home-admin');
        }

        // Redirection par défaut
        return $this->redirectToRoute('home');
    }

    #[Route('/logout', name:'logout', methods: ['GET'])]
    public function logout() {
        
    }
}
