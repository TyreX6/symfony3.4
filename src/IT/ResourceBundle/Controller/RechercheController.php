<?php

namespace IT\ResourceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RechercheController
 * @package IT\ResourceBundle\Controller
 */
class RechercheController extends Controller
{
    /**
     * @Route("/recherche/" , name="rechercher")
     * @Template()
     * @internal param Request $request
     * @return array
     */
    public function RechercheAction()
    {
        $keyword = $_GET["keyword"];
        $em = $this->getDoctrine()->getManager();
        $dispositifs = $em->getRepository("ITResourceBundle:Dispositif")->rechercheDispositif($keyword);
        $reservations = $em->getRepository("ITReservationBundle:Reservation")->rechercheDispositif($keyword);
        return array("keyword" => $keyword, "dispositifs" => $dispositifs, "reservations" => $reservations);
    }
}
