<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Entity\Admin;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/user", name="user")
     * @Template()
     */
    public function registerAction()
    {
        $admin = new Admin;
        $encoder = $this->get('security.encoder_factory')->getEncoder($admin);
        $em = $this->getDoctrine()->getManager();
        $admin->setUsername("admin");
        $admin->setEmail("admin@admin.com");
        $password = $encoder->encodePassword('admin', $admin->getSalt());
        $admin->setPassword($password);
        $admin->setRoles(array("admin"));
        $admin->setEnabled(true);
        $em->persist($admin);
        $em->flush();
        //return $this->render('Register/register.html.twig');
        return array();
    }
}
