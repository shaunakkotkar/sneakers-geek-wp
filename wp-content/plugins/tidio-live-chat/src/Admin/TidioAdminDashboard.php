<?php

class TidioAdminDashboard
{
    const TIDIO_ICON_BASE64 = 'PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDIzLjEuMSwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IldhcnN0d2FfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiCgkgdmlld0JveD0iMCAwIDIwIDIwIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCAyMCAyMDsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPgoJLnN0MHtmaWxsOiNBMEE1QUE7fQo8L3N0eWxlPgo8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMiwxOHYtNS45YzAtMi43LDEuNy01LDQuMy01LjdDNy4xLDMuNyw5LjQsMiwxMi4xLDJDMTUuNCwyLDE4LDQuNiwxOCw3Ljl2NS45aC00LjVsMCwwLjEKCWMtMC44LDIuNS0zLDQuMS01LjYsNC4xSDJ6IE02LjEsOC4xTDYuMSw4LjFjLTEuNiwwLjctMi42LDIuMy0yLjYsNC4xdjQuNGg0LjRjMS44LDAsMy4zLTEsNC4xLTIuNmwwLTAuMWwtMC4xLDAKCWMtMy4xLTAuMi01LjYtMi43LTUuNy01LjZsMC0wLjFMNi4xLDguMXogTTEyLjEsMy41Yy0xLjgsMC0zLjMsMS00LjEsMi42bDAsMC4xbDAuMSwwYzMuMiwwLjEsNS43LDIuNyw1LjcsNS45djAuMWwwLjEsMC4xaDIuN1Y3LjkKCUMxNi41LDUuNSwxNC41LDMuNSwxMi4xLDMuNXogTTcuNiw3LjhMNy42LDcuOGMwLDIuNSwyLDQuNSw0LjQsNC41aDAuMWwwLjEtMC4xYzAtMi41LTItNC41LTQuNC00LjVINy43TDcuNiw3Ljh6Ii8+Cjwvc3ZnPgo=';

    /**
     * @var TidioIntegrationState
     */
    private $integrationState;
    /**
     * @var TidioIframeSetup
     */
    private $iframeSetup;

    /**
     * @param TidioIntegrationState $integrationState
     * @param TidioIframeSetup $iframeSetup
     */
    public function __construct($integrationState, $iframeSetup)
    {
        $this->integrationState = $integrationState;
        $this->iframeSetup = $iframeSetup;

        add_action('admin_menu', [$this, 'addAdminMenuLink']);
    }

    public function addAdminMenuLink()
    {
        add_menu_page(
            'Tidio Chat',
            'Tidio Chat',
            'manage_options',
            TidioLiveChat::TIDIO_PLUGIN_NAME,
            [$this, 'addAdminPage'],
            'data:image/svg+xml;base64,' . self::TIDIO_ICON_BASE64
        );
    }

    public function addAdminPage()
    {
        $dir = plugin_dir_path(__FILE__);
        if ($this->integrationState->isPluginIntegrated()) {
            $iframeUrl = $this->iframeSetup->prepareIntegrationSuccessIframeUrl();
        } else {
            $iframeUrl = $this->iframeSetup->prepareAuthenticationIframeUrl();
        }

        include $dir . '../../views/iframe.php';
    }
}
