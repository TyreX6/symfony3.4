<?php

namespace IT\DispositifBundle\Controller;

use IT\DispositifBundle\Entity\Dispositif;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DispositifController
 * @package IT\DispositifBundle\Controller
 */
class DispositifController extends Controller
{
    /**
     * @Route("/ajouter" , name="ajouter_dispositif")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function ajouter_DispositifAction(Request $request)
    {
        $message = null;
        $dispositif = new Dispositif();

        $form = $this->createForm("IT\DispositifBundle\Form\DispositifType", $dispositif);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            foreach ($dispositif->getImages() as $image) {
                $dispositif->addImage($image);
            }

            $dispositif->setEtat("Fonctionnel");
            $em->persist($dispositif);
            $em->flush();
            $message = "Dispositif a été crée avec succée";
        }

        return array('dispositifForm' => $form->createView(), 'message' => $message);
    }


    /**
     * @Route("/edit/{id}",name="modifier_dispositif")
     * @Template()
     */
    public function modifier_DispositifAction($id)
    {
        $message = null;
        $em = $this->getDoctrine()->getManager();
        $dispositif = $em->getRepository('ITDispositifBundle:Dispositif')->find($id);
        $form = $this->createForm('IT\DispositifBundle\Form\DispositifType', $dispositif);
        $request = $this->get('request_stack')->getCurrentRequest();
        $form->handleRequest($request);

        if ($form->isValid()) {

            foreach ($dispositif->getImages() as $image) {
                $dispositif->addImage($image);
            }

            $em->persist($dispositif);
            $em->flush();
            $message = "Le dispositif a étè bien modifié";

        }

        return array('dispositifForm' => $form->createView(), 'message' => $message);
    }



    /**
     * @Route("/liste" , name="liste_dispositif")
     * @Template()
     */
    public function lister_dispositifAction()
    {
        $em = $this->getDoctrine()->getManager();
        $dispositifs = $em->getRepository("ITDispositifBundle:Dispositif")->findAll();
        return array('dispositifs' => $dispositifs);
    }



    /**
     * @Route("/localiser/{id}" , name="localiser_dispositif")
     * @Template()
     */
    public function localiser_DispositifAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $dispositif = $em->getRepository('ITDispositifBundle:Dispositif')->findOneBy(['id' => $id]);
        return array('dispositif' => $dispositif);
    }


}
