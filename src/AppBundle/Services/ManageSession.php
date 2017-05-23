<?php

namespace AppBundle\Services;

use AppBundle\Entity\Ticket;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

class ManageSession
{
    private $limit_ticket_by_day = 1000;
    protected $session;
    protected $request;
    protected $em;

    public function __construct(Session $session, RequestStack $request, EntityManager $em)
    {
        $this->session = $session;
        $this->request = $request;
        $this->em = $em;
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

    public function blockCommandTicket($session, String $date, String $entity) {
        $repository = $this->em->getRepository($entity);
        $number_ticket = $repository->CountAllTicketsByDay($date);
        if ($number_ticket === null) {
            $number_ticket = 0;
        }
        $session_number_tickets = count($session->getTickets());

        if ($session_number_tickets >= $this->limit_ticket_by_day - $number_ticket ) {
            return false;
        }
        return true;
    }


    public function clearSession(String $name) {
        $this->session->remove($name);
    }
}
