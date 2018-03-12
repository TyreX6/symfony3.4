<?php

namespace IT\DispositifBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RechercheController
 * @package IT\DispositifBundle\Controller
 */
class RechercheController extends Controller
{
    /**
     * @Route("/recherche/{keyword}" , name="rechercher")
     * @Template()
     * @internal param Request $request
     * @param $keyword
     * @return array
     */
    public function RechercheAction($keyword)
    {
        $em = $this->getDoctrine()->getManager();
        $dispositifs = $em->getRepository("ITDispositifBundle:Dispositif")->rechercheDispositif($keyword);
        $reservations = $em->getRepository("ITReservationBundle:Reservation")->rechercheDispositif($keyword);
        return array("keyword"=>$keyword,"dispositifs"=>$dispositifs,"reservations"=>$reservations) ;
    }
}
