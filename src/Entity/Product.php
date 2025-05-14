<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPublished = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    // méthode pour créer un produit
    public function __construct(string $title, string $description, float $price, bool $isPublished, Category $category, string $imageNewName)
    {

        if (strlen(trim($title)) < 3) {

            throw new Exception('Le titre doit faire plus de 3 caractères');
        }

        if (strlen(trim($description)) < 5) {

            throw new Exception('La description doit faire plus de 5 caractères');
        }

        if ($price <= 0) {

            throw new Exception('Le prix doit être strictement supérieur à 0');
        }
        // données envoyées par l'utilisateur
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->isPublished = $isPublished;
        $this->category = $category;
        $this->image = $imageNewName;

        // données remplies automatiquement lors de l'envoi
        $this->createdAt = new \DateTime();
    }

    // méthode pour modifier/mettre à jour les produits
    public function update(string $title, string $description, float $price, bool $isPublished, Category $category) {
        if (strlen(trim($title)) < 3) {

            throw new Exception('Le titre doit faire plus de 3 caractères');
        }

        if (strlen(trim($description)) < 5) {

            throw new Exception('La description doit faire plus de 5 caractères');
        }

        if ($price <= 0) {

            throw new Exception('Le prix doit être strictement supérieur à 0');
        }
        // données remplies par l'utilisateur
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->isPublished = $isPublished;
        $this->category = $category;

        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(?bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
