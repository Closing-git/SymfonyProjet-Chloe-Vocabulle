<?php

namespace App\Entity;

use App\Repository\InfosJeuRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InfosJeuRepository::class)]
class InfosJeu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $bestScoresDifficultes = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateDernierJeu = null;

    #[ORM\ManyToOne(inversedBy: 'infosJeux')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ListeVocabulaire $listeVocabulaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBestScoresDifficultes(): ?array
    {
        return $this->bestScoresDifficultes;
    }

    public function setBestScoresDifficultes(?array $bestScoresDifficultes): static
    {
        $this->bestScoresDifficultes = $bestScoresDifficultes;

        return $this;
    }

    public function getDateDernierJeu(): ?\DateTime
    {
        return $this->dateDernierJeu;
    }

    public function setDateDernierJeu(?\DateTime $dateDernierJeu): static
    {
        $this->dateDernierJeu = $dateDernierJeu;

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
