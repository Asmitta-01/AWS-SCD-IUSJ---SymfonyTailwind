<?php

namespace App\Entity;

use App\Entity\SaleItem;
use App\Enum\SaleStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Index(columns: ['sale_date'], name: 'idx_sale_date')]
#[ORM\Index(columns: ['status'], name: 'idx_sale_status')]
class Sale
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column] private ?int $id = null;
    #[ORM\Column(length: 40, unique: true)] #[Assert\NotBlank] private string $reference = '';
    #[ORM\ManyToOne(inversedBy: 'sales')] #[ORM\JoinColumn(nullable: false)] private ?Customer $customer = null;
    #[ORM\Column] private \DateTimeImmutable $saleDate;
    #[ORM\Column(enumType: SaleStatus::class)] private SaleStatus $status = SaleStatus::PENDING;
    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)] private string $subtotal = '0.00';
    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)] private string $total = '0.00';
    #[ORM\Column] private \DateTimeImmutable $createdAt;
    #[ORM\OneToMany(mappedBy: 'sale', targetEntity: SaleItem::class, cascade: ['persist'], orphanRemoval: true)] private Collection $items;
    #[ORM\OneToMany(mappedBy: 'sale', targetEntity: Transaction::class, cascade: ['persist'], orphanRemoval: false)] private Collection $transactions;

    public function __construct()
    {
        $this->saleDate = new \DateTimeImmutable();
        $this->createdAt = new \DateTimeImmutable();
        $this->items = new ArrayCollection();
        $this->transactions = new ArrayCollection();
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
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }
    public function setCustomer(Customer $value): self
    {
        $this->customer = $value;
        return $this;
    }
    public function getSaleDate(): \DateTimeImmutable
    {
        return $this->saleDate;
    }
    public function setSaleDate(\DateTimeImmutable $value): self
    {
        $this->saleDate = $value;
        return $this;
    }
    public function getStatus(): SaleStatus
    {
        return $this->status;
    }
    public function setStatus(SaleStatus $value): self
    {
        $this->status = $value;
        return $this;
    }
    public function getSubtotal(): string
    {
        return $this->subtotal;
    }
    public function setSubtotal(string|float $value): self
    {
        $this->subtotal = (string) $value;
        return $this;
    }
    public function getTotal(): string
    {
        return $this->total;
    }
    public function setTotal(string|float $value): self
    {
        $this->total = (string) $value;
        return $this;
    }
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function getItems(): Collection
    {
        return $this->items;
    }
    public function addItem(SaleItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setSale($this);
        }
        return $this;
    }
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }
    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setSale($this);
        }
        return $this;
    }
}
