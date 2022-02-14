<?php

namespace App\Entity;

use App\Repository\ProductHasAttributeItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ProductHasAttributeItemRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class ProductHasAttributeItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    // ...
    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="productHasAttributeItems")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    // ...
    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="App\Entity\AttributeItem", inversedBy="productHasAttributeItems")
     * @ORM\JoinColumn(name="attribute_item_id", referencedColumnName="id")
     */
    private $attributeItem;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createddAt;

    public function __construct()
    {
        $this->createddAt = new \DateTime();


    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getCreateddAt(): ?\DateTimeInterface
    {
        return $this->createddAt;
    }

    public function setCreateddAt(?\DateTimeInterface $createddAt): self
    {
        $this->createddAt = $createddAt;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getAttributeItem(): ?AttributeItem
    {
        return $this->attributeItem;
    }

    public function setAttributeItem(?AttributeItem $attributeItem): self
    {
        $this->attributeItem = $attributeItem;

        return $this;
    }
}
