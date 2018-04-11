<?php

namespace IT\UserBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Model\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class RedirectUserListener
{
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;
    private $router;

    /**
     * @param TokenStorageInterface $tokenStorage
     * @param RouterInterface $r
     */
    public function __construct(TokenStorageInterface $tokenStorage, RouterInterface $r)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router = $r;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($this->isUserLogged() && $event->isMasterRequest()) {
            $currentRoute = $event->getRequest()->attributes->get('_route');
            if ($this->isAuthenticatedUserOnAnonymousPage($currentRoute)) {
                $response = new RedirectResponse($this->router->generate('configLDAP'));
                $event->setResponse($response);
            }
        }

    }

    private function isUserLogged()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        return $user;
    }


    private function isAuthenticatedUserOnAnonymousPage($currentRoute)
    {
        return in_array(
            $currentRoute,
            ['fos_user_security_login', 'fos_user_resetting_request', 'app_user_registration']
        );
    }
}