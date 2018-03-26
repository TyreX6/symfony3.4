<?php

namespace IT\ApiBundle\Controller;

use DateTime;
use Exception;
use IT\ReservationBundle\Entity\Notification;
use IT\ReservationBundle\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use IT\UserBundle\Entity\Admin;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;



class ReservationsApiController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Post("api/reservations/add")
     * @param Request $request
     * @Operation(
     *      @SWG\Schema(
     *         type="Object",
     *         @Model(type=Reservation::class)
     *      ),
     *     tags={"Reservation"},
     *     summary="Adding a reservation",
     *      @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Reservation object",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/Reservation"),
     *
     *
     *   ),
     *     @SWG\Response(
     *         response="200",
     *         description="Reservation added sccessfully"
     *     ),
     * )
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
     * @Operation(
     *  tags={"Reservation"},summary="Retreive all reservations",
     *
     *  @SWG\Response(response=200,description="Array of reservations",
     *  @SWG\Schema(type="array",@SWG\Items(ref="#/definitions/Reservation")
     *  ),
     * ),
     * )
     * @return array
     */
    public function listReservations(){
        $em = $this->getDoctrine()->getManager();
        $reservations = $em->getRepository("ITReservationBundle:Reservation")->findAll() ;
        return $reservations ;
    }

    /**
     * @param $id
     * @Rest\View()
     * @Rest\PUT("api/reservations/modify/{id}")
     * @Operation(
     *     tags={"Reservation"},
     *     summary="Edit the reservation with the ID passed into parameters",
     *      @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="id of the reservation",
     *     required=true,
     *     type="integer"
     *   ),
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Data to modify",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/Reservation")
     *   ),
     *
     *   @SWG\Response(response="200",description="Reservation modified successfully"),
     *   @SWG\Response(response=400, description="Invalid Reservation supplied"),
     *   @SWG\Response(response=404, description="Reservation not found")
     * )
     */
    public function modifyReservation($id){
    }

    /**
     * @Rest\View()
     * @Rest\Delete("api/reservations/delete/{id}")
     * @param Request $request
     * @param $id
     * @return array
     * @Operation(
     *     tags={"Reservation"},
     *     summary="Edit the Reservation with the ID passed into parameters",
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id of the Reservation",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Response(response="200",description="Reservation deleted successfully"),
     *   @SWG\Response(response=400, description="Invalid Reservation supplied"),
     *   @SWG\Response(response=404, description="Reservation not found")
     * )
     *
     */
    public function deleteReservationAction(Request $request,$id)
    {
        return array();
    }






}
