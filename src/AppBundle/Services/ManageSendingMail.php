<?php

namespace AppBundle\Services;

use Swift_Message;
use Swift_Image;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\HttpFoundation\Session\Session;


class ManageSendingMail
{
    protected $templating;
    protected $mailer;
    protected $session;

    public function __construct(TwigEngine $templating, \Swift_Mailer $mailer, Session $session)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->session = $session;
    }


    public function sendCommandMail($command_number, $session)
    {
        $message = Swift_Message::newInstance();
        $message->setSubject('Confirmation de votre commande sur le site du Louvre')
            ->setFrom('louvre@mail.com')
            ->setTo('root@localhost')
            ->setBody(
                $this->templating->render('emails/confirmation.html.twig', array(
                    'command' => $session,
                    'command_number' => $command_number,
                )
            )
        );

        return $this->mailer->send($message);
    }
}
