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

    public function __construct(LoggerInterface $logger, $app_key, $app_secret, $app_id, $options)
    {
        if ($app_key === null || $app_secret === null || $app_id === null) {
            $logger->debug('pusher key,secret or id are null, disabling pusher');
            $this->pusher = null;
        } else {
            $this->pusher = new Pusher(
                $app_key,
                $app_secret,
                $app_id,
                $options
            );
        }
    }

    /**
     * @param string $channel
     * @param string $socket_id
     * @param string|null $custom_data
     * @return null|string
     */
    public function createSocketSignature($channel, $socket_id, $custom_data = null)
    {
        if ($this->pusher === null) {
            return null;
        }

        return $this->pusher->socket_auth($channel, $socket_id, $custom_data);
    }

    /**
     * @param Tenant $tenant
     * @param string $channel
     * @return bool
     */
    public function hasChannelAccess(Tenant $tenant, $channel)
    {
        if (preg_match('/private-tenant-(\d+)/', $channel, $matches)) {
            return (int)$matches[1] === $tenant->getId();
        }
        $this->logger->error("Invalid or unsupported channel-name: $channel");

        return false;
    }

    /**
     * @param Tenant $tenant
     * @param string $event_name
     * @param string $payload
     * @param string $exclude_socket_id
     * @return array|bool
     */
    public function publishTenantEvent(Tenant $tenant, $event_name, $payload, $exclude_socket_id = null)
    {
        if ($this->pusher === null) {
            return false;
        }

        return $this->pusher->trigger(
            'private-tenant-'.$tenant->getId(),
            $event_name,
            $payload,
            $exclude_socket_id,
            false,
            true
        );
    }
}