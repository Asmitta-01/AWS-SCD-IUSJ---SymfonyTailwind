<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Category
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column] private ?int $id = null;
    #[ORM\Column(length: 120, unique: true)] #[Assert\NotBlank] private string $name = '';
    #[ORM\Column(type: 'text', nullable: true)] private ?string $description = null;
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Product::class, cascade: ['persist'], orphanRemoval: false)] private Collection $products;
    public function __construct()
    {
        $this->products = new ArrayCollection();
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
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $value): self
    {
        $this->description = $value;
        return $this;
    }
    public function getProducts(): Collection
    {
        return $this->products;
    }
    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCategory($this);
        }
        return $this;
    }
}
