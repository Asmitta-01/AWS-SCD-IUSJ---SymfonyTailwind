<?php

namespace App\Entity;

use App\Enum\PaymentMethod;
use App\Enum\TransactionStatus;
use App\Entity\Sale;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'transactions')]
#[ORM\Index(columns: ['transaction_date'], name: 'idx_transaction_date')]
#[ORM\Index(columns: ['status'], name: 'idx_transaction_status')]
class Transaction
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column] private ?int $id = null;
    #[ORM\Column(length: 40, unique: true)] private string $reference = '';
    #[ORM\ManyToOne(inversedBy: 'transactions')] #[ORM\JoinColumn(nullable: false)] private ?Sale $sale = null;
    #[ORM\ManyToOne(inversedBy: 'transactions')] #[ORM\JoinColumn(nullable: false)] private ?Customer $customer = null;
    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)] private string $amount = '0.00';
    #[ORM\Column(enumType: PaymentMethod::class)] private PaymentMethod $paymentMethod = PaymentMethod::CARD;
    #[ORM\Column(enumType: TransactionStatus::class)] private TransactionStatus $status = TransactionStatus::PENDING;
    #[ORM\Column] private \DateTimeImmutable $transactionDate;
    public function __construct()
    {
        $this->transactionDate = new \DateTimeImmutable();
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getReference(): string
    {
        return $this->reference;
    }
    public function setReference(string $value): self
    {
        $this->reference = $value;
        return $this;
    }
    public function getSale(): ?Sale
    {
        return $this->sale;
    }
    public function setSale(Sale $value): self
    {
        $this->sale = $value;
        return $this;
    }
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }
    public function setCustomer(Customer $value): self
    {
        $this->customer = $value;
        return $this;
    }
    public function getAmount(): string
    {
        return $this->amount;
    }
    public function setAmount(string|float $value): self
    {
        $this->amount = (string) $value;
        return $this;
    }
    public function getPaymentMethod(): PaymentMethod
    {
        return $this->paymentMethod;
    }
    public function setPaymentMethod(PaymentMethod $value): self
    {
        $this->paymentMethod = $value;
        return $this;
    }
    public function getStatus(): TransactionStatus
    {
        return $this->status;
    }
    public function setStatus(TransactionStatus $value): self
    {
        $this->status = $value;
        return $this;
    }
    public function getTransactionDate(): \DateTimeImmutable
    {
        return $this->transactionDate;
    }
    public function setTransactionDate(\DateTimeImmutable $value): self
    {
        $this->transactionDate = $value;
        return $this;
    }
}
