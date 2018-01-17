<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="phone")
 */
class Phone
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $model;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=25)
     *
     * @Assert\NotBlank()
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="op_system", type="string", length=10)
     */
    private $opSystem;

    /**
     * @var integer
     *
     * @ORM\Column(name="storage", type="integer")
     *
     * @Assert\NotBlank()
     */
    private $storage;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=25)
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     *
     * @Assert\NotBlank(message="Veuillez décrire (même brièvement) le téléphone SVP.")
     */
    private $description;
    /**
     * @var float
     *
     * @ORM\Column(name="price", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $price;

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set brand
     *
     * @param string $brand
     *
     * @return Phone
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set model
     *
     * @param string $model
     *
     * @return Phone
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Phone
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }


    /**
     * Set opSystem
     *
     * @param string $opSystem
     *
     * @return Phone
     */
    public function setOpSystem($opSystem)
    {
        $this->opSystem = $opSystem;

        return $this;
    }

    /**
     * Get opSystem
     *
     * @return string
     */
    public function getOpSystem()
    {
        return $this->opSystem;
    }

    /**
     * Set storage
     *
     * @param string $storage
     *
     * @return Phone
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;

        return $this;
    }

    /**
     * Get storage
     *
     * @return integer
     */
    public function getStorage()
    {
        return $this->storage;
    }


    /**
     * Set color
     *
     * @param string $color
     *
     * @return Phone
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
     * Set description
     *
     * @param string $description
     *
     * @return Phone
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Phone
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }
}