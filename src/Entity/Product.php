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
    private $model;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $color;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $colorCode;
    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

     /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $statuses;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isDeleted;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isInPromotion;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $discount;

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
     * @ORM\Column(type="string", nullable=true)
     */
    private $pictures;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ShoppingCart", mappedBy="cartProduct")
     */
    private $shoppingCarts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="product")
     */
    private $categoryId;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isNew;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $avgRating;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="product")
     */
    public $reviews;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $inside;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $outside;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $soleThickness;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $shoeHigth;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="string",length=50, nullable=true)
     */
    private $insole;

    public function __construct()
    {
        $this->shoppingCarts = new ArrayCollection();
        $this->categoryId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }
    public function getColorCode(): ?string
    {
        return $this->colorCode;
    }

    public function setColorCode(string $colorCode): self
    {
        $this->colorCode = $colorCode;

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

    public function getPictures(): ?string
    {
        return $this->pictures;
    }

    public function setPictures(string $pictures): self
    {
        $this->pictures = $pictures;
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
    
     /**
     * @return Collection|Review[]
     */
    public function getReviews(): ? Collection
    {
        return $this->reviews;
    }

    public function getAvgRating(): ?int
    {
        return $this->avgRating;
    }

    public function setAvgRating(int $avgRating)
    {
        $this->avgRating = $avgRating;

        return $this;
    }

     public function getInside(): ?string
     {
         return $this->inside;
     }

     public function setInside(string $inside)
     {
         $this->inside = $inside;

         return $this;
     }

     public function getStatuses(): ?string
     {
         return $this->statuses;
     }
     public function setStatuses(string $statuses)
     {
         $this->statuses = $statuses;

         return $this;
     }

     public function getOutside(): ?string
     {
         return $this->outside;
     }
     public function setOutside(string $outside)
     {
         $this->outside = $outside;

         return $this;
     }

     public function getSoleThickness(): ?float
     {
         return $this->soleThickness;
     }

     public function setSoleThickness(?float $soleThickness)
     {
         $this->soleThickness = $soleThickness;

         return $this;
     }

     public function getShoeHigth(): ?float
     {
         return $this->shoeHigth;
     }

     public function setShoeHigth(?float $shoeHigthh)
     {
         $this->shoeHigth = $shoeHigthh;

         return $this;
     }

     public function getWeight(): ?float
     {
         return $this->weight;
     }

     public function setWeight(?float $weight)
     {
         $this->weight = $weight;

         return $this;
     }
     public function getInsole(): ?string
     {
         return $this->insole;
     }

     public function setInsole(?string $insole)
     {
         $this->insole = $insole;

         return $this;
     }

     public function getDiscount(): ?float
     {
         return $this->discount;
     }

     public function setDiscount(float $discount)
     {
         $this->discount = $discount;

         return $this;
     }
}
