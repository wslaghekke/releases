<?php

namespace AppBundle\Controller\Api;

use AtlassianConnectBundle\Entity\Tenant;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class PusherController
 * @package AppBundle\Controller\Api
 * @author Willem Slaghekke <w.slaghekke@bytefusion.nl>
 *
 * @Route("/api")
 */
class PusherController extends Controller
{
    /**
     * @Route("/pusher/auth")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function authenticateAction(Request $request)
    {
        $pusherService = $this->get('app.pusher_service');
        /** @var Tenant $tenant */
        $tenant = $this->getUser();
        $channel_name = $request->request->get('channel_name');
        $socket_id = $request->request->get('socket_id');

        if ($tenant !== null && $pusherService->hasChannelAccess($tenant, $channel_name)) {
            return new JsonResponse($pusherService->createSocketSignature($channel_name, $socket_id), 200, [], true);
        }

        throw new AccessDeniedHttpException();
    }

}