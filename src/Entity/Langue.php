<?php

namespace App\Entity;

use App\Repository\LangueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    
    /**
     * @var Collection<int, ListeVocabulaire>
     */
    #[ORM\ManyToMany(targetEntity: ListeVocabulaire::class, mappedBy: 'langues')]
    private Collection $listesVocabulaire;

    #[ORM\Column(nullable: true)]
    private ?array $caracteresSpeciaux = null;

    public function __construct()
    {
        $this->listesVocabulaire = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, ListeVocabulaire>
     */
    public function getListesVocabulaire(): Collection
    {
        return $this->listesVocabulaire;
    }

    public function addListesVocabulaire(ListeVocabulaire $listesVocabulaire): static
    {
        if (!$this->listesVocabulaire->contains($listesVocabulaire)) {
            $this->listesVocabulaire->add($listesVocabulaire);
            $listesVocabulaire->addLangue($this);
        }

        return $this;
    }

    public function removeListesVocabulaire(ListeVocabulaire $listesVocabulaire): static
    {
        if ($this->listesVocabulaire->removeElement($listesVocabulaire)) {
            $listesVocabulaire->removeLangue($this);
        }

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
