<?php

defined( 'ABSPATH' ) || exit;
use YayMail\Page\Source\CustomPostType;
$postID          = CustomPostType::postIDByTemplate( $this->template );
$text_link_color = get_post_meta( $postID, '_yaymail_email_textLinkColor_settings', true ) ? get_post_meta( $postID, '_yaymail_email_textLinkColor_settings', true ) : '#7f54b3';
$sent_to_admin   = isset( $this->sent_to_admin ) ? $this->sent_to_admin : false;
$titleColor      = isset( $atts['titlecolor'] ) && $atts['titlecolor'] ? 'color:' . html_entity_decode( $atts['titlecolor'], ENT_QUOTES, 'UTF-8' ) : 'color:inherit';
?>

<?php
if ( $sent_to_admin ) {
	$before = '<a style="font-weight: normal;' . esc_attr( $titleColor ) . '" class="yaymail_builder_link" href="' . esc_url( $order->get_edit_order_url() ) . '">';
	$after  = '</a>';
	/* translators: %s: Order ID. */
	echo wp_kses_post( $before . sprintf( __( '[Order #%s]', 'woocommerce' ) . $after . ' (<time datetime="%s">%s</time>)', $order->get_order_number(), $order->get_date_created()->format( 'c' ), wc_format_datetime( $order->get_date_created() ) ) );
} else {
	$before = '<h2 style="font-weight: normal;font-size: 18px;' . esc_attr( $titleColor ) . '" class="yaymail_builder_link" >';
	$after  = '</h2>';
	/* translators: %s: Order ID. */
	echo wp_kses_post( $before . sprintf( __( '[Order #%s]', 'woocommerce' ) . ' (<time datetime="%s">%s</time>)', $order->get_order_number(), $order->get_date_created()->format( 'c' ), wc_format_datetime( $order->get_date_created() ) ) . $after );
}
