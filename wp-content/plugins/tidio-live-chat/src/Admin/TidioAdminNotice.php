<?php

class TidioAdminNotice
{
    /**
     * @var TidioErrorTranslator
     */
    private $errorTranslator;

    /**
     * @param TidioErrorTranslator $errorTranslator
     */
    public function __construct($errorTranslator)
    {
        $this->errorTranslator = $errorTranslator;

        add_action('admin_notices', [$this, 'addAdminErrorNotice']);
    }

    public function addAdminErrorNotice()
    {
        if (!QueryParameters::has('error')) {
            return;
        }

        $errorCode = QueryParameters::get('error');
        $errorMessage = $this->errorTranslator->translate($errorCode);
        echo sprintf('<div class="notice notice-error is-dismissible"><p>%s</p></div>', $errorMessage);
    }
}