<?php

namespace AppBundle\Services;

use AppBundle\Entity\Ticket;
use Symfony\Component\HttpFoundation\Session\Session;

class AssignPrice
{
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function manageTicket(Object $session) {
        $tickets = $session->getTickets();

        foreach ($tickets as $key => $ticket) {
            $pt = $this->choosePriceType($ticket);
            $price_type = $this->isReduce($ticket, $pt);
            $ticket->price = $this->getPrice($price_type);
            $ticket->setPriceType($price_type);
        }

        $total = $this->calculTotal($session);
        $quantity = $this->calculQuantity($session);
        $session->setTotal($total);
        $session->setQuantity($quantity);
    }

    public function isReduce(Ticket $ticket, String $price_type) {
        if($ticket->getReduce() && $price_type === 'free') {
            $price_type = 'free';
        }elseif($ticket->getReduce() && $price_type === 'enfant') {
            $price_type = 'enfant';
        }elseif($ticket->getReduce()) {
            $price_type = 'reduce';
        }

        return $price_type;
    }


    public function choosePriceType(Ticket $ticket) {
        $date = $ticket->getBirthdate()->format('d-m-Y');
        $birthdate = new \DateTime($date);
        $now = new \DateTime();
        $interval = $now->diff($birthdate);
        $age = $interval->y;

        switch ($age) {
            case $age >= 4 && $age <= 12:

                return "enfant";
                break;
            case $age > 12 && $age < 60:

                return "normal";
                break;
            case $age >= 60:

                return "senior";
                break;
            default:

                return "free";
        }
    }


    public function getPrice(string $price_type) {
        switch ($price_type) {
            case "enfant":
                return 8;
                break;

            case "normal":
                return 16;
                break;

            case "senior":
                return 12;
                break;

            case "reduce":
                return 10;
                break;

            default:
                return 0;
        }
    }


    public function calculTotal(Object $session) {
        $tickets = $session->getTickets();

        $sum = 0;
        foreach($tickets as $key => $ticket){
            $sum += $ticket->price;
        }

        return $sum;
    }


    public function calculQuantity(Object $session) {
        $tickets = $session->getTickets();
        $quantity = count($tickets);

        return $quantity;
    }

}
