<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 11/03/2018
 * Time: 20:18
 */

namespace IT\ApiBundle\Controller;

use Exception;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Yaml\Yaml;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Nelmio\ApiDocBundle\Annotation\Model;
use IT\UserBundle\Entity\LdapConfig;
use IT\UserBundle\Entity\User;
use Swagger\Annotations as SWG;


class LdapApiController extends FOSRestController
{
    /**
     * @Rest\View()
     * @Rest\Post("api/admin/ldap")
     * @param Request $request
     * @Operation(
     *      @SWG\Schema(
     *         type="Object",
     *         @Model(type=LdapConfig::class)
     *      ),
     *     tags={"Ldap"},
     *     summary="Modify LdapConfig",
     *      @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="LdapConfig object",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/LdapConfig"),
     *
     *
     *   ),
     *     @SWG\Response(
     *         response="200",
     *         description="LdapConfig modified sccessfully"
     *     ),
     * )
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
            return array("success" => 0);
        }
    }

    /**
     *
     * @Rest\View()
     * @Rest\Post("/api/login_check")
     * @Operation(
     *     tags={"Login"},
     *     summary="Login Api",
     *    @SWG\Parameter(
     *     in="body",
     *     name="Login form data",
     *     description="Username and password for login",
     *     required=true,
     *     @SWG\Schema(
     *     type="object",
     *     @SWG\Property(property="username", type="string"),
     *     @SWG\Property(property="password", type="string"),
     *        )
     *   ),
     *     @SWG\Response(
     *         response="200",
     *         description="Logged in sccessfully"
     *     ),
     * )
     */
    public function checkAction()
    {
    }


    /**
     * @Rest\View()
     * @Rest\Post("/api/loginRes_check")
     * @Operation(
     *     tags={"Reservation"},
     *     summary="Login for reservation",
     *    @SWG\Parameter(
     *     in="body",
     *     name="Verifier debut réservation",
     *     description="Vérification et autorisation du début de réservation",
     *     required=true,
     *     @SWG\Schema(
     *     type="object",
     *     @SWG\Property(property="username", type="string"),
     *     @SWG\Property(property="password", type="string"),
     *     @SWG\Property(property="dispositif_id", type="integer"),
     *        )
     *   ),
     *     @SWG\Response(
     *         response="200",
     *         description="Reservation launched"
     *     ),
     * )
     * @param Request $request
     * @return array|Response
     */
    public function loginAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ldapConfig = $em->getRepository("ITUserBundle:LdapConfig")->findAll()[0];

        $data = $request->request->all();
        $login = $data["username"];
        $password = $data["password"];

        $ldap_dn = "uid=" . $login . "," . $ldapConfig->getBaseDn();
        $ldaphost = "ldap://" . $ldapConfig->getHost();  // votre serveur LDAP
        $ldap_con = ldap_connect($ldaphost, $ldapConfig->getPort());
        ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);

        if (@ldap_bind($ldap_con, $ldap_dn, $password)) {
            $regles = $em->getRepository("ITReservationBundle:Regles")->findAll()[0];
            $count = $em->getRepository("ITReservationBundle:Reservation")->getActualReservationByUserRawSql($login, $regles);
            return array("success" => 1, "sql" => $count);
        } else {
            return array("success" => 0);
        }
    }

}