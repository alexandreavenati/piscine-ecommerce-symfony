<?php

namespace App\Controller\admin;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductController extends AbstractController {

    #[Route('/admin/create-product', name:'admin-create-product')]
    public function displayCreateProduct(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository){

        // Récupère toutes les catégories enregistrés dans le tableau de la bdd
        $category = $categoryRepository->findAll();

        // Vérifie le type de méthode envoyée par l'utilisateur
        if ($request->isMethod('POST')) {

            // Récupère les données envoyées via les 'name' du formulaire
            $title = $request->request->get('title');
            $description = $request->request->get('description');
            $price = $request->request->get('price');
            
            if ($request->request->get('is-published') === 'on') {
				$isPublished = true;
			} else {
				$isPublished = false;
			}

            // On récupère l'id de la catégorie sélectionnée
            $categoryId = $request->request->get('category');

            // On récupère la catégorie complète liée à l'id
            $category = $categoryRepository->find($categoryId);
            
            $product = new Product($title, $description, $price, $isPublished,  $category);

            // sauvegarde l'article créé
            $entityManager->persist($product);

            // pousse l'article créé dans la base de donnée
			$entityManager->flush();

            // msg flash
            $this->addFlash('product_created', 'Produit : "' . $product->getTitle() . '" a été enregistré.');
        }

        return $this->render('admin/product/create-product.html.twig', ['categories' => $category]);
    }
}