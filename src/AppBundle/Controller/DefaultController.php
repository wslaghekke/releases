<?php

namespace AppBundle\Controller;

use AtlassianConnectBundle\Entity\Tenant;
use Pusher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    protected function getEnvironmentConfig()
    {
        return [
            'tenant_id'      => $this->getUser()->getId(),
            'api_key'        => $this->get('lexik_jwt_authentication.jwt_manager')->create($this->getUser()),
            'base_url'       => $this->getUser()->getBaseUrl(),
            'pusher_api_key' => $this->getParameter('pusher_app_key'),
            'pusher_config'  => [
                'cluster'      => $this->getParameter('pusher_cluster'),
                'encrypted'    => $this->getParameter('pusher_encrypted'),
                'authEndpoint' => $this->generateUrl('app_api_pusher_authenticate'),
                'auth'         => [
                    'headers' => [
                        'Authorization' => null,
                    ],
                ],
            ],
            'sentry_public_dsn' => $this->getParameter('sentry_public_dsn'),
        ];
    }

    /**
     * @Route("/protected/releases-home", name="homepage")
     * @return Response
     * @throws \Exception
     */
    public function indexAction()
    {
        /** @var Tenant $user */
        $user = $this->getUser();
        if ($user->getUsername() === null) {
            throw new AccessDeniedHttpException('Unknown user');
        }

        $assets = json_decode(file_get_contents(__DIR__.'/../../../assets.json'), true);
        $response = new Response();
        $response->headers->set('Referrer-Policy', 'no-referrer');

        return $this->render(
            '@App/Default/index.html.twig',
            [
                'assets' => $assets,
                'config' => $this->getEnvironmentConfig(),
            ],
            $response
        );
    }

    /**
     * @Route("/protected/dev-environment")
     * @return JsonResponse
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function devEnvironmentAction()
    {
        if (!$this->get('kernel')->isDebug()) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($this->getEnvironmentConfig());
    }
}
