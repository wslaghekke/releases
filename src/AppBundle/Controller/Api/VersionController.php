<?php

namespace AppBundle\Controller\Api;

use AtlassianConnectBundle\Model\JWTRequest;
use GuzzleHttp\Exception\ClientException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

}