<?php

namespace IT\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use IT\UserBundle\Entity\User;
use IT\UserBundle\Entity\Admin;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute("configLDAP");
        } else {
            return $this->redirect("/login");
        }

    }

    /**
     * @Route("/user", name="user")
     * @Template()
     */
    public function registerAction()
    {
        $admin = new Admin();
        $encoder = $this->get('security.encoder_factory')->getEncoder($admin);
        $em = $this->getDoctrine()->getManager();
        $admin->setUsername("admin");
        $admin->setEmail("admin@admin.com");
        $password = $encoder->encodePassword('admin', $admin->getSalt());
        $admin->setPassword($password);
        $admin->setRoles(array("ROLE_ADMIN"));
        $admin->setEnabled(true);
        $em->persist($admin);
        $em->flush();
        //return $this->render('Register/register.html.twig');
        return array();
    }


}
