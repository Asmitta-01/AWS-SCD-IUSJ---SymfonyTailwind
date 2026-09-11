<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /** @return list<Product> */
    public function findForList(?string $search = null, int $limit = 100): array
    {
        $queryBuilder = $this->createQueryBuilder('product')
            ->addSelect('category')
            ->join('product.category', 'category')
            ->orderBy('product.name', 'ASC')
            ->setMaxResults($limit);

        if ($search !== null && $search !== '') {
            $queryBuilder
                ->andWhere('LOWER(product.name) LIKE :search OR LOWER(product.sku) LIKE :search OR LOWER(category.name) LIKE :search')
                ->setParameter('search', '%' . mb_strtolower($search) . '%');
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /** @return list<Product> */
    public function findLowStock(int $limit = 10): array
    {
        return $this->createQueryBuilder('product')
            ->andWhere('product.stockQuantity = 0 OR product.stockQuantity <= product.minimumStock')
            ->orderBy('product.stockQuantity', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
