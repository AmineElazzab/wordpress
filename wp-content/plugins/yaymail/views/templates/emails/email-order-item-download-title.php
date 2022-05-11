<?php

defined( 'ABSPATH' ) || exit;
$titleColor = isset( $atts['titlecolor'] ) && $atts['titlecolor'] ? 'color:' . html_entity_decode( $atts['titlecolor'], ENT_QUOTES, 'UTF-8' ) : 'color:inherit';
?>

<!-- Table Items has Border -->
<?php
if ( isset( $downloads ) && ! empty( $downloads ) || null === $order ) {
	?>
<h2 style="margin: 13px 0px;<?php echo esc_attr( $titleColor ); ?>" class="woocommerce-order-downloads__title"><?php esc_html_e( 'Downloads', 'woocommerce' ); ?></h2>
	<?php
}
?>
