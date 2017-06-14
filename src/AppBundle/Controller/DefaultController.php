<?php

namespace AppBundle\Controller;

use AtlassianConnectBundle\Entity\Tenant;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @Route("/protected/releases-home", name="homepage")
     * @throws \Exception
     */
    public function indexAction()
    {
        /** @var Tenant $user */
        $user = $this->getUser();
        if($user->getUsername() === null) {
            throw new AccessDeniedHttpException('Unknown user');
        }

        $assets = json_decode(file_get_contents(__DIR__.'/../../../assets.json'), true);
        $response = new Response();
        $response->headers->set('Referrer-Policy', 'no-referrer');
        return $this->render('@App/Default/index.html.twig', [
            'assets' => $assets,
            'jwt' => $this->get('lexik_jwt_authentication.jwt_manager')->create($this->getUser())
        ], $response);
    }

    /**
     * @Route("/protected/dev-api-key")
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function devApiKeyAction()
    {
        if ($this->get('kernel')->isDebug()) {
            return new Response($this->get('lexik_jwt_authentication.jwt_manager')->create($this->getUser()), 200);
        }

        throw new NotFoundHttpException();
    }
}
