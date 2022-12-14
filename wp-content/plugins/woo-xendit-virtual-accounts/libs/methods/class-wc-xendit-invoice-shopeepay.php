<?php

if (!defined('ABSPATH')) {
    exit;
}

class WC_Xendit_Shopeepay extends WC_Xendit_Invoice
{
    const DEFAULT_MAXIMUM_AMOUNT = 10000000;
    const DEFAULT_MAXIMUM_AMOUNT_PH = 200000;
    const DEFAULT_MINIMUM_AMOUNT = 100;
    const DEFAULT_MINIMUM_AMOUNT_PH = 1;
    const DEFAULT_PAYMENT_DESCRIPTION = 'Bayar pesanan dengan akun ShopeePay anda melalui <strong>Xendit</strong>';
    const DEFAULT_PAYMENT_DESCRIPTION_PH = 'Pay with your ShopeePay via <strong>Xendit</strong>';

    public function __construct()
    {
        parent::__construct();

        $this->id           = 'xendit_shopeepay';

        // Load the form fields.
        $this->init_form_fields();

        // Load the settings.
        $this->init_settings();
        
        // $xendit_settings = get_option( 'woocommerce_xendit_settings' );
        $this->enabled = $this->get_option('enabled');

        $this->method_code = 'shopeepay';
        $this->default_title = 'ShopeePay';
        $this->title = !empty($this->get_option('channel_name')) ? $this->get_option('channel_name') : $this->default_title;
        $this->description = !empty($this->get_option('payment_description')) ? nl2br($this->get_option('payment_description')) : $this->get_xendit_method_description();

        $this->DEFAULT_MAXIMUM_AMOUNT = get_woocommerce_currency() === 'PHP' ? self::DEFAULT_MAXIMUM_AMOUNT_PH : self::DEFAULT_MAXIMUM_AMOUNT;
        $this->DEFAULT_MINIMUM_AMOUNT = get_woocommerce_currency() === 'PHP' ? self::DEFAULT_MINIMUM_AMOUNT_PH : self::DEFAULT_MINIMUM_AMOUNT;

        $this->method_title = __('Xendit ShopeePay', 'woocommerce-gateway-xendit');
        $this->method_description = sprintf(__('Collect payment from ShopeePay on checkout page and get the report realtime on your Xendit Dashboard. <a href="%1$s" target="_blank">Sign In</a> or <a href="%2$s" target="_blank">sign up</a> on Xendit and integrate with <a href="%3$s" target="_blank">your Xendit keys</a>.', 'woocommerce-gateway-xendit'), 'https://dashboard.xendit.co/auth/login', 'https://dashboard.xendit.co/register', 'https://dashboard.xendit.co/settings/developers#api-keys');

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

    public function init_form_fields()
    {
        $this->form_fields = require(WC_XENDIT_PG_PLUGIN_PATH . '/libs/forms/wc-xendit-invoice-shopeepay-settings.php');
    }
    
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