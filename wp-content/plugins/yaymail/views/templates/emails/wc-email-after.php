<?php

defined( 'ABSPATH' ) || exit;
$sent_to_admin = isset( $args['sent_to_admin'] ) ? $args['sent_to_admin'] : false;
$email         = isset( $args['email'] ) ? $args['email'] : '';
$plain_text    = isset( $args['plain_text'] ) ? $args['plain_text'] : '';
if ( ! class_exists( 'WooCommerce_Germanized' ) && 'yes' !== get_option( 'woocommerce_gzd_display_emails_product_units' )
||  ! class_exists( 'WooCommerce_Germanized' ) && 'yes' !== get_option( 'woocommerce_gzd_display_emails_delivery_time' )
||  ! class_exists( 'WooCommerce_Germanized' ) && 'yes' !== get_option( 'woocommerce_gzd_display_emails_product_item_desc' )
||  ! class_exists( 'WooCommerce_Germanized' ) && 'yes' !== get_option( 'woocommerce_gzd_display_emails_product_attributes' )
||  ! class_exists( 'WooCommerce_Germanized' ) && 'yes' !== get_option( 'woocommerce_gzd_display_emails_unit_price' ) ) {
    do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email );
}
