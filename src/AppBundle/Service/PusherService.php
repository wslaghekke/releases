<?php

namespace AppBundle\Service;

use AtlassianConnectBundle\Entity\Tenant;
use Psr\Log\LoggerInterface;
use Pusher;

/**
 * Class PusherService
 * @package AppBundle\Service
 * @author Willem Slaghekke <w.slaghekke@bytefusion.nl>
 */
class PusherService
{
    /** @var LoggerInterface */
    protected $logger;

    /** @var Pusher|null */
    protected $pusher;

    public function __construct(LoggerInterface $logger, $appKey, $appSecret, $appId, $options)
    {
        if ($appKey === null || $appSecret === null || $appId === null) {
            $logger->debug('pusher key,secret or id are null, disabling pusher');
            $this->pusher = null;
        } else {
            $this->pusher = new Pusher(
                $appKey,
                $appSecret,
                $appId,
                $options
            );
        }
    }

    /**
     * @param string      $channel
     * @param string      $socketId
     * @param string|null $customData
     * @return null|string
     */
    public function createSocketSignature($channel, $socketId, $customData = null)
    {
        if ($this->pusher === null) {
            return null;
        }

        return $this->pusher->socket_auth($channel, $socketId, $customData);
    }

    /**
     * @param Tenant $tenant
     * @param string $channel
     * @return bool
     */
    public function hasChannelAccess(Tenant $tenant, $channel)
    {
        if (preg_match('/private-tenant-(\d+)/', $channel, $matches)) {
            return (int) $matches[1] === $tenant->getId();
        }
        $this->logger->error("Invalid or unsupported channel-name: $channel");

        return false;
    }

    /**
     * @param Tenant $tenant
     * @param string $eventName
     * @param string $payload
     * @param string $excludeSocketId
     * @return array|bool
     */
    public function publishTenantEvent(Tenant $tenant, $eventName, $payload, $excludeSocketId = null)
    {
        if ($this->pusher === null) {
            return false;
        }

        return $this->pusher->trigger(
            'private-tenant-'.$tenant->getId(),
            $eventName,
            $payload,
            $excludeSocketId,
            false,
            true
        );
    }
}