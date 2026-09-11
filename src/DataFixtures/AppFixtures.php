<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Customer;
use App\Entity\Product;
use App\Entity\Sale;
use App\Entity\SaleItem;
use App\Entity\Transaction;
use App\Entity\User;
use App\Enum\CustomerStatus;
use App\Enum\PaymentMethod;
use App\Enum\SaleStatus;
use App\Enum\TransactionStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('en_US');
        $faker->seed(20260911);

        foreach ([['admin@salesboard.local', 'Admin', 'User', ['ROLE_ADMIN']], ['manager@salesboard.local', 'Sales', 'Manager', ['ROLE_USER']]] as [$email, $firstName, $lastName, $roles]) {
            $user = (new User())->setEmail($email)->setFirstName($firstName)->setLastName($lastName)->setRoles($roles);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $manager->persist($user);
        }

        $categories = [];
        foreach (['Electronics', 'Office Supplies', 'Furniture', 'Software', 'Networking', 'Accessories'] as $name) {
            $category = (new Category())->setName($name)->setDescription($faker->sentence());
            $categories[] = $category;
            $manager->persist($category);
        }

        $products = [];
        for ($index = 1; $index <= 30; ++$index) {
            $stock = $index % 10 === 0 ? 0 : ($index % 4 === 0 ? $faker->numberBetween(1, 5) : $faker->numberBetween(8, 80));
            $product = (new Product())
                ->setName($faker->unique()->catchPhrase())
                ->setSku(sprintf('SB-%04d', $index))
                ->setDescription($faker->sentence())
                ->setPrice(number_format($faker->randomFloat(2, 15, 1200), 2, '.', ''))
                ->setStockQuantity($stock)
                ->setMinimumStock($index % 4 === 0 ? 8 : 5)
                ->setCategory($categories[($index - 1) % count($categories)]);
            $products[] = $product;
            $manager->persist($product);
        }

        $customers = [];
        for ($index = 0; $index < 25; ++$index) {
            $customer = (new Customer())
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setEmail($faker->unique()->safeEmail())
                ->setPhone($faker->phoneNumber())
                ->setCompanyName($index % 3 === 0 ? $faker->company() : null)
                ->setStatus($index % 7 === 0 ? CustomerStatus::INACTIVE : CustomerStatus::ACTIVE);
            $customers[] = $customer;
            $manager->persist($customer);
        }

        for ($index = 1; $index <= 80; ++$index) {
            $customer = $faker->randomElement($customers);
            $status = $index % 13 === 0 ? SaleStatus::CANCELLED : ($index % 9 === 0 ? SaleStatus::PENDING : SaleStatus::COMPLETED);
            $saleDate = \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-12 months', 'now'));
            $sale = (new Sale())->setReference(sprintf('SAL-%05d', $index))->setCustomer($customer)->setSaleDate($saleDate)->setStatus($status);
            $subtotal = 0.0;
            $usedProducts = [];
            for ($itemIndex = 0, $itemCount = $faker->numberBetween(2, 4); $itemIndex < $itemCount; ++$itemIndex) {
                do {
                    $product = $faker->randomElement($products);
                } while (isset($usedProducts[$product->getSku()]));
                $usedProducts[$product->getSku()] = true;
                $item = (new SaleItem())->setProduct($product)->setQuantity($faker->numberBetween(1, 4))->setUnitPrice($product->getPrice())->recalculateTotal();
                $sale->addItem($item);
                $subtotal += (float) $item->getTotal();
            }
            $sale->setSubtotal(number_format($subtotal, 2, '.', ''))->setTotal(number_format($subtotal, 2, '.', ''));
            $manager->persist($sale);

            $transactionStatus = $status === SaleStatus::COMPLETED ? TransactionStatus::COMPLETED : ($status === SaleStatus::PENDING ? TransactionStatus::PENDING : TransactionStatus::FAILED);
            $transaction = (new Transaction())->setReference(sprintf('TXN-%05d', $index))->setSale($sale)->setCustomer($customer)->setAmount($sale->getTotal())->setPaymentMethod($faker->randomElement(PaymentMethod::cases()))->setStatus($transactionStatus)->setTransactionDate($saleDate->modify(sprintf('+%d hours', $faker->numberBetween(1, 48))));
            $sale->addTransaction($transaction);
            $manager->persist($transaction);
        }

        $manager->flush();
    }
}
