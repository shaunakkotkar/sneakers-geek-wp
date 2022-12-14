<?php

if (!defined('ABSPATH')) {
    exit;
}

final class WC_Xendit_PG_Helper
{
    public static function is_wc_lt($version)
    {
        return version_compare(WC_VERSION, $version, '<');
    }

    public static function get_order_id($order)
    {
        return self::is_wc_lt('3.0') ? $order->id : $order->get_id();
    }

    public static function generate_items_and_customer($order, $total_amount, $type = '')
    {
        global $woocommerce;

        if (!is_object($order)) {
            return;
        }

        $items = array();
        foreach ($order->get_items() as $item_data) {
            if (!is_object($item_data)) {
                continue;
            }

            // Get an instance of WC_Product object
            $product = $item_data->get_product();
            if (!is_object($product)) {
                continue;
            }

            // Get all category names of item
            $category_names = wp_get_post_terms($item_data->get_product_id(), 'product_cat', ['fields' => 'names']);
            $category_name = !empty($category_names) ? implode(', ', $category_names) : 'n/a';

            $item = array();
            $item['id']         = $product->get_id();
            $item['name']       = $product->get_name();
            $item['price']      = !empty($product->get_price()) ? floatval($product->get_price()) : 0;
            $item['type']       = "PRODUCT";
            $item['quantity']   = $item_data->get_quantity();
            $item['url']        = !empty(get_permalink($item['id'])) ? get_permalink($item['id']) : 'https://xendit.co/';
            $item['category']   = $category_name;
            array_push($items, json_encode(array_map('strval', $item)));
        }

        $customer = array();
        $email = !empty($order->get_billing_email()) ? $order->get_billing_email() : 'noreply@mail.com';
        $customer['given_names']            = $order->get_billing_first_name() ? $order->get_billing_first_name() : 'N/A';
        $customer['surname']                = $order->get_billing_last_name() ? $order->get_billing_last_name() : 'N/A';
        $customer['email']                  = $email;
        $customer['mobile_number']          = $order->get_billing_phone() ? $order->get_billing_phone() : '';
        $customer['addresses']              = array(
                (object) array_filter(array(
                    'country'       => $order->get_billing_country() ? $order->get_billing_country() : "N/A",
                    'street_line1'  => $order->get_billing_address_1() ? $order->get_billing_address_1() : "N/A",
                    'street_line2'  => $order->get_billing_address_2() ? $order->get_billing_address_2() : "N/A",
                    'city'          => $order->get_billing_city() ? $order->get_billing_city() : "N/A",
                    'state'         => $order->get_billing_state() ? $order->get_billing_state() : "N/A",
                    'postal_code'   => $order->get_billing_postcode() ? $order->get_billing_postcode() : "N/A"
                ))
        );

        return array(
            'items' => '[' . implode(",", $items) . ']',
            'customer' => json_encode($customer)
        );
    }

    public static function build_order_notes($transaction_id, $status, $payment_method, $currency, $amount, $installment = '')
    {
        $notes  = "Transaction ID: " . $transaction_id . "<br>";
        $notes .= "Status: " . $status . "<br>";
        $notes .= "Payment Method: " . str_replace("_", " ", $payment_method) . "<br>";
        $notes .= "Amount: " . $currency . " " . number_format($amount);

        if ($installment) {
            $notes .= " (" . $installment . " installment)";
        }

        return $notes;
    }

    public static function complete_payment($order, $notes, $success_payment_status = 'processing', $transaction_id = '')
    {
        global $woocommerce;

        // Add a default payment status.
        // Our default value doesn't working properly on some merchant's site.
        if (empty($success_payment_status)) {
            $success_payment_status = "processing";
        }

        $order->add_order_note('<b>Xendit payment successful.</b><br>' . $notes);
        $order->payment_complete($transaction_id);

        $order_id = self::get_order_id($order);

        $re_get_order = new WC_Order($order_id);
        if ($re_get_order->get_status() != $success_payment_status) {
            $re_get_order->set_status($success_payment_status, '--');
            $re_get_order->save();
        }

        // Reduce stock levels
        version_compare(WC_VERSION, '3.0.0', '<') ? $order->reduce_order_stock() : wc_reduce_stock_levels($order_id);
    }

    public static function cancel_order($order, $note)
    {
        $order->update_status('wc-cancelled');
        $order->add_order_note($note);
    }

    public function validate_form($data)
    {
        global $wpdb, $woocommerce;

        $countries = new WC_Countries();
        $result = [];
        $billlingfields = $countries->get_address_fields($countries->get_base_country(), 'billing_');
        $shippingfields = $countries->get_address_fields($countries->get_base_country(), 'shipping_');
        foreach ($billlingfields as $key => $val) {
            if ($val['required'] == 1) {
                if (empty($data[$key])) {
                    array_push($result, 'Billing '.$val['label'].' is a required field.');
                }
            }
        }

        foreach ($shippingfields as $key => $val) {
            if ($val['required'] === 1) {
                if (empty($data[$key])) {
                    array_push($result, 'Shipping '.$val['label'].' is a required field.');
                }
            }
        }

        return (count($result) > 0) ? array('error_code' => 'VALIDATION_ERROR', 'message' => $result) : $result;
    }

    /**
     * Create or update customer object
     *
     * * @since 2.18.0
     */
    public static function process_customer_object($reference_id = '', $payload = array())
    {
        global $wpdb, $woocommerce;
        $xenditClass = new WC_Xendit_PG_API();
        // search for existing customer
        $customer = $xenditClass->getCustomerByReferenceId($reference_id);

        $response;
        if ($payload) {
            if (empty($customer)) { // create new customer
                $response = $xenditClass->createCustomer($payload);
            } else { // update customer data
                $payload->reference_id = $customer['reference_id'];
                $response =  $xenditClass->updateCustomer($customer['id'], $payload);
            }
        }

        return $response;
    }

    /**
     * Generate customer
     *
     * * @since 2.18.0
     */
    public static function generate_customer($order)
    {
        global $woocommerce;

        if (!is_object($order)) {
            return;
        }

        $customer = array();
        $customer['reference_id']       = (!empty($order->get_billing_email())) ? $order->get_billing_email() : $order->get_billing_phone();
        $customer['individual_detail']  =
            (object) array_filter(array(
                'given_names'   => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                'surname'       => $order->get_billing_first_name(),
                'nationality'   => $order->get_billing_country()
            ));
        $customer['email']              = !empty($order->get_billing_email()) ? $order->get_billing_email() : 'noreply@mail.com';
        $customer['phone_number']       = $order->get_billing_phone();
        $customer['addresses']          = array(
            (object) array_filter(array(
                'country'       => $order->get_billing_country(),
                'street_line1'  => $order->get_billing_address_1(),
                'street_line2'  => $order->get_billing_address_2(),
                'city'          => $order->get_billing_city(),
                'province_state'=> $order->get_billing_state(),
                'postal_code'   => $order->get_billing_postcode()
            ))
        );

        return (object) array_filter($customer);
    }
}
