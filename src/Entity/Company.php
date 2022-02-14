<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 */
class Company
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
    private $name;



    /**
     * @ORM\Column(type="string", length=255)
     */
    private $legalForm;

    /**
     * @ORM\Column(type="integer")
     */
    private $effective;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $siret;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $siretConcerned;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nafApe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lasname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numberPhone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dayOfPermanence;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;




    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="company")
     */
    private $users;

    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="company")
     */
    private $orders;

    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="App\Entity\Menu", inversedBy="companies")
     * @ORM\JoinTable(name="companies_menus")
     */
    private $menus;


    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="App\Entity\CompanyHasAddress", mappedBy="company")
     */
    private $companyHasAdresses;

    public function __construct() {
        $this->users = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->menus = new ArrayCollection();
        $this->companyHasAdresses = new ArrayCollection();

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
            $user->setCompany($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCompany() === $this) {
                $user->setCompany(null);
            }
        }

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
            $order->setCompany($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getCompany() === $this) {
                $order->setCompany(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection|Menu[]
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        $this->menus->removeElement($menu);

        return $this;
    }



    public function getLegalForm(): ?string
    {
        return $this->legalForm;
    }

    public function setLegalForm(string $legalForm): self
    {
        $this->legalForm = $legalForm;

        return $this;
    }

    public function getEffective(): ?int
    {
        return $this->effective;
    }

    public function setEffective(int $effective): self
    {
        $this->effective = $effective;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getSiretConcerned(): ?string
    {
        return $this->siretConcerned;
    }

    public function setSiretConcerned(string $siretConcerned): self
    {
        $this->siretConcerned = $siretConcerned;

        return $this;
    }

    public function getNafApe(): ?string
    {
        return $this->nafApe;
    }

    public function setNafApe(string $nafApe): self
    {
        $this->nafApe = $nafApe;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLasname(): ?string
    {
        return $this->lasname;
    }

    public function setLasname(string $lasname): self
    {
        $this->lasname = $lasname;

        return $this;
    }

    public function getNumberPhone(): ?string
    {
        return $this->numberPhone;
    }

    public function setNumberPhone(string $numberPhone): self
    {
        $this->numberPhone = $numberPhone;

        return $this;
    }

    public function getDayOfPermanence(): ?string
    {
        return $this->dayOfPermanence;
    }

    public function setDayOfPermanence(string $dayOfPermanence): self
    {
        $this->dayOfPermanence = $dayOfPermanence;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|CompanyHasAddress[]
     */
    public function getCompanyHasAdresses(): Collection
    {
        return $this->companyHasAdresses;
    }

    public function addCompanyHasAdress(CompanyHasAddress $companyHasAdress): self
    {
        if (!$this->companyHasAdresses->contains($companyHasAdress)) {
            $this->companyHasAdresses[] = $companyHasAdress;
            $companyHasAdress->setCompany($this);
        }

        return $this;
    }

    public function removeCompanyHasAdress(CompanyHasAddress $companyHasAdress): self
    {
        if ($this->companyHasAdresses->removeElement($companyHasAdress)) {
            // set the owning side to null (unless already changed)
            if ($companyHasAdress->getCompany() === $this) {
                $companyHasAdress->setCompany(null);
            }
        }

        return $this;
    }
}
