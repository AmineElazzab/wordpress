<?php
	$plain_text = false;
if ( ! $plain_text ) {
	?>
	<ul style="list-style: none; padding: 0; list-style-type:none;">
	<?php
	$checkEmpty = true;
	$index      = 0;
	foreach ( $order_fields as $order_field ) :
		$value = $order_field->get_value_formatted();
		if ( $order_field->is_visible() && ( $value ) ) :
			$checkEmpty = false;
			?>
			<li style="
			<?php
			if ( $index ) {
				echo esc_attr( 'padding-bottom: 10px;' );}
			?>
			 font-size:14px; margin-left: 15px;">
				<strong><?php echo wp_kses_post( $order_field->label ); ?>:</strong>
				<div class="text" style="padding-left: 20px;">
					<?php echo 'textarea' === $order_field->type ? wp_kses_post( wpautop( $value ) ) : wp_kses_post( $value ); ?> 
				</div>
			</li>
			<?php
		endif;
		$index++;
	endforeach;
	?>
	</ul>
<?php } else {
	echo esc_html__( 'Additional Order Details', 'woocommerce-admin-custom-order-fields' ) . "\n\n";
	foreach ( $order_fields as $order_field ) {
		$value = $order_field->get_value_formatted();
		if ( $order_field->is_visible() && ( $value ) ) {

			$output .= wp_kses_post( $order_field->label ) . ': <br>';
			$output .= wp_kses_post( $value ) . '<br><br>';
		}
	}
	echo '<br>****************************************************<br><br>';
} ?>
