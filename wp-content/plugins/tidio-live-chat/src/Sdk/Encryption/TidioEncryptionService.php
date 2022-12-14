<?php

interface TidioEncryptionService
{
    /**
     * @param string $value
     * @return string
     */
    public function encrypt($value);

    /**
     * @param string $encryptedString
     * @return string
     * @throws TidioDecryptionFailedException
     */
    public function decrypt($encryptedString);
}