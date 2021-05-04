<?php

namespace App\Entity;

use App\Entity\Media;
use App\Repository\CategorieRepository;
use App\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE")
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="categories", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Categorie::class, mappedBy="parent")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="categorie")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity=Media::class, mappedBy="product", cascade={"persist"})
     */
    private $file;

    public function __construct()
    {
        $this->file = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategorie(self $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setParent($this);
        }

        return $this;
    }

    public function removeCategorie(self $category): self
    {
        if ($this->categories->removeElement($category)) {


            // set the owning side to null (unless already changed)
            if ($category->getParent() === $this) {
                $category->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCategorie($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategorie() === $this) {
                $product->setCategorie(null);
            }
        }

        return $this;
    }

   /**
     * @return Collection|Media[]
     */
    public function getFile(): Collection
    {
        return $this->file;
    }

    public function addFile(Media $file): self
    {
        if (!$this->file->contains($file)) {
            $this->file[] = $file;
            $file->setCategorie($this);
        }

        return $this;
    }

    public function removeFile(Media $file): self
    {
        if ($this->file->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getCategorie() === $this) {
                $file->setCategorie(null);
            }
        }

        return $this;
    }
}
