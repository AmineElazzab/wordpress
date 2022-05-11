<?php

defined( 'ABSPATH' ) || exit;
use YayMail\Page\Source\CustomPostType;
$sent_to_admin   = ( isset( $sent_to_admin ) ? $sent_to_admin : false );
$email           = ( isset( $email ) ? $email : '' );
$plain_text      = ( isset( $plain_text ) ? $plain_text : '' );
$text_align      = is_rtl() ? 'right' : 'left';
$postID          = CustomPostType::postIDByTemplate( $this->template );
$text_link_color = get_post_meta( $postID, '_yaymail_email_textLinkColor_settings', true ) ? get_post_meta( $postID, '_yaymail_email_textLinkColor_settings', true ) : '#7f54b3';
// instructions payment
$paymentGateways  = wc_get_payment_gateway_by_order( $order );
$yaymail_settings = get_option( 'yaymail_settings' );
$cash_on_delivery = esc_html__( 'Cash on delivery', 'woocommerce' );

if ( ( 'customer_on_hold_order' === $this->template
	|| 'customer_processing_order' === $this->template
	|| 'customer_completed_order' === $this->template
	|| 'customer_refunded_order' === $this->template
	|| 'customer_invoice' === $this->template
	|| 'customer_note' === $this->template
	|| 'customer_partial_shipped_order' === $this->template
	|| 'customer_shipped_order' === $this->template )
	&& 2 == $yaymail_settings['payment']
	|| ( isset( $paymentGateways->method_title ) ? $cash_on_delivery == $paymentGateways->method_title : false
	&& 'cancelled_order' != $this->template
	&& 'new_order' != $this->template
	&& 'failed_order' != $this->template
	&& 'customer_new_account' != $this->template
	&& 'customer_reset_password' != $this->template )
) {?>

	<p style="text-align: <?php echo esc_attr( $text_align ); ?>; margin: 0 0 16px;" class="yaymail_builder_instructions">
	<?php echo wp_kses_post( isset( $paymentGateways->instructions ) ? $paymentGateways->instructions : '', 'woocommerce' ); ?>
	</p>

<?php } elseif ( 1 == $yaymail_settings['payment'] ) { ?>

	<p style="margin: 0 0 16px;" class="yaymail_builder_instructions">
	<?php echo wp_kses_post( isset( $paymentGateways->instructions ) ? $paymentGateways->instructions : '', 'woocommerce' ); ?>
	</p>

	<?php
}

/*
Our bank details
payment: Direct bank transfer
 */
if ( false != $paymentGateways && isset( $paymentGateways->account_details ) ) {
	$account_details      = $paymentGateways->account_details;
	$texts                = array(
		'bank_name'      => 'Bank',
		'account_number' => 'Account number',
		'sort_code'      => 'Sort code',
		'iban'           => 'IBAN',
		'bic'            => 'BIC',
	);
	$direct_bank_transfer = esc_html__( 'Direct bank transfer', 'woocommerce' );

	if ( ( 'customer_on_hold_order' === $this->template
		|| 'customer_processing_order' === $this->template
		|| 'customer_completed_order' === $this->template
		|| 'customer_refunded_order' === $this->template
		|| 'customer_invoice' === $this->template
		|| 'customer_note' === $this->template
		|| 'customer_partial_shipped_order' === $this->template
		|| 'customer_shipped_order' === $this->template )
		&& $direct_bank_transfer == $paymentGateways->method_title
		&& is_array( $account_details )
		&& count( $account_details ) > 0
		&& 1 == $yaymail_settings['payment']
	) {
		?>

		<section style="text-align: ' . $text_align . '" class="yaymail_builder_wrap_account">
			<h2 class="yaymail_builder_bank_details" style="color: #7f54b3;">
		<?php esc_html_e( 'Our bank details', 'woocommerce' ); ?>
			</h2>

		<?php
		foreach ( $account_details as $accounts ) {
			foreach ( $accounts as $label_name => $infor_account ) {
				if ( 'account_name' === $label_name && ! empty( $infor_account ) ) {
					?>
						<h3 class="yaymail_builder_account_name" style="color: #7f54b3;">
					<?php
					esc_html_e( $infor_account, 'woocommerce' );
					?>
						</h3>
					<?php
				}
			}
			?>

				<ul>
			<?php
			foreach ( $accounts as $label_name => $infor_account ) {
				if ( 'account_name' !== $label_name && ! empty( $infor_account ) ) {
					?>

							<li><?php esc_html_e( $texts[ $label_name ], 'woocommerce' ); ?>:
								<strong><?php esc_html_e( $infor_account, 'woocommerce' ); ?></strong>
							</li>

					<?php
				}
			}
			?>
				</ul>

		<?php } ?>

		</section>
		<?php
	}
}
?>

