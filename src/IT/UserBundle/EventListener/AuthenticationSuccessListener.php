<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 11/03/2018
 * Time: 20:00
 */
namespace IT\UserBundle\EventListener;

use FOS\UserBundle\Model\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;


class AuthenticationSuccessListener
{
    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $data['data'] = array(
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        );
        $event->setData($data);
    }

    public function onJwtCreated(JWTCreatedEvent $event)
    {
        $user = $event->getUser();
        $payload = $event->getData();
        $payload['id'] = $user->getId();
        $payload['email'] = $user->getEmail();

        $event->setData($payload);
    }



}