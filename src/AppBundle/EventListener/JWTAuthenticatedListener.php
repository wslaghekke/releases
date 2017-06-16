<?php

namespace AppBundle\EventListener;

use AtlassianConnectBundle\Entity\Tenant;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTAuthenticatedEvent;

/**
 * Class JWTAuthenticatedListener
 * @package AppBundle\EventListener
 * @author Willem Slaghekke <w.slaghekke@bytefusion.nl>
 */
class JWTAuthenticatedListener
{

    public function onJWTAuthenticated(JWTAuthenticatedEvent $event)
    {
        $token = $event->getToken();
        $payload = $event->getPayload();

        /** @var Tenant $user */
        $user = $token->getUser();
        $user->setUsername($payload['username']);
    }

}