<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminPageController extends AbstractController
{

    #[Route('/admin', name: 'home-admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function displayHome()
    {
        return $this->render('admin/admin-home.html.twig');
    }

    #[Route('/admin/error-404', name:'admin-404')]
    public function display404() {

        $html = $this->renderView('admin/admin-404.html.twig');
		
		return new Response($html, '404');
    }
}
