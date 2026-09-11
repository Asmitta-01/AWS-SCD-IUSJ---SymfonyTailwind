<?php

namespace App\Entity;

use App\Entity\SaleItem;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Index(columns: ['stock_quantity'], name: 'idx_product_stock')]
class Product
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column] private ?int $id = null;
    #[ORM\Column(length: 180)] #[Assert\NotBlank] private string $name = '';
    #[ORM\Column(length: 80, unique: true)] #[Assert\NotBlank] private string $sku = '';
    #[ORM\Column(type: 'text', nullable: true)] private ?string $description = null;
    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)] #[Assert\Positive] private string $price = '0.00';
    #[ORM\Column] #[Assert\PositiveOrZero] private int $stockQuantity = 0;
    #[ORM\Column] #[Assert\PositiveOrZero] private int $minimumStock = 0;
    #[ORM\Column] private \DateTimeImmutable $createdAt;
    #[ORM\Column] private \DateTimeImmutable $updatedAt;
    #[ORM\ManyToOne(inversedBy: 'products')] #[ORM\JoinColumn(nullable: false)] private ?Category $category = null;
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: SaleItem::class)] private Collection $saleItems;
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
        $this->saleItems = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $value): self
    {
        $this->name = $value;
        return $this;
    }
    public function getSku(): string
    {
        return $this->sku;
    }
    public function setSku(string $value): self
    {
        $this->sku = $value;
        return $this;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $value): self
    {
        $this->description = $value;
        return $this;
    }
    public function getPrice(): string
    {
        return $this->price;
    }
    public function setPrice(string|float $value): self
    {
        $this->price = (string) $value;
        return $this;
    }
    public function getStockQuantity(): int
    {
        return $this->stockQuantity;
    }
    public function setStockQuantity(int $value): self
    {
        $this->stockQuantity = $value;
        return $this;
    }
    public function getMinimumStock(): int
    {
        return $this->minimumStock;
    }
    public function setMinimumStock(int $value): self
    {
        $this->minimumStock = $value;
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
    public function getCategory(): ?Category
    {
        return $this->category;
    }
    public function setCategory(Category $value): self
    {
        $this->category = $value;
        return $this;
    }
    public function getSaleItems(): Collection
    {
        return $this->saleItems;
    }
    public function isOutOfStock(): bool
    {
        return $this->stockQuantity === 0;
    }
    public function isLowStock(): bool
    {
        return $this->stockQuantity > 0 && $this->stockQuantity <= $this->minimumStock;
    }
}
