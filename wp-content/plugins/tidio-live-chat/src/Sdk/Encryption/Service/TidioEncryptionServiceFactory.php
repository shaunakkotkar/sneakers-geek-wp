<?php

class TidioEncryptionServiceFactory
{
    public function create()
    {
        $encryptionKey = $this->getEncryptionKey();
        if (empty($encryptionKey) || !extension_loaded('openssl')) {
            return new PlainTextTidioEncryptionService();
        }

        return new OpenSslTidioEncryptionService($encryptionKey);
    }

    /**
     * @return string|null
     */
    private function getEncryptionKey()
    {
        if (!defined('LOGGED_IN_KEY')) {
            return null;
        }

        return LOGGED_IN_KEY;
    }
}