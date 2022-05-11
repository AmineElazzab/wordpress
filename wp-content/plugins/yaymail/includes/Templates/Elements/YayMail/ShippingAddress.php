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
		  id="web-<?php echo esc_attr( $id ); ?>-shipping-address"
		  class="web-shipping-address"
		  align="left"
		  style='font-size: 13px;  line-height: 22px; word-break: break-word;
		  <?php echo 'font-family: ' . wp_kses_post( $attrs['family'] ); ?>;
		  <?php echo esc_attr( 'color: ' . $attrs['textColor'] ); ?>;
		  <?php echo esc_attr( 'padding: ' . $attrs['paddingTop'] . 'px ' . $attrs['paddingRight'] . 'px ' . $attrs['paddingBottom'] . 'px ' . $attrs['paddingLeft'] . 'px;' ); ?>
		  '
		>
		  <div style="min-height: 10px">
			<div
			  style="<?php echo esc_attr( 'color: ' . $attrs['titleColor'] ); ?>"
			>
			  <h2 style="color: inherit; display: block; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px;">
				<?php echo wp_kses_post( $attrs['contentTitle'] ); ?>
			  </h2>
			</div>
			<div style="<?php echo esc_attr( 'border: 1px solid ' . $attrs['borderColor'] ); ?>" ><?php echo wp_kses_post( $attrs['content'] ); ?></div>
		  </div>
		</td>
	  </tr>
	</tbody>
  </table>
