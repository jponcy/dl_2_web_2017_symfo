<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity()
 *
 * @UniqueEntity("reference")
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Animal
{

    /**
     * @ORM\Id()
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue()
     */
    protected $id;

    /**
     * @ORM\Column(unique=true)
     */
    private $reference;

    /**
     * @ORM\Column(type="float")
     */
    private $weight;

    /**
     * @ORM\Column(type="datetime")
     */
    private $birthdate;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(nullable=true, length=50)
     */
    private $color;

    /**
     * @ORM\ManyToOne(targetEntity="Species")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var Species
     */
    private $species;

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updatePrice()
    {
        if ($this->getPrice() === null) {
            $this->setPrice($this->getSpecies()->getPrice());
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
        return $this;
    }

    public function getBirthdate()
    {
        return $this->birthdate;
    }

    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }

    /**
     * Set the species.
     *
     * @param Species $species
     *
     * @return Animal
     */
    public function setSpecies(Species $species)
    {
        if ($this->species !== $species) {
            $this->species = $species;
        }

        return $this;
    }

    /**
     * Get the species.
     *
     * @return Species
     */
    public function getSpecies()
    {
        return $this->species;
    }
}
