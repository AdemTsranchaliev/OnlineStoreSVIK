<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="`order`")
 */
class Order
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $wayOfDelivery;

     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
     private $deliverNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $deliver;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $populatedPlace;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $office;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="datetime")
     */
    private $orderOn;

    /**
     * @ORM\Column(type="string")
     */
    private $orderJson;

    /**
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $additionalInfo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="orders")
     */
    private $userId;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

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

    public function getPopulatedPlace(): ?string
    {
        return $this->populatedPlace;
    }

    public function setPopulatedPlace(string $populatedPlace): self
    {
        $this->populatedPlace = $populatedPlace;

        return $this;
    }

    public function getOffice(): ?string
    {
        return $this->office;
    }

    public function setOffice(string $office): self
    {
        $this->office = $office;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getOrderOn(): ?\DateTime
    {
        return $this->orderOn;
    }

    public function setOrderOn(\DateTime $orderOn): self
    {
        $this->orderOn = $orderOn;

        return $this;
    }

    public function getAdditionalInfo(): ?string
    {
        return $this->additionalInfo;
    }

    public function setAdditionalInfo(?string $additionalInfo): self
    {
        $this->additionalInfo = $additionalInfo;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

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

    public function getWayOfDelivery(): ?string
    {
        return $this->wayOfDelivery;
    }

    public function setWayOfDelivery(string $wayOfDelivery): self
    {
        $this->wayOfDelivery = $wayOfDelivery;

        return $this;
    }
    public function getDeliver(): ?string
    {
        return $this->deliver;
    }

    public function setDeliver(string $deliver): self
    {
        $this->deliver = $deliver;

        return $this;
    }
    public function getOrderJson(): ?string
    {
        return $this->orderJson;
    }

    public function setOrderJson(string $orderJson): self
    {
        $this->orderJson = $orderJson;

        return $this;
    }
    public function getDeliverNumber(): ?string
    {
        return $this->deliverNumber;
    }

    public function setDeliverNumber(string $deliverNumber): self
    {
        $this->deliverNumber = $deliverNumber;

        return $this;
    }
    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getstatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
