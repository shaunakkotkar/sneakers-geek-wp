<?php

if (!defined('ABSPATH')) {
    exit;
}

class WC_Xendit_DD_BRI extends WC_Xendit_Invoice
{
    const DEFAULT_MAXIMUM_AMOUNT = 50000000;
    const DEFAULT_MINIMUM_AMOUNT = 1;
    const DEFAULT_PAYMENT_DESCRIPTION = 'Bayar pesanan dengan akun Direct Debit BRI anda melalui <strong>Xendit</strong>';

    public function __construct()
    {
        parent::__construct();

        $this->id = 'xendit_dd_bri';

        // Load the form fields.
        $this->init_form_fields();
        
        // Load the settings.
        $this->init_settings();

        $this->enabled = $this->get_option('enabled');

        $this->method_code = 'DD_BRI';
        $this->default_title = 'Direct Debit BRI';
        $this->title = !empty($this->get_option('channel_name')) ? $this->get_option('channel_name') : $this->default_title;
        $this->description = !empty($this->get_option('payment_description')) ? nl2br($this->get_option('payment_description')) : $this->get_xendit_method_description();

        $this->DEFAULT_MAXIMUM_AMOUNT = self::DEFAULT_MAXIMUM_AMOUNT;
        $this->DEFAULT_MINIMUM_AMOUNT = self::DEFAULT_MINIMUM_AMOUNT;

        $this->method_title = __('Xendit Direct Debit BRI', 'woocommerce-gateway-xendit');
        $this->method_description = sprintf(__('Collect payment from Direct Debit BRI on checkout page and get the report realtime on your Xendit Dashboard. <a href="%1$s" target="_blank">Sign In</a> or <a href="%2$s" target="_blank">sign up</a> on Xendit and integrate with <a href="%3$s" target="_blank">your Xendit keys</a>.', 'woocommerce-gateway-xendit'), 'https://dashboard.xendit.co/auth/login', 'https://dashboard.xendit.co/register', 'https://dashboard.xendit.co/settings/developers#api-keys');

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

    /**
     * Initialise Gateway Settings Form Fields
     */
    public function init_form_fields()
    {
        $this->form_fields = require(WC_XENDIT_PG_PLUGIN_PATH . '/libs/forms/wc-xendit-dd-bri-settings.php');
    }

    /**
     * Render admin settings HTML
     *
     * Host some PHP reliant JS to make the form dynamic
     */
    public function admin_options()
    {
        ?>
        <script>
            jQuery(document).ready(function($) {
                $('.channel-name-format').text('<?=$this->title;?>');
                $('#woocommerce_<?=$this->id;?>_channel_name').change(
                    function() {
                        $('.channel-name-format').text($(this).val());
                    }
                );

                var isSubmitCheckDone = false;

                $("button[name='save']").on('click', function(e) {
                    if (isSubmitCheckDone) {
                        isSubmitCheckDone = false;
                        return;
                    }

                    e.preventDefault();

                    var paymentDescription = $('#woocommerce_<?=$this->id;?>_payment_description').val();
                    if (paymentDescription.length > 250) {
                        return swal({
                            text: 'Text is too long, please reduce the message and ensure that the length of the character is less than 250.',
                            buttons: {
                                cancel: 'Cancel'
                            }
                        });
                    } else {
                        isSubmitCheckDone = true;
                    }

                    $("button[name='save']").trigger('click');
                });
            });
        </script>
        <table class="form-table">
            <?php $this->generate_settings_html(); ?>
        </table>
        <?php
    }
}