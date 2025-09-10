<?php

namespace App\Entity;

use App\Repository\LangueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LangueRepository::class)]
class Langue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?bool $majImportante = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $caracteresSpeciaux = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function isMajImportante(): ?bool
    {
        return $this->majImportante;
    }

    public function setMajImportante(bool $majImportante): static
    {
        $this->majImportante = $majImportante;

        return $this;
    }

    public function getCaracteresSpeciaux(): ?array
    {
        return $this->caracteresSpeciaux;
    }

    public function setCaracteresSpeciaux(?array $caracteresSpeciaux): static
    {
        $this->caracteresSpeciaux = $caracteresSpeciaux;

        return $this;
    }
}
