<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! empty( $billing_address ) && ! empty( $shipping_address ) ) {
	$width = '50%';
} else {
	$width = '100%';
}
?>
<?php if ( ! empty( $billing_address ) ) { ?>
<div style="width: <?php echo esc_attr( $width ); ?>; float: left;">
  <h2 style="color: #7f54b3; display: block; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px;">
	<?php esc_html_e( 'Billing Address', 'woocommerce' ); ?>
  </h2>
</div>
<?php } ?>
<?php if ( ! empty( $shipping_address ) ) { ?>
<div style="width: <?php echo esc_attr( $width ); ?>; float: right;">
  <h2 style="color: #7f54b3; display: block; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px;">
	<?php esc_html_e( 'Shipping Address', 'woocommerce' ); ?>
  </h2>
</div>
<?php } ?>

<?php if ( ! empty( $billing_address ) ) { ?>
<div style="width: <?php echo esc_attr( $width ); ?>; float: left;border-color: rgb(229, 229, 229);">
  <table style="width: 100%; height: 18px; border-collapse: collapse;color: inherit;border-color: inherit;" border="0">
	<tbody style="border-color: inherit;">
	  <tr style="height: 18px;border-color: inherit;">
		<td style="height: 18px;border-color: inherit;" valign="top">
		  <address style="padding: 12px;border: 1px solid;border-color:inherit;">
			<span style="font-size: 14px; color: inherit;">
			<?php echo wp_kses_post( $billing_address ); ?>
			</span>
		  </address>
		</td>
	  </tr>
	</tbody>
  </table>
</div>
<?php } ?>
<?php if ( ! empty( $shipping_address ) ) { ?>
<div style="width: <?php echo esc_attr( $width ); ?>; float: right;border-color: rgb(229, 229, 229);">
  <table style="width: 100%; height: 18px;border-collapse: collapse;border-color: inherit;" border="0">
	<tbody style="border-color: inherit;">
	  <tr style="height: 18px;border-color: inherit;">
		<td style="height: 18px;border-color: inherit;" valign="top">
		  <address style="padding: 12px;border: 1px solid;border-color:inherit;">
			<span style="font-size: 14px; color: inherit;">
			<?php echo wp_kses_post( $shipping_address ); ?>
			</span>
		  </address>
		</td>
	  </tr>
	</tbody>
  </table>
</div>
<?php } ?>
