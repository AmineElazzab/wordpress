<?php

defined( 'ABSPATH' ) || exit;
use YayMail\Helper\Helper;

$text_align = is_rtl() ? 'right' : 'left';
// instructions payment
$paymentGateways        = wc_get_payment_gateway_by_order( $order );
$yaymail_settings       = get_option( 'yaymail_settings' );
$colorContentTableItems = isset( $yaymail_settings['content_items_color'] ) && ! empty( $yaymail_settings['content_items_color'] ) ? $yaymail_settings['content_items_color'] : '#636363';
$colorTitleTableItems   = isset( $yaymail_settings['title_items_color'] ) && ! empty( $yaymail_settings['title_items_color'] ) ? $yaymail_settings['title_items_color'] : '#7f54b3';
$cash_on_delivery       = esc_html__( 'Cash on delivery', 'woocommerce' );
if ( ( 'customer_on_hold_order' === $this->template
	|| 'customer_processing_order' === $this->template
	|| 'customer_completed_order' === $this->template
	|| 'customer_refunded_order' === $this->template
	|| 'customer_invoice' === $this->template
	|| 'customer_note' === $this->template )
	&& 2 == $yaymail_settings['payment']
	|| ( Helper::checkKeyExist( $paymentGateways, 'method_title', false ) == $cash_on_delivery
	&& 'cancelled_order' != $this->template
	&& 'new_order' != $this->template
	&& 'failed_order' != $this->template
	&& 'customer_new_account' != $this->template
	&& 'customer_reset_password' != $this->template )
) {?>

	<p style="color: <?php echo esc_attr( $colorContentTableItems ); ?>;text-align:  <?php echo esc_attr( $text_align ); ?>" class="yaymail_builder_instructions">
	<?php echo wp_kses_post( isset( $paymentGateways->instructions ) ? $paymentGateways->instructions : '', 'woocommerce' ); ?>
	</p>

<?php } elseif ( 1 == $yaymail_settings['payment'] ) { ?>

	<p class="yaymail_builder_instructions" style="color: <?php echo esc_attr( $colorContentTableItems ); ?>">
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
		|| 'customer_note' === $this->template )
		&& $direct_bank_transfer == $paymentGateways->method_title
		&& is_array( $account_details )
		&& count( $account_details ) > 0
		&& 1 == $yaymail_settings['payment']
	) {
		?>

		<section style="text-align: ' . $text_align . '" class="yaymail_builder_wrap_account">
		<h2 style="color: inherit;" class="yaymail_builder_bank_details">
		<?php esc_html_e( 'Our bank details', 'woocommerce' ); ?>
			</h2>

		<?php
		foreach ( $account_details as $accounts ) {
			foreach ( $accounts as $label_name => $infor_account ) {
				if ( 'account_name' === $label_name && ! empty( $infor_account ) ) {
					?>
						<h3 style="color: color: inherit;" class="yaymail_builder_account_name">
					   <?php
						esc_html_e( $infor_account, 'woocommerce' );
						?>
						</h3>
					<?php
				}
			}
			?>

			<ul style="color: <?php echo esc_attr( $colorContentTableItems ); ?>">
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
