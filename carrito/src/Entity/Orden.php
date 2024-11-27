<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\OrdenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdenRepository::class)]
class Orden
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $estado = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $iniciada = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $confirmada = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $usuario = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): static
    {
        $this->estado = $estado;

        return $this;
    }

    public function getIniciada(): ?\DateTimeInterface
    {
        return $this->iniciada;
    }

    public function setIniciada(\DateTimeInterface $iniciada): static
    {
        $this->iniciada = $iniciada;

        return $this;
    }

    public function getConfirmada(): ?\DateTimeInterface
    {
        return $this->confirmada;
    }

    public function setConfirmada(?\DateTimeInterface $confirmada): static
    {
        $this->confirmada = $confirmada;

        return $this;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'orden', cascade: ['persist'])]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function addItem(Item $item): Item
    {
        foreach ($this->items as $existeItem) {
            if ($existeItem->equals($item)) {
                $nuevaCantidad = $existeItem->getCantidad() + $item->getCantidad();
                $existeItem->setCantidad($nuevaCantidad);
                return $existeItem; 
            }
        }
        $this->items[] = $item;
        $item->setOrden($this); 
        return $item;
    }

    public function removeItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            if ($item->getOrden() === $this) {
                $item->setOrden(null);
            }
        }
        return $this;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }
    public function agregarItem($producto, $cantidad)
    {
        $item = new Item();
        $item->setCantidad($cantidad);
        $item->setProducto($producto);
        $item->setOrden($this);
        $itemnuevo= $this->addItem($item);
        return $itemnuevo;
    }

    public function getTotal(){
        $total = 0;
        foreach($this->items as $item){
            $total+= $item->getTotal();
        }   
        return $total;
    }
}
