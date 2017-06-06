<?php

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        return $this->handlePostApi('/rest/api/2/version', $request->getContent());
    }

    /**
     * @Route("/version/{id}", requirements={"id": "\d+"})
     * @Method({"DELETE"})
     *
     * @param $id
     * @return Response
     */
    public function deleteAction($id)
    {
        return $this->handleDeleteApi("/rest/api/2/version/$id");
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
        return $this->handlePutApi("/rest/api/2/version/$id", $data);
    }

}