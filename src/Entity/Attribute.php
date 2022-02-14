<?php

namespace App\Entity;

use App\Repository\AttributeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=AttributeRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)

 */
class Attribute
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createddAt;


    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="App\Entity\AttributeItem", mappedBy="attribute", cascade={"persist"})

     */
    private $attributeItems;



    public function __construct()
    {
        $this->createddAt = new \DateTime();
        $this->attributeItems = new ArrayCollection();
        $this->productHasAttributes = new ArrayCollection();

    }



    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

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
    public function getCreateddAt(): \DateTime
    {
        return $this->createddAt;
    }

    /**
     * @param \DateTime $createddAt
     */
    public function setCreateddAt(\DateTime $createddAt): void
    {
        $this->createddAt = $createddAt;
    }

    /**
     * @return Collection|AttributeItem[]
     */
    public function getAttributeItems(): Collection
    {
        return $this->attributeItems;
    }

    public function addAttributeItem(AttributeItem $attributeItem): self
    {
        if (!$this->attributeItems->contains($attributeItem)) {
            $this->attributeItems[] = $attributeItem;
            $attributeItem->setAttribute($this);
        }

        return $this;
    }

    public function removeAttributeItem(AttributeItem $attributeItem): self
    {
        if ($this->attributeItems->removeElement($attributeItem)) {
            // set the owning side to null (unless already changed)
            if ($attributeItem->getAttribute() === $this) {
                $attributeItem->setAttribute(null);
            }
        }

        return $this;
    }




}
