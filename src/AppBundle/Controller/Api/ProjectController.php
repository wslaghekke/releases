<?php

namespace AppBundle\Controller\Api;

use AtlassianConnectBundle\Model\JWTRequest;
use GuzzleHttp\Exception\ClientException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProjectController
 * @package AppBundle\Controller\Api
 * @Route("/api")
 */
class ProjectController extends AbstractAtlassianConnectController
{

    /**
     * @Route("/project")
     * @throws \Exception
     */
    public function listAction()
    {
        return $this->handleGetApi('/rest/api/2/project');
    }

    /**
     * @Route("/project/{key}/versions")
     * @Method({"GET"})
     *
     * @param string $key Project Key
     * @return JsonResponse|Response
     */
    public function listVersionsAction($key)
    {
        try {
            $jwtRequest = new JWTRequest($this->getUser());

            return new JsonResponse($jwtRequest->get("/rest/api/2/project/$key/versions"));
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 404) {
                return new Response('', 404);
            }
            throw $e;
        }
    }

}