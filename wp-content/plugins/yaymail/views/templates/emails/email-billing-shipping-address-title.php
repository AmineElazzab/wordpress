<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! empty( $billing_address ) && ! empty( $shipping_address ) ) {
	$width = '50%';
} else {
		$width = '100%';
}

$title_shipping = get_post_meta( $postID, '_email_title_shipping', true ) ? get_post_meta( $postID, '_email_title_shipping', true ) : 'Shipping address';
$title_billing  = get_post_meta( $postID, '_email_title_billing', true ) ? get_post_meta( $postID, '_email_title_billing', true ) : 'Billing address';
$fontFamily     = isset( $atts['fontfamily'] ) && $atts['fontfamily'] ? 'font-family:' . html_entity_decode( $atts['fontfamily'], ENT_QUOTES, 'UTF-8' ) : 'font-family:inherit';
?>
	<tr>
	<?php if ( ! empty( $billing_address ) ) { ?>
		<td style="width: <?php echo esc_attr( $width ); ?>;">
			<h2 class="title-billing" style="color: inherit; display: block; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 10px 18px 0;<?php echo esc_attr( $fontFamily ); ?>">
				<?php esc_html_e( $title_billing, 'woocommerce' ); ?>
			</h2>
	</td>
		<?php } ?>
		<?php if ( ! empty( $shipping_address ) ) { ?>
		<td style="width: <?php echo esc_attr( $width ); ?>;">
			<h2 class="title-shipping" style="color: inherit; display: block; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px;<?php echo esc_attr( $fontFamily ); ?>">
				<?php esc_html_e( $title_shipping, 'woocommerce' ); ?>
			</h2>
		</td>
		<?php } ?>
	</tr>
