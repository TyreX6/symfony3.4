<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Admin;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReservationsApiController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Post("api/reservations/add")
     */
    public function addReservationAction(){
        $em = $this->getDoctrine()->getManager();
        $dispositifs = $em->getRepository("AppBundle:Dispositif")->findAll() ;
        return $dispositifs ;
    }

}
