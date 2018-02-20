<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 08/02/2018
 * Time: 15:35
 */

namespace AppBundle\Controller;
use AppBundle\Entity\Admin;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller #Or extends FOSRestController
{
 /**
 * @Rest\View()
 * @Rest\Get("api/test")
 */
    public function getTestAction(Request $request)
    {
        $usersRepos = $this->getDoctrine()->getManager()->getRepository(Admin::class);
        $users = $usersRepos->findAll() ;
        return $users ;
    }

}