<?php

class TidioIntegrationFacade
{
    const TIDIO_WORDPRESS_OAUTH_CLIENT_ID = '8ea883be-28c3-4bfd-9fe2-4091eb38fe08';

    /**
     * @var TidioApiClientFactory
     */
    private $apiClientFactory;

    /**
     * @param TidioApiClientFactory $apiClientFactory
     */
    public function __construct($apiClientFactory)
    {
        $this->apiClientFactory = $apiClientFactory;
    }

    /**
     * @param string $refreshToken
     * @return array<string, mixed>
     * @throws TidioApiException
     */
    public function integrateProject($refreshToken)
    {
        $apiClient = $this->apiClientFactory->create();
        $tokens = $apiClient->sendPostRequest('/platforms/oauth/access_token', [
            'grant_type' => 'refresh_token',
            'client_id' => self::TIDIO_WORDPRESS_OAUTH_CLIENT_ID,
            'refresh_token' => $refreshToken
        ]);

        $apiClient = $this->apiClientFactory->createAuthenticated($tokens['access_token']);
        $data = $apiClient->sendPostRequest('/platforms/wordpress/integrate');

        return [
            'projectPublicKey' => $data['projectPublicKey'],
            'accessToken' => $tokens['access_token'],
            'refreshToken' => $tokens['refresh_token']
        ];
    }
}
