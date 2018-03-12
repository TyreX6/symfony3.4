<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 11/03/2018
 * Time: 20:18
 */

namespace IT\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use LdapTools\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;


class LdapApiController extends FOSRestController
{
    /**
     * @Rest\View()
     * @Rest\Post("api/admin/ldap")
     * @param Request $request
     * @return array
     */
    public function configLdapAction(Request $request)
    {
        try {
            $data = $request->request->all();
            $host = $data["host"];
            $port = $data["port"];
            $baseDN = $data["baseDN"];
            $em = $this->getDoctrine()->getManager();
            $ldap = $em->getRepository("ITUserBundle:LdapConfig")->findAll()[0];
            $ldap->setHost($host);
            $ldap->setPort($port);
            $ldap->setBaseDn($baseDN);
            $em->persist($ldap);
            $em->flush();
            $yaml = Yaml::parse(file_get_contents($this->container->get('kernel')->getRootDir() . '/config/parameters.yml'));
            $yaml['parameters']['LDAP_host'] = $ldap->getHost();
            $yaml['parameters']['LDAP_baseDn'] = $ldap->getBaseDn();
            $yaml['parameters']['Port'] = $ldap->getPort();
            $new_yaml = Yaml::dump($yaml, 5);
            file_put_contents($this->container->get('kernel')->getRootDir() . '/config/parameters.yml', $new_yaml);
            return array("success" => 1);
        } catch (Exception $e) {
            return array("success"=>0);
        }
    }

}