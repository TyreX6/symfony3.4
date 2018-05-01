<?php

namespace IT\ResourceBundle\Controller;

use IT\ResourceBundle\Entity\Dispositif;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DispositifController
 * @package IT\ResourceBundle\Controller
 */
class DispositifController extends Controller
{
    /**
     * @Route("/add" , name="add_device")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function add_deviceAction(Request $request)
    {
        $message = null;
        $dispositif = new Dispositif();

        $form = $this->createForm("IT\ResourceBundle\Form\DispositifType", $dispositif);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            foreach ($dispositif->getImages() as $image) {
                $dispositif->addImage($image);
            }
            foreach ($dispositif->getApps() as $app){
                $app->setDispositif($dispositif);
            }

            $em->persist($dispositif);
            $em->flush();
            $message = "Dispositif a été crée avec succée";
        }

        return array('dispositifForm' => $form->createView(), 'message' => $message);
    }


    /**
     * @Route("/edit/{id}",name="edit_device")
     * @Template()
     * @param $id
     * @return array
     */
    public function edit_deviceAction($id)
    {
        $message = null;
        $em = $this->getDoctrine()->getManager();
        $dispositif = $em->getRepository('ITResourceBundle:Dispositif')->find($id);
        $form = $this->createForm('IT\ResourceBundle\Form\DispositifType', $dispositif);
        $request = $this->get('request_stack')->getCurrentRequest();
        $form->handleRequest($request);

        if ($form->isValid()) {
            foreach ($dispositif->getApps() as $app){
                $app->setDispositif($dispositif);
            }

//            foreach ($dispositif->getImages() as $image) {
//                $dispositif->addImage($image);
//            }

            $em->persist($dispositif);
            $em->flush();
            $message = "Le dispositif a étè bien modifié";

        }

        return array('dispositifForm' => $form->createView(), 'message' => $message);
    }


    /**
     * @Route("/list" , name="list_devices")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function list_devicesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dispositifs = $em->getRepository("ITResourceBundle:Dispositif")->findAll();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $dispositifs,
            $request->query->get('page', 1)/*page number*/,
            5/*limit per page*/
        );
        return array('dispositifs' => $pagination);
    }



    /**
     * @Route("/geolocate/{id}" , name="geolocate_device")
     * @Template()
     */
    public function geolocate_deviceAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $dispositif = $em->getRepository('ITResourceBundle:Dispositif')->findOneBy(['id' => $id]);
        return array('dispositif' => $dispositif);
    }




}
