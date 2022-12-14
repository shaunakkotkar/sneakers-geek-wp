<?php

class TidioLiveChat
{
    const TIDIO_PLUGIN_NAME = 'tidio-live-chat';

    public static function load()
    {
        $encryptionService = (new TidioEncryptionServiceFactory())->create();
        $integrationState = new TidioIntegrationState($encryptionService);
        if (!is_admin()) {
            new TidioWidgetLoader($integrationState);
            return;
        }

        if (current_user_can('activate_plugins')) {
            new TidioTranslationLoader();
            $apiClientFactory = new TidioApiClientFactory();
            $integrationFacade = new TidioIntegrationFacade($apiClientFactory);
            $adminController = new TidioAdminController($integrationFacade, $integrationState);
            $iframeSetup = new TidioIframeSetup($integrationState);
            $errorTranslator = new TidioErrorTranslator();

            new TidioAdminRouting($adminController);
            new TidioAdminActionLink($integrationState);
            new TidioAdminDashboard($integrationState, $iframeSetup);
            new TidioAdminNotice($errorTranslator);
        }
    }
}
