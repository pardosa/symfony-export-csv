<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /** 
     * @ORM\Id()
     * @ORM\Column(type="integer") 
     */
    private $product_id;
    /** @ORM\Column(length=140) */
    private $title;
    /** @ORM\Column(length=140) */
    private $image;
    /** @ORM\Column(length=140) */
    private $thumbnail;
    /** @ORM\Column(length=140) */
    private $url;
    /** @ORM\Column(length=140) */
    private $created_at;

    /**
     * One product can has many categories.
     * @ORM\OneToMany(targetEntity="Category", mappedBy="product")
     */
    private $categories;

    /**
     * @ORM\OneToOne(targetEntity="Brand")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="brand_id")
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     */
    private $subtitle;

    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     */
    private $upc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $gtin14;
    

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function setProductId($id): ?int
    {
        return $this->product_id = $id;
    }

    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(string $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setProduct($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            // set the owning side to null (unless already changed)
            if ($category->getProduct() === $this) {
                $category->setProduct(null);
            }
        }

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getUpc(): ?string
    {
        return $this->upc;
    }

    public function setUpc(?string $upc): self
    {
        $this->upc = $upc;

        return $this;
    }

    public function getGtin14(): ?int
    {
        return $this->gtin14;
    }

    public function setGtin14(?int $gtin14): self
    {
        $this->gtin14 = $gtin14;

        return $this;
    }

}
