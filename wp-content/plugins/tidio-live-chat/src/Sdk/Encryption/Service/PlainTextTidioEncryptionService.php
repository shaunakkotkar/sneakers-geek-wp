<?php

class PlainTextTidioEncryptionService implements TidioEncryptionService
{
    /**
     * @inerhitDoc
     */
    public function encrypt($value)
    {
        return $value;
    }

    /**
     * @inerhitDoc
     */
    public function decrypt($encryptedString)
    {
        return $encryptedString;
    }
}