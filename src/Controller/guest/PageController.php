<?php

namespace App\Controller\guest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function displayHome(): Response
    {
        return $this->render('guest/home.html.twig');
    }

    #[Route('/guest/error-404', name:'guest-404')]
    public function display404(): Response {

        $html = $this->renderView('guest/guest-404.html.twig');
		
		return new Response($html, '404');
    }
}
