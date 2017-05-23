<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketRepository")
 *
 */
class Ticket
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
     * @ORM\Column(name="firstname", type="string", length=255)
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.", groups={"ticket"})
     * @Assert\Length(
     *   min=2,
     *   max=255,
     *   minMessage="Votre prénom doit comporter au moins 2 caractères.",
     *   maxMessage="Votre prénom ne peut pas dépasser 50 caractères.",
     *   groups={"ticket"}
     * )
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.", groups={"ticket"})
     * @Assert\Length(
     *   min=2,
     *   max=255,
     *   minMessage="Votre nom doit comporter au moins 2 caractères.",
     *   maxMessage="Votre nom ne peut pas dépasser 50 caractères.",
     *   groups={"ticket"}
     * )
     */
    private $lastname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="datetime")
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.", groups={"ticket"})
     * @Assert\DateTime(groups={"ticket"})
     */
    private $birthdate;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.", groups={"ticket"})
     * @Assert\Country(groups={"ticket"})
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="price_type", type="string", length=255)
     * @Assert\NotNull()
     */
    private $priceType;

    /**
     * @var boolean
     *
     * @ORM\Column(name="reduce", type="boolean")
     * @Assert\Type(type="boolean")
     */
    private $reduce;

    /**
     * @ORM\ManyToOne(targetEntity="Command", inversedBy="tickets", cascade={"persist"})
     * @ORM\JoinColumn(name="command_id", referencedColumnName="id")
     */
    private $command;



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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Ticket
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Ticket
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     *
     * @return Ticket
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Ticket
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set priceType
     *
     * @param string $priceType
     *
     * @return Ticket
     */
    public function setPriceType($priceType)
    {
        $this->priceType = $priceType;

        return $this;
    }

    /**
     * Get priceType
     *
     * @return string
     */
    public function getPriceType()
    {
        return $this->priceType;
    }

    /**
     * Set command
     *
     * @param \AppBundle\Entity\Command $command
     *
     * @return Ticket
     */
    public function setCommand(\AppBundle\Entity\Command $command = null)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Get command
     *
     * @return \AppBundle\Entity\Command
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Set reduce
     *
     * @param boolean $reduce
     *
     * @return Ticket
     */
    public function setReduce($reduce)
    {
        $this->reduce = $reduce;

        return $this;
    }

    /**
     * Get reduce
     *
     * @return boolean
     */
    public function getReduce()
    {
        return $this->reduce;
    }
}