<!-- Title Table Order Items -->
<div class="yaymail_builder_order" style="color: #7f54b3;font-size: 18px;font-weight: 700;">
	<?php
	if ( $sent_to_admin ) {
		$before = '<a style="font-weight: normal;color: ' . esc_attr( $text_link_color ) . '" class="yaymail_builder_link" href="' . esc_url( $order->get_edit_order_url() ) . '">';
		$after  = '</a>';
		/* translators: %s: Order ID. */
		echo wp_kses_post( $before . sprintf( __( '[Order #%s]', 'woocommerce' ) . $after . ' (<time datetime="%s">%s</time>)', $order->get_order_number(), $order->get_date_created()->format( 'c' ), wc_format_datetime( $order->get_date_created() ) ) );
	} else {
		$before = '<h2 style="font-weight: normal;' . esc_attr( $text_link_color ) . '" class="yaymail_builder_link" >';
		$after  = '</h2>';
		/* translators: %s: Order ID. */
		echo wp_kses_post( $before . sprintf( __( '[Order #%s]', 'woocommerce' ) . ' (<time datetime="%s">%s</time>)', $order->get_order_number(), $order->get_date_created()->format( 'c' ), wc_format_datetime( $order->get_date_created() ) ) . $after );
	}
	?>
</div>

<!-- Table Items has Border -->
<table class="yaymail_builder_table_items_content" cellspacing="0" cellpadding="6" border="1" style="width: 100% !important;color: inherit;flex-direction:column;border: 1px solid;border-color: inherit;" width="100%">
	<thead>
		<tr style="word-break: normal">
			<th class="td" scope="col" style="text-align:left;vertical-align: middle;padding: 12px;font-size: 14px;border: 1px solid;border-color: inherit;">
				<?php esc_html_e( 'Product', 'woocommerce' ); ?>
			</th>
			<th class="td" scope="col" style="text-align:left;vertical-align: middle;padding: 12px;font-size: 14px;border: 1px solid;border-color: inherit;">
				<?php esc_html_e( 'Quantity', 'woocommerce' ); ?>
			</th>
			<th class="td" scope="col" style="text-align:left; width: 30%;vertical-align: middle;padding: 12px;font-size: 14px;border: 1px solid;border-color: inherit;">
				<?php esc_html_e( 'Price', 'woocommerce' ); ?>
			</th>
		</tr>
	</thead>

	<tbody style="flex-direction:inherit;" >
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
				)
			)
		);

		?>
	</tbody>

	<tfoot>
		<?php
		$totalItem = $order->get_order_item_totals();
		$i         = 0;
		foreach ( $totalItem as $key => $total ) {
			$i++;
			?>

		<tr>
			<th class="td" scope="row" colspan="2" style="text-align:left;vertical-align: middle;padding: 12px;font-size: 14px;border: 1px solid;border-color: inherit; <?php echo esc_attr( ( 1 === $i ) ? 'border-top-width: 4px;' : '' ); ?>">
			<?php echo esc_html( $total['label'] ); ?>
			</th>
			<td class="td" style="text-align:left;vertical-align: middle;padding: 12px;font-size: 14px;border: 1px solid;border-color: inherit; <?php echo esc_attr( ( 1 === $i ) ? 'border-top-width: 4px;' : '' ); ?>">
			<?php echo wp_kses_post( $total['value'] ); ?>
			</td>
		</tr>

			<?php
		}

		if ( ! empty( $order->get_customer_note() ) ) {
			$note = $order->get_customer_note();
			?>

			<tr>
				<th class="td" scope="row" colspan="2" style="text-align:left;vertical-align: middle;padding: 12px;font-size: 14px;border: 1px solid;border-color: inherit; <?php echo esc_attr( ( 1 === $i ) ? 'border-top-width: 4px;' : '' ); ?>">
			<?php esc_html_e( 'Note:', 'woocommerce' ); ?>
				</th>
				<td class="td" style="text-align:left;vertical-align: middle;padding: 12px;font-size: 14px;border: 1px solid;border-color: inherit; <?php echo esc_attr( ( 1 === $i ) ? 'border-top-width: 4px;' : '' ); ?>">
			<?php echo esc_html( $note ); ?>
				</td>
			</tr>

		<?php } ?>
	</tfoot>
</table>