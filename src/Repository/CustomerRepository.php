<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    /** @return list<Customer> */
    public function findForList(?string $search = null, int $limit = 100): array
    {
        $queryBuilder = $this->createQueryBuilder('customer')
            ->orderBy('customer.lastName', 'ASC')
            ->addOrderBy('customer.firstName', 'ASC')
            ->setMaxResults($limit);

        if ($search !== null && $search !== '') {
            $queryBuilder
                ->andWhere('LOWER(customer.firstName) LIKE :search OR LOWER(customer.lastName) LIKE :search OR LOWER(customer.email) LIKE :search OR LOWER(customer.companyName) LIKE :search')
                ->setParameter('search', '%' . mb_strtolower($search) . '%');
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
