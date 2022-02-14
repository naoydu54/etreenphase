<?php

namespace App\Entity;

use App\Repository\ActualityHasProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActualityHasProductRepository::class)
 */
class ActualityHasProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="actualityHasProducts")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;


    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="App\Entity\Actuality", inversedBy="actualityHasProducts")
     * @ORM\JoinColumn(name="actuality_id", referencedColumnName="id")
     */
    private $actuality;

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

    public function getActuality(): ?Actuality
    {
        return $this->actuality;
    }

    public function setActuality(?Actuality $actuality): self
    {
        $this->actuality = $actuality;

        return $this;
    }
}
