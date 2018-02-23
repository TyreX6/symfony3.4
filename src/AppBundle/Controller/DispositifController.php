<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Dispositif;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DispositifController extends Controller
{
    /**
     * @Route("/admin/dispositif/ajouter" , name="ajouter_dispositif")
     * @Template()
     */
    public function ajouterDispositifAction($name)
    {
        $message = null ;
        $dispositif = new Dispositif();
        $form=$this->createForm("AppBundle\Form\ImageType",$dispositif);
        $request = $this->get('request_stack')->getCurrentRequest();
        $form->handleRequest($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            foreach ($dispositif->getImages() as $image) {
                $dispositif->addImage($image);
            }
            $em->persist($dispositif);
            $em->flush();
            $message = "Disposiif a été crée avec succée";
        }
        return array('dispositifForm' => $form->createView(), 'message' => $message);
    }
}
