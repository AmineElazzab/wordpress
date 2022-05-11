<table
	width="<?php esc_attr_e( $general_attrs['tableWidth'], 'woocommerce' ); ?>"
	cellspacing="0"
	cellpadding="0"
	border="0"
	align="center"
	style="height:100%;display: table; background-color: <?php echo esc_attr( $attrs['backgroundColor'] ); ?>; <?php echo ! $isInColumns ? esc_attr( 'min-width:' . $general_attrs['tableWidth'] . 'px' ) : esc_attr( 'width: 100%' ); ?>;"
	class="web-main-row"
	id="web<?php echo esc_attr( $id ); ?>"
  >
  <tbody>
	  <tr>
		<td
		id="web-<?php echo esc_attr( $id ); ?>el-text"
		  class="web-el-text"
		  align="left"
		  style='font-size: 13px;  line-height: 22px; word-break: break-word;
		<?php echo 'font-family: ' . wp_kses_post( $attrs['family'] ); ?>;
		<?php echo esc_attr( 'padding: ' . $attrs['paddingTop'] . 'px ' . $attrs['paddingRight'] . 'px ' . $attrs['paddingBottom'] . 'px ' . $attrs['paddingLeft'] . 'px;' ); ?>
		<?php echo esc_attr( 'color: ' . $attrs['textColor'] ); ?>;
		'
		>
		<div class="element-text-content" style="min-height: 10px; text-align: <?php echo esc_attr( isset( $attrs['textAlign'] ) ? $attrs['textAlign'] : '' ); ?>">
		<?php
			$preg = '/<h[0-9] ([a-z0-9A-Z="-: ;]+)>/';
			preg_match( $preg, $attrs['content'], $h );

		if ( isset( $h[0] ) && ! empty( $h[0] ) ) {
			$newh             = substr( $h[0], 0, -2 );
			$newh[10]         = '\'';
			$newh            .= ';font-family: ' . wp_kses_post( $attrs['family'] ) . ';\'>';
			$attrs['content'] = str_replace( $h, $newh, $attrs['content'] );
		}
		echo wp_kses_post( $attrs['content'] );

		?>
		</div>
		</td>
	</tr>
	</tbody>
</table>
