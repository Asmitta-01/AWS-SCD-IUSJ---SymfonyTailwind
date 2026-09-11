<?php

namespace App\Entity;

use App\Entity\Sale;
use App\Repository\SaleItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SaleItemRepository::class)]
class SaleItem
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column] private ?int $id = null;
    #[ORM\ManyToOne(inversedBy: 'items')] #[ORM\JoinColumn(nullable: false)] private ?Sale $sale = null;
    #[ORM\ManyToOne(inversedBy: 'saleItems')] #[ORM\JoinColumn(nullable: false)] private ?Product $product = null;
    #[ORM\Column] #[Assert\Positive] private int $quantity = 1;
    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)] #[Assert\Positive] private string $unitPrice = '0.00';
    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)] private string $total = '0.00';

    public function getId(): ?int
    {
        return $this->id;
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
    public function getProduct(): ?Product
    {
        return $this->product;
    }
    public function setProduct(Product $value): self
    {
        $this->product = $value;
        return $this;
    }
    public function getQuantity(): int
    {
        return $this->quantity;
    }
    public function setQuantity(int $value): self
    {
        $this->quantity = $value;
        $this->recalculateTotal();
        return $this;
    }
    public function getUnitPrice(): string
    {
        return $this->unitPrice;
    }
    public function setUnitPrice(string|float $value): self
    {
        $this->unitPrice = (string) $value;
        $this->recalculateTotal();
        return $this;
    }
    public function getTotal(): string
    {
        return $this->total;
    }
    public function recalculateTotal(): self
    {
        $this->total = number_format($this->quantity * (float) $this->unitPrice, 2, '.', '');
        return $this;
    }
}
