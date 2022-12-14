<?php

class TidioWidgetLoader
{
    /**
     * @var TidioIntegrationState
     */
    private $integrationState;

    /**
     * @param TidioIntegrationState $integrationState
     */
    public function __construct($integrationState)
    {
        $this->integrationState = $integrationState;

        if (!$this->integrationState->isPluginIntegrated()) {
            return;
        }

        add_action('wp_head', [$this,'addPreconnectLink']);
        if ($this->integrationState->isAsyncLoadingTurnedOn()) {
            add_action('wp_footer', [$this, 'enqueueScriptsAsync'], PHP_INT_MAX);
            return;
        }

        add_action('wp_enqueue_scripts', [$this, 'enqueueScriptsSync'], 1000);
    }

    public function addPreconnectLink() {
        echo '<link rel="preconnect" href="//code.tidio.co">';
    }

    public function enqueueScriptsAsync()
    {
        $publicKey = $this->integrationState->getProjectPublicKey();
        $widgetUrl = sprintf('%s/%s.js', TidioLiveChatConfig::getWidgetUrl(), $publicKey);
        $asyncScript = <<<SRC
<script type='text/javascript'>
document.tidioChatCode = "$publicKey";
(function() {
  function asyncLoad() {
    var tidioScript = document.createElement("script");
    tidioScript.type = "text/javascript";
    tidioScript.async = true;
    tidioScript.src = "{$widgetUrl}";
    document.body.appendChild(tidioScript);
  }
  if (window.attachEvent) {
    window.attachEvent("onload", asyncLoad);
  } else {
    window.addEventListener("load", asyncLoad, false);
  }
})();
</script>
SRC;
        echo $asyncScript;
    }

    public function enqueueScriptsSync()
    {
        $projectPublicKey = $this->integrationState->getProjectPublicKey();

        $widgetUrl = sprintf('%s/%s.js', TidioLiveChatConfig::getWidgetUrl(), $projectPublicKey);
        wp_enqueue_script(TidioLiveChat::TIDIO_PLUGIN_NAME, $widgetUrl, [], TIDIOCHAT_VERSION, true);

        $inlineScriptWithProjectPublicKeyVariable = sprintf('document.tidioChatCode = "%s";', $projectPublicKey);
        wp_add_inline_script(TidioLiveChat::TIDIO_PLUGIN_NAME, $inlineScriptWithProjectPublicKeyVariable, 'before');
    }
}