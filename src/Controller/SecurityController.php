<?php

namespace App\Controller;

use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractFOSRestController
{
    /**
     * @var ClientManagerInterface
     */
    private $clientManager;

    /**
     * SecurityController constructor.
     */
    public function __construct(ClientManagerInterface $clientManager)
    {
        $this->clientManager = $clientManager;
    }

    /**
     * Create new client.
     *
     * @FOSRest\Post("/client/new")
     *
     * @return Response
     */
    public function authenticationAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if (empty($data['redirect-uri']) || empty($data['grant-type'])) {
            return $this->handleView($this->view($data));
        }

        $client = $this->clientManager->createClient();
        $client->setRedirectUris([$data['redirect-uri']]);
        $client->setAllowedGrantTypes([$data['grant-type']]);
        $this->clientManager->updateClient($client);

        $rows = [
            'client_id' => $client->getPublicId(),
            'client_secret' => $client->getSecret(),
        ];

        return $this->handleView($this->view($rows));
    }
}
