<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    /**
     * @var Collection<int, Note>
     */
    #[ORM\OneToMany(targetEntity: Note::class, mappedBy: 'utilisateur', orphanRemoval: true)]
    private Collection $notes;

    /**
     * @var Collection<int, InfosJeu>
     */
    #[ORM\OneToMany(targetEntity: InfosJeu::class, mappedBy: 'utilisateur', orphanRemoval: true)]
    private Collection $infosJeu;

    /**
     * @var Collection<int, ListeVocabulaire>
     */
    #[ORM\ManyToMany(targetEntity: ListeVocabulaire::class, inversedBy: 'utilisateursQuiFav')]
    private Collection $favListes;

    /**
     * @var Collection<int, ListeVocabulaire>
     */
    #[ORM\OneToMany(targetEntity: ListeVocabulaire::class, mappedBy: 'createur', orphanRemoval: true)]
    private Collection $createdListes;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->infosJeu = new ArrayCollection();
        $this->favListes = new ArrayCollection();
        $this->createdListes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): static
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setUtilisateur($this);
        }

        return $this;
    }

    public function removeNote(Note $note): static
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getUtilisateur() === $this) {
                $note->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, InfosJeu>
     */
    public function getInfosJeu(): Collection
    {
        return $this->infosJeu;
    }

    public function addInfosJeu(InfosJeu $infosJeu): static
    {
        if (!$this->infosJeu->contains($infosJeu)) {
            $this->infosJeu->add($infosJeu);
            $infosJeu->setUtilisateur($this);
        }

        return $this;
    }

    public function removeInfosJeu(InfosJeu $infosJeu): static
    {
        if ($this->infosJeu->removeElement($infosJeu)) {
            // set the owning side to null (unless already changed)
            if ($infosJeu->getUtilisateur() === $this) {
                $infosJeu->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ListeVocabulaire>
     */
    public function getFavListes(): Collection
    {
        return $this->favListes;
    }

    public function addFavListe(ListeVocabulaire $favListe): static
    {
        if (!$this->favListes->contains($favListe)) {
            $this->favListes->add($favListe);
        }

        return $this;
    }

    public function removeFavListe(ListeVocabulaire $favListe): static
    {
        $this->favListes->removeElement($favListe);

        return $this;
    }

    /**
     * @return Collection<int, ListeVocabulaire>
     */
    public function getCreatedListes(): Collection
    {
        return $this->createdListes;
    }

    public function addCreatedListe(ListeVocabulaire $createdListe): static
    {
        if (!$this->createdListes->contains($createdListe)) {
            $this->createdListes->add($createdListe);
            $createdListe->setCreateur($this);
        }

        return $this;
    }

    public function removeCreatedListe(ListeVocabulaire $createdListe): static
    {
        if ($this->createdListes->removeElement($createdListe)) {
            // set the owning side to null (unless already changed)
            if ($createdListe->getCreateur() === $this) {
                $createdListe->setCreateur(null);
            }
        }

        return $this;
    }
}
