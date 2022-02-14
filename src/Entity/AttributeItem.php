<?php

namespace App\Entity;

use App\Repository\AttributeItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=AttributeItemRepository::class)
 *@Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)

 */
class AttributeItem
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
    private $value;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;


    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="App\Entity\Attribute", inversedBy="attributeitems")
     * @ORM\JoinColumn(name="attribute_id", referencedColumnName="id")
     */
    private $attribute;

    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="App\Entity\ProductHasAttributeItem", mappedBy="attributeItem")
     */
    private $productHasAttributeItems;


    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="App\Entity\Combination", mappedBy="attributeItem")
     */
    private $combinations;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->attribute = new ArrayCollection();
        $this->productHasAttributeItems = new ArrayCollection();
        $this->combinations = new ArrayCollection();

    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param mixed $deletedAt
     */
    public function setDeletedAt($deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreateddAt(\DateTime $createdAt): void
    {
        $this->createddAt = $createdAt;
    }


    public function setAttribute(?Attribute $attribute): self
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAttribute(): ?Attribute
    {
        return $this->attribute;
    }

    /**
     * @return Collection|ProductHasAttributeItem[]
     */
    public function getProductHasAttributeItems(): Collection
    {
        return $this->productHasAttributeItems;
    }

    public function addProductHasAttributeItem(ProductHasAttributeItem $productHasAttributeItem): self
    {
        if (!$this->productHasAttributeItems->contains($productHasAttributeItem)) {
            $this->productHasAttributeItems[] = $productHasAttributeItem;
            $productHasAttributeItem->setAttributeItem($this);
        }

        return $this;
    }

    public function removeProductHasAttributeItem(ProductHasAttributeItem $productHasAttributeItem): self
    {
        if ($this->productHasAttributeItems->removeElement($productHasAttributeItem)) {
            // set the owning side to null (unless already changed)
            if ($productHasAttributeItem->getAttributeItem() === $this) {
                $productHasAttributeItem->setAttributeItem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Combination[]
     */
    public function getCombinations(): Collection
    {
        return $this->combinations;
    }

    public function addCombination(Combination $combination): self
    {
        if (!$this->combinations->contains($combination)) {
            $this->combinations[] = $combination;
            $combination->setAttributeItem($this);
        }

        return $this;
    }

    public function removeCombination(Combination $combination): self
    {
        if ($this->combinations->removeElement($combination)) {
            // set the owning side to null (unless already changed)
            if ($combination->getAttributeItem() === $this) {
                $combination->setAttributeItem(null);
            }
        }

        return $this;
    }



}
