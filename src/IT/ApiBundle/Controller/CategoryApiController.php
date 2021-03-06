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
use IT\ResourceBundle\Entity\Categorie;

/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 06/04/2018
 * Time: 23:20
 */

/**
 * Class DispositifApiController
 * @package IT\ApiBundle\Controller
 */
class CategoryApiController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("api/categories/names")
     * @Operation(
     *     tags={"Reservation"},
     *     summary="Login for reservation",
     *     @SWG\Response(
     *         response="200",
     *         description="Reservation launched"
     *     ),
     * )
     * @return array|Response
     */
    public function listCategoriesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository("ITResourceBundle:Categorie")->findAll();
        $categoriesNames = [];

        foreach ($categories as $categ){
            $categoriesNames[]=array("id"=>$categ->getId(),"name"=>$categ->getName());
        }
        return $categoriesNames;
    }


    /**
     * @Rest\View(
     *     serializerGroups={"categories"}
     * )
     * @Rest\Get("api/categories/list/resources")
     * @Operation(
     *  tags={"Category"},summary="Retreive all resources grouped by Category",
     *@SWG\Schema(
     *         type="Object",
     *         @Model(type=Categorie::class)
     *      ),
     *  @SWG\Response(response=200,description="Tableau de categories",
     *  @SWG\Schema(type="array",@SWG\Items(ref="#/definitions/Categorie")
     *  ),
     * ),
     * )
     */
    public function listCategoriesWithResourcesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository("ITResourceBundle:Categorie")->findAll();
        return $categories;
    }


    /**
     * @param $id
     * @Rest\View(
     *     serializerGroups={"categories"}
     * )
     * @Rest\GET("api/categories/list/resources/{id}")
     * @Operation(
     *  tags={"Category"},
     *     summary="Retreive all resources for specific category",
     *     @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="id of the category",
     *     required=true,
     *     type="integer"
     *   ),
     *
     *  @SWG\Response(response=200,description="Array of resources",
     *  @SWG\Schema(type="array",@SWG\Items(ref="#/definitions/Categorie")
     *  ),
     * ),
     * )
     * @return Categorie|null|object
     */
    public function listResourcesByCategoryAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository("ITResourceBundle:Categorie")->findOneBy(["id"=>$id]);
        return $category;
    }
}