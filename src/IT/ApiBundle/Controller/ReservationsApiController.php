<?php

namespace IT\ApiBundle\Controller;

use DateTime;
use IT\ReservationBundle\Entity\Notification;
use IT\ReservationBundle\Entity\Reservation;
use LdapTools\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use IT\UserBundle\Entity\Admin;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReservationsApiController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Post("api/reservations/add")
     * @param Request $request
     * @return array
     */
    public function addReservationAction(Request $request)
    {
        try {
            $data = $request->request->all();

            $user_id = $data["user_id"];
            $date_debut = $data["date_debut"];
            $date_fin = $data["date_fin"];
            $dispositif_id = $data["dispositif_id"];

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository("ITUserBundle:User")->findOneBy(['id' => $user_id]);
            $dispositif = $em->getRepository("ITDispositifBundle:Dispositif")->findOneBy(['id' => $dispositif_id]);

            $date_debut = DateTime::createFromFormat('Y-m-d H:i:s', $date_debut);
            $date_fin = DateTime::createFromFormat('Y-m-d H:i:s', $date_fin);

            $dateNow = new \DateTime(null, new \DateTimeZone("Africa/Tunis"));

            //On ne peut pas ajouter un réservation avec un date de début déja passé
            if ($date_debut->getTimestamp() < $dateNow->getTimestamp() + 3600) {
                return array("success"=>0,"message"=>"On ne peut pas réserver dans le passé Non !") ;
            }

            $notification = new Notification();
            $reservation = new Reservation();

            $reservation->setUser($user);
            $reservation->setDispositif($dispositif);
            $reservation->setDateFin($date_debut);
            $reservation->setDateFin($date_fin);

            $regles = $em->getRepository("ITReservationBundle:Regles")->findAll()[0];
            $result = $em->getRepository("ITReservationBundle:Reservation")->verifReservation($reservation,$regles);

            if ($result["success"] === 0) {
                return array("success" => 0, "message"=>$result["message"]);
            }

            $notification->setReservation($reservation);

            $em->persist($reservation);
            $em->persist($notification);
            $em->flush();

            return array("success" => 1, "message"=>"Réservation ajouté avec succé");

        } catch (Exception $e) {
            return array("success" => 0, "message"=>"Erreur lors du réservation") ;
        }
    }

    /**
     * @Rest\View()
     * @Rest\GET("api/reservations/liste")
     * @return array
     */
    public function listReservations(){
        $em = $this->getDoctrine()->getManager();
        $reservations = $em->getRepository("ITReservationBundle:Reservation")->findAll() ;
        return $reservations ;
    }






}
