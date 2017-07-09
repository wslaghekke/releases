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
    public function listAction(): Response
    {
        return $this->handleGetApi('/rest/api/2/project');
    }

    /**
     * @Route("/project/{key}/versions")
     * @Method({"GET"})
     *
     * @param string $key Project Key
     *
     * @return JsonResponse|Response
     * @throws \LogicException
     * @throws \InvalidArgumentException
     * @throws \GuzzleHttp\Exception\ClientException
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

    /**
     * @Route("/project/{key}/allversions")
     * @Method({"GET"})
     *
     * @param string $key Project Key
     *
     * @return JsonResponse|Response
     * @throws \Exception
     */
    public function listAllVersionsAction($key)
    {
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://{{host}}.atlassian.net/rest/projects/1.0/project/$key/release/allversions",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'cache-control: no-cache',
                    'content-type: application/json',
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            curl_close($curl);

            if ($err) {
                if ($http_code === 404) {
                    return new Response('', 404);
                }
                throw new \RuntimeException('cURL Error #:' . $err, $http_code);
            }

            return new JsonResponse(json_decode($response), $http_code);
        } catch (\Exception $e) {
            if ($e->getResponse()->getStatusCode() === 404) {
                return new Response('', 404);
            }
            throw $e;
        }
    }

}