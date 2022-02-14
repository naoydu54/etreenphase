<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 *@Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)

 */
class Order
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
    private $createddAt;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="App\Entity\Cart", inversedBy="orders")
     * @ORM\JoinTable(name="orders_carts")
     */
    private $carts;

    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="App\Entity\OrderElement", mappedBy="order")
     */
    private $orderElements;


    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="App\Entity\OrderStatus", inversedBy="orders")
     * @ORM\JoinColumn(name="order_status_id", referencedColumnName="id")
     */
    private $orderStatus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pdfOrder;

    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="orders")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;

    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="orers")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    public function __construct()
    {
        $this->createddAt = new \DateTime();
        $this->orderElements = new ArrayCollection();
        $this->cart = new ArrayCollection();
        $this->carts = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Cart[]
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): self
    {
        if (!$this->carts->contains($cart)) {
            $this->carts[] = $cart;
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        $this->carts->removeElement($cart);

        return $this;
    }

    /**
     * @return Collection|OrderElement[]
     */
    public function getOrderElements(): Collection
    {
        return $this->orderElements;
    }

    public function addOrderElement(OrderElement $orderElement): self
    {
        if (!$this->orderElements->contains($orderElement)) {
            $this->orderElements[] = $orderElement;
            $orderElement->setOrder($this);
        }

        return $this;
    }

    public function removeOrderElement(OrderElement $orderElement): self
    {
        if ($this->orderElements->removeElement($orderElement)) {
            // set the owning side to null (unless already changed)
            if ($orderElement->getOrder() === $this) {
                $orderElement->setOrder(null);
            }
        }

        return $this;
    }



    public function getPdfOrder(): ?string
    {
        return $this->pdfOrder;
    }

    public function setPdfOrder(string $pdfOrder): self
    {
        $this->pdfOrder = $pdfOrder;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getOrderStatus(): ?OrderStatus
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(?OrderStatus $orderStatus): self
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }




}
