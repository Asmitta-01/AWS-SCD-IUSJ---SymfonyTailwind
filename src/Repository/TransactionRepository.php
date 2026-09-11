<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    /** @return list<Transaction> */
    public function findForList(?string $search = null, ?string $status = null, int $limit = 100): array
    {
        $queryBuilder = $this->createQueryBuilder('transaction')
            ->addSelect('sale', 'customer')
            ->join('transaction.sale', 'sale')
            ->join('transaction.customer', 'customer')
            ->orderBy('transaction.transactionDate', 'DESC')
            ->setMaxResults($limit);

        if ($search !== null && $search !== '') {
            $queryBuilder
                ->andWhere('LOWER(transaction.reference) LIKE :search OR LOWER(sale.reference) LIKE :search OR LOWER(customer.firstName) LIKE :search OR LOWER(customer.lastName) LIKE :search')
                ->setParameter('search', '%'.mb_strtolower($search).'%');
        }

        if ($status !== null && in_array($status, ['completed', 'pending', 'failed'], true)) {
            $queryBuilder->andWhere('transaction.status = :status')->setParameter('status', $status);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /** @return list<Transaction> */
    public function findRecent(int $limit = 8): array
    {
        return $this->createQueryBuilder('transaction')
            ->addSelect('customer')
            ->join('transaction.customer', 'customer')
            ->orderBy('transaction.transactionDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}