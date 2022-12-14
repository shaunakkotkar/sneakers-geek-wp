<?php

class TidioApiException extends \Exception
{
    const UNAUTHORIZED_ERROR_CODE = 'unauthorized';
    const UNKNOWN_ERROR_CODE = 'unknown_error';

    /**
     * @param string $errorCode
     * @return TidioApiException
     */
    public static function withErrorCode($errorCode)
    {
        return new self($errorCode);
    }

    /**
     * @return TidioApiException
     */
    public static function withUnauthorizedErrorCode()
    {
        return new self(self::UNAUTHORIZED_ERROR_CODE);
    }

    /**
     * @return TidioApiException
     */
    public static function withUnknownErrorCode()
    {
        return new self(self::UNKNOWN_ERROR_CODE);
    }
}