<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPageController extends AbstractController
{

    #[Route('/admin', name: 'home-admin')]
    public function displayHome()
    {
        return $this->render('admin/admin-home.html.twig');
    }
}
