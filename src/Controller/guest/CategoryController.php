<?php

namespace App\Controller\guest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;

class CategoryController extends AbstractController {

    #[Route('/categories', name: 'category-list', methods: ['GET'])]
    public function displayListCategory(CategoryRepository $categoryRepository): Response
    {

        $categories = $categoryRepository->findAll();

        return $this->render('guest/category/categories-list.html.twig', ['categories' => $categories]);
    }

    #[Route('/categorie/{id}', name: 'show-category', methods: ['GET'])]
    public function showCategory(CategoryRepository $categoryRepository, int $id): Response
    {
        $category = $categoryRepository->find($id);

        if(!$category) {
            return $this->redirectToRoute('guest-404');
        }

        return $this->render('guest/category/show-category.html.twig', ['category' => $category]);
    }
}