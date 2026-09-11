<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\Product;
use App\Entity\Sale;
use App\Entity\SaleItem;
use App\Entity\Transaction;
use App\Enum\SaleStatus;
use Doctrine\ORM\EntityManagerInterface;

class DashboardService
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    public function getStatistics(): array
    {
        $sales = $this->entityManager->getRepository(Sale::class)->findAll();
        $completedSales = array_filter($sales, static fn(Sale $sale): bool => $sale->getStatus() === SaleStatus::COMPLETED);
        $revenue = array_sum(array_map(static fn(Sale $sale): float => (float) $sale->getTotal(), $completedSales));
        $productTotals = [];
        foreach ($this->entityManager->getRepository(SaleItem::class)->findAll() as $item) {
            $name = $item->getProduct()?->getName() ?? 'Unknown';
            $productTotals[$name] = ($productTotals[$name] ?? 0) + $item->getQuantity();
        }
        arsort($productTotals);
        $salesByMonth = [];
        foreach ($completedSales as $sale) {
            $month = $sale->getSaleDate()->format('Y-m');
            $salesByMonth[$month] = ($salesByMonth[$month] ?? 0) + (float) $sale->getTotal();
        }
        ksort($salesByMonth);

        return [
            'totalRevenue' => $revenue,
            'totalSales' => count($sales),
            'totalCustomers' => $this->entityManager->getRepository(Customer::class)->count([]),
            'averageOrderValue' => count($completedSales) > 0 ? $revenue / count($completedSales) : 0,
            'salesOverTime' => $salesByMonth,
            'recentTransactions' => $this->entityManager->getRepository(Transaction::class)->findBy([], ['transactionDate' => 'DESC'], 8),
            'topProducts' => array_slice($productTotals, 0, 5, true),
            'lowStockProducts' => array_values(array_filter($this->entityManager->getRepository(Product::class)->findBy([], ['stockQuantity' => 'ASC']), static fn(Product $product): bool => $product->isLowStock() || $product->isOutOfStock())),
        ];
    }
}
