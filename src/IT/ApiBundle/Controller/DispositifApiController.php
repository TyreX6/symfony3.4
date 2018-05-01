<?php

namespace IT\ApiBundle\Controller;

use DateInterval;
use DateTime;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use IT\UserBundle\Entity\Admin;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use IT\ResourceBundle\Entity\Dispositif;

/**
 * Class DispositifApiController
 * @package IT\ApiBundle\Controller
 */
class DispositifApiController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Post("api/dispositifs/add")
     * @param Request $request
     * @Operation(
     *      @SWG\Schema(
     *         type="Object",
     *         @Model(type=Dispositif::class)
     *      ),
     *     tags={"Device"},
     *     summary="Permet d'ajouter un dispositif avec ses informations",
     *      @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/Dispositif"),
     *
     *   ),
     *     @SWG\Response(
     *         response="200",
     *         description="Dispositif ajouté avec succée"
     *     ),
     * )
     *
     * @return array
     */
    public function addDispositifsAction(Request $request)
    {
        //Receive all data
        $data = $request->request->all();

        $device_name = $data["device_name"];
        $device_model = $data["model"];
        $device_resolution = $data["resolution"];
        $os = $data["os"];
        $version_os = $data["_os_version"];
        $device_UUID = $data["device_u_u_i_d"];
        $device_memory = $data["disk_space"];
        $freediskspace = $data["free_disk_space"];
        $useddiskspace = $data["used_disk_space"];
        $ramsize = $data["ram"];
        $cpucore = $data["cpu_cores"];
        $cpuinfo = $data["cpu"];

        //Set new Device
        $em = $this->getDoctrine()->getManager();

        $dispositif = $em->getRepository("ITResourceBundle:Dispositif")->findOneBy(["deviceUUID" => $device_UUID]);

        if ($dispositif == null) {
            $dispositif = new Dispositif();
        }

        $dispositif->setStatus(1);
        $dispositif->setLastCheckDate(new \DateTime(null, new \DateTimeZone("Africa/Tunis")));
        if (strpos(strtolower($os), 'ios') !== false) {
            //TODO automate categ
            $categ = $em->getRepository("ITResourceBundle:Categorie")->findOneBy(["id" => 1]);
            $dispositif->setCategory($categ);
        } else {
            //TODO automate categ
            $categ = $em->getRepository("ITResourceBundle:Categorie")->findOneBy(["id" => 2]);
            $dispositif->setCategory($categ);
        }
        $dispositif->setDeviceName($device_name);
        $dispositif->setModel($device_model);
        $dispositif->setResolution($device_resolution);
        $dispositif->setOs($os);
        $dispositif->setOsVersion($version_os);
        $dispositif->setDeviceUUID($device_UUID);
        $dispositif->setDiskSpace($device_memory);
        $dispositif->setFreeDiskSpace($freediskspace);
        $dispositif->setUsedDiskSpace($useddiskspace);
        $dispositif->setRam($ramsize);
        $dispositif->setCpuCores($cpucore);
        $dispositif->setCpu($cpuinfo);

        $em->persist($dispositif);
        $em->flush();

        return array("success" => 1);
    }

    /**
     * @Rest\View()
     * @Rest\Put("api/dispositifs/modify/{id}")
     * @param Request $request
     * @Operation(
     *     tags={"Device"},
     *     summary="Edit the device with the ID passed into parameters",
     *      @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="id of the device",
     *     required=true,
     *     type="integer"
     *   ),
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Data to modify",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/Dispositif")
     *   ),
     *
     *   @SWG\Response(response="200",description="Device modified successfully"),
     *   @SWG\Response(response=400, description="Invalid Device supplied"),
     *   @SWG\Response(response=404, description="Device not found")
     * )
     *
     * @return array
     */
    public function modifierDispositifAction(Request $request)
    {
        return array();
    }


    /**
     * @Rest\View()
     * @Rest\Get("api/dispositifs/list")
     * @Operation(
     *  tags={"Device"},summary="Retreive all the devices",
     *
     *  @SWG\Response(response=200,description="Tableau de dispositifs",
     *  @SWG\Schema(type="array",@SWG\Items(ref="#/definitions/Dispositif")
     *  ),
     * ),
     * )
     */
    public function listDispositifsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $dispositifs = $em->getRepository("ITResourceBundle:Dispositif")->findBy(["status"=>1]);
        return $dispositifs;
    }

    /**
     * @Rest\View()
     * @Rest\Delete("api/dispositifs/delete/{id}")
     * @param Request $request
     * @Operation(
     *     tags={"Device"},
     *     summary="Delete the device with the ID passed into parameters",
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id of the device",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Response(response="200",description="Device deleted successfully"),
     *   @SWG\Response(response=400, description="Invalid Device supplied"),
     *   @SWG\Response(response=404, description="Device not found")
     * )
     *
     * @return array
     */
    public function deleteDispositifAction(Request $request)
    {
        return array();
    }


    /**
     * @Rest\View()
     * @Rest\Get("api/dispositif/geolocate/{id}")
     * @Operation(
     *     tags={"Device"},
     *     summary="Get the location of the device with the ID passed into parameters",
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id of the device",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Response(response="200",description="Device located successfully"),
     *   @SWG\Response(response=400, description="Invalid Device supplied"),
     *   @SWG\Response(response=404, description="Device not found"),
     * )
     * @return Dispositif|null|object
     */
    public function geolocateDispositif($id)
    {
        $em = $this->getDoctrine()->getManager();
        $dispositif = $em->getRepository("ITResourceBundle:Dispositif")->findOneBy(['id' => $id]);
        //TODO Get device location and send it
        return $dispositif;
    }

    /**
     * @Rest\View()
     * @Rest\Post("api/dispositif/lock/{id}")
     * @Operation(
     *     tags={"Device"},
     *     summary="Lock the device",
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id of the device",
     *     required=true,
     *     type="integer"
     *   ),
     *     @SWG\Parameter(
     *     name="device_id",
     *     in="formData",
     *     description="UUID of the device",
     *     required=true,
     *     type="integer"
     *   ),
     *     @SWG\Parameter(
     *     name="app_id",
     *     in="formData",
     *     description="application ID",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Response(response="200",description="Device locked successfully"),
     *   @SWG\Response(response=400, description="Invalid Device supplied"),
     *   @SWG\Response(response=404, description="Device not found"),
     * )
     * @return Dispositif|null|object
     */
    public function lockDispositif($id)
    {
        $em = $this->getDoctrine()->getManager();
        $dispositif = $em->getRepository("ITResourceBundle:Dispositif")->findOneBy(['id' => $id]);
        return $dispositif;
    }

}
