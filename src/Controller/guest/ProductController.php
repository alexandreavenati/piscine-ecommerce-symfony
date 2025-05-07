<?php

namespace App\Controller\guest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;

class ProductController extends AbstractController {

    #[Route('/produits', name: 'product-list')]
    public function displayListCategory(ProductRepository $productRepository)
    {

        $products = $productRepository->findAll();

        return $this->render('guest/products-list.html.twig', ['products' => $products]);
    }

    #[Route('/produit/{id}', name: 'show-product')]
    public function showCategory(ProductRepository $productRepository, $id)
    {

        $product = $productRepository->find($id);

        return $this->render('guest/show-product.html.twig', ['product' => $product]);
    }
}