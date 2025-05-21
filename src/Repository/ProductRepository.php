<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findByTitleContain(string $search) {

        $queryBuilder = $this->createQueryBuilder('product');

        // Je veux faire une requête de type select à la condition que la recherche du titre du produit ressemble à ce que
        // l'utilisateur a recherché
        $query = $queryBuilder->select('product')
            // Utiliser les paramètres (donc mettre la variable contenant la recherche utilisateur en deux temps)
            // Permet de sécuriser la requête SQL (éviter mes injections SQL) c'est à dire, vérifier
            // que la recherche utilisateur ne contient pas de requête SQL (attaque)
            ->where('product.title LIKE :search')
            ->andWhere('product.isPublished = :isPublished') 
            ->setParameter('search', '%'.$search.'%')
            ->setParameter('isPublished', true)
            ->getQuery();

        return $query->getResult();
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
