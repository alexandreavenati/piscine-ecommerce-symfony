<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{

    #[Route('/admin/create-user', name: 'admin-create-user')]
    public function displayCreateUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {

        if ($request->isMethod('POST')) {

            $email = $request->request->get('email');
            $password = $request->request->get('password');

            $user = new User();

            $passwordHashed = $userPasswordHasher->hashPassword($user, $password);

            $user->createAdmin($email, $passwordHashed);

            try {
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash("admin_created", "Le compte administrateur a été créé.");
                return $this->redirectToRoute('admin-list-admin');

            } catch (UniqueConstraintViolationException $e) {

                $this->addFlash("error_email_taken", "Erreur : email déjà pris.");

            } catch (Exception $e) {

                $this->addFlash("error_admin_created", "Erreur : Impossible de créer l'admin.");
            }
        }

        return $this->render('/admin/user/create-user.html.twig');
    }

    #[Route('/admin/list-admin', name:'admin-list-admin')]
    public function displayListAdmins(UserRepository $userRepository): Response {

        $users = $userRepository->findAll();

		return $this->render('/admin/user/admin-list.html.twig', ['users' => $users]);
    }
}
