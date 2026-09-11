<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Product;
use App\Entity\Sale;
use App\Entity\Transaction;
use App\Service\DashboardService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SalesBoardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(DashboardService $dashboardService): Response
    {
        return $this->render('dashboard/index.html.twig', ['stats' => $dashboardService->getStatistics()]);
    }

    #[Route('/sales', name: 'sales')]
    public function sales(EntityManagerInterface $em): Response
    {
        return $this->render('sales/index.html.twig', ['sales' => $em->getRepository(Sale::class)->findBy([], ['saleDate' => 'DESC'])]);
    }

    #[Route('/customers', name: 'customers')]
    public function customers(EntityManagerInterface $em): Response
    {
        return $this->render('customers/index.html.twig', ['customers' => $em->getRepository(Customer::class)->findBy([], ['lastName' => 'ASC'])]);
    }

    #[Route('/products', name: 'products')]
    public function products(EntityManagerInterface $em): Response
    {
        return $this->render('products/index.html.twig', ['products' => $em->getRepository(Product::class)->findBy([], ['name' => 'ASC'])]);
    }

    #[Route('/transactions', name: 'transactions')]
    public function transactions(EntityManagerInterface $em): Response
    {
        return $this->render('transactions/index.html.twig', ['transactions' => $em->getRepository(Transaction::class)->findBy([], ['transactionDate' => 'DESC'])]);
    }

    #[Route('/reports', name: 'reports')]
    public function reports(DashboardService $dashboardService): Response
    {
        return $this->render('reports/index.html.twig', ['stats' => $dashboardService->getStatistics()]);
    }

    #[Route('/settings', name: 'settings')]
    public function settings(): Response
    {
        return $this->render('settings/index.html.twig');
    }
}
