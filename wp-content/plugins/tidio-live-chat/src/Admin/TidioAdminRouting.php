<?php

class TidioAdminRouting
{
    const CLEAR_ACCOUNT_DATA_ACTION = 'tidio-live-chat-clear-account-data';
    const INTEGRATE_PROJECT_ACTION = 'tidio-live-chat-integrate-project';
    const TOGGLE_ASYNC_LOADING_ACTION = 'tidio-live-chat-toggle-async-loading';

    /**
     * @param TidioAdminController $adminController
     */
    public function __construct($adminController)
    {
        add_action('admin_post_' . self::INTEGRATE_PROJECT_ACTION, [$adminController, 'handleIntegrateProjectAction']);
        add_action('admin_post_' . self::TOGGLE_ASYNC_LOADING_ACTION, [$adminController, 'handleToggleAsyncLoadingAction']);
        add_action('admin_post_' . self::CLEAR_ACCOUNT_DATA_ACTION, [$adminController, 'handleClearAccountDataAction']);
    }

    /**
     * @return string
     */
    public static function getEndpointForIntegrateProjectAction()
    {
        return self::getEndpointForAction(self::INTEGRATE_PROJECT_ACTION);
    }

    /**
     * @return string
     */
    public static function getEndpointForToggleAsyncLoadingAction()
    {
        return self::getEndpointForAction(self::TOGGLE_ASYNC_LOADING_ACTION);
    }

    /**
     * @return string
     */
    public static function getEndpointForClearAccountDataAction()
    {
        return self::getEndpointForAction(self::CLEAR_ACCOUNT_DATA_ACTION);
    }

    /**
     * @param string $action
     * @return string
     */
    private static function getEndpointForAction($action)
    {
        $queryString = http_build_query([
            'action' => $action,
            '_wpnonce' => wp_create_nonce($action),
        ]);
        return admin_url('admin-post.php?' . $queryString);
    }
}