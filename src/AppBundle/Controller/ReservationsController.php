<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Reservation;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;


class ReservationsController extends Controller
{
    /**
     * @Route("/admin/reservations/ajouter/", name="ajouter_reservation")
     * @Template()
     */
    public function ajouter_ReservationsAction()
    {
        $em = $this->getDoctrine()->getManager();
//        $reservations = $em->getRepository("AppBundle:Reservation")->getReservationsByDispositive($id);
//        $dispositif = $em->getRepository("AppBundle:Dispositif")->findOneBy(['id' => $id]);
        $dispos = $em->getRepository("AppBundle:Dispositif")->findAll();
        $utilisateurs = $em->getRepository("AppBundle:User")->getUsersByRole("ROLE_USER");
        return array('utilisateurs' => $utilisateurs, 'dispos' => $dispos);
    }


    /**
     * @Route("/admin/reservations/ajouter/ajax", name="ajout_reservation_ajax")
     */
    public function ajouterReservationsAjaxAction()
    {
        try {
            $postData = $this->get('request_stack')->getCurrentRequest()->request->all();
            $em=$this->getDoctrine()->getManager();
            $user = $em->getRepository("AppBundle:User")->findOneBy(['id'=>$postData['user']]);
            $dispositif = $em->getRepository("AppBundle:Dispositif")->findOneBy(['id'=>$postData['dispositif']]) ;
            $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $postData['dateDebut']);
            $dateFin = DateTime::createFromFormat('Y-m-d H:i:s', $postData['dateFin']);
            $date= new \DateTime(null, new \DateTimeZone("Africa/Tunis"));
            //On ne peut pas ajouter un réservation avec un date de début déja passé
            if ($dateDebut->getTimestamp() < $date->getTimestamp()+3600 )
            {
                return JsonResponse::create(array("success"=>0,"timestamp datedebut"=>$dateDebut->getTimestamp(),"timestamp date now"=>$date->getTimestamp()), 200)
                    ->setSharedMaxAge(900);
            }
            $reservation = new Reservation();
            $reservation->setUser($user);
            $reservation->setDateDebut($dateDebut);
            $reservation->setDispositif($dispositif);
            $reservation->setDateFin($dateFin);
            $reservation->setStatut("En attente") ;
            $em->persist($reservation);
            $em->flush();
            return JsonResponse::create(array("success"=>1 ,"id" => $reservation->getId(),"timestamp datedebut"=>$dateDebut->getTimestamp(),"timestamp date now"=>$date->getTimestamp()), 200)
                ->setSharedMaxAge(900);
        } catch (Exception $e) {
            return JsonResponse::create(array("success"=>0), 200)
                ->setSharedMaxAge(900);
        }

    }
    /**
     * @Route("/admin/reservations/modifier/ajax", name="modifier_reservation_ajax")
     */
    public function modifierReservationsAjaxAction()
    {
        try {
            $data = $this->get('request_stack')->getCurrentRequest()->request->all();
            $id = $data['id'];
            $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $data['dateDebut']);
            $dateFin = DateTime::createFromFormat('Y-m-d H:i:s', $data['dateFin']);
            //TODO configure the timeZone to match the comparison
            //Date actuelle pour le comparer avec la date fin ulturieurement
            $date= new \DateTime(null, new \DateTimeZone("Africa/Tunis"));
            $em = $this->getDoctrine()->getManager();
            $reser = $em->getRepository("AppBundle:Reservation")->findOneBy(['id'=>$id]) ;
            //Si la réservation est en cours on ne peut changer que par une prolongation convenable
            //Si date fin réservation est passé , alors on ne peut plus modifier une réservation terminée
            //On peut pas faire une modification en modifiant la date de début du réservation
            if (($dateFin->getTimestamp() < $date->getTimestamp()+3600) || ($reser->getDateDebutTimeStamp() != $dateDebut->getTimestamp()))
            {
                return JsonResponse::create(array("success"=>0,"timestamp datfin"=>$dateFin->getTimestamp(),"timestamp date now"=>$date->getTimestamp()), 200)
                    ->setSharedMaxAge(900);
            }
            //Si contraintes validés , on passe à la modification
            $reser->setDateFin($dateFin);
            $em->persist($reser);
            $em->flush();
            return JsonResponse::create(array("success"=>1,"reservation" => $reser,"timestamp datfin"=>$dateFin->getTimestamp(),"timestamp date now"=>$date->getTimestamp()), 200)
                ->setSharedMaxAge(900);
        } catch (Exception $e) {
            return JsonResponse::create(array("success"=>0), 200)
                ->setSharedMaxAge(900);
        }
    }



    /**
     * @Route("/admin/reservations/fetch/ajax", name="fetch_reservation")
     */
    public function fetch_ReservationsAction()
    {
        //Retourner les réservations d'un dispositif passé en paramétre
        $data = $this->get('request_stack')->getCurrentRequest()->request->all();
        $id = $data['id'];
        $em = $this->getDoctrine()->getManager();
        $reservations = $em->getRepository("AppBundle:Reservation")->getReservationsByDispositive($id);
        return JsonResponse::create(array("reservations" => $reservations), 200)
            ->setSharedMaxAge(900);
    }


    /**
     * @Route("/admin/reservations/delete/ajax", name="delete_reservations")
     */
    public function delete_ReservationsAction()
    {
        $data = $this->get('request_stack')->getCurrentRequest()->request->all();
        $id = $data['id'];
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository("AppBundle:Reservation")->findOneBy(['id'=>$id]);
        $em->remove($reservation);
        $em->flush();
        return JsonResponse::create(array("success" => 1), 200)
            ->setSharedMaxAge(900);
    }


}
