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
		  id="web<?php echo esc_attr( $id ); ?>-space"
		  align="<?php echo esc_attr( $attrs['align'] ); ?>"
		  class="web-space"
		  style="font-size: 0px; line-height: 0px;<?php echo esc_attr( 'height: ' . $attrs['height'] . 'px;' ); ?> ">
		</td>
	  </tr>
	</tbody>
  </table>
