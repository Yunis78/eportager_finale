<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commandes")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $payedAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\OneToMany(targetEntity=CommmandeProduct::class, mappedBy="commande")
     */
    private $commmandeProducts;

    public function __construct()
    {
        $this->commmandeProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPayedAt(): ?\DateTimeInterface
    {
        return $this->payedAt;
    }

    public function setPayedAt(\DateTimeInterface $payedAt): self
    {
        $this->payedAt = $payedAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return Collection|CommmandeProduct[]
     */
    public function getCommmandeProducts(): Collection
    {
        return $this->commmandeProducts;
    }

    public function addCommmandeProduct(CommmandeProduct $commmandeProduct): self
    {
        if (!$this->commmandeProducts->contains($commmandeProduct)) {
            $this->commmandeProducts[] = $commmandeProduct;
            $commmandeProduct->setCommande($this);
        }

        return $this;
    }

    public function removeCommmandeProduct(CommmandeProduct $commmandeProduct): self
    {
        if ($this->commmandeProducts->removeElement($commmandeProduct)) {
            // set the owning side to null (unless already changed)
            if ($commmandeProduct->getCommande() === $this) {
                $commmandeProduct->setCommande(null);
            }
        }

        return $this;
    }
}
