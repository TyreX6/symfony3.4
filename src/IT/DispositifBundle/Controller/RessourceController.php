<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 31/03/2018
 * Time: 22:59
 */

namespace IT\DispositifBundle\Controller;

use IT\DispositifBundle\Entity\Dispositif;
use FOS\RestBundle\Controller\Annotations as Rest;
use IT\DispositifBundle\Entity\Projecteur;
use IT\DispositifBundle\Entity\Ressource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class RessourceController extends Controller
{
    /**
     * @Route("/ajouter/categorie/{id}" , name="ajouter_ressource")
     * @Template()
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public function ajouter_RessourceAction(Request $request, $id)
    {
        $message = null;
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository("ITDispositifBundle:Categorie")->findOneBy(["id" => $id]);
        if ($category->getName() == "Projecteur"){
            $projecteur = new Projecteur();
            $projecteur->setCategorie($category);
            $form = $this->createForm("IT\DispositifBundle\Form\ProjecteurType", $projecteur);
            $form->handleRequest($request);
            if ($form->isValid()) {

                $em->persist($projecteur);
                $em->flush();
                $message = "Projecteur crée avec succée";
            }

            return array('projecteurForm' => $form->createView(), 'message' => $message);
        }
        return array();

    }

}