<?php
switch ( $attrs['numberCol'] ) {
	case 'one':
		$width = '100%';
		break;
	case 'two':
		$width = '50%';
		break;
	case 'three':
		$width = '33.333%';
		break;
	default:
		break;
}
?>
<table
width="<?php esc_attr_e( $general_attrs['tableWidth'], 'woocommerce' ); ?>"
cellspacing="0"
cellpadding="0"
border="0"
align="center"
style="display: table;
<?php echo esc_attr( 'width: ' . $general_attrs['tableWidth'] ); ?>;
<?php echo esc_attr( 'background-color: ' . $attrs['backgroundColor'] ); ?>;
<?php echo ! $isInColumns ? esc_attr( 'min-width:' . $general_attrs['tableWidth'] . 'px' ) : esc_attr( 'width: 100%' ); ?>
"
class="web-main-row nta-web-text-list-wrap"
id="web<?php echo esc_attr( $id ); ?>"
>
<tbody>
	<tr>
	<td 
		valign="top"
		style="<?php echo esc_attr( 'width: ' . $width ); ?>"
	>
		<?php
			$col = 1;
			ob_start();
			require YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/TextList/ColEl.php';
			$colEl = ob_get_contents();
			ob_end_clean();
			$colEl = do_shortcode( $colEl );
			echo wp_kses_post( $colEl );
		?>
	</td>
	<?php if ( 'two' === $attrs['numberCol'] || 'three' === $attrs['numberCol'] ) : ?>
	<td 

		valign="top"
		style="<?php echo esc_attr( 'width: ' . $width ); ?>"
	>
		<?php
			$col = 2;
			ob_start();
			include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/TextList/ColEl.php';
			$colEl = ob_get_contents();
			ob_end_clean();
			$colEl = do_shortcode( $colEl );
			echo wp_kses_post( $colEl );
		?>
	</td>
	<?php endif; ?>
	<?php if ( 'three' === $attrs['numberCol'] ) : ?>
	<td
		valign="top"
		style="<?php echo esc_attr( 'width: ' . $width ); ?>"
	>
		<?php
			$col = 3;
			ob_start();
			include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/TextList/ColEl.php';
			$colEl = ob_get_contents();
			ob_end_clean();
			$colEl = do_shortcode( $colEl );
			echo wp_kses_post( $colEl );
		?>
	</td>
	<?php endif; ?>
	</tr>
</tbody>
</table>
