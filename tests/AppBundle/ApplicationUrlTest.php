<?php

namespace AppBundle;

use AppBundle\Entity\Command;
use AppBundle\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class ApplicationUrlTest extends WebTestCase
{
    public function testPageIsSuccessful()
    {
        $client = self::createClient();
        $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function dataUrlPageIsRedirect()
    {
        return array(
            array('/command'),
            array('/payment')
        );
    }

    /**
     * @dataProvider dataUrlPageIsRedirect
     */
    public function testPageIsRedirect($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isRedirect());
    }



    public function testAjaxUrl()
    {
        $client = self::createClient();
        $client->request('GET', '/api/ticket/count/coucou');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());

        $client->request('GET', '/api/ticket/count/2017-05-30');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
}