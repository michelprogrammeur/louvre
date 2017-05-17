<?php

namespace AppBundle\Services;

use AppBundle\Entity\Command;
use AppBundle\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class ManageSessionTest extends TestCase
{
    public function testAddTicketInSession() {;
        $command = new Command();
        $command->setFirstname('Antoine');

        $session = new Session(new MockArraySessionStorage());
        $session->set('command', $command);
        $session_command = $session->get('command');

        $ticket = new Ticket();
        $ticket->setFirstname('Paul');

        $manage_session = new ManageSession($session);
        $manage_session->addTicketInSession($ticket, $session_command);
        $this->assertEquals(1, count($command->getTickets()));
    }


    public function testRemoveTicketFromSession() {
        $command = new Command();
        $command->setFirstname('Antoine');

        $session = new Session(new MockArraySessionStorage());
        $session->set('command', $command);

        $ticket = new Ticket();
        $ticket->setFirstname('Mark');
        $command->addTicket($ticket);
        $session_command = $session->get('command');

        $manage_session = new ManageSession($session);
        $manage_session->removeTicketFromSession(0, $session_command);

        $this->assertEquals(0, count($session_command->getTickets()));
    }


    public function testClearSession() {
        $command = new Command();
        $command->setFirstname('Antoine');

        $session = new Session(new MockArraySessionStorage());
        $session->set('command', $command);

        $manage_session = new ManageSession($session);
        $manage_session->clearSession('command');

        $this->assertEquals(null, $session->get('command'));
    }
}