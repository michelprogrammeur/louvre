<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Tests\StringableObject;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Command
 *
 * @ORM\Table(name="command")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandRepository")
 *
 */
class Command
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
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.", groups={"payment"})
     * @Assert\Length(
     *   min=2,
     *   max=255,
     *   minMessage="Votre prénom doit comporter au moins 2 caractères.",
     *   maxMessage="Votre prénom ne peut pas dépasser 50 caractères.",
     *   groups={"payment"}
     * )
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.", groups={"payment"})
     * @Assert\Length(
     *   min=2,
     *   max=255,
     *   minMessage="Votre nom doit comporter au moins 2 caractères.",
     *   maxMessage="Votre nom ne peut pas dépasser 50 caractères.",
     *   groups={"payment"}
     * )
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.", groups={"payment"})
     * @Assert\Email(message="L'email rentrée n'est pas valide.", groups={"payment"})
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="visit_date", type="date")
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.", groups={"visit"})
     * @Assert\DateTime(message="La date de visite n'est pas valide.", groups={"visit"})
     * @Assert\GreaterThanOrEqual("today", message="Vous ne pouvez pas choisir une date passée", groups={"visit"})
     */
    private $visitDate;

    /**
     * @var string
     *
     * @ORM\Column(name="ticket_type", type="string", length=255)
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.", groups={"visit"})
     */
    private $ticketType;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     * @Assert\NotNull()
     * @Assert\Type(type="integer")
     */
    private $quantity;

    /**
     * @var int
     *
     * @ORM\Column(name="total", type="integer")
     * @Assert\NotNull()
     * @Assert\Type(type="integer")
     */
    private $total;

    /**
     * @var string
     *
     * @ORM\Column(name="command_number", type="string")
     * @Assert\DateTime()
     */
    private $commandNumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="commanded_at", type="datetime")
     * @Assert\DateTime()
     */
    private $commandedAt;

    /**
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="command", cascade={"persist"})
     */
    private $tickets;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }


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
     * @return Command
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
     * @return Command
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
     * Set email
     *
     * @param string $email
     *
     * @return Command
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
     * Set visitDate
     *
     * @param \DateTime $visitDate
     *
     * @return Command
     */
    public function setVisitDate($visitDate)
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    /**
     * Get visitDate
     *
     * @return \DateTime
     */
    public function getVisitDate()
    {
        return $this->visitDate;
    }

    /**
     * Set ticketType
     *
     * @param string $ticketType
     *
     * @return Command
     */
    public function setTicketType($ticketType)
    {
        $this->ticketType = $ticketType;

        return $this;
    }

    /**
     * Get ticketType
     *
     * @return string
     */
    public function getTicketType()
    {
        return $this->ticketType;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Command
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set total
     *
     * @param integer $total
     *
     * @return Command
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }


    /**
     * Set commandNumber
     *
     * @return Command
     */
    public function setCommandNumber()
    {
        $commandNumber = $this->generateCommandNum();
        $this->commandNumber = $commandNumber;

        return $this;
    }

    private function generateCommandNum() {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $random_letter = substr(str_shuffle($chars), 0, 8);
        $bytes = random_bytes(8);
        $command_number = $random_letter . bin2hex($bytes);

        return $command_number;
    }

    /**
     * Get commandNumber
     *
     * @return string
     */
    public function getCommandNumber()
    {
        return $this->commandNumber;
    }

    /**
     * Set commandedAt
     *
     * @param \DateTime $commandedAt
     *
     * @return Command
     */
    public function setCommandedAt($commandedAt)
    {
        $this->commandedAt = $commandedAt;

        return $this;
    }

    /**
     * Get commandedAt
     *
     * @return \DateTime
     */
    public function getCommandedAt()
    {
        return $this->commandedAt;
    }

    /**
     * Add ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     *
     * @return Command
     */
    public function addTicket(\AppBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;
        $ticket->setCommand($this);

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\AppBundle\Entity\Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

}
