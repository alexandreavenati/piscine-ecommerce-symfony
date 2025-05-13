<?php

namespace App\Controller\guest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController {

    #[Route('/produits', name: 'product-list', methods: ['GET'])]
    public function displayListProducts(ProductRepository $productRepository, CategoryRepository $categoryRepository):Response
    {

        $products = $productRepository->findBy(['isPublished' => true]);
        $category = $categoryRepository->findAll();

        return $this->render('guest/product/products-list.html.twig', ['products' => $products, 'categories' => $category]);
    }

    #[Route('/produit/{id}', name: 'show-product', methods: ['GET'])]
    public function showProduct(ProductRepository $productRepository, CategoryRepository $categoryRepository, int $id): Response
    {

        $product = $productRepository->find($id);
        
        if(!$product) {
			return $this->redirectToRoute("guest-404");
		}

        $category = $categoryRepository->findAll();

        return $this->render('guest/product/show-product.html.twig', ['product' => $product, 'category' => $category]);
    }

    #[Route('/resultats-recherche', name: 'search-result', methods: ['GET'])]
    public function displayResultSearch(Request $request, ProductRepository $productRepository) {

        $search = $request->query->get('search');

        $productsFound = $productRepository->findByTitleContain($search);

    }
}