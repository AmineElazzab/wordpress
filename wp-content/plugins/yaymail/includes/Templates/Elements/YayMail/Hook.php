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
		  id="web-<?php echo esc_attr( $id ); ?>-yaymail-hook"
		  class="web-yaymail-hook"
		  align="left"
		  style="font-size: 13px; line-height: 22px; word-break: break-word;
		  font-family: <?php echo wp_kses_post( $attrs['family'] ); ?>;
		  <?php echo esc_attr( 'padding: ' . $attrs['paddingTop'] . 'px ' . $attrs['paddingRight'] . 'px ' . $attrs['paddingBottom'] . 'px ' . $attrs['paddingLeft'] . 'px;' ); ?>
		  "
		>
		  <div
		  class="yaymail-items-order-border"
			style="min-height: 10px; <?php echo esc_attr( 'color: ' . $attrs['textColor'] ); ?>"
		  >
		  <?php echo wp_kses_post( $attrs['content'] ); ?>
		  </div>
		</td>
	  </tr>
	</tbody>
  </table>
