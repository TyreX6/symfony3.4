<?php

namespace IT\ReservationBundle\Controller;

use IT\ReservationBundle\Entity\Notification;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class NotificationController extends Controller
{
    /**
     * @return array
     * @Template()
     */
    public function notificationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $notifications = $em->getRepository('ITReservationBundle:Notification')->getLatestNotifications(5);
        return array('notifications' => $notifications);
    }

    /**
     * @param $notifications Notification[]
     * @return Response
     */
    public function unsetNotificationAction($notifications)
    {
        $em = $this->getDoctrine()->getManager();
        foreach ($notifications as $notif) {
            $notif->setVu(true);
            $em->persist($notif);
        }
        $em->flush();
        return new Response();
    }
}
