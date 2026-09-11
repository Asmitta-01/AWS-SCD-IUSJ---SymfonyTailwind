<?php

namespace App\Repository;

use App\Entity\SaleItem;
use App\Enum\SaleStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SaleItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SaleItem::class);
    }

    /** @return list<array{name: string, quantity: int}> */
    public function findTopProducts(int $limit = 5): array
    {
        return $this->createQueryBuilder('item')
            ->select('product.name AS name, SUM(item.quantity) AS quantity')
            ->join('item.product', 'product')
            ->join('item.sale', 'sale')
            ->andWhere('sale.status = :status')
            ->setParameter('status', SaleStatus::COMPLETED)
            ->groupBy('product.id, product.name')
            ->orderBy('quantity', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getArrayResult();
    }
}