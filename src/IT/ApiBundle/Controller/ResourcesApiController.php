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
use IT\ResourceBundle\Entity\Ressource;

/**
 * Class ResourcesApiController
 * @package IT\ApiBundle\Controller
 */
class ResourcesApiController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Post("api/ressource/add")
     * @param Request $request
     * @Operation(
     *      @SWG\Schema(
     *         type="Object",
     *         @Model(type=Ressource::class)
     *      ),
     *     tags={"Resource"},
     *     summary="",
     *      @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/Ressource"),
     *
     *   ),
     *     @SWG\Response(
     *         response="200",
     *         description="Ressource ajouté avec succée"
     *     ),
     * )
     *
     * @return array
     */
    public function addResourceAction(Request $request)
    {
        return array("success" => 1);
    }


    /**
     * @Rest\View(
     *     serializerGroups={"resources"}
     * )
     * @Rest\Get("api/resources/list")
     * @Operation(
     *  tags={"Resource"},summary="Retreive all the Ressource",
     *
     *  @SWG\Response(response=200,description="Array of resources",
     *  @SWG\Schema(type="array",@SWG\Items(ref="#/definitions/Ressource")
     *  ),
     * ),
     * )
     */
    public function listResourceAction()
    {
        $em = $this->getDoctrine()->getManager();
        $dispositifs = $em->getRepository("ITResourceBundle:Ressource")->findAll();
        return $dispositifs;
    }

}
