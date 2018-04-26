<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 31/03/2018
 * Time: 22:59
 */

namespace IT\ResourceBundle\Controller;

use GuzzleHttp\Psr7\Response;
use IT\ResourceBundle\Entity\Dispositif;
use FOS\RestBundle\Controller\Annotations as Rest;
use IT\ResourceBundle\Entity\Projecteur;
use IT\ResourceBundle\Entity\Ressource;
use IT\ResourceBundle\Entity\Salle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RessourceController extends Controller
{
    /**
     * @Route("/resource/add/{id}" , name="ajouter_ressource")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function ajouter_RessourceAction(Request $request, $id)
    {
        $message = '';
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository("ITResourceBundle:Categorie")->findAll();
        $message = '';
        $postData = $this->get('request_stack')->getCurrentRequest()->request->all();
//        $type = $postData["type"];
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository("ITResourceBundle:Categorie")->findOneBy(["id" => $id]);
        $form = null;
        $resource = null;
        switch ($id) {
            case '1':
                $resource = new Dispositif();
                $resource->setCategory($category);
                $form = $this->createForm("IT\ResourceBundle\Form\DispositifType", $resource);
                break;
            case '2':
                $resource = new Dispositif();
                $resource->setCategory($category);
                $form = $this->createForm("IT\ResourceBundle\Form\DispositifType", $resource);
                break;
            case '3':
                $resource = new Projecteur();
                $resource->setCategory($category);
                $form = $this->createForm("IT\ResourceBundle\Form\ProjecteurType", $resource);
                break;
            case '4':
                $resource = new Salle();
                $resource->setCategory($category);
                $form = $this->createForm("IT\ResourceBundle\Form\SalleType", $resource);
                break;
        }
        $resource->setCategory($category);
        $request = $this->get('request_stack')->getCurrentRequest();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($resource);
            $em->flush();
            $message = "Le ressource a été crée correctement";
        }

        return array('form' => $form->createView(),
            'category' => $category->getName(),
            'message' => $message,
            "categories" => $categories);
//        if ($category->getName() == "Projecteur"){
//            $projecteur = new Projecteur();
//            $projecteur->setCategorie($category);
//            $form = $this->createForm("IT\ResourceBundle\Form\ProjecteurType", $projecteur);
//            $form->handleRequest($request);
//            if ($form->isValid()) {
//
//                $em->persist($projecteur);
//                $em->flush();
//                $message = "Projecteur crée avec succée";
//            }
//
//            return array('projecteurForm' => $form->createView(), 'message' => $message);
//        }
//        return array();

    }

    /**
     * @Route("/ajouter/categorie/form" , name="add_form_categ")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAjaxFormAction()
    {
        $message = '';
        $postData = $this->get('request_stack')->getCurrentRequest()->request->all();
        $type = $postData["type"];
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository("ITResourceBundle:Categorie")->findOneBy(["id" => (int)$type]);
        $form = null;
        $resource = null;
        switch ($type) {
            case '1':
                $resource = new Dispositif();
                $form = $this->createForm("IT\ResourceBundle\Form\DispositifType", $resource);
                break;
            case '2':
                $resource = new Dispositif();

                $form = $this->createForm("IT\ResourceBundle\Form\DispositifType", $resource);
                break;
            case '3':
                $resource = new Projecteur();
                $form = $this->createForm("IT\ResourceBundle\Form\ProjecteurType", $resource);
                break;
            case '4':
                $resource = new Salle();
                $form = $this->createForm("IT\ResourceBundle\Form\SalleType", $resource);
                break;
        }
        $resource->setCategory($category);
        $request = $this->get('request_stack')->getCurrentRequest();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($resource);
            $em->flush();
            $message = "Le ressource a été crée correctement";
        }

        return $this->render('ITResourceBundle:Dispositif:form_ressource.html.twig', array(
            'form' => $form->createView(),
            'category' => $category->getName(),
            'message' => $message
        ));
    }

}