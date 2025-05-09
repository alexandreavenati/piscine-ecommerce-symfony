<?php

namespace App\Controller\admin;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductController extends AbstractController
{

    #[Route('/admin/create-product', name: 'admin-create-product')]
    public function displayCreateProduct(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository)
    {

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

            if (!$category) {
                $this->addFlash('error_product_created', 'La catégorie sélectionnée est invalide.');
                return $this->redirectToRoute('admin-create-product');
            }

            try {
                $product = new Product($title, $description, $price, $isPublished,  $category);
                // sauvegarde le produit créé
                $entityManager->persist($product);
                // pousse le produit créé dans la base de donnée
                $entityManager->flush();
                // msg flash
                $this->addFlash('product_created', 'Produit : "' . $product->getTitle() . '" a été enregistré.');
            } catch (Exception $e) {
                $this->addFlash('error_product_created', 'Erreur lors de la création du produit : ' . $e->getMessage());
                return $this->redirectToRoute('admin-create-product');
            }
        }

        return $this->render('admin/product/create-product.html.twig', ['categories' => $category]);
    }

    #[Route('/admin/produits', name: 'admin-product-list')]
    public function displayListCategory(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {

        $products = $productRepository->findAll();
        $category = $categoryRepository->findAll();

        return $this->render('admin/product/admin-products-list.html.twig', ['products' => $products, 'categories' => $category]);
    }

    #[Route('/supprimer-produit/{id}', name: 'admin-delete-product')]
    public function deleteProduct(ProductRepository $productRepository, EntityManagerInterface $entityManager, $id)
    {

        $product = $productRepository->find($id);
        $entityManager->remove($product);
        $entityManager->flush();

        $this->addFlash("product_deleted", "Le produit a été supprimé");

        return $this->redirectToRoute('admin-product-list');
    }

    #[Route('/admin/update-product/{id}', name: 'admin-update-product')]
    public function displayUpdateProduct(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, ProductRepository $productRepository, $id)
    {
        $product = $productRepository->find($id);
        if (!$product) {
            $this->addFlash('error', 'Produit non trouvé.');
            return $this->redirectToRoute('admin-product-list');
        }

        $categories = $categoryRepository->findAll();

        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $description = $request->request->get('description');
            $price = $request->request->get('price');
            $isPublished = $request->request->get('is-published') === 'on' ? true : false;
            $categoryId = $request->request->get('category');
            $category = $categoryRepository->find($categoryId);

            if (!$category) {
                $this->addFlash('error', 'Catégorie invalide.');
                return $this->redirectToRoute('admin-update-product', ['id' => $id]);
            }

            try {
                $product = new Product($title, $description, $price, $isPublished,  $category);
                $entityManager->persist($product);
                $entityManager->flush();

                $this->addFlash('product_updated', 'Produit mis à jour avec succès.');
                return $this->redirectToRoute('admin-product-list');
            } catch (Exception $e) {
                $this->addFlash('error_product_updated', 'Erreur lors de la mise à jour du produit : ' . $e->getMessage());
                return $this->redirectToRoute('admin-update-product', ['id' => $id]);
            }
        }

        return $this->render('admin/product/admin-update-product.html.twig', ['product' => $product, 'categories' => $categories]);
    }
}
