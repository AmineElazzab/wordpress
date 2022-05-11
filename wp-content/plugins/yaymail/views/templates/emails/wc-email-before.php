<?php

defined( 'ABSPATH' ) || exit;
$sent_to_admin = isset( $args['sent_to_admin'] ) ? $args['sent_to_admin'] : false;
$email         = isset( $args['email'] ) ? $args['email'] : '';
$plain_text    = isset( $args['plain_text'] ) ? $args['plain_text'] : '';
do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email );
