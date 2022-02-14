<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 */
class Product
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pricePublicTTC;


    /**
     * @ORM\Column(type="float", nullable=true, nullable=true)
     */
    private $priceCeTTC;

    /**
     * @ORM\Column(type="text", nullable=true)
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
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="App\Entity\ProductHasAttributeItem", mappedBy="product", cascade={"persist"})
     */
    private $productHasAttributeItems;

    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="App\Entity\Menu", inversedBy="products")
     */
    private $menus;


    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="App\Entity\Combination", mappedBy="product")
     */
    private $combinations;

    /**
     * @ORM\Column(type="boolean")
     */
    private $configurable;

    /**
     * @ORM\Column(type="boolean")
     */
    private $incontournable;

    /**
     * @ORM\Column(type="boolean")
     */
    private $available;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $useAndMaintenance;

    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="App\Entity\File", inversedBy="products")
     * @ORM\JoinTable(name="products_files")
     */
    private $files;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $image;

    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="App\Entity\CartElement", mappedBy="product")
     */
    private $cartElements;

    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="App\Entity\ActualityHasProduct", mappedBy="product")
     */
    private $actualityHasProducts;



    /**
     * Many Groups have Many Users.
     * @ORM\ManyToMany(targetEntity="App\Entity\TutorialAndRecipe", mappedBy="products", cascade={"persist"})
     */
    private $tutorialAndRecipes;

    public function __construct()
    {
        $this->createddAt = new \DateTime();
        $this->productHasAttributes = new ArrayCollection();
        $this->productHasAttributeItems = new ArrayCollection();
        $this->productHasMenus = new ArrayCollection();
        $this->menus = new ArrayCollection();
        $this->combinations = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->cartElements = new ArrayCollection();
        $this->actualityHasProducts = new ArrayCollection();
        $this->incontournable = 0;
        $this->available = 1;
        $this->tutorialAndRecipes = new \Doctrine\Common\Collections\ArrayCollection();




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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

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
            $productHasAttributeItem->setProduct($this);
        }

        return $this;
    }

    public function removeProductHasAttributeItem(ProductHasAttributeItem $productHasAttributeItem): self
    {
        if ($this->productHasAttributeItems->removeElement($productHasAttributeItem)) {
            // set the owning side to null (unless already changed)
            if ($productHasAttributeItem->getProduct() === $this) {
                $productHasAttributeItem->setProduct(null);
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPricePublicTTC(): ?float
    {
        return $this->pricePublicTTC;
    }

    public function setPricePublicTTC(float $pricePublicTTC): self
    {
        $this->pricePublicTTC = $pricePublicTTC;

        return $this;
    }

    public function getPriceCeTTC(): ?float
    {
        return $this->priceCeTTC;
    }

    public function setPriceCeTTC(float $priceCeTTC): self
    {
        $this->priceCeTTC = $priceCeTTC;

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
            $combination->setProduct($this);
        }

        return $this;
    }

    public function removeCombination(Combination $combination): self
    {
        if ($this->combinations->removeElement($combination)) {
            // set the owning side to null (unless already changed)
            if ($combination->getProduct() === $this) {
                $combination->setProduct(null);
            }
        }

        return $this;
    }

    public function getConfigurable(): ?bool
    {
        return $this->configurable;
    }

    public function setConfigurable(bool $configurable): self
    {
        $this->configurable = $configurable;

        return $this;
    }

    public function getUseAndMaintenance(): ?string
    {
        return $this->useAndMaintenance;
    }

    public function setUseAndMaintenance(?string $useAndMaintenance): self
    {
        $this->useAndMaintenance = $useAndMaintenance;

        return $this;
    }

    /**
     * @return Collection|File[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
        }

        return $this;
    }

    public function removeFile(File $file): self
    {
        $this->files->removeElement($file);

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
            $cartElement->setProduct($this);
        }

        return $this;
    }

    public function removeCartElement(CartElement $cartElement): self
    {
        if ($this->cartElements->removeElement($cartElement)) {
            // set the owning side to null (unless already changed)
            if ($cartElement->getProduct() === $this) {
                $cartElement->setProduct(null);
            }
        }

        return $this;
    }

    public function getIncontournable(): ?bool
    {
        return $this->incontournable;
    }

    public function setIncontournable(bool $incontournable): self
    {
        $this->incontournable = $incontournable;

        return $this;
    }

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): self
    {
        $this->available = $available;

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
            $actualityHasProduct->setProduct($this);
        }

        return $this;
    }

    public function removeActualityHasProduct(ActualityHasProduct $actualityHasProduct): self
    {
        if ($this->actualityHasProducts->removeElement($actualityHasProduct)) {
            // set the owning side to null (unless already changed)
            if ($actualityHasProduct->getProduct() === $this) {
                $actualityHasProduct->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TutorialAndRecipe[]
     */
    public function getTutorialAndRecipes(): Collection
    {
        return $this->tutorialAndRecipes;
    }

    public function addTutorialAndRecipe(TutorialAndRecipe $tutorialAndRecipe): self
    {
        if (!$this->tutorialAndRecipes->contains($tutorialAndRecipe)) {
            $this->tutorialAndRecipes[] = $tutorialAndRecipe;
            $tutorialAndRecipe->addProduct($this);
        }

        return $this;
    }

    public function removeTutorialAndRecipe(TutorialAndRecipe $tutorialAndRecipe): self
    {
        if ($this->tutorialAndRecipes->removeElement($tutorialAndRecipe)) {
            $tutorialAndRecipe->removeProduct($this);
        }

        return $this;
    }






}
