<?php

namespace App\Entity;

use App\Repository\TutorialAndRecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=TutorialAndRecipeRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)

 */
class TutorialAndRecipe
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
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createddAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sugar;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $tutorial;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $image;

    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", inversedBy="tutorialAndRecipes", cascade={"persist"})
     * @ORM\JoinTable(name="tutorialAndRecipes_products")
     */
    private $products;


    public function __construct()
    {
        $this->createddAt = new \DateTime();
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();


    }



    public function getId(): ?int
    {
        return $this->id;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getCreateddAt(): ?\DateTimeInterface
    {
        return $this->createddAt;
    }

    public function setCreateddAt(?\DateTimeInterface $createddAt): self
    {
        $this->createddAt = $createddAt;

        return $this;
    }

    public function getSugar(): ?bool
    {
        return $this->sugar;
    }

    public function setSugar(?bool $sugar): self
    {
        $this->sugar = $sugar;

        return $this;
    }

    public function getTutorial(): ?bool
    {
        return $this->tutorial;
    }

    public function setTutorial(?bool $tutorial): self
    {
        $this->tutorial = $tutorial;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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
        $this->products->removeElement($product);

        return $this;
    }









}
