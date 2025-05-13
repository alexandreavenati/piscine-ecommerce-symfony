<?php

namespace App\Controller\guest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;

class ProductController extends AbstractController {

    #[Route('/produits', name: 'product-list')]
    public function displayListCategory(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {

        $products = $productRepository->findBy(['isPublished' => true]);
        $category = $categoryRepository->findAll();

        return $this->render('guest/product/products-list.html.twig', ['products' => $products, 'categories' => $category]);
    }

    #[Route('/produit/{id}', name: 'show-product')]
    public function showCategory(ProductRepository $productRepository, CategoryRepository $categoryRepository, $id)
    {

        $product = $productRepository->find($id);
        
        if(!$product) {
			return $this->redirectToRoute("guest-404");
		}

        $category = $categoryRepository->findAll();

       

        return $this->render('guest/product/show-product.html.twig', ['product' => $product, 'category' => $category]);
    }
}