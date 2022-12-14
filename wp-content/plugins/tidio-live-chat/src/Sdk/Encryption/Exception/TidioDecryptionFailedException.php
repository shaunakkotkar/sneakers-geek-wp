<?php

class TidioDecryptionFailedException extends \Exception
{
    const INVALID_HASH_ERROR_CODE = 'invalid_hash';

    /**
     * @return TidioDecryptionFailedException
     */
    public static function withInvalidHashErrorCode()
    {
        return new self(self::INVALID_HASH_ERROR_CODE);
    }
}