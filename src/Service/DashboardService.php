<?php

namespace App\Service;

use App\Repository\CustomerRepository;
use App\Repository\ProductRepository;
use App\Repository\SaleItemRepository;
use App\Repository\SaleRepository;
use App\Repository\TransactionRepository;

class DashboardService
{
    public function __construct(
        private readonly CustomerRepository $customerRepository,
        private readonly SaleRepository $saleRepository,
        private readonly SaleItemRepository $saleItemRepository,
        private readonly ProductRepository $productRepository,
        private readonly TransactionRepository $transactionRepository,
    ) {}

    public function getStatistics(): array
    {
        $summary = $this->saleRepository->getCompletedSummary();
        $productTotals = [];
        foreach ($this->saleItemRepository->findTopProducts() as $product) {
            $productTotals[$product['name']] = (int) $product['quantity'];
        }
        $salesByMonth = [];
        foreach ($this->saleRepository->findCompletedForTrend() as $sale) {
            $month = $sale['saleDate']->format('Y-m');
            $salesByMonth[$month] = ($salesByMonth[$month] ?? 0) + (float) $sale['total'];
        }
        ksort($salesByMonth);

        return [
            'totalRevenue' => $summary['revenue'],
            'totalSales' => $this->saleRepository->count([]),
            'totalCustomers' => $this->customerRepository->count([]),
            'averageOrderValue' => $summary['count'] > 0 ? $summary['revenue'] / $summary['count'] : 0,
            'salesOverTime' => $salesByMonth,
            'recentTransactions' => $this->transactionRepository->findRecent(),
            'topProducts' => array_slice($productTotals, 0, 5, true),
            'lowStockProducts' => $this->productRepository->findLowStock(),
        ];
    }
}
