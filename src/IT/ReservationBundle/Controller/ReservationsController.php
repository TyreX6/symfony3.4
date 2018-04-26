<?php

namespace IT\ReservationBundle\Controller;

use IT\ReservationBundle\Entity\Reservation;
use IT\ReservationBundle\Entity\Notification;
use DateTime;
use JMS\Serializer\SerializerBuilder;
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
use Unirest;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request as GuzzleRequest;



/**
 * Class ReservationsController
 * @package IT\ReservationBundle\Controller
 */
class ReservationsController extends Controller
{
    /**
     * @Route("/list", name="reservations_list")
     * @Template()
     * @return array
     */
    public function reservations_listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $reservations = $em->getRepository("ITReservationBundle:Reservation")->getIncomingReservationsOrdererByDate();
        return array("reservations" => $reservations);
    }

    /**
     * @Route("/list/{id}", name="reservations_resource_list")
     * @Template()
     * @return array
     */
    public function reservations_list_By_DeviceAction()
    {
        $em = $this->getDoctrine()->getManager();
        $reservations = $em->getRepository("ITReservationBundle:Reservation")->getIncomingReservationsOrdererByDate();
        return array("reservations" => $reservations);
    }


    /**
     * @Route("/add/{id_categ}", name="add_reservation")
     * @Template()
     */
    public function add_ReservationsAction($id_categ)
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository("ITResourceBundle:Categorie")->findAll();
        $category = $em->getRepository("ITResourceBundle:Categorie")->findOneBy(["id" => $id_categ]);
        if ($category == null) {
            return $this->redirectToRoute("add_reservation", ["id_categ" => 1]);
        }
        $dispos = $category->getRessource();
        $utilisateurs = $em->getRepository("ITUserBundle:User")->getUsersByRole("ROLE_USER");

        return array('utilisateurs' => $utilisateurs, 'dispos' => $dispos, "categories" => $categories, "categ_id" => $id_categ);
    }


    /**
     * @Route("/add_reservation_ajax", name="add_reservation_ajax")
     */
    public function addReservationsAjaxAction()
    {

        try {
            $postData = $this->get('request_stack')->getCurrentRequest()->request->all();

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository("ITUserBundle:User")->findOneBy(['id' => $postData['user']]);
            $resource = $em->getRepository("ITResourceBundle:Ressource")->findOneBy(['id' => $postData['dispositif']]);

            $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $postData['dateDebut']);
            $dateFin = DateTime::createFromFormat('Y-m-d H:i:s', $postData['dateFin']);
            $dateNow = new \DateTime(null, new \DateTimeZone("Africa/Tunis"));


            //On ne peut pas ajouter un réservation avec un date de début déja passé
            if ($dateDebut->getTimestamp() < $dateNow->getTimestamp()) {
                return JsonResponse::create(array("success" => 0), 200)
                    ->setSharedMaxAge(900);
            }


            $reservation = new Reservation();
            $notification = new Notification();

            $reservation->setUser($user);
            $reservation->setDateDebut($dateDebut);
            $reservation->setRessource($resource);
            $reservation->setDateFin($dateFin);
            $regles = $em->getRepository("ITReservationBundle:Regles")->findAll()[0];
            $result = $em->getRepository("ITReservationBundle:Reservation")->verifReservation($reservation, $regles);

            if ($result["success"] === 0) {
                return JsonResponse::create(array("success" => 0, "message" => $result["message"]), 200)
                    ->setSharedMaxAge(900);
            }

            $notification->setReservation($reservation);

            $em->persist($reservation);
            $em->persist($notification);
            $em->flush();

            $message = $resource." réservé pour " . $user->getUsername();

            return JsonResponse::create(array("success" => 1, "message" => $message, "id" => $reservation->getId()), 200)->setSharedMaxAge(900);
        } catch (Exception $e) {
            return JsonResponse::create(array("success" => 0, "message" => "erreur"), 200)
                ->setSharedMaxAge(900);
        }

    }

    /**
     * @Route("/modify/ajax", name="modify_reservation_ajax")
     */
    public function modifyReservationsAjaxAction()
    {
        try {
            $data = $this->get('request_stack')->getCurrentRequest()->request->all();
            $id = $data['id'];

            $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $data['dateDebut']);
            $dateFin = DateTime::createFromFormat('Y-m-d H:i:s', $data['dateFin']);

            //Date actuelle pour le comparer avec la date fin ulturieurement
            $date = new \DateTime(null, new \DateTimeZone("Africa/Tunis"));
            $dateTimeStamp = $date->getTimestamp() + 3600;
            $em = $this->getDoctrine()->getManager();
            $reser = $em->getRepository("ITReservationBundle:Reservation")->findOneBy(['id' => $id]);
            $reser->setDateFin($dateFin);
            $reser->setDateDebut($dateDebut);

            //Si la réservation est en cours on ne peut changer que par une prolongation convenable
            //Si date fin réservation est passé , alors on ne peut plus modifier une réservation terminée
            //On peut pas faire une modification en modifiant la date de début du réservation
            if (($dateFin->getTimestamp() < $dateTimeStamp)) {
                return JsonResponse::create(array("success" => 0, "message" => "reservation passé"), 200)
                    ->setSharedMaxAge(900);
            }

            $regles = $em->getRepository("ITReservationBundle:Regles")->findAll()[0];
            $result = $em->getRepository("ITReservationBundle:Reservation")->verifReservation($reser, $regles);

            if ($result["success"] === 0) {
                return JsonResponse::create(array("success" => 0, "message" => $result["message"]), 200)
                    ->setSharedMaxAge(900);
            }

            // if valid, pass to modification
            $em->persist($reser);
            $em->flush();

            return JsonResponse::create(array("success" => 1, "reservation" => $reser), 200)
                ->setSharedMaxAge(900);
        } catch (Exception $e) {
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
        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize(array("reservations" => $reservations), 'json');
        return new Response($jsonContent);

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

    /**
     * @Route("/reservations/delete/{id}", name="delete_reservation")
     */
    public function delete_ReservationAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository("ITReservationBundle:Reservation")->findOneBy(['id' => $id]);

        $em->remove($reservation);
        $em->flush();

        return $this->redirectToRoute("reservations_list");
    }

    function CallAPI($method, $url, $data = false)
    {
        $curl = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }


}
