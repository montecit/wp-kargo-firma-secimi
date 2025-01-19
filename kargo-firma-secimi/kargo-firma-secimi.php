<?php
/**
 * Plugin Name: WooCommerce Kargo Firması Seçimi
 * Plugin URI: https://github.com/montecit/kargo-firma-secimi
 * Description: WooCommerce ödeme sayfasına kargo firması seçimi ekleyen bir eklenti.
 * Version: 1.0
 * Author: Makeitdigi Dijital Ajans
 * Author URI: https://makeitdigi.com
 * License: GPL2
 * Text Domain: kargo-firma-secimi
 * Domain Path: /languages
 */

// Ödeme sayfasına kargo firması seçeneği ekleyin
add_action('woocommerce_after_order_notes', 'add_shipping_company_field');
function add_shipping_company_field($checkout) {
    echo '<div id="shipping_company_field"><h3>' . __('Kargo Firması Seçimi', 'kargo-firma-secimi') . '</h3>';

    woocommerce_form_field('shipping_company', [
        'type'    => 'select',
        'class'   => ['shipping-company-field form-row-wide'],
        'label'   => __('Lütfen bir kargo firması seçin:', 'kargo-firma-secimi'),
        'options' => [
            ''             => __('Kargo firması seçiniz', 'kargo-firma-secimi'),
            'aras_kargo'   => 'Aras Kargo',
            'mng_kargo'    => 'MNG Kargo',
            'yurtici_kargo'=> 'Yurtiçi Kargo',
        ],
        'required' => true,
    ], $checkout->get_value('shipping_company'));

    echo '</div>';
}

// Seçilen kargo firmasını siparişe kaydet
add_action('woocommerce_checkout_update_order_meta', 'save_shipping_company_field');
function save_shipping_company_field($order_id) {
    if (!empty($_POST['shipping_company'])) {
        update_post_meta($order_id, 'Kargo Firması', sanitize_text_field($_POST['shipping_company']));
    }
}

// Sipariş detaylarında seçilen kargo firmasını göster
add_action('woocommerce_admin_order_data_after_shipping_address', 'display_shipping_company_in_admin', 10, 1);
function display_shipping_company_in_admin($order) {
    $shipping_company = get_post_meta($order->get_id(), 'Kargo Firması', true);
    if ($shipping_company) {
        echo '<p><strong>' . __('Seçilen Kargo Firması', 'kargo-firma-secimi') . ':</strong> ' . esc_html($shipping_company) . '</p>';
    }
}

// Müşteri sipariş detaylarında kargo firmasını göster
add_action('woocommerce_order_details_after_order_table', 'display_shipping_company_in_order_details', 10, 1);
function display_shipping_company_in_order_details($order) {
    $shipping_company = get_post_meta($order->get_id(), 'Kargo Firması', true);
    if ($shipping_company) {
        echo '<p><strong>' . __('Seçilen Kargo Firması', 'kargo-firma-secimi') . ':</strong> ' . esc_html($shipping_company) . '</p>';
    }
}
