<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="products")
     */
    private $categorie;

    /**
     * @ORM\ManyToMany(targetEntity=File::class, cascade={"persist"})
     */
    private $file;

    //private $files;

    /**
     * @ORM\ManyToOne(targetEntity=Producer::class, inversedBy="products")
     */
    private $producer;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity=CommandeProduct::class, mappedBy="product")
     */
    private $commandeProducts;

    public function __construct()
    {
        $this->file = new ArrayCollection();
        $this->commandeProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection|File[]
     */
    public function getFile(): Collection
    {
        return $this->file;
    }

    public function addFile(File $file): self
    {
        if (!$this->file->contains($file)) {
            $this->file[] = $file;
            $file->setProduct($this);
        }

        return $this;
    }

    public function removeFile(File $file): self
    {
        if ($this->file->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getProduct() === $this) {
                $file->setProduct(null);
            }
        }

        return $this;
    }

    public function getProducer(): ?Producer
    {
        return $this->producer;
    }

    public function setProducer(?Producer $producer): self
    {
        $this->producer = $producer;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|CommandeProduct[]
     */
    public function getCommandeProducts(): Collection
    {
        return $this->commandeProducts;
    }

    public function addCommandeProduct(CommandeProduct $commandeProduct): self
    {
        if (!$this->commandeProducts->contains($commandeProduct)) {
            $this->commandeProducts[] = $commandeProduct;
            $commandeProduct->setProduct($this);
        }

        return $this;
    }

    public function removeCommandeProduct(CommandeProduct $commandeProduct): self
    {
        if ($this->commandeProducts->removeElement($commandeProduct)) {
            // set the owning side to null (unless already changed)
            if ($commandeProduct->getProduct() === $this) {
                $commandeProduct->setProduct(null);
            }
        }

        return $this;
    }

    public function getFiles(): ?array
    {
        return $this->files;
    }

    public function setFiles( array $files): self
    {
        $this->files = $files;

        return $this;
    }
}
