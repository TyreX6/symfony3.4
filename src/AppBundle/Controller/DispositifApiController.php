<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Admin;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DispositifApiController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Post("api/dispositifs/add")
     */
    public function addDispositifsAction(){
        $em = $this->getDoctrine()->getManager();
        $dispositifs = $em->getRepository("AppBundle:Dispositif")->findAll() ;
        return $dispositifs ;
    }
    /**
     * @Rest\View()
     * @Rest\Get("api/dispositifs/list")
     */
    public function listDispositifsAction(){
        $em = $this->getDoctrine()->getManager();
        $dispositifs = $em->getRepository("AppBundle:Dispositif")->findAll() ;
        return $dispositifs ;
    }

}
