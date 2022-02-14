<?php

namespace App\Entity;

use App\Repository\ActualityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ActualityRepository::class)
 *@Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)

 */
class Actuality
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateStart;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEnd;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="App\Entity\ActualityHasProduct", mappedBy="actuality")
     */
    private $actualityHasProducts;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->actualityHasProducts = new ArrayCollection();

    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
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

    /**
     * @return Collection|ActualityHasProduct[]
     */
    public function getActualityHasProducts(): Collection
    {
        return $this->actualityHasProducts;
    }

    public function addActualityHasProduct(ActualityHasProduct $actualityHasProduct): self
    {
        if (!$this->actualityHasProducts->contains($actualityHasProduct)) {
            $this->actualityHasProducts[] = $actualityHasProduct;
            $actualityHasProduct->setActuality($this);
        }

        return $this;
    }

    public function removeActualityHasProduct(ActualityHasProduct $actualityHasProduct): self
    {
        if ($this->actualityHasProducts->removeElement($actualityHasProduct)) {
            // set the owning side to null (unless already changed)
            if ($actualityHasProduct->getActuality() === $this) {
                $actualityHasProduct->setActuality(null);
            }
        }

        return $this;
    }


}
