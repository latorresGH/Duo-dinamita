<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $cantidad = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Producto $producto = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'item')]
    #[ORM\JoinColumn(nullable: false)]
    private ?self $item = null;

    public function __construct()
    {
        $this->item = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): static
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getProducto(): ?Producto
    {
        return $this->producto;
    }

    public function setProducto(?Producto $producto): static
    {
        $this->producto = $producto;

        return $this;
    }

    public function getItem(): ?self
    {
        return $this->item;
    }

    public function setItem(?self $item): static
    {
        $this->item = $item;

        return $this;
    }

    public function addItem(self $item): static
    {
        if (!$this->item->contains($item)) {
            $this->item->add($item);
            $item->setItem($this);
        }

        return $this;
    }

    public function removeItem(self $item): static
    {
        if ($this->item->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getItem() === $this) {
                $item->setItem(null);
            }
        }

        return $this;
    }
}
