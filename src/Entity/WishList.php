<?php

namespace App\Entity;

use App\Repository\WishListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=WishListRepository::class)
 * @Serializer\ExclusionPolicy("all")
 */
class WishList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Serializer\Expose
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Expose
     *
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    private ?string $title;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, inversedBy="wishLists")
     *
     * @Serializer\Expose
     */
    private Collection $products;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="wishLists")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Serializer\Expose
     */
    private ?User $user;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
        }

        return $this;
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
}
