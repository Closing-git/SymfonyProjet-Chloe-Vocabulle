<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $montantNote = null;

    #[ORM\ManyToOne(inversedBy: 'note')]
    private ?ListeVocabulaire $listeVocabulaire = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontantNote(): ?int
    {
        return $this->montantNote;
    }

    public function setMontantNote(?int $montantNote): static
    {
        $this->montantNote = $montantNote;

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
