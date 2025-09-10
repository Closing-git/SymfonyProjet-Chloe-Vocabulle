<?php

namespace App\Entity;

use App\Repository\ListeVocabulaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListeVocabulaireRepository::class)]
class ListeVocabulaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbMots = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $motsLangue1 = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $motsLangue2 = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateDerniereModif = null;

    #[ORM\Column]
    private ?bool $publicStatut = null;

    #[ORM\Column(nullable: true)]
    private ?int $noteTotale = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getNbMots(): ?int
    {
        return $this->nbMots;
    }

    public function setNbMots(?int $nbMots): static
    {
        $this->nbMots = $nbMots;

        return $this;
    }

    public function getMotsLangue1(): ?array
    {
        return $this->motsLangue1;
    }

    public function setMotsLangue1(?array $motsLangue1): static
    {
        $this->motsLangue1 = $motsLangue1;

        return $this;
    }

    public function getMotsLangue2(): ?array
    {
        return $this->motsLangue2;
    }

    public function setMotsLangue2(?array $motsLangue2): static
    {
        $this->motsLangue2 = $motsLangue2;

        return $this;
    }

    public function getDateDerniereModif(): ?\DateTime
    {
        return $this->dateDerniereModif;
    }

    public function setDateDerniereModif(\DateTime $dateDerniereModif): static
    {
        $this->dateDerniereModif = $dateDerniereModif;

        return $this;
    }

    public function isPublicStatut(): ?bool
    {
        return $this->publicStatut;
    }

    public function setPublicStatut(bool $publicStatut): static
    {
        $this->publicStatut = $publicStatut;

        return $this;
    }

    public function getNoteTotale(): ?int
    {
        return $this->noteTotale;
    }

    public function setNoteTotale(?int $noteTotale): static
    {
        $this->noteTotale = $noteTotale;

        return $this;
    }
}
