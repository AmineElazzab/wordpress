<?php

defined( 'ABSPATH' ) || exit;
use YayMail\Page\Source\CustomPostType;
$text_align            = is_rtl() ? 'right' : 'left';
$sent_to_admin         = ( isset( $sent_to_admin ) ? true : false );
$plain_text            = ( isset( $plain_text ) ? $plain_text : '' );
$email                 = ( isset( $email ) ? $email : '' );
$postID                = CustomPostType::postIDByTemplate( $this->template );
$order_item_title      = get_post_meta( $postID, '_yaymail_email_order_item_title', true );
$product_title         = false != $order_item_title ? $order_item_title['product_title'] : 'Product';
$quantity_title        = false != $order_item_title ? $order_item_title['quantity_title'] : 'Quantity';
$price_title           = false != $order_item_title ? $order_item_title['price_title'] : 'Price';
$subtoltal_title       = false != $order_item_title ? $order_item_title['subtoltal_title'] : 'Subtotal:';
$payment_method_title  = false != $order_item_title ? $order_item_title['payment_method_title'] : 'Payment method:';
$fully_refunded        = false != $order_item_title && isset( $order_item_title['fully_refunded'] ) ? $order_item_title['fully_refunded'] : 'Order fully refunded.';
$total_title           = false != $order_item_title ? $order_item_title['total_title'] : 'Total:';
$get_order_item_totals = array(
	'cart_subtotal'  => $subtoltal_title,
	'payment_method' => $payment_method_title,
	'order_total'    => $total_title,
	'refund_0'       => $fully_refunded,
);

$get_order_item_totals_class = array(
	'cart_subtotal'  => 'yaymail_item_subtoltal_title',
	'payment_method' => 'yaymail_item_payment_method_title',
	'shipping'       => 'yaymail_item_shipping_title',
	'order_total'    => 'yaymail_item_total_title',
	'refund_0'       => 'yaymail_item_fully_refunded',
	'tax'            => 'yaymail_item_tax',
);
$borderColor                 = isset( $atts['bordercolor'] ) && $atts['bordercolor'] ? 'border-color:' . html_entity_decode( $atts['bordercolor'], ENT_QUOTES, 'UTF-8' ) : 'border-color:inherit';
$textColor                   = isset( $atts['textcolor'] ) && $atts['textcolor'] ? 'color:' . html_entity_decode( $atts['textcolor'], ENT_QUOTES, 'UTF-8' ) : 'color:inherit';
?>



		<tr style="word-break: normal">
			<th class="td yaymail_item_product_title" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;vertical-align: middle;padding: 12px;font-size: 14px;border-width: 1px;border-style: solid;<?php echo esc_attr( $borderColor ); ?>;">
				<?php esc_html_e( $product_title, 'woocommerce' ); ?>
			</th>
			<th class="td yaymail_item_quantity_title" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;vertical-align: middle;padding: 12px;font-size: 14px;border-width: 1px;border-style: solid;<?php echo esc_attr( $borderColor ); ?>;">
				<?php esc_html_e( $quantity_title, 'woocommerce' ); ?>
			</th>
			<th class="td yaymail_item_price_title" scope="col" style="width: 30%;text-align:<?php echo esc_attr( $text_align ); ?>;vertical-align: middle;padding: 12px;font-size: 14px;border-width: 1px;border-style: solid;<?php echo esc_attr( $borderColor ); ?>;">
				<?php esc_html_e( $price_title, 'woocommerce' ); ?>
			</th>
		</tr>

		<?php
		echo wp_kses_post(
			$this->ordetItemTables(
				$order,
				array(
					'show_sku'      => $sent_to_admin,
					'show_image'    => false,
					'image_size'    => array( 32, 32 ),
					'plain_text'    => $plain_text,
					'sent_to_admin' => $sent_to_admin,
					'border_color'  => $borderColor,
					'text_color'    => $textColor,
				)
			)
		);

		?>

		<?php
		$totalItem = $order->get_order_item_totals();
		$i         = 0;
		foreach ( $totalItem as $key => $total ) {
			$i++;
			?>
		<tr class="<?php
			if ( array_key_exists( $key, $get_order_item_totals_class ) ) {
				if ( 'refund_0' == $key ) {
					if ( __( 'Order fully refunded.', 'woocommerce' ) == $total['label'] ) {
						echo esc_attr( $get_order_item_totals_class[ $key ] . '_row' );
					}
				} else {
					echo esc_attr( $get_order_item_totals_class[ $key ] . '_row' );
				}
			}
			?>">
			<th class="td <?php
			if ( array_key_exists( $key, $get_order_item_totals_class ) ) {
				if ( 'refund_0' == $key ) {
					if ( __( 'Order fully refunded.', 'woocommerce' ) == $total['label'] ) {
						echo esc_attr( $get_order_item_totals_class[ $key ] );
					}
				} else {
					echo esc_attr( $get_order_item_totals_class[ $key ] );
				}
			}
			?>" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;vertical-align: middle;padding: 12px;font-size: 14px;border-width: 1px;border-style: solid;<?php echo esc_attr( $borderColor ); ?>; <?php echo esc_attr( ( 1 === $i ) ? 'border-top-width: 4px;' : '' ); ?>">
				<?php
				if ( array_key_exists( $key, $get_order_item_totals ) ) {
					if ( 'refund_0' == $key ) {
						if ( __( 'Order fully refunded.', 'woocommerce' ) == $total['label'] ) {
							echo esc_html_e( $get_order_item_totals[ $key ], 'woocommerce' );
						} else {
							echo esc_html_e( $total['label'], 'woocommerce' );
						}
					} else {
						echo esc_html_e( $get_order_item_totals[ $key ], 'woocommerce' );
					}
				} else {
					echo esc_html_e( $total['label'], 'woocommerce' );
				}
				?>
			</th>
			<th class="td" style="font-weight: normal;text-align:<?php echo esc_attr( $text_align ); ?>;vertical-align: middle;padding: 12px;font-size: 14px;border-width: 1px;border-style: solid;<?php echo esc_attr( $borderColor ); ?>; <?php echo esc_attr( ( 1 === $i ) ? 'border-top-width: 4px;' : '' ); ?>">
			<?php echo wp_kses_post( $total['value'] ); ?>
			</th>
		</tr>

			<?php
		}

		if ( ! empty( $order->get_customer_note() ) ) {
			$note = $order->get_customer_note();
			?>

			<tr>
				<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;vertical-align: middle;padding: 12px;font-size: 14px;border-width: 1px;border-style: solid;<?php echo esc_attr( $borderColor ); ?>; <?php echo esc_attr( ( 1 === $i ) ? 'border-top-width: 4px;' : '' ); ?>">
					<?php esc_html_e( 'Note:', 'woocommerce' ); ?>
				</th>
				<th class="td" style="font-weight: normal;text-align:<?php echo esc_attr( $text_align ); ?>;vertical-align: middle;padding: 12px;font-size: 14px;border-width: 1px;border-style: solid;<?php echo esc_attr( $borderColor ); ?>; <?php echo esc_attr( ( 1 === $i ) ? 'border-top-width: 4px;' : '' ); ?>">
					<?php echo esc_html( $note ); ?>
				</th>
			</tr>

		<?php } ?>
