<?php

namespace App\Entity;

use App\Repository\TraductionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TraductionRepository::class)]
class Traduction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $motLangue1 = null;

    #[ORM\Column(length: 255)]
    private ?string $motLangue2 = null;

    #[ORM\ManyToOne(inversedBy: 'traduction')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ListeVocabulaire $listeVocabulaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotLangue1(): ?string
    {
        return $this->motLangue1;
    }

    public function setMotLangue1(string $motLangue1): static
    {
        $this->motLangue1 = $motLangue1;

        return $this;
    }

    public function getMotLangue2(): ?string
    {
        return $this->motLangue2;
    }

    public function setMotLangue2(string $motLangue2): static
    {
        $this->motLangue2 = $motLangue2;

        return $this;
    }

    public function getListeVocabulaire(): ?ListeVocabulaire
    {
        return $this->listeVocabulaire;
    }

    public function setListeVocabulaire(?ListeVocabulaire $listeVocabulaire): static
    {
        $this->listeVocabulaire = $listeVocabulaire;

        return $this;
    }
}
