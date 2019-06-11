<?php

namespace SoftUniBlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Orders
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="SoftUniBlogBundle\Repository\OrdersRepository")
 */
class Orders
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255)
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="Adress", type="string", length=255)
     */
    private $adress;

    /**
     * @var string
     *
     * @ORM\Column(name="populatedPlace", type="string", length=255)
     */
    private $populatedPlace;

    /**
     * @var string
     *
     * @ORM\Column(name="EcontOffice", type="string", length=255)
     */
    private $econtOffice;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="orderedOn", type="datetime")
     */
    private $orderedOn;

    /**
     * @var string
     *
     * @ORM\Column(name="modelNumber", type="string")
     */
    private $modelNumber;

    /**
     * @var boolean
     *
     * @ORM\Column(name="newOrArchived", type="boolean")
     */
    private $newOrArchived;

    /**
     * @var string
     *
     * @ORM\Column(name="postalCode", type="string")
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="addedInfo", type="string")
     */
    private $addedInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="modelNumberOrdered", type="string")
     */
    private $modelNumberOrdered;
    /**
     * @var string
     *
     * @ORM\Column(name="priceOrdered", type="string")
     */
    private $priceOrdered;
    /**
     * @var string
     *
     * @ORM\Column(name="modelId", type="string")
     */
    private $modelId;

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
     * Set name
     *
     * @param string $name
     *
     * @return Orders
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     *
     * @return Orders
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Orders
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set adress
     *
     * @param string $adress
     *
     * @return Orders
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get adress
     *
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set populatedPlace
     *
     * @param string $populatedPlace
     *
     * @return Orders
     */
    public function setPopulatedPlace($populatedPlace)
    {
        $this->populatedPlace = $populatedPlace;

        return $this;
    }

    /**
     * Get populatedPlace
     *
     * @return string
     */
    public function getPopulatedPlace()
    {
        return $this->populatedPlace;
    }

    /**
     * Set econtOffice
     *
     * @param string $econtOffice
     *
     * @return Orders
     */
    public function setEcontOffice($econtOffice)
    {
        $this->econtOffice = $econtOffice;

        return $this;
    }

    /**
     * Get econtOffice
     *
     * @return string
     */
    public function getEcontOffice()
    {
        return $this->econtOffice;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Orders
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return DateTime
     */
    public function getOrderedOn(): DateTime
    {
        return $this->orderedOn;
    }

    /**
     * @param DateTime $orderedOn
     */
    public function setOrderedOn(DateTime $orderedOn)
    {
        $this->orderedOn = $orderedOn;
    }

    /**
     * @return string
     */
    public function getModelNumber(): string
    {
        return $this->modelNumber;
    }

    /**
     * @param string $modelNumber
     */
    public function setModelNumber(string $modelNumber)
    {
        $this->modelNumber = $modelNumber;
    }

    /**
     * @return bool
     */
    public function isNewOrArchived()
    {
        return $this->newOrArchived;
    }

    /**
     * @param bool $newOrArchived
     */
    public function setNewOrArchived(bool $newOrArchived)
    {
        $this->newOrArchived = $newOrArchived;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     */
    public function setPostalCode(string $postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return string
     */
    public function getAddedInfo()
    {
        return $this->addedInfo;
    }

    /**
     * @param string $addedInfo
     */
    public function setAddedInfo(string $addedInfo)
    {
        $this->addedInfo = $addedInfo;
    }

    /**
     * @return string
     */
    public function getModelNumberOrdered()
    {
        return $this->modelNumberOrdered;
    }

    /**
     * @param string $modelNumberOrdered
     */
    public function setModelNumberOrdered(string $modelNumberOrdered)
    {
        $this->modelNumberOrdered = $modelNumberOrdered;
    }

    /**
     * @return string
     */
    public function getPriceOrdered()
    {
        return $this->priceOrdered;
    }

    /**
     * @param string $priceOrdered
     */
    public function setPriceOrdered(string $priceOrdered)
    {
        $this->priceOrdered = $priceOrdered;
    }

    /**
     * @return string
     */
    public function getModelId()
    {
        return $this->modelId;
    }

    /**
     * @param string $modelId
     */
    public function setModelId(string $modelId)
    {
        $this->modelId = $modelId;
    }


}

