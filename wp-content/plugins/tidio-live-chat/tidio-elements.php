<?php

/**
 * Plugin Name: Tidio Chat
 * Plugin URI: http://www.tidio.com
 * Description: Tidio Live Chat - live chat boosted with chatbots for your online business. Integrates with your website in less than 20 seconds.
 * Version: 5.0.0
 * Author: Tidio Ltd.
 * Author URI: http://www.tidio.com
 * Text Domain: tidio-live-chat
 * Domain Path: /languages/
 * License: GPL2
 */
define('TIDIOCHAT_VERSION', '5.0.0');
define('AFFILIATE_CONFIG_FILE_PATH', get_template_directory() . '/tidio_affiliate_ref_id.txt');

require_once __DIR__ . '/src/TidioLiveChatConfig.php';
require_once __DIR__ . '/src/Translation/TidioTranslationLoader.php';
require_once __DIR__ . '/src/Translation/TidioErrorTranslator.php';
require_once __DIR__ . '/src/Translation/i18n.php';
require_once __DIR__ . '/src/Widget/TidioWidgetLoader.php';
require_once __DIR__ . '/src/Admin/TidioAdminActionLink.php';
require_once __DIR__ . '/src/Admin/TidioAdminController.php';
require_once __DIR__ . '/src/Admin/TidioIframeSetup.php';
require_once __DIR__ . '/src/Admin/TidioAdminDashboard.php';
require_once __DIR__ . '/src/Admin/TidioAdminRouting.php';
require_once __DIR__ . '/src/Admin/TidioAdminNotice.php';
require_once __DIR__ . '/src/Admin/TidioIframeSetup.php';
require_once __DIR__ . '/src/Sdk/TidioIntegrationFacade.php';
require_once __DIR__ . '/src/Sdk/Encryption/TidioEncryptionService.php';
require_once __DIR__ . '/src/Sdk/Encryption/Exception/TidioDecryptionFailedException.php';
require_once __DIR__ . '/src/Sdk/Encryption/Service/PlainTextTidioEncryptionService.php';
require_once __DIR__ . '/src/Sdk/Encryption/Service/OpenSslTidioEncryptionService.php';
require_once __DIR__ . '/src/Sdk/Encryption/Service/TidioEncryptionServiceFactory.php';
require_once __DIR__ . '/src/Sdk/Api/TidioApiClient.php';
require_once __DIR__ . '/src/Sdk/Api/Client/TidioApiClientFactory.php';
require_once __DIR__ . '/src/Sdk/Api/Client/CurlTidioApiClient.php';
require_once __DIR__ . '/src/Sdk/Api/Client/FileGetContentsTidioApiClient.php';
require_once __DIR__ . '/src/Sdk/Api/Exception/TidioApiException.php';
require_once __DIR__ . '/src/Utils/QueryParameters.php';
require_once __DIR__ . '/src/TidioIntegrationState.php';
require_once __DIR__ . '/src/TidioLiveChat.php';

function initializeTidioLiveChat()
{
    if (!empty($_GET['tidio_chat_version'])) {
        echo TIDIOCHAT_VERSION;
        exit;
    }

    TidioLiveChat::load();
}

add_action('init', 'initializeTidioLiveChat');

$encryptionService = (new TidioEncryptionServiceFactory())->create();
register_activation_hook(__FILE__, [new TidioIntegrationState($encryptionService), 'turnOnAsyncLoading']);
