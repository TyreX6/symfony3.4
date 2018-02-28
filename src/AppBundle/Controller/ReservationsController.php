<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Reservation;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


class ReservationsController extends Controller
{
    /**
     * @Route("/admin/reservations/ajouter/ajax", name="ajout_reservation_ajax")
     */
    public function ajouterReservationsAjaxAction()
    {
        $postData = $this->get('request_stack')->getCurrentRequest()->request->all();
        $em=$this->getDoctrine()->getManager();

        $user = $em->getRepository("AppBundle:User")->findOneBy(['id'=>$postData['user']]);
        $dispositif = $em->getRepository("AppBundle:Dispositif")->findOneBy(['id'=>$postData['dispositif']]) ;
        $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $postData['dateDebut']);
        $dateFin = DateTime::createFromFormat('Y-m-d H:i:s', $postData['dateFin']);
        $reservation = new Reservation();
        $reservation->setUser($user);
        $reservation->setDateDebut($dateDebut);
        $reservation->setDispositif($dispositif);
        $reservation->setDateFin($dateFin);
        $reservation->setStatut("En attente") ;
        $em->persist($reservation);
        $em->flush();
        return JsonResponse::create(array("id" => $reservation->getId()), 200)
            ->setSharedMaxAge(900);
    }
    /**
     * @Route("/admin/reservations/modifier/ajax", name="modifier_reservation_ajax")
     */
    public function modifierReservationsAjaxAction()
    {
        $data = $this->get('request_stack')->getCurrentRequest()->request->all();
        $id = $data['id'];
        $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $data['dateDebut']);
        $dateFin = DateTime::createFromFormat('Y-m-d H:i:s', $data['dateFin']);

        $em = $this->getDoctrine()->getManager();
        $reser = $em->getRepository("AppBundle:Reservation")->findOneBy(['id'=>$id]) ;
        $reser->setDateDebut($dateDebut);
        $reser->setDateFin($dateFin);
        $em->persist($reser);
        $em->flush();
        return JsonResponse::create(array("success" => true), 200)
            ->setSharedMaxAge(900);

    }

    /**
     * @Route("/admin/reservations/ajouter/{id}", name="ajouter_reservation")
     * @Template()
     */
    public function ajouter_ReservationsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $reservations = $em->getRepository("AppBundle:Reservation")->getReservationByDispositive($id);
        $dispositif = $em->getRepository("AppBundle:Dispositif")->findOneBy(['id' => $id]);
        $dispos = $em->getRepository("AppBundle:Dispositif")->findAll();
        $utilisateurs = $em->getRepository("AppBundle:User")->getUsersByRole("ROLE_USER");
        return array('reservations' => $reservations, 'utilisateurs' => $utilisateurs, 'dispos' => $dispos, 'dispositif' => $dispositif);
    }


}
