<?php
namespace AppBundle\Services;

use Stripe\Charge;
use Stripe\Stripe;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

class PaymentStripe
{
    protected $session;
    protected $container;
    protected $request;

    public function __construct(Session $session, ContainerInterface $container, RequestStack $request)
    {
        $this->session = $session;
        $this->container = $container;
        $this->request = $request;
    }

    public function Stripe() {
        $request = $this->request->getCurrentRequest();
        $session_command = $this->session->get('command');
        $secret_key = $this->container->getParameter('stripe_secret');

        Stripe::setApiKey($secret_key);

        $total = $session_command->getTotal();
        $token = $request->get('stripeToken');

        try {
            Charge::create(array(
                "amount" =>  $total . "00",
                "currency" => "eur",
                "description" => "Example charge",
                "source" => $token,
            ));
            $session_command->setCommandedAt(new \DateTime());
            $session_command->setCommandNumber();
        }catch (Exception $e) {
            return false;
        }
        return true;
    }
}