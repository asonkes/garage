<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['email'], message: "Un autre utilisateur possède déjà cette adresse e-mail, merci de la modifier")]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email(message: "Veuillez renseigner une adresse e-mail valide")]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: "Veuillez renseigner un mot de passe")]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez renseigner votre prénom")]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez renseigner votre nom de famille")]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Assert\Url(message: "Veuillez donner une URL valide pour votre avatar")]
    private ?string $picture = null;

    #[ORM\Column(length: 255)]
    // Travailler avec l'assert "length", vérifie le nombre de caractère minimum ou maximum du champ "introduction"
    #[Assert\Length(min: 10, max: 255, minMessage: "Votre introduction doit faire plus de 10 caractères", maxMessage: "Votre introduction ne doit pas faire plus de 255 caractères")]
    private ?string $introduction = null;

    #[ORM\Column(type: Types::TEXT)]
    // Travailler avec l'assert "length", vérifie le nombre de caractère minimum ou maximum du champ "description"
    #[Assert\Length(min: 100, max: 255, minMessage: "Votre description doit faire plus de 100 caractères")]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Voiture::class)]
    private Collection $voitures;

    public function __construct()
    {
        $this->voitures = new ArrayCollection();
    }

    /**
     * Permet d'initialiser le slug automatiquement si on ne le donne pas...
     *
     * @return void
     */
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function initializeSlug(): void
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->email);
        }
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

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): static
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Voiture>
     */
    public function getVoitures(): Collection
    {
        return $this->voitures;
    }

    public function addVoiture(Voiture $voiture): static
    {
        if (!$this->voitures->contains($voiture)) {
            $this->voitures->add($voiture);
            $voiture->setAuthor($this);
        }

        return $this;
    }

    public function removeVoiture(Voiture $voiture): static
    {
        if ($this->voitures->removeElement($voiture)) {
            // set the owning side to null (unless already changed)
            if ($voiture->getAuthor() === $this) {
                $voiture->setAuthor(null);
            }
        }

        return $this;
    }
}
