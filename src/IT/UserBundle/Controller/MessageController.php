<?php

namespace IT\UserBundle\Controller;

use IT\ReservationBundle\Entity\Notification;
use IT\UserBundle\Entity\Message;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{

    /**
     * @Route("/messages", name="listMessages")
     * @Template()
     */
    public function list_MessagesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository("ITUserBundle:Message")->findAll();
        return array("messages"=>$messages);
    }

    /**
     * @Route("/messages/detail/{id}", name="detailMessage")
     * @Template()
     */
    public function detail_MessageAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository("ITUserBundle:Message")->findOneBy(["id"=>$id]);
        return array("message"=>$message);
    }

    /**
     * @Route("/messages/send/{id}" , name="send_message")
     * @internal param Request $request
     */
    public function send_messageAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $messageEntity = $em->getRepository("ITUserBundle:Message")->findOneBy(["id"=>$id]);
        $adminMessage = $_POST["message"];

        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('ghandrisemh@gmail.com')
            ->setTo('EdwardBenton@protonmail.com')
            ->setBody(
                $adminMessage,
                'text/plain'
            );

        $this->get('mailer')->send($message);

        return $this->redirectToRoute("detailMessage",['id'=>$id]);

    }

    /**
     * @return array
     * @Template()
     */
    public function messageAction()
    {
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository('ITUserBundle:Message')->getLatestMessages(5);
        return array('messages' => $messages);
    }

    /**
     * @param $message Message
     * @return Response
     */
    public function unsetMessageAction($message)
    {
        $em = $this->getDoctrine()->getManager();
        $message->setVu(true);
        $em->flush();
        return new Response();
    }
}
