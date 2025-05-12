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
}
