<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 *@Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)

 */
class Cart
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
     * Many Groups have Many Users.
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="carts")
     */
    private $users;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createddAt;

    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="App\Entity\CartElement", mappedBy="cart")
     */
    private $cartElements;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numberphone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $paiement;


    /**
     * @ORM\Column(type="boolean")
     */
    private $sendByCSE;

    /**
     * Many Groups have Many Users.
     * @ORM\ManyToMany(targetEntity="App\Entity\Order", mappedBy="carts")
     */
    private $orders;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pdf;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->createddAt = new \DateTime();
        $this->cartElements = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->orders = new ArrayCollection();

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|CartElement[]
     */
    public function getCartElements(): Collection
    {
        return $this->cartElements;
    }

    public function addCartElement(CartElement $cartElement): self
    {
        if (!$this->cartElements->contains($cartElement)) {
            $this->cartElements[] = $cartElement;
            $cartElement->setCart($this);
        }

        return $this;
    }

    public function removeCartElement(CartElement $cartElement): self
    {
        if ($this->cartElements->removeElement($cartElement)) {
            // set the owning side to null (unless already changed)
            if ($cartElement->getCart() === $this) {
                $cartElement->setCart(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addCart($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeCart($this);
        }

        return $this;
    }

    public function getTotal()
    {
        $total = 0;

        foreach ($this->getCartElements() as $cartElement) {
            $total += $cartElement->getQuantity() * $cartElement->getProductPriceCETTEC();
        }
        return $total;

    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getNumberphone()
    {
        return $this->numberphone;
    }

    /**
     * @param mixed $numberphone
     */
    public function setNumberphone($numberphone): void
    {
        $this->numberphone = $numberphone;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * @param mixed $activity
     */
    public function setActivity($activity): void
    {
        $this->activity = $activity;
    }

    /**
     * @return mixed
     */
    public function getPaiement()
    {
        return $this->paiement;
    }

    /**
     * @param mixed $paiement
     */
    public function setPaiement($paiement): void
    {
        $this->paiement = $paiement;
    }

    public function getSendByCSE(): ?bool
    {
        return $this->sendByCSE;
    }

    public function setSendByCSE(bool $sendByCSE): self
    {
        $this->sendByCSE = $sendByCSE;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->addCart($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            $order->removeCart($this);
        }

        return $this;
    }

    public function getPdf(): ?string
    {
        return $this->pdf;
    }

    public function setPdf(?string $pdf): self
    {
        $this->pdf = $pdf;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

}
