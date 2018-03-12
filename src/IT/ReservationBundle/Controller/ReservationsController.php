<?php

namespace IT\ReservationBundle\Controller;

use IT\ReservationBundle\Entity\Notification;
use IT\ReservationBundle\Entity\Reservation;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

//TODO verify reservations with administrator contraints
/**
 * Class ReservationsController
 * @package IT\ReservationBundle\Controller
 */
class ReservationsController extends Controller
{
    /**
     * @Route("/liste", name="liste_reservation")
     * @Template()
     * @return array
     */
    public function liste_ReservationsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $reservations = $em->getRepository("ITReservationBundle:Reservation")->getIncomingReservationsOrdererByDate();

        return array("reservations"=>$reservations) ;
    }


    /**
     * @Route("/ajouter", name="ajouter_reservation")
     * @param Request $request
     * @Template()
     * @return array
     */
    public function ajouter_ReservationsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dispos = $em->getRepository("ITDispositifBundle:Dispositif")->findAll();
        $utilisateurs = $em->getRepository("ITUserBundle:User")->getUsersByRole("ROLE_USER");

        return array('utilisateurs' => $utilisateurs, 'dispos' => $dispos);
    }


    /**
     * @Route("/ajouter/ajax", name="ajout_reservation_ajax")
     */
    public function ajouterReservationsAjaxAction()
    {

        try {
            $postData = $this->get('request_stack')->getCurrentRequest()->request->all();

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository("ITUserBundle:User")->findOneBy(['id' => $postData['user']]);
            $dispositif = $em->getRepository("ITDispositifBundle:Dispositif")->findOneBy(['id' => $postData['dispositif']]);

            $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $postData['dateDebut']);
            $dateFin = DateTime::createFromFormat('Y-m-d H:i:s', $postData['dateFin']);
            $dateNow = new \DateTime(null, new \DateTimeZone("Africa/Tunis"));


            //On ne peut pas ajouter un réservation avec un date de début déja passé
            if ($dateDebut->getTimestamp() < $dateNow->getTimestamp() + 3600) {
                return JsonResponse::create(array("success" => 0), 200)
                    ->setSharedMaxAge(900);
            }


            $reservation = new Reservation();
            $notification = new Notification();

            $reservation->setUser($user);
            $reservation->setDateDebut($dateDebut);
            $reservation->setDispositif($dispositif);
            $reservation->setDateFin($dateFin);
            $regles = $em->getRepository("ITReservationBundle:Regles")->findAll()[0];
            $result = $em->getRepository("ITReservationBundle:Reservation")->verifReservation($reservation,$regles);

            if ($result["success"] === 0) {
                return JsonResponse::create(array("success" => 0, "message"=>$result["message"]), 200)
                    ->setSharedMaxAge(900);
            }


            $notification->setReservation($reservation);

            $em->persist($reservation);
            $em->persist($notification);
            $em->flush();

            return JsonResponse::create(array("success" => 1, "id" => $reservation->getId()), 200)->setSharedMaxAge(900);
        }

        catch (Exception $e) {
            return JsonResponse::create(array("success" => 0, "message"=>"erreur"), 200)
                ->setSharedMaxAge(900);
        }

    }

    /**
     * @Route("/modifier/ajax", name="modifier_reservation_ajax")
     */
    public function modifierReservationsAjaxAction()
    {
        try {
            $data = $this->get('request_stack')->getCurrentRequest()->request->all();
            $id = $data['id'];

            $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $data['dateDebut']);
            $dateFin = DateTime::createFromFormat('Y-m-d H:i:s', $data['dateFin']);

            //Date actuelle pour le comparer avec la date fin ulturieurement
            $date = new \DateTime(null, new \DateTimeZone("Africa/Tunis"));
            $em = $this->getDoctrine()->getManager();
            $reser = $em->getRepository("ITReservationBundle:Reservation")->findOneBy(['id' => $id]);


            //Si la réservation est en cours on ne peut changer que par une prolongation convenable
            //Si date fin réservation est passé , alors on ne peut plus modifier une réservation terminée
            //On peut pas faire une modification en modifiant la date de début du réservation
            if (($dateFin->getTimestamp() < $date->getTimestamp() + 3600) || ($reser->getDateDebutTimeStamp() != $dateDebut->getTimestamp())) {
                return JsonResponse::create(array("success" => 0, "timestamp datfin" => $dateFin->getTimestamp(), "timestamp date now" => $date->getTimestamp()), 200)
                    ->setSharedMaxAge(900);
            }


            //Si contraintes validés , on passe à la modification
            $reser->setDateFin($dateFin);
            $em->persist($reser);
            $em->flush();


            return JsonResponse::create(array("success" => 1, "reservation" => $reser, "timestamp datfin" => $dateFin->getTimestamp(), "timestamp date now" => $date->getTimestamp()), 200)
                ->setSharedMaxAge(900);
        }

        catch (Exception $e) {
            return JsonResponse::create(array("success" => 0), 200)
                ->setSharedMaxAge(900);
        }
    }


    /**
     * @Route("/fetch/ajax", name="fetch_reservation")
     */
    public function fetch_ReservationsAction()
    {
        //Retourner les réservations d'un dispositif passé en paramétre
        $data = $this->get('request_stack')->getCurrentRequest()->request->all();
        $id = $data['id'];

        $em = $this->getDoctrine()->getManager();
        $reservations = $em->getRepository("ITReservationBundle:Reservation")->getReservationsByDispositive($id);

        return JsonResponse::create(array("reservations" => $reservations), 200)
            ->setSharedMaxAge(900);
    }


    /**
     * @Route("/reservations/delete/ajax", name="delete_reservations")
     */
    public function delete_ReservationsAction()
    {
        $data = $this->get('request_stack')->getCurrentRequest()->request->all();
        $id = $data['id'];

        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository("ITReservationBundle:Reservation")->findOneBy(['id' => $id]);

        $em->remove($reservation);
        $em->flush();

        return JsonResponse::create(array("success" => 1), 200)
            ->setSharedMaxAge(900);
    }
}
