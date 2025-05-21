<?php

namespace App\Controller\admin;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductController extends AbstractController
{

    #[Route('/admin/create-product', name: 'admin-create-product', methods: ['GET', 'POST'])]
    public function displayCreateProduct(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, ParameterBagInterface $parameterBag): Response
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

            // je récupère l'image dans le formulaire avec la propriété files
			$image = $request->files->get('image');

			// si une image a bien été envoyée
			if ($image) {
				// je créé un nouveau nom unique pour l'image et je rajoute l'extension
				// originale de l'image (.jpg ou .png etc)
				$imageNewName = uniqid() . '.' . $image->guessExtension();
				// je déplace l'image dans le dossier /public/uploads (je récupère le chemin du dossier grâce à la 
                // classe parameterbag) et je la renomme avec le nouveau nom
				$image->move($parameterBag->get('kernel.project_dir').'/public/uploads', $imageNewName);
			}

            // On récupère la catégorie complète liée à l'id
            $category = $categoryRepository->find($categoryId);

            if (!$category) {
                $this->addFlash('error_product_created', 'La catégorie sélectionnée est invalide.');
                return $this->redirectToRoute('admin-create-product');
            }

            try {
                $product = new Product($title, $description, $price, $isPublished, $category, $imageNewName);
                // sauvegarde le produit créé
                $entityManager->persist($product);
                // pousse le produit créé dans la base de donnée
                $entityManager->flush();
                // msg flash
                $this->addFlash('product_created', 'Produit : "' . $product->getTitle() . '" a été enregistré.');
                return $this->redirectToRoute('admin-product-list');
            } catch (Exception $e) {
                $this->addFlash('error_product_created', 'Erreur lors de la création du produit : ' . $e->getMessage());
                return $this->redirectToRoute('admin-create-product');
            }
        }

        return $this->render('admin/product/create-product.html.twig', ['categories' => $category]);
    }

    #[Route('/admin/produits', name: 'admin-product-list', methods: ['GET'])]
    public function displayListProduct(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {

        $products = $productRepository->findAll();
        $category = $categoryRepository->findAll();

        return $this->render('admin/product/admin-products-list.html.twig', ['products' => $products, 'categories' => $category]);
    }

    #[Route('/admin/supprimer-produit/{id}', name: 'admin-delete-product', methods: ['GET'])]
    public function deleteProduct(ProductRepository $productRepository, EntityManagerInterface $entityManager, int $id): Response
    {

        $product = $productRepository->find($id);

        if (!$product) {

            return $this->redirectToRoute('admin-404');
        }

        try {

            $entityManager->remove($product);
            $entityManager->flush();

            $this->addFlash("product_deleted", "Le produit a été supprimé");
        } catch (Exception $e) {

            $this->addFlash('error_product_deletion', 'Impossible de supprimer le produit');
        }

        return $this->redirectToRoute('admin-product-list');
    }

    #[Route('/admin/update-product/{id}', name: 'admin-update-product', methods: ['GET', 'POST'])]
    public function displayUpdateProduct(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, ProductRepository $productRepository, int $id): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            $this->addFlash('error', 'Produit non trouvé.');
            return $this->redirectToRoute('admin-product-list');
        }

        $categories = $categoryRepository->findAll();

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
                $this->addFlash('error', 'Catégorie invalide.');
                return $this->redirectToRoute('admin-update-product', ['id' => $id]);
            }

            try {
                $product->update($title, $description, $price, $isPublished, $category);
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
