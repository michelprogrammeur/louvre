<?php

namespace AppBundle\Services;

use AppBundle\Entity\Command;
use AppBundle\Entity\Ticket;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Templating\TemplateNameParserInterface;

class ManageSendingMailTest
{
    public function testMailIsSent()
    {
        $command = new Command();
        $command->setFirstname('Antoine');

        $session = new Session(new MockArraySessionStorage());
        $transport = new \Swift_Transport_SpoolTransport(
            new \Swift_Events_SimpleEventDispatcher()
        );
        $mailer = new \Swift_Mailer($transport);
       $loader = new \Twig_Loader_Filesystem('email');
        $twig = new TwigEngine(new \Twig_Environment($loader), TemplateNameParserInterface::class);
        $session->set('command', $command);

        $command_number = "H2US23ugyud767fdev790aéfy7H89";
        $ticket = new Ticket();
        $ticket->setFirstname('Mark');
        $command->addTicket($ticket);
        $session_command = $session->get('command');

        $manage_sending_mail = new ManageSendingMail($twig, $mailer, $session);

        $result = $manage_sending_mail->sendCommandMail($command_number, $session_command);
        $this->assertEquals(1, $result);
    }
}