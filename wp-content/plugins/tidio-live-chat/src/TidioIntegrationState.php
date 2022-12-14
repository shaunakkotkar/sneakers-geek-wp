<?php

class TidioIntegrationState
{
    const PUBLIC_KEY_OPTION = 'tidio-one-public-key';
    const PRIVATE_KEY_OPTION = 'tidio-one-private-key';
    const ASYNC_LOAD_OPTION = 'tidio-async-load';
    const TIDIO_OAUTH_ACCESS_TOKEN_KEY = 'tidio-access-token';
    const TIDIO_OAUTH_REFRESH_TOKEN_KEY = 'tidio-refresh-token';

    /**
     * @var OpenSslTidioEncryptionService
     */
    private $encryptionService;

    /**
     * @param OpenSslTidioEncryptionService $encryptionService
     */
    public function __construct($encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }

    /**
     * @return string|null
     */
    public function getProjectPublicKey()
    {
        return get_option(self::PUBLIC_KEY_OPTION, null);
    }

    /**
     * @return string|null
     */
    public function getProjectPrivateKey()
    {
        return get_option(self::PRIVATE_KEY_OPTION, null);
    }

    /**
     * @return bool
     */
    public function hasProjectPrivateKey()
    {
        return !empty(get_option(self::PRIVATE_KEY_OPTION));
    }

    /**
     * @return bool
     */
    public function isPluginIntegrated()
    {
        return !empty(get_option(self::PUBLIC_KEY_OPTION));
    }

    /**
     * @return bool
     */
    public function isAsyncLoadingTurnedOn()
    {
        return (bool) get_option(self::ASYNC_LOAD_OPTION);
    }

    public function integrate($projectPublicKey, $accessToken, $refreshToken)
    {
        $encryptedRefreshToken = $this->encryptionService->encrypt($refreshToken);

        update_option(self::PUBLIC_KEY_OPTION, $projectPublicKey);
        update_option(self::TIDIO_OAUTH_ACCESS_TOKEN_KEY, $accessToken);
        update_option(self::TIDIO_OAUTH_REFRESH_TOKEN_KEY, $encryptedRefreshToken);
        update_option(self::ASYNC_LOAD_OPTION, true);
    }

    public function removeIntegration()
    {
        delete_option(self::PUBLIC_KEY_OPTION);
        delete_option(self::TIDIO_OAUTH_ACCESS_TOKEN_KEY);
        delete_option(self::TIDIO_OAUTH_REFRESH_TOKEN_KEY);
        delete_option(self::ASYNC_LOAD_OPTION);
        delete_option(self::PRIVATE_KEY_OPTION);
    }

    public function turnOnAsyncLoading()
    {
        update_option(self::ASYNC_LOAD_OPTION, true);
    }

    public function toggleAsyncLoading()
    {
        update_option(self::ASYNC_LOAD_OPTION, !$this->isAsyncLoadingTurnedOn());
    }
}
