<?php

namespace App\Entity;

use App\Entity\Sale;
use App\Enum\CustomerStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Index(columns: ['status'], name: 'idx_customer_status')]
class Customer
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 100)] #[Assert\NotBlank] private string $firstName = '';
    #[ORM\Column(length: 100)] #[Assert\NotBlank] private string $lastName = '';
    #[ORM\Column(length: 180)] #[Assert\Email] #[Assert\NotBlank] private string $email = '';
    #[ORM\Column(length: 30, nullable: true)] private ?string $phone = null;
    #[ORM\Column(length: 180, nullable: true)] private ?string $companyName = null;
    #[ORM\Column(enumType: CustomerStatus::class)] private CustomerStatus $status = CustomerStatus::ACTIVE;
    #[ORM\Column] private \DateTimeImmutable $createdAt;
    #[ORM\Column] private \DateTimeImmutable $updatedAt;
    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Sale::class, cascade: ['persist'], orphanRemoval: false)] private Collection $sales;
    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Transaction::class, cascade: ['persist'], orphanRemoval: false)] private Collection $transactions;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
        $this->sales = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getFirstName(): string
    {
        return $this->firstName;
    }
    public function setFirstName(string $value): self
    {
        $this->firstName = $value;
        return $this;
    }
    public function getLastName(): string
    {
        return $this->lastName;
    }
    public function setLastName(string $value): self
    {
        $this->lastName = $value;
        return $this;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(string $value): self
    {
        $this->email = $value;
        return $this;
    }
    public function getPhone(): ?string
    {
        return $this->phone;
    }
    public function setPhone(?string $value): self
    {
        $this->phone = $value;
        return $this;
    }
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }
    public function setCompanyName(?string $value): self
    {
        $this->companyName = $value;
        return $this;
    }
    public function getStatus(): CustomerStatus
    {
        return $this->status;
    }
    public function setStatus(CustomerStatus $value): self
    {
        $this->status = $value;
        return $this;
    }
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function setCreatedAt(\DateTimeImmutable $value): self
    {
        $this->createdAt = $value;
        return $this;
    }
    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
    public function setUpdatedAt(\DateTimeImmutable $value): self
    {
        $this->updatedAt = $value;
        return $this;
    }
    public function getSales(): Collection
    {
        return $this->sales;
    }
    public function addSale(Sale $sale): self
    {
        if (!$this->sales->contains($sale)) {
            $this->sales->add($sale);
            $sale->setCustomer($this);
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
            $transaction->setCustomer($this);
        }
        return $this;
    }
    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
