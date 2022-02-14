<?php

namespace App\Entity;

use App\Repository\CartElementRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=CartElementRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)

 */
class CartElement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $productName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $productReference;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default" : false})
     */
    private $send;
    /**
     * @ORM\Column(type="float")
     */
    private $productPricePublicTTC;

    /**
     * @ORM\Column(type="string")
     */
    private $productImage;


    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createddAt;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;


    /**
     * @ORM\Column(type="float")
     */
    private $productPriceCETTEC;

    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="App\Entity\Cart", inversedBy="cartElements",  cascade={"persist"})
     * @ORM\JoinColumn(name="cart_id", referencedColumnName="id")
     */
    private $cart;

    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="cartElements")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    public function __construct()
    {
        $this->createddAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductReference(): ?string
    {
        return $this->productReference;
    }

    public function setProductReference(string $productReference): self
    {
        $this->productReference = $productReference;

        return $this;
    }

    public function getProductPricePublicTTC(): ?float
    {
        return $this->productPricePublicTTC;
    }

    public function setProductPricePublicTTC(float $productPricePublicTTC): self
    {
        $this->productPricePublicTTC = $productPricePublicTTC;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

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

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getProductPriceCETTEC(): ?float
    {
        return $this->productPriceCETTEC;
    }

    public function setProductPriceCETTEC(float $productPriceCETTEC): self
    {
        $this->productPriceCETTEC = $productPriceCETTEC;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function getPriceQuantity()
    {
        return $this->productPriceCETTEC * $this->getQuantity();

    }

    public function getProductImage(): ?string
    {
        return $this->productImage;
    }

    public function setProductImage(string $productImage): self
    {
        $this->productImage = $productImage;

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

    public function getSend(): ?bool
    {
        return $this->send;
    }

    public function setSend(bool $send): self
    {
        $this->send = $send;

        return $this;
    }


}
