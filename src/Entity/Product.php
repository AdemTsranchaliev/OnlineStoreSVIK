<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\FormTypeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modelNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $boughtCounter;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isInPromotion;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $discountPrice;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sizes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categoryR;

    /**
     * @ORM\Column(type="integer")
     */
    private $photoCount;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ShoppingCart", mappedBy="cartProduct")
     */
    private $shoppingCarts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="product")
     */
    private $categoryId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isNew;

    public function __construct()
    {
        $this->shoppingCarts = new ArrayCollection();
        $this->categoryId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModelNumber(): ?string
    {
        return $this->modelNumber;
    }

    public function setModelNumber(string $modelNumber): self
    {
        $this->modelNumber = $modelNumber;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getBoughtCounter(): ?string
    {
        return $this->boughtCounter;
    }

    public function setBoughtCounter(string $boughtCounter): self
    {
        $this->boughtCounter = $boughtCounter;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getIsInPromotion(): ?bool
    {
        return $this->isInPromotion;
    }

    public function setIsInPromotion(bool $isInPromotion): self
    {
        $this->isInPromotion = $isInPromotion;

        return $this;
    }

    public function getDiscountPrice(): ?float
    {
        return $this->discountPrice;
    }

    public function setDiscountPrice(?float $discountPrice): self
    {
        $this->discountPrice = $discountPrice;

        return $this;
    }

    public function getSizes(): ?string
    {
        return $this->sizes;
    }

    public function setSizes(string $sizes): self
    {
        $this->sizes = $sizes;

        return $this;
    }

    public function getCategoryR(): ?Category
    {
        return $this->categoryR;
    }

    public function setCategoryR(?Category $categoryR): self
    {
        $this->categoryR = $categoryR;

        return $this;
    }

    public function getPhotoCount(): ?int
    {
        return $this->photoCount;
    }

    public function setPhotoCount(int $photoCount): self
    {
        $this->photoCount = $photoCount;

        return $this;
    }

    /**
     * @return Collection|ShoppingCart[]
     */
    public function getShoppingCarts(): Collection
    {
        return $this->shoppingCarts;
    }

    public function addShoppingCart(ShoppingCart $shoppingCart): self
    {
        if (!$this->shoppingCarts->contains($shoppingCart)) {
            $this->shoppingCarts[] = $shoppingCart;
            $shoppingCart->addCartProduct($this);
        }

        return $this;
    }

    public function removeShoppingCart(ShoppingCart $shoppingCart): self
    {
        if ($this->shoppingCarts->contains($shoppingCart)) {
            $this->shoppingCarts->removeElement($shoppingCart);
            $shoppingCart->removeCartProduct($this);
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategoryId(): Collection
    {
        return $this->categoryId;
    }

    public function addCategoryId(Category $categoryId): self
    {
        if (!$this->categoryId->contains($categoryId)) {
            $this->categoryId[] = $categoryId;
            $categoryId->setProduct($this);
        }

        return $this;
    }

    public function removeCategoryId(Category $categoryId): self
    {
        if ($this->categoryId->contains($categoryId)) {
            $this->categoryId->removeElement($categoryId);
            // set the owning side to null (unless already changed)
            if ($categoryId->getProduct() === $this) {
                $categoryId->setProduct(null);
            }
        }

        return $this;
    }

    public function getIsNew(): ?bool
    {
        return $this->isNew;
    }

    public function setIsNew(bool $isNew): self
    {
        $this->isNew = $isNew;

        return $this;
    }
}
