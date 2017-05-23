<?php

namespace AppBundle\Services;

use Swift_Message;
use Symfony\Bundle\TwigBundle\TwigEngine;


class ManageSendingMail
{
    protected $templating;
    protected $mailer;

    public function __construct(TwigEngine $templating, \Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
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
