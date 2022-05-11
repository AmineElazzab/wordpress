<?php
defined( 'ABSPATH' ) || exit;
use YayMail\Page\Source\CustomPostType;
$postID               = CustomPostType::postIDByTemplate( $this->template );
$order_item_title     = get_post_meta( $postID, '_yaymail_email_order_item_title', true );
$product_title        = false != $order_item_title ? $order_item_title['product_title'] : 'Product';
$quantity_title       = false != $order_item_title ? $order_item_title['quantity_title'] : 'Quantity';
$price_title          = false != $order_item_title ? $order_item_title['price_title'] : 'Price';
$subtoltal_title      = false != $order_item_title ? $order_item_title['subtoltal_title'] : 'Subtotal:';
$payment_method_title = false != $order_item_title ? $order_item_title['payment_method_title'] : 'Payment method:';
$total_title          = false != $order_item_title ? $order_item_title['total_title'] : 'Total:';
$borderColor          = isset( $atts['bordercolor'] ) && $atts['bordercolor'] ? 'border-color:' . html_entity_decode( $atts['bordercolor'], ENT_QUOTES, 'UTF-8' ) : 'border-color:inherit';
$textColor            = isset( $atts['textcolor'] ) && $atts['textcolor'] ? 'color:' . html_entity_decode( $atts['textcolor'], ENT_QUOTES, 'UTF-8' ) : 'color:inherit';
?>
<!-- <table class="yaymail_builder_table_items_border" cellspacing="0" cellpadding="6" border="1" style="width: 100% !important; border-color: inherit;color: inherit" width="100%"> -->
	<!-- <thead> -->
		<tr style="word-break: normal">
			<th class="td yaymail_item_product_title" scope="col" style="text-align:left;vertical-align: middle;padding: 12px;font-size: 14px;border-width: 1px;border-style: solid;<?php echo esc_attr( $borderColor ); ?>;<?php echo esc_attr( $textColor ); ?>">
				<?php esc_html_e( $product_title, 'woocommerce' ); ?>
			</th>
			<th class="td yaymail_item_quantity_title" scope="col" style="text-align:left;vertical-align: middle;padding: 12px;font-size: 14px;border-width: 1px;border-style: solid;<?php echo esc_attr( $borderColor ); ?>;<?php echo esc_attr( $textColor ); ?>">
				<?php esc_html_e( $quantity_title, 'woocommerce' ); ?>
			</th>
			<th class="td yaymail_item_price_title" scope="col" style="text-align:left;vertical-align: middle;padding: 12px;font-size: 14px;border-width: 1px;border-style: solid;<?php echo esc_attr( $borderColor ); ?>;<?php echo esc_attr( $textColor ); ?>">
				<?php esc_html_e( $price_title, 'woocommerce' ); ?>
			</th>
		</tr>
	<!-- </thead>
	<tbody> -->
		<tr>
			<th class="td" scope="row" style="font-weight: normal;text-align:left;vertical-align: middle;padding: 12px;font-size: 14px;border-width: 1px;border-style: solid;<?php echo esc_attr( $borderColor ); ?>;<?php echo esc_attr( $textColor ); ?>">
				<?php esc_html_e( 'Happy YayCommerce', 'yaymail' ); ?>
			</th>
			<th class="td" scope="row" style="font-weight: normal;text-align:left;vertical-align: middle;padding: 12px;font-size: 14px;border-width: 1px;border-style: solid;<?php echo esc_attr( $borderColor ); ?>;<?php echo esc_attr( $textColor ); ?>">
				<?php esc_html_e( 1, 'yaymail' ); ?>
			<th class="td" scope="row" style="font-weight: normal;text-align:left;vertical-align: middle;padding: 12px;font-size: 14px;border-width: 1px;border-style: solid;<?php echo esc_attr( $borderColor ); ?>;<?php echo esc_attr( $textColor ); ?>">
				<?php esc_html_e( '£18.00', 'yaymail' ); ?>
			</th>
		</tr>
	<!-- </tbody>
	<tfoot> -->
		<tr>
			<th class="td yaymail_item_subtoltal_title" scope="row" colspan="2" style="text-align:left;font-weight: bold;vertical-align: middle;padding: 12px;font-size: 14px;border-width: 1px;border-style: solid;<?php echo esc_attr( $borderColor ); ?>;<?php echo esc_attr( $textColor ); ?> ;border-top-width: 4px;">
				<?php esc_html_e( $subtoltal_title, 'woocommerce' ); ?>
			</th>
			<th class="td" scope="row" colspan="1" style="font-weight: normal;text-align:left;vertical-align: middle;padding: 12px;font-size: 14px;border-width: 1px;border-style: solid;<?php echo esc_attr( $borderColor ); ?>;<?php echo esc_attr( $textColor ); ?>; border-top-width: 4px;">
				<?php esc_html_e( '£18.00', 'yaymail' ); ?>
			</th>
		</tr>
		<tr>
			<th class="td yaymail_item_payment_method_title" scope="row" colspan="2" style="text-align:left; font-weight: bold;vertical-align: middle;padding: 12px;font-size: 14px;border-width: 1px;border-style: solid;<?php echo esc_attr( $borderColor ); ?>;<?php echo esc_attr( $textColor ); ?>">
				<?php esc_html_e( $payment_method_title, 'woocommerce' ); ?>
			</th>
			<th class="td" scope="row" colspan="1" style="font-weight: normal;text-align:left;vertical-align: middle;padding: 12px;font-size: 14px;border-width: 1px;border-style: solid;<?php echo esc_attr( $borderColor ); ?>;<?php echo esc_attr( $textColor ); ?>">
				<?php esc_html_e( 'Direct bank transfer', 'woocommerce' ); ?>
			</th>
		</tr>
		<tr>
			<th class="td yaymail_item_total_title" scope="row" colspan="2" style="text-align:left; font-weight: bold;vertical-align: middle;padding: 12px;font-size: 14px;border-width: 1px;border-style: solid;<?php echo esc_attr( $borderColor ); ?>;<?php echo esc_attr( $textColor ); ?>">
				<?php esc_html_e( $total_title, 'woocommerce' ); ?>
			</th>
			<th class="td" scope="row" colspan="1" style="font-weight: normal;text-align:left;vertical-align: middle;padding: 12px;font-size: 14px;border-width: 1px;border-style: solid;<?php echo esc_attr( $borderColor ); ?>;<?php echo esc_attr( $textColor ); ?>">
				<?php esc_html_e( '£18.00', 'yaymail' ); ?>
			</th>
		</tr>
	<!-- </tfoot>
</table> -->
