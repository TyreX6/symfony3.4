<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Dispositif;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class DispositifController
 * @package AppBundle\Controller
 * @Route("/admin")
 */
class DispositifController extends Controller
{
    /**
     * @Route("/dispositif/ajouter" , name="ajouter_dispositif")
     * @Template()
     */
    public function ajouter_DispositifAction()
    {
        $message = null ;
        $dispositif = new Dispositif();
        $form=$this->createForm("AppBundle\Form\DispositifType",$dispositif);
        $request = $this->get('request_stack')->getCurrentRequest();
        $form->handleRequest($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            foreach ($dispositif->getImages() as $image) {
                $dispositif->addImage($image);
            }
            $em->persist($dispositif);
            $em->flush();
            $message = "Dispositif a été crée avec succée";
        }
        return array('dispositifForm' => $form->createView(), 'message' => $message);
    }

    /**
     * @Route("/dispositif/liste" , name="liste_dispositif")
     * @Template()
     */
    public function lister_dispositifAction(){
        $em = $this->getDoctrine()->getManager();
        $dispositifs = $em->getRepository("AppBundle:Dispositif")->findAll() ;
        return array('dispositifs'=> $dispositifs);
    }
}
