<?php

namespace IT\ReservationBundle\Controller;

use IT\ReservationBundle\Entity\LimiteDuree;
use IT\ReservationBundle\Entity\LimiteParJour;
use IT\ReservationBundle\Entity\LimiteParSemaine;
use IT\ReservationBundle\Entity\Regles;
use IT\ReservationBundle\Entity\ReservationTimeout;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class ReglesController extends Controller
{
    /**
     * @Route("/regles/" , name="afficher_regles")
     * @Template()
     */
    public function reglesAction() {
        $message = null;
        $em = $this->getDoctrine()->getManager();
        $regles = $em->getRepository('ITReservationBundle:Regles')->findAll()[0];
        $form = $this->createForm('IT\ReservationBundle\Form\ReglesType', $regles);
        $request = $this->get('request_stack')->getCurrentRequest();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($regles);
            $em->flush();
            $message = "Le dispositif a étè bien modifié";
        }

        return array('reglesForm' => $form->createView(), 'message' => $message);
    }


    /**
     * @Route("/regles/fixer" , name="fixer_regles")
     */
    public function reglesFixedAction()
    {
        $postData = $this->get('request_stack')->getCurrentRequest()->request->all();

        $duree = $postData["limiteDuree"] ;
        $limite_par_jour = $postData["limiteParJour"];
        $limite_par_semaine = $postData["limiteParSemaine"];
        $reservation_timeout = $postData["reservationTimeout"];

        $message = null;
        $em = $this->getDoctrine()->getManager();

        $regles = $em->getRepository("ITReservationBundle:Regles")->findAll()[0];
        $regles->setLimDureeReservation($duree);
        $regles->setNbrLimiteParJour($limite_par_jour);
        $regles->setNbrLimiteParSemaine($limite_par_semaine);
        $regles->setDureeTimeout($reservation_timeout);
        $regles->setDateModif(new \DateTime(null, new \DateTimeZone("Africa/Tunis")));
        $em->persist($regles);
        $em->flush();

        return JsonResponse::create(array("success" => 1), 200)->setSharedMaxAge(900);
    }


    /**
     * @Route("/regles/setDefault" , name="set_regles")
     */
    public function setReglesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $regles = new Regles();


        $regles->setLimDureeReservation(2);
        $regles->setNbrLimiteParJour(2);
        $regles->setNbrLimiteParSemaine(4);
        $regles->setDureeTimeout(15);

        $em->persist($regles);
        $em->flush();

        return new Response("good");
    }

}
