<?php

namespace IT\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use IT\UserBundle\Entity\Admin;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DispositifApiController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Post("api/dispositifs/add")
     * @param Request $request
     */
    public function addDispositifsAction(Request $request){

    }


    /**
     * @Rest\View()
     * @Rest\Get("api/dispositifs/list")
     */
    public function listDispositifsAction(){
        $em = $this->getDoctrine()->getManager();
        $dispositifs = $em->getRepository("ITDispositifBundle:Dispositif")->findAll() ;
        return $dispositifs ;
    }


    /**
     * @Rest\View()
     * @Rest\GET("api/dispositif/geolocate/{id}")
     * @param Request $request
     */
    public function localiserDispositif($id){
        $em = $this->getDoctrine()->getManager();
        $dispositif = $em->getRepository("ITDispositifBundle:Dispositif")->findOneBy(['id'=>$id]);
        //TODO Get device location and send it
        return $dispositif ;
    }

}
