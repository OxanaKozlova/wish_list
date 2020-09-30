<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @Serializer\ExclusionPolicy("all")
 */
class Product
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
     * @Serializer\Expose
     *
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    private string $name;

    /**
     * @ORM\ManyToMany(targetEntity=WishList::class, mappedBy="products")
     */
    private Collection $wishLists;

    public function __construct()
    {
        $this->wishLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|WishList[]
     */
    public function getWishLists(): Collection
    {
        return $this->wishLists;
    }

    public function addWishList(WishList $wishList): self
    {
        if (!$this->wishLists->contains($wishList)) {
            $this->wishLists[] = $wishList;
            $wishList->addProduct($this);
        }

        return $this;
    }

    public function removeWishList(WishList $wishList): self
    {
        if ($this->wishLists->contains($wishList)) {
            $this->wishLists->removeElement($wishList);
            $wishList->removeProduct($this);
        }

        return $this;
    }
}
