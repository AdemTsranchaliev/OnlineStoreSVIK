<?php

namespace SoftUniBlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Models
 *
 * @ORM\Table(name="models")
 * @ORM\Entity(repositoryClass="SoftUniBlogBundle\Repository\ModelsRepository")
 */
class Models
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="modelNumber", type="string", length=40, nullable=true)
     */
    private $modelNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=50, nullable=true)
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="string", length=50, nullable=true)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="size", type="string", length=255, nullable=true)
     */
    private $size;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255)
     */
    private $category;

    /**
     * @var int
     *
     * @ORM\Column(name="boughtCounter", type="integer")
     */
    private $boughtCounter;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string")
     */
    private $description;
    /**
     * @var string
     *
     * @ORM\Column(name="discount", type="string")
     */
    private $discount;
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=40, nullable=true)
     */
    private $title;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set modelNumber
     *
     * @param string $modelNumber
     *
     * @return Models
     */
    public function setModelNumber($modelNumber)
    {
        $this->modelNumber = $modelNumber;

        return $this;
    }

    /**
     * Get modelNumber
     *
     * @return string
     */
    public function getModelNumber()
    {
        return $this->modelNumber;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return Models
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set size
     *
     * @param string $size
     *
     * @return Models
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }



    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $price
     */
    public function setPrice( $price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory( $category)
    {
        $this->category = $category;
    }

    /**
     * @return int
     */
    public function getBoughtCounter()
    {
        return $this->boughtCounter;
    }

    /**
     * @param int $boughtCounter
     */
    public function setBoughtCounter($boughtCounter)
    {
        $this->boughtCounter = $boughtCounter;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription( $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param string $discount
     */
    public function setDiscount( $discount)
    {
        $this->discount = $discount;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle( $title)
    {
        $this->title = $title;
    }


}

