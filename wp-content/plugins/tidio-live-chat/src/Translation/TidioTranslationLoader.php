<?php

class TidioTranslationLoader
{
    public function __construct()
    {
        add_filter('load_textdomain_mofile', [$this, 'loadTextDomain'], 10, 2);
        load_plugin_textdomain(TidioLiveChat::TIDIO_PLUGIN_NAME);
    }

    /**
     * @param string $mofile
     * @param string $domain
     * @return string
     */
    public function loadTextDomain($mofile, $domain)
    {
        if (TidioLiveChat::TIDIO_PLUGIN_NAME === $domain && false !== strpos($mofile, WP_LANG_DIR . '/plugins/')) {
            $userId = get_current_user_id();
            $userLocaleCode = get_user_locale($userId);

            $locale = apply_filters('plugin_locale', $userLocaleCode, $domain);
            $mofile = sprintf('%s/%s/../../languages/%s-%s.mo', WP_PLUGIN_DIR, dirname(plugin_basename(__FILE__)), $domain, $locale);
        }

        return $mofile;
    }
}