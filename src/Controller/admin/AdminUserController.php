<?php

namespace App\Controller\admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController {

    #[Route('/admin/create-user', name:'admin-create-user')]
    public function displayCreateUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager){

        if($request->isMethod('POST')){

            $email = $request->request->get('email');
            $password = $request->request->get('password');

            $user = new User();

            $passwordHashed = $userPasswordHasher->hashPassword($user, $password);

            $user->createAdmin($email, $passwordHashed);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash("admin_created", "Le compte administrateur a été créé.");
        }

        return $this->render('/admin/user/create-user.html.twig');
    }
}