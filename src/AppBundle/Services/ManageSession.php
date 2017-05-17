<?php

namespace AppBundle\Services;

use AppBundle\Entity\Ticket;
use Symfony\Component\HttpFoundation\Session\Session;

class ManageSession
{
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }


    public function addTicketInSession(Ticket $ticket, $session) {
        $session->addTicket($ticket);
    }


    public function removeTicketFromSession(int $id = null, $session) {
        $tickets = $session->getTickets();
        foreach($tickets as $key => $ticket) {
            if ($key == $id) {
                $session->removeTicket($ticket);
            }
        }
    }


    public function clearSession(String $name) {
        $this->session->remove($name);
    }
}
