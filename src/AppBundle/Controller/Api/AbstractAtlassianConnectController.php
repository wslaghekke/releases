<?php

namespace AppBundle\Controller\Api;

use AtlassianConnectBundle\Model\JWTRequest;
use GuzzleHttp\Exception\ClientException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractAtlassianConnectController
 * @package AppBundle\Controller\Api
 * @author Willem Slaghekke <w.slaghekke@bytefusion.nl>
 */
abstract class AbstractAtlassianConnectController extends Controller
{

    /**
     * @param string $endPoint
     * @return Response
     */
    protected function handleGetApi(string $endPoint)
    {
        try {
            $jwtRequest = new JWTRequest($this->getUser());

            return new JsonResponse($jwtRequest->get($endPoint));
        } catch (ClientException $e) {
            return $this->handleClientException($e);
        }
    }

    /**
     * @param string       $endPoint
     * @param string|array $data
     * @return Response
     */
    protected function handlePostApi(string $endPoint, $data): Response
    {
        try {
            if (is_string($data)) {
                $data = json_decode($data);
            }
            $jwtRequest = new JWTRequest($this->getUser());
            return new JsonResponse($jwtRequest->post($endPoint, $data));
        } catch (ClientException $e) {
            return $this->handleClientException($e);
        }
    }

    /**
     * @param string $endPoint
     * @param string|array $data
     * @return Response
     */
    protected function handlePutApi(string $endPoint, $data): Response
    {
        try {
            if (is_string($data)) {
                $data = json_decode($data);
            }
            $jwtRequest = new JWTRequest($this->getUser());
            return new JsonResponse($jwtRequest->put($endPoint, $data));
        } catch (ClientException $e) {
            return $this->handleClientException($e);
        }
    }

    /**
     * @param string $endPoint
     * @return Response
     */
    protected function handleDeleteApi(string $endPoint): Response
    {
        try {
            $jwtRequest = new JWTRequest($this->getUser());
            $jwtRequest->delete($endPoint);
            return new JsonResponse('', 204);
        } catch (ClientException $e) {
            return $this->handleClientException($e);
        }
    }

    /**
     * @param ClientException $e
     * @return Response
     */
    protected function handleClientException(ClientException $e): Response
    {
        $logger = $this->get('logger');
        if ($e->getResponse()->getStatusCode() === Response::HTTP_NOT_FOUND) {
            $logger->info($e->getMessage(), ['exception' => $e]);
        } else {
            $logger->error($e->getMessage(), ['exception' => $e]);
        }

        return new Response($e->getResponse()->getBody()->getContents(), $e->getResponse()->getStatusCode());
    }

}