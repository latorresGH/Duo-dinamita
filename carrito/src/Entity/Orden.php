<?php

namespace App\Entity;

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
}
