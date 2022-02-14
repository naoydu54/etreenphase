<?php

namespace App\Entity;

use App\Repository\PageRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass=PageRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)

 */
class Page
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")

     */
    private $id;

    /**
     * @ORM\Column(name="deletedAt", type="string", length=255)
     */
    private $name;

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
     * One Customer has One Cart.
     * @ORM\OneToOne(targetEntity="App\Entity\Menu", mappedBy="page")
     */
    private $menu;



    public function __construct()
    {
        $this->createddAt = new \DateTime();
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
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
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

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): self
    {
        // unset the owning side of the relation if necessary
        if ($menu === null && $this->menu !== null) {
            $this->menu->setPage(null);
        }

        // set the owning side of the relation if necessary
        if ($menu !== null && $menu->getPage() !== $this) {
            $menu->setPage($this);
        }

        $this->menu = $menu;

        return $this;
    }
}
