<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Command;
use AppBundle\Entity\Ticket;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FrontController extends Controller
{
    /**
     * @Route("/", name="visit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        $manage_session = $this->get("app.manage_session");
        $manage_session->clearSession('command');

        $command = new Command();
        $form = $this->createForm('AppBundle\Form\VisitType', $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('command', $command);

            return $this->redirectToRoute('command');
        }

        return $this->render('front/index.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/command", name="command")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function commandAction(Request $request)
    {
        $session = $request->getSession();
        $session_command = $session->get('command');
        $manage_session = $this->get('app.manage_session');

        if (empty($session_command) || $session_command->getVisitDate() === null) {

            return $this->redirectToRoute('visit');
        }else {
            $ticket = new Ticket();
            $form = $this->createForm('AppBundle\Form\TicketType', $ticket);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $block_billet = $this->blockCommandTicket($request, $session_command->getVisitDate()->date);
                if (!$block_billet) {
                    $session->getFlashBag()->add('warning', "Désolé, plus de billets disponibles");

                    return $this->redirectToRoute('command');
                }else {
                    $manage_session->addTicketInSession($ticket, $session_command);
                    $session->getFlashBag()->add('success', "Le billet a bien été ajouté.");

                    return $this->redirectToRoute('command');
                }
            }
            $tickets = $session_command->getTickets();

            return $this->render('front/command.html.twig', array(
                'form' => $form->createView(),
                'tickets' => $tickets,
                'command' => $session_command
            ));
        }
    }

    /**
     * @Route("/payment", name="payment")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function paymentAction(Request $request)
    {
        $session = $request->getSession();
        $session_command = $session->get('command');

        if (empty($session_command) || empty($session_command->getTickets()->elements)) {

            return $this->redirectToRoute('visit');
        }else {
            $assign_price = $this->get('app.assign_price');
            $manage_session = $this->get("app.manage_session");
            $payment_stripe = $this->get('app.payment_stripe');
            $em = $this->getDoctrine()->getManager();
            $assign_price->manageTicket($session_command);
            $form = $this->createForm('AppBundle\Form\PaymentType', $session_command);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid() && $payment_stripe->stripe()) {
                $manage_sending_mail = $this->get("app.manage_sending_mail");
                $manage_sending_mail->sendCommandMail($session_command->getCommandNumber(), $session_command);
                $em->persist($session_command);
                $em->flush();
                $manage_session->clearSession('command');
                $session->getFlashBag()->add('success', "Votre commande à bien été effectuée. Vous allez recevoir un mail de confirmation, contenants vos billets");

                return $this->redirectToRoute('visit');
            }

            return $this->render('front/payment.html.twig', array(
                'form' => $form->createView(),
                'command' => $session_command,
            ));
        }
    }

    /**
     * @param Request $request, $id
     * @Route("/{id}/ticket_remove", name="delete_ticket_session", requirements={"id" = "\d+"} )
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteTicketAction(Request $request, $id) {
        $session = $request->getSession();
        $session_command = $session->get('command');
        $manage_session = $this->get("app.manage_session");
        $m = $manage_session->removeTicketFromSession($id, $session_command);
        if (!$m) {
            $session->getFlashBag()->add('warning', "Le ticket à supprmer n'existe pas !.");

            return $this->redirectToRoute('command');
        }
        $session->getFlashBag()->add('success', "Le ticket sélectionné à bien été supprimé.");

        return $this->redirectToRoute('command');
    }

    /**
     * @param Request $request
     * @Route("/session-remove", name="remove_tickets_session" )
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteSessionAction(Request $request) {
        $session = $request->getSession();
        $manage_session = $this->get("app.manage_session");
        $manage_session->clearSession('command');
        $session->getFlashBag()->add('success', 'La liste de vos billets à été vidée.');

        return $this->redirectToRoute('command');
    }

    /**
     * @param String $date
     *
     * @Route("/api/ticket/count/{date}", name="count_tickets", requirements={"date" = "[0-9]{4}-[0-9]{2}-[0-9]{2}"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function countAllTicketsByDayAction(string $date) {

        $repository = $this->getDoctrine()->getRepository('AppBundle:Command');
        $req = $repository->countAllTicketsByDay($date);

        $number_ticket = array_sum(array_column($req, 'quantity'));

        $response = new Response(json_encode($number_ticket));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }



    private function blockCommandTicket(Request $request, string $date) {
        $session = $request->getSession();
        $session_command = $session->get('command');

        $repository = $this->getDoctrine()->getRepository('AppBundle:Command');
        $req = $repository->CountAllTicketsByDay($date);
        $number_ticket = array_sum(array_column($req, 'quantity'));
        $session_number_tickets = count($session_command->getTickets());

        if ($session_number_tickets >= 4 - $number_ticket ) {
            return false;
        }
        return true;
    }
}
