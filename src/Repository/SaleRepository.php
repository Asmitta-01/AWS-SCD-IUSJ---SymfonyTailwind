<?php

namespace App\Repository;

use App\Entity\Sale;
use App\Enum\SaleStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sale::class);
    }

    /** @return list<Sale> */
    public function findForList(?string $search = null, ?SaleStatus $status = null, int $limit = 100): array
    {
        $queryBuilder = $this->createQueryBuilder('sale')
            ->addSelect('customer', 'transaction')
            ->join('sale.customer', 'customer')
            ->leftJoin('sale.transactions', 'transaction')
            ->orderBy('sale.saleDate', 'DESC')
            ->setMaxResults($limit);

        if ($search !== null && $search !== '') {
            $queryBuilder
                ->andWhere('LOWER(sale.reference) LIKE :search OR LOWER(customer.firstName) LIKE :search OR LOWER(customer.lastName) LIKE :search')
                ->setParameter('search', '%' . mb_strtolower($search) . '%');
        }

        if ($status !== null) {
            $queryBuilder->andWhere('sale.status = :status')->setParameter('status', $status);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /** @return array{revenue: float, count: int} */
    public function getCompletedSummary(): array
    {
        $result = $this->createQueryBuilder('sale')
            ->select('COALESCE(SUM(sale.total), 0) AS revenue, COUNT(sale.id) AS count')
            ->andWhere('sale.status = :status')
            ->setParameter('status', SaleStatus::COMPLETED)
            ->getQuery()
            ->getSingleResult();

        return ['revenue' => (float) $result['revenue'], 'count' => (int) $result['count']];
    }

    /** @return list<array{saleDate: \DateTimeImmutable, total: string}> */
    public function findCompletedForTrend(): array
    {
        return $this->createQueryBuilder('sale')
            ->select('sale.saleDate, sale.total')
            ->andWhere('sale.status = :status')
            ->setParameter('status', SaleStatus::COMPLETED)
            ->orderBy('sale.saleDate', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
