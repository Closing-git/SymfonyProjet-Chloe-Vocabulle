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


    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateDernierJeu = null;

    #[ORM\ManyToOne(inversedBy: 'infosJeux')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ListeVocabulaire $listeVocabulaire = null;

    #[ORM\Column]
    private array $bestScores = [];

    #[ORM\ManyToOne(inversedBy: 'infosJeu')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBestScores(): array
    {
        return $this->bestScores;
    }

    public function setBestScores(array $bestScores): static
    {
        $this->bestScores = $bestScores;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
