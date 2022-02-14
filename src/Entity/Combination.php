<?php

namespace App\Entity;

use App\Repository\CombinationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CombinationRepository::class)
 */
class Combination
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="combinations")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="App\Entity\AttributeItem", inversedBy="combinations")
     * @ORM\JoinColumn(name="attribute_item_id", referencedColumnName="id")
     */
    private $attributeItem;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pricePublicTTC;


    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $priceCeTTC;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPricePublicTTC(): ?float
    {
        return $this->pricePublicTTC;
    }

    public function setPricePublicTTC(?float $pricePublicTTC): self
    {
        $this->pricePublicTTC = $pricePublicTTC;

        return $this;
    }

    public function getPriceCeTTC(): ?float
    {
        return $this->priceCeTTC;
    }

    public function setPriceCeTTC(?float $priceCeTTC): self
    {
        $this->priceCeTTC = $priceCeTTC;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }
}
