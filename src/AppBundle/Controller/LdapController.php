<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 22/02/2018
 * Time: 14:48
 */

namespace AppBundle\Controller;

use AppBundle\Form\LdapConfigType;
use LdapTools\Exception\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Entity\Admin;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Yaml\Yaml;


class LdapController extends Controller
{
    /**
     * @Route("/admin/LDAP", name="configLDAP")
     * @Template()
     */
    public function config_ldapAction()
    {
        $message = null;
        $em = $this->getDoctrine()->getManager();
        $ldaps = $em->getRepository("AppBundle:LdapConfig")->findAll();
        $ldap = $ldaps[0];
        $form = $this->createForm("AppBundle\Form\LdapConfigType", $ldap);
        $request = $this->get('request_stack')->getCurrentRequest();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($ldap);
            $em->flush();
            $message = "Le serveur LDAP a été configuré correctement";
            $yaml = Yaml::parse(file_get_contents($this->container->get('kernel')->getRootDir() . '/config/parameters.yml'));
            $yaml['parameters']['LDAP_host'] = $ldap->getHost();
            $yaml['parameters']['LDAP_baseDn'] = $ldap->getBaseDn();
            $yaml['parameters']['Port'] = $ldap->getPort();
            $new_yaml = Yaml::dump($yaml, 5);
            file_put_contents($this->container->get('kernel')->getRootDir() . '/config/parameters.yml', $new_yaml);
        }
        return array('editForm' => $form->createView(), 'message' => $message);
    }

    /**
     * @Route("/admin/LDAP/verifyLDAP", name="verifyLDAP")
     */
    public function verifyLdapAction()
    {
        $postData = $this->get('request_stack')->getCurrentRequest()->request->all();
        $host = $postData['host'];
        $port = (int)$postData["port"];
        $connected = 0;
        if ($this->serviceping($host, $port)) {
            $connected = 1;
        }
        return JsonResponse::create(array("connected" => $connected), 200)
            ->setSharedMaxAge(300);


    }

    //Une fonction de ping pour tester un serveur LDAP en fournir le host et le port
    // du serveur
    function serviceping($host, $port, $timeout = 7)
    {
        $op = 0;
        try {
            $op = fsockopen($host, $port, $errno, $errstr, $timeout);
        } catch (\Exception $er) {
            return 0;
        }
        if (!$op) return 0; //DC is N/A
        else {
            fclose($op); //explicitly close open socket connection
            return 1; //DC is up & running, we can safely connect with ldap_connect
        }
    }
}