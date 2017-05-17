<?php

namespace AppBundle\Services;


use AppBundle\Entity\Command;
use AppBundle\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use \DateTime;

class AssignPriceTest extends TestCase
{
    public function dataIsReduce() {
        return [
            ['enfant', true, 'enfant'],
            ['normal', true, 'reduce']
        ];
    }

    /**
     * @dataProvider dataIsReduce
     */
    public function testIsReduce($type, $reduce, $result)
    {
        $session = new Session(new MockArraySessionStorage());

        $price_type = $type;
        $ticket = new Ticket();
        $ticket->setReduce($reduce);

        $assign_price = new AssignPrice($session);
        $r = $assign_price->isReduce($ticket, $price_type);

        $this->assertEquals($result, $r);
    }


    public function testChoosePriceType()
    {
        $session = new Session(new MockArraySessionStorage());

        $ticket = new Ticket();
        $datetime = DateTime::createFromFormat('d/m/Y', '1/10/2012');
        $birthdate = $datetime->setTime(0, 0, 0);
        $ticket->setBirthdate($birthdate);

        $assign_price = new AssignPrice($session);
        $price_type = $assign_price->choosePriceType($ticket);

        $this->assertEquals('enfant', $price_type);
    }


    public function testGetPrice()
    {
        $session = new Session(new MockArraySessionStorage());
        $price_type = "enfant";

        $assign_price = new AssignPrice($session);
        $result = $assign_price->getPrice($price_type);

        $this->assertEquals(8, $result);
    }


    public function testCalculTotal()
    {
        $command = new Command();
        $command->setFirstname('Antoine');

        $session = new Session(new MockArraySessionStorage());
        $session->set('command', $command);

        $ticket1 = new Ticket();
        $ticket1->price = 8;
        $ticket2 = new Ticket();
        $ticket2->price = 16;
        $command->addTicket($ticket1);
        $command->addTicket($ticket2);
        $session_command = $session->get('command');

        $assign_price = new AssignPrice($session);
        $result = $assign_price->calculTotal($session_command);

        $this->assertEquals(24, $result);
    }


    public function testCalculQuantity()
    {
        $command = new Command();
        $command->setFirstname('Antoine');

        $session = new Session(new MockArraySessionStorage());
        $session->set('command', $command);

        $ticket1 = new Ticket();
        $ticket1->setFirstname('Antoine');
        $ticket2 = new Ticket();
        $ticket2->setFirstname('Thomas');
        $command->addTicket($ticket1);
        $command->addTicket($ticket2);
        $session_command = $session->get('command');

        $assign_price = new AssignPrice($session);
        $result = $assign_price->calculQuantity($session_command);

        $this->assertEquals(2, $result);
    }
}
