<?php

namespace IT\InventoryBundle\Controller;

use IT\ResourceBundle\Entity\Ressource;
use DateTimeZone;
use JMS\Serializer\SerializerBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use IT\UserBundle\Entity\User;
use IT\InventoryBundle\Entity\Inventory;
use IT\InventoryBundle\Entity\LineInventory;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Response;

class InventoryController extends Controller
{

    /**
     * @Route("/admin/inventaire/" , name="list_inventaires")
     * @Template()
     */
    public function list_InventaireAction()
    {

        $inventaires = $this->getDoctrine()->getRepository("ITInventoryBundle:Inventory")->findAll();
        return array('inventaires' => $inventaires);
    }

    /**
     * @Route("/admin/inventaire/{id}" , name="consulter_inventaire")
     * @Template()
     */
    public function consulterInventaireAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $inventaire = $em->getRepository("ITInventoryBundle:Inventory")->find($id);
        $etat = $inventaire->getEtat();
        $lignesInventaire = $inventaire->getLigneInventaire();
        if ($lignesInventaire != null)
            return array('lignes' => $lignesInventaire, 'id' => $id, 'etat' => $etat);
        else
            return $this->redirect($this->generateUrl("list_inventaires"));

    }

    /**
     * @Route("/admin/ajouter/inventaire/" , name="add_inventaire")
     * @Template()
     */
    public function add_InventaireAction()
    {
        $inventaire = new Inventory();
        $inventaire->setEtat("Ouvert");
        $inventaire->setDateInventaire(new \DateTime('now', new DateTimeZone('Africa/Tunis')));
        $em = $this->getDoctrine()->getManager();
        $em->persist($inventaire);
        $em->flush();
        $postData = $this->get('request_stack')->getCurrentRequest()->request->all();

        if (isset($postData['mode'])) {
            $encoders = array(new XmlEncoder(), new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);
            $jsonContent = $serializer->serialize($inventaire, 'json');
            $response = new Response($jsonContent);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } else
            return $this->redirect($this->generateUrl("consulter_inventaire", array('id' => $inventaire->getId())));
    }

    /**
     * @Route("/admin/inventaire/add/" , name="addMat_inventaire")
     */
    public function ajoutMaterielInventaireAction()
    {
        $postData = $this->get('request_stack')->getCurrentRequest()->request->all();
        $id = $postData['id'];
        $codeBarre = $postData['codeBarre'];
        $id = (int)$id;
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $em = $this->getDoctrine()->getManager();
        $inventaire = $em->getRepository("ITInventoryBundle:Inventory")->find($id);
        $materiel = $em->getRepository("ITResourceBundle:Ressource")->findOneBy(array('bar_code' => $codeBarre));
//        return JsonResponse::create(array("id" => $materiel->getId()), 200)
//            ->setSharedMaxAge(900);
        if ($materiel) {

            $existantList = $em->getRepository("ITInventoryBundle:Inventory")->verifierMateriel($inventaire, $materiel);
//        $nonExistant=$em->getRepository("ITInventoryBundle:Inventory")->verifierExistantMateriel($materiel->getCodeBarre()) ;


            if ($existantList == 1) {
                $result = array("result" => 0);
                $jsonContent = $serializer->serialize($result, 'json');
                $response = new Response($jsonContent);
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            } else {
                $materiel->setLastCheckDate(new \DateTime('now', new DateTimeZone('Africa/Tunis')));
                $em->persist($materiel);
                $em->flush();
                $ligneInventaire = new LineInventory();
                $ligneInventaire->setInventaire($inventaire);
                $ligneInventaire->setResource($materiel);
                $ligneInventaire->setEtat($materiel->getEtat());
                $em->persist($ligneInventaire);
                $em->flush();

                $ressource = $em->getRepository(get_class($materiel))->findOneBy(["id"=>$materiel->getId()]) ;
                $result = array("result" => 1,"id" => $ligneInventaire->getId(), "materiel" => $ressource);
                $serializer = SerializerBuilder::create()->build();
                $jsonContent = $serializer->serialize($result, 'json');
                //$jsonContent = $serializer->serialize($result, 'json');
                return new Response($jsonContent);
            }
        }
    }

    /**
     * @Route("/admin/ligne/{id}/etat/{statut}/",name="changer_statut")
     */
    public function changerStatutAction($id, $statut)
    {
        $em = $this->getDoctrine()->getManager();
        $ligneInventaire = $em->getRepository("ITInventoryBundle:LineInventory")->find($id);
        $ligneInventaire->setEtat($statut);
        $em->persist($ligneInventaire);
        $em->flush();
        $materiels = $this->getDoctrine()->getRepository("AppBundle:Materiel")->findAll();
        $message = "L'etat est modifié avec succée";
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * @Route("/admin/inventaire/{id}/cloturer",name="cloture_inventaire")
     * @Template()
     */
    public function cloturerInventaire($id)
    {
        $em = $this->getDoctrine()->getManager();
        $inventaire = $em->getRepository("ITInventoryBundle:Inventory")->find($id);
        $inventaire->setEtat("Terminé");
        $em->persist($inventaire);
        $em->flush();
        $ligneInv = $inventaire->getLigneInventaire();
        foreach ($ligneInv as &$ligne) {
            $materielLigne = $ligne->getResource();
            $materiel = $em->getRepository("ITResourceBundle:Ressource")->find($materielLigne->getId());
            $materiel->setEtat($ligne->getEtat());
            $em->persist($materiel);
            $em->flush();
        }
        return $this->redirect($this->generateUrl("list_inventaires"));
    }

    /**
     * @Route("/admin/changeStatut/materiel/",name="changer_statutPost")
     */
    public function changerStatutPostAction()
    {
        $postData = $this->get('request_stack')->getCurrentRequest()->request->all();
        $id = $postData['id'];
        $id = (int)$id;
        $statut = $postData['statut'];
        $em = $this->getDoctrine()->getManager();
        $ligneInventaire = $em->getRepository("ITInventoryBundle:LineInventory")->find($id);
        $ligneInventaire->setEtat($statut);
        $em->persist($ligneInventaire);
        $em->flush();
        return new Response("succéé");
    }

    public function implementChangerStatut()
    {

    }

}
