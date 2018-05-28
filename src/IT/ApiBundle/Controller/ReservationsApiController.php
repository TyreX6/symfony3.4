<?php

namespace IT\ApiBundle\Controller;

use DateTime;
use Exception;
use JMS\JobQueueBundle\Entity\Job;
use JMS\Serializer\SerializerBuilder;
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
use Symfony\Component\Serializer\Serializer;


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

            $user_id = (int)$data["user"];
            $date_debut = DateTime::createFromFormat('Y-m-d H:i:s', $data["start"]);
            $date_fin = DateTime::createFromFormat('Y-m-d H:i:s', $data["end"]);
            $dispositif_id = (int)$data["resource"];

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository("ITUserBundle:User")->findOneBy(['id' => $user_id]);
            $ressource = $em->getRepository("ITResourceBundle:Ressource")->findOneBy(['id' => $dispositif_id]);



            $dateNow = new \DateTime(null, new \DateTimeZone("Africa/Tunis"));

            //On ne peut pas ajouter un réservation avec un date de début déja passé
            if ($date_debut->getTimestamp() < $dateNow->getTimestamp()) {
                return array("success" => 0, "message" => "On ne peut pas réserver dans le passé");
            }

            $notification = new Notification();
            $reservation = new Reservation();


            $reservation->setUser($user);
            $reservation->setRessource($ressource);
            $reservation->setDateDebut($date_debut);
            $reservation->setDateFin($date_fin);

//            return array("resource"=>$ressource,"reservation"=>$reservation);

            $regles = $em->getRepository("ITReservationBundle:Regles")->findAll()[0];
            $result = $em->getRepository("ITReservationBundle:Reservation")->verifReservation($reservation, $regles);


            if ($result["success"] === 0) {
                return array("success" => 0, "message" => $result["message"]);
            }

            $notification->setReservation($reservation);

            $em->persist($reservation);
            $em->persist($notification);
            $em->flush();

//            $job = new Job('reservation:update', array($reservation->getId()));
//            $job->setExecuteAfter($reservation->getDateFin());
//            $job->addRelatedEntity($reservation);
//            $em->persist($job);
//            $em->flush();

            return array("success" => 1, "reservation_id" => $reservation->getId(), "message" => "Réservation ajouté avec succé");

        } catch (Exception $e) {
            return array("success" => 0, "exception" => $e, "message" => "Erreur lors du réservation");
        }
    }

    /**
     * @Rest\View(
     *     serializerGroups={"resources","user","Default"}
     * )
     * @Rest\Get("api/reservations/liste")
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
    public function listReservationsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $reservations = $em->getRepository("ITReservationBundle:Reservation")->findAll();
        return $reservations;
    }

    /**
     * @Rest\View(
     *     serializerGroups={"resources","Default"}
     * )
     * @Rest\Get("api/reservations/actif/device/{id}")
     * @param $id
     * @return array|\IT\ReservationBundle\Entity\Regles[]|Reservation[]|\IT\ResourceBundle\Entity\Dispositif[]
     */
    public function getActifReservationByDeviceAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $reservations = $em->getRepository("ITReservationBundle:Reservation")->getActifReservationsByDevice($id);
        return $reservations;
    }

    /**
     * @param $id
     * @Rest\View(
     *     serializerGroups={"resources","user","Default"}
     * )
     * @Rest\Get("api/reservations/liste/{id}")
     * @Operation(
     *  tags={"Reservation"},
     *     summary="Retreive all reservations for specific dispositif",
     *     @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="id of the dispositif",
     *     required=true,
     *     type="integer"
     *   ),
     *
     *  @SWG\Response(response=200,description="Array of reservations",
     *  @SWG\Schema(type="array",@SWG\Items(ref="#/definitions/Reservation")
     *  ),
     * ),
     * )
     * @return array
     */
    public function listReservationsByDispositifAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $reservations = $em->getRepository("ITReservationBundle:Reservation")->getReservationsByDispositive($id);
        return $reservations;
    }

    /**
     * @param $id
     * @Rest\View(
     *     serializerGroups={"resources","user","Default"}
     * )
     * @Rest\Get("api/reservations/liste/user/{id}")
     * @Operation(
     *  tags={"Reservation"},
     *     summary="Retreive all reservations for specific user",
     *     @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="id of the user",
     *     required=true,
     *     type="integer"
     *   ),
     *
     *  @SWG\Response(response=200,description="Array of reservations",
     *  @SWG\Schema(type="array",@SWG\Items(ref="#/definitions/Reservation")
     *  ),
     * ),
     * )
     * @return array
     */
    public function listReservationsByUser($id)
    {
        $em = $this->getDoctrine()->getManager();
        $reservations = $em->getRepository("ITReservationBundle:Reservation")->getReservationsByUser($id);
        return $reservations;
    }

    /**
     * @param Request $request
     * @return array
     * @Rest\View(
     *     serializerGroups={"resources","user","Default"}
     * )
     * @Rest\Post("api/reservations/listeByUuid")
     * @Operation(
     *  tags={"Reservation"},
     *     summary="Retreive all reservations for specific device By its UUID",
     *     @SWG\Parameter(
     *     in="query",
     *     name="deviceUUID",
     *     type="string",
     *     description="Uuid of the device"
     *   ),
     *
     *  @SWG\Response(response=200,description="Array of reservations",
     *  @SWG\Schema(type="array",@SWG\Items(ref="#/definitions/Reservation")
     *  ),
     * ),
     * )
     */
    public function listReservationsByDispositifUuidAction(Request $request)
    {
        $data = $request->request->all();
        $device_UUID = $data["deviceUUID"];

        $em = $this->getDoctrine()->getManager();
        $resource = $em->getRepository("ITResourceBundle:Dispositif")->findOneBy(["deviceUUID" => $device_UUID]);

        $reservations = $em->getRepository("ITReservationBundle:Reservation")->getReservationsByDispositive($resource->getId());

        return $reservations;
    }

    /**
     * @param $id
     * @param $request Request
     * @Rest\View()
     * @Rest\Put("api/reservations/modify/{id}")
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
     * @return array
     */
    public function modifyReservation(Request $request, $id)
    {
        try {
            $data = $request->request->all();
            $id_res = (int)$data["id_res"];
            $date_debut = DateTime::createFromFormat('Y-m-d H:i:s', $data["start"]);
            $date_fin = DateTime::createFromFormat('Y-m-d H:i:s', $data["end"]);
            $em = $this->getDoctrine()->getManager();
            $reservation = $em->getRepository("ITReservationBundle:Reservation")->findOneBy(["id" => $id]);



            $dateNow = new \DateTime(null, new \DateTimeZone("Africa/Tunis"));

            //On ne peut pas ajouter un réservation avec un date de début déja passé
//            if ($date_fin->getTimestamp() < $dateNow->getTimestamp()) {
////                return array("success" => 0, "message" => "Vous ne pouvez pas faire ça !");
////            }


            $reservation->setDateDebut($date_debut);
            $reservation->setDateFin($date_fin);
            if (isset($data["status"])) {
                $reservation->setStatut((int)$data["status"]);
            }

            $regles = $em->getRepository("ITReservationBundle:Regles")->findAll()[0];
            $result = $em->getRepository("ITReservationBundle:Reservation")->verifReservation($reservation, $regles);


            if ($result["success"] === 0) {
                return array("success" => 0, "message" => $result["message"]);
            }

            $em->persist($reservation);
            $em->flush();

            return array("success" => 1, "reservation_id" => $reservation->getId(), "message" => "Réservation modifié avec succé");
        } catch (Exception $e) {
            return array("success" => 0, "exception" => $e, "message" => "Erreur lors du réservation");
        }

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
    public function deleteReservationAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository("ITReservationBundle:Reservation")->findOneBy(["id" => $id]);
        if ($reservation) {
            $dateNow = new \DateTime(null, new \DateTimeZone("Africa/Tunis"));
            if ($dateNow > $reservation->getDateDebut()) {
                $reservation->setDateFin($dateNow);
                $reservation->setStatut(2);
                $em->persist($reservation);
                $em->flush();
            }
            else {
                $em->remove($reservation);
                $em->flush();
            }

            return array("success" => 1);
        } else return array("success" => 0);
    }


}
