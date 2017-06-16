<?php

namespace AppBundle\Controller\Api;

use AppBundle\TenantEvents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class VersionController
 * @package AppBundle\Controller\Api
 * @Route("/api")
 */
class VersionController extends AbstractAtlassianConnectController
{

    /**
     * @Route("/version")
     * @Method("POST")
     *
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $response = $this->handlePostApi('/rest/api/2/version', $request->getContent());
        if ($response->isSuccessful()) {
            $this->get('app.pusher_service')->publishTenantEvent(
                $this->getUser(),
                TenantEvents::CREATE_VERSION,
                $response->getContent(),
                $request->headers->get('Socket-Id')
            );
        }

        return $response;
    }

    /**
     * @Route("/version/{id}", requirements={"id": "\d+"})
     * @Method({"DELETE"})
     *
     * @param $id
     * @return Response
     */
    public function deleteAction(Request $request, $id)
    {
        $response = $this->handleDeleteApi("/rest/api/2/version/$id");
        if ($response->isSuccessful()) {
            $this->get('app.pusher_service')->publishTenantEvent(
                $this->getUser(),
                TenantEvents::DELETE_VERSION,
                json_encode(['id' => $id]),
                $request->headers->get('Socket-Id')
            );
        }

        return $response;
    }

    /**
     * @Route("/version/{id}", requirements={"id": "\d+"})
     * @Method({"PUT"})
     *
     * @param $id
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        $data = json_decode($request->getContent(), true);
        unset($data['userReleaseDate'], $data['userStartDate']);
        $response = $this->handlePutApi("/rest/api/2/version/$id", $data);
        if ($response->isSuccessful()) {
            $this->get('app.pusher_service')->publishTenantEvent(
                $this->getUser(),
                TenantEvents::UPDATE_VERSION,
                $response->getContent(),
                $request->headers->get('Socket-Id')
            );
        }

        return $response;
    }

}