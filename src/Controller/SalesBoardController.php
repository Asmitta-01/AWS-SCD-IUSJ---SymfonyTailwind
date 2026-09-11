<?php

namespace App\Controller;

use App\Enum\SaleStatus;
use App\Repository\CustomerRepository;
use App\Repository\ProductRepository;
use App\Repository\SaleRepository;
use App\Repository\TransactionRepository;
use App\Service\DashboardService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SalesBoardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(DashboardService $dashboardService): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'stats' => $dashboardService->getStatistics(),
        ]);
    }

    #[Route('/sales', name: 'sales')]
    public function sales(Request $request, SaleRepository $saleRepository): Response
    {
        $status = SaleStatus::tryFrom((string) $request->query->get('status'));

        return $this->render('sales/index.html.twig', [
            'sales' => $saleRepository->findForList($request->query->get('q'), $status),
            'search' => $request->query->get('q', ''),
            'selectedStatus' => $status?->value,
        ]);
    }

    #[Route('/customers', name: 'customers')]
    public function customers(Request $request, CustomerRepository $customerRepository): Response
    {
        return $this->render('customers/index.html.twig', [
            'customers' => $customerRepository->findForList($request->query->get('q')),
            'search' => $request->query->get('q', ''),
        ]);
    }

    #[Route('/products', name: 'products')]
    public function products(Request $request, ProductRepository $productRepository): Response
    {
        return $this->render('products/index.html.twig', [
            'products' => $productRepository->findForList($request->query->get('q')),
            'search' => $request->query->get('q', ''),
        ]);
    }

    #[Route('/transactions', name: 'transactions')]
    public function transactions(Request $request, TransactionRepository $transactionRepository): Response
    {
        return $this->render('transactions/index.html.twig', [
            'transactions' => $transactionRepository->findForList($request->query->get('q'), $request->query->get('status')),
            'search' => $request->query->get('q', ''),
            'selectedStatus' => $request->query->get('status', ''),
        ]);
    }

    #[Route('/reports', name: 'reports')]
    public function reports(DashboardService $dashboardService): Response
    {
        return $this->render('reports/index.html.twig', [
            'stats' => $dashboardService->getStatistics(),
        ]);
    }

    #[Route('/settings', name: 'settings')]
    public function settings(): Response
    {
        return $this->render('settings/index.html.twig');
    }
}
