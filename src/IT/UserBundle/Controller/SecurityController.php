<?php

namespace IT\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as FOSController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SecurityController
 * @package IT\Bundles\UserBundle\Controller
 */
class SecurityController extends FOSController
{

    public function loginAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('configLDAP'));
        }
        return parent::loginAction($request);
    }

    public function renderLogin(array $data)
    {
        return $this->render('@ITUser/Security/login.html.twig', $data);
    }
}