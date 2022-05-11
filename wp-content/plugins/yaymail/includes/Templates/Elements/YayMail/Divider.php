<table
	width="<?php esc_attr_e( $general_attrs['tableWidth'], 'woocommerce' ); ?>"
	cellspacing="0"
	cellpadding="0"
	border="0"
	align="center"
	style="display: table; <?php echo esc_attr( 'background-color: ' . $attrs['backgroundColor'] ); ?>; <?php echo ! $isInColumns ? esc_attr( 'min-width:' . $general_attrs['tableWidth'] . 'px' ) : esc_attr( 'width: 100%' ); ?>"
	class="web-main-row"
	id="web<?php echo esc_attr( $id ); ?>"
  >
	<tbody>
	  <tr>
		<td
		  id="web<?php echo esc_attr( $id ); ?>-divider"
		  class="web-divider-wrap"
		  style="<?php echo esc_attr( 'padding: ' . $attrs['paddingTop'] . 'px ' . $attrs['paddingRight'] . 'px ' . $attrs['paddingBottom'] . 'px ' . $attrs['paddingLeft'] . 'px;' ); ?> ">
		  <table
			width="<?php echo esc_attr( $attrs['width'] . '%' ); ?>"
			align="<?php echo esc_attr( $attrs['align'] ); ?>"
			cellspacing="0"
			cellpadding="0"
			border="0"
			style="<?php echo esc_attr( 'border-top-width: ' . $attrs['height'] . 'px;border-top-color: ' . $attrs['dividerColor'] . ';border-top-style: ' . $attrs['dividerStyle'] . ';' ); ?> "
		  >
			<tbody>
			  <tr>
				<td style="width: 100%;"></td>
			  </tr>
			</tbody>
		  </table>
		</td>
	  </tr>
	</tbody>
  </table>
