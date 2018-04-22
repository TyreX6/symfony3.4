<?php

namespace IT\ApiBundle\Controller;

use DateTime;
use Exception;
use JMS\Serializer\SerializerBuilder;
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


class ReglesApiController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\GET("api/regles/")
     *
     */
    public function getReglesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rules = $em->getRepository("ITReservationBundle:Regles")->findAll();
        return $rules[0];
    }

}