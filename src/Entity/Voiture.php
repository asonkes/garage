<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\VoitureRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Permet de comprendre à doctrine quelle classe doit être utilisée poour le repository associé à l'entité (donc ici, à voiture)
 */
#[ORM\Entity(repositoryClass: VoitureRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['modele'], message: "Une autre annonce possède déjà ce titre, merci de le modifier")]

class Voiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez renseigner la marque de la voiture")]
    private ?string $marque = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez renseigner le modèle de la voiture")]
    private ?string $modele = null;

    #[ORM\Column(length: 255)]
    #[Assert\Url(message: "Il faut une URL valide")]
    private ?string $cover = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Veuillez renseigner le nombre de km de la voiture")]
    private ?int $km = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\NotBlank(message: "Veuillez renseigner le nombre de propriétaire qui ont possédé la voiture auparavant")]
    private ?int $nbProprio = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Veuillez renseigner la cylindrée de la voiture")]
    private ?int $cylindree = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\NotBlank(message: "Veuillez renseigner le type de puissance de la voiture")]
    private ?int $puissance = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez renseigner le type de carburant de la voiture")]
    private ?string $carburant = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 5, max: 255, minMessage: "Le titre doit faire plus de 5 caractères", maxMessage: "Le titre ne doit pas faire plus de 255 caractères")]
    private ?string $transmission = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 10, max: 255, minMessage: "Le titre doit faire plus de 10 caractères", maxMessage: "Le titre ne doit pas faire plus de 255 caractères")]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(min: 10, max: 255, minMessage: "Le titre doit faire plus de 10 caractères", maxMessage: "Le titre ne doit pas faire plus de 255 caractères")]
    private string $texte = '';

    #[ORM\OneToMany(mappedBy: 'voiture', targetEntity: Image::class, orphanRemoval: true)]
    #[Assert\Valid()]
    private Collection $images;

    #[ORM\ManyToOne(inversedBy: 'voitures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    public function __construct()
    {
        $this->images = new ArrayCollection();
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
            $this->slug = $slugify->slugify($this->marque);
        }
    }

    /**
     * function = méthode à laquelle on peut avoir accès depuis l'intérieur et depuis l'extérieur de la méthode...
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): static
    {
        $this->modele = $modele;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(string $cover): static
    {
        $this->cover = $cover;

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

    public function getKm(): ?int
    {
        return $this->km;
    }

    public function setKm(int $km): static
    {
        $this->km = $km;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getNbProprio(): ?int
    {
        return $this->nbProprio;
    }

    public function setNbProprio(int $nbProprio): static
    {
        $this->nbProprio = $nbProprio;

        return $this;
    }

    public function getCylindree(): ?int
    {
        return $this->cylindree;
    }

    public function setCylindree(int $cylindree): static
    {
        $this->cylindree = $cylindree;

        return $this;
    }

    public function getPuissance(): ?int
    {
        return $this->puissance;
    }

    public function setPuissance(int $puissance): static
    {
        $this->puissance = $puissance;

        return $this;
    }

    public function getCarburant(): ?string
    {
        return $this->carburant;
    }

    public function setCarburant(string $carburant): static
    {
        $this->carburant = $carburant;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTransmission(): ?string
    {
        return $this->transmission;
    }

    public function setTransmission(string $transmission): static
    {
        $this->transmission = $transmission;

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

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(?string $texte): static
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setVoiture($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getVoiture() === $this) {
                $image->setVoiture(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }
}
