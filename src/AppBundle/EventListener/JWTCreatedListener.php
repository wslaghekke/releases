<?php

namespace AppBundle\EventListener;

use AtlassianConnectBundle\Entity\Tenant;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class JWTCreatedListener
 * @package AppBundle\EventListener
 * @author Willem Slaghekke <w.slaghekke@bytefusion.nl>
 */
class JWTCreatedListener
{
    /** @var Tenant */
    protected $tenant;


    public function __construct(TokenStorage $storage)
    {
        $token = $storage->getToken();
        if ($token !== null) {
            $this->tenant = $token->getUser();
        }

    }

    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $payload = $event->getData();
        $payload['username'] = $this->tenant->getUsername();

        $event->setData($payload);
    }

}