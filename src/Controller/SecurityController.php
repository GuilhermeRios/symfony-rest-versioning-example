<?php

namespace App\Controller;

use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as FOSRest;

class SecurityController extends AbstractFOSRestController
{
    /**
     * @var ClientManagerInterface
     */
    private $client_manager;

    /**
     * SecurityController constructor.
     * @param ClientManagerInterface $client_manager
     */
    public function __construct(ClientManagerInterface $client_manager)
    {
        $this->client_manager = $client_manager;
    }

    /**
     * Create new client
     * @FOSRest\Post("/client/new")
     * @param Request $request
     * @return Response
     */
    public function AuthenticationAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if (empty($data['redirect-uri']) || empty($data['grant-type'])) {
            return $this->handleView($this->view($data));
        }

        $client = $this->client_manager->createClient();
        $client->setRedirectUris([$data['redirect-uri']]);
        $client->setAllowedGrantTypes([$data['grant-type']]);
        $this->client_manager->updateClient($client);

        $rows = [
            'client_id' => $client->getPublicId(),
            'client_secret' => $client->getSecret(),
        ];
        return $this->handleView($this->view($rows));
    }
}
