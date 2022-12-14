<?php

class i18n
{
    /**
     * echo translation
     */
    public static function _e($message)
    {
        _e($message, TidioLiveChat::TIDIO_PLUGIN_NAME);
    }

    /**
     * returns translation
     */
    public static function _t($message)
    {
        return __($message, TidioLiveChat::TIDIO_PLUGIN_NAME);
    }
}