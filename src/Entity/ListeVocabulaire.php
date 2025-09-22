<?php

namespace App\Entity;

use App\Repository\ListeVocabulaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateDerniereModif = null;

    #[ORM\Column]
    private ?bool $publicStatut = null;


    /**
     * @var Collection<int, InfosJeu>
     */
    #[ORM\OneToMany(targetEntity: InfosJeu::class, mappedBy: 'listeVocabulaire', orphanRemoval: true)]
    private Collection $infosJeux;

    /**
     * @var Collection<int, Note>
     */
    #[ORM\OneToMany(targetEntity: Note::class, mappedBy: 'listeVocabulaire')]
    private Collection $note;

    /**
     * @var Collection<int, Langue>
     */
    #[ORM\ManyToMany(targetEntity: Langue::class, inversedBy: 'listesVocabulaire')]
    private Collection $langues;

    /**
     * @var Collection<int, Traduction>
     */
    #[ORM\OneToMany(targetEntity: Traduction::class, mappedBy: 'listeVocabulaire', orphanRemoval: true)]
    private Collection $traduction;

    /**
     * @var Collection<int, Utilisateur>
     */
    #[ORM\ManyToMany(targetEntity: Utilisateur::class, mappedBy: 'favListes')]
    private Collection $utilisateursQuiFav;

    #[ORM\ManyToOne(inversedBy: 'createdListes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $createur = null;

    public function __construct()
    {
        $this->infosJeux = new ArrayCollection();
        $this->note = new ArrayCollection();
        $this->langues = new ArrayCollection();
        $this->traduction = new ArrayCollection();
        $this->utilisateursQuiFav = new ArrayCollection();
    }

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


    //NOTE TOTALE CALCULÉE EN FONCTION DE TOUTES LES NOTES
    public function getNoteTotale(): ?int
    {
        $notes = $this->getNotes();
        $noteTotale = 0;
        foreach ($notes as $note) {
            $noteTotale += $note->getMontantNote();
        }
        return $noteTotale;
    }

    /**
     * @return Collection<int, InfosJeu>
     */
    public function getInfosJeux(): Collection
    {
        return $this->infosJeux;
    }

    public function addInfosJeux(InfosJeu $infosJeux): static
    {
        if (!$this->infosJeux->contains($infosJeux)) {
            $this->infosJeux->add($infosJeux);
            $infosJeux->setListeVocabulaire($this);
        }

        return $this;
    }

    public function removeInfosJeux(InfosJeu $infosJeux): static
    {
        if ($this->infosJeux->removeElement($infosJeux)) {
            // set the owning side to null (unless already changed)
            if ($infosJeux->getListeVocabulaire() === $this) {
                $infosJeux->setListeVocabulaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->note;
    }

    public function addNote(Note $note): static
    {
        if (!$this->note->contains($note)) {
            $this->note->add($note);
            $note->setListeVocabulaire($this);
        }

        return $this;
    }

    public function removeNote(Note $note): static
    {
        if ($this->note->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getListeVocabulaire() === $this) {
                $note->setListeVocabulaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Langue>
     */
    public function getLangues(): Collection
    {
        return $this->langues;
    }

    public function addLangue(Langue $langue): static
    {
        if (!$this->langues->contains($langue)) {
            $this->langues->add($langue);
        }

        return $this;
    }

    public function removeLangue(Langue $langue): static
    {
        $this->langues->removeElement($langue);

        return $this;
    }

    /**
     * @return Collection<int, Traduction>
     */
    public function getTraduction(): Collection
    {
        return $this->traduction;
    }

    public function addTraduction(Traduction $traduction): static
    {
        if (!$this->traduction->contains($traduction)) {
            $this->traduction->add($traduction);
            $traduction->setListeVocabulaire($this);
        }

        return $this;
    }

    public function removeTraduction(Traduction $traduction): static
    {
        if ($this->traduction->removeElement($traduction)) {
            // set the owning side to null (unless already changed)
            if ($traduction->getListeVocabulaire() === $this) {
                $traduction->setListeVocabulaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getUtilisateursQuiFav(): Collection
    {
        return $this->utilisateursQuiFav;
    }

    public function addUtilisateursQuiFav(Utilisateur $utilisateursQuiFav): static
    {
        if (!$this->utilisateursQuiFav->contains($utilisateursQuiFav)) {
            $this->utilisateursQuiFav->add($utilisateursQuiFav);
            $utilisateursQuiFav->addFavListe($this);
        }

        return $this;
    }

    public function removeUtilisateursQuiFav(Utilisateur $utilisateursQuiFav): static
    {
        if ($this->utilisateursQuiFav->removeElement($utilisateursQuiFav)) {
            $utilisateursQuiFav->removeFavListe($this);
        }

        return $this;
    }

    public function getCreateur(): ?Utilisateur
    {
        return $this->createur;
    }

    public function setCreateur(?Utilisateur $createur): static
    {
        $this->createur = $createur;

        return $this;
    }
}
