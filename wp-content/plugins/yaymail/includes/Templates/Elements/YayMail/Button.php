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
	  id="web-<?php echo esc_attr( $id ); ?>-button"
		  class="web-button-full-width"
		  style="<?php echo esc_attr( 'padding: ' . $attrs['paddingTop'] . 'px ' . $attrs['paddingRight'] . 'px ' . $attrs['paddingBottom'] . 'px ' . $attrs['paddingLeft'] . 'px;' ); ?>"
		>
		  <table
			align="<?php echo esc_attr( $attrs['align'] ); ?>"
			cellspacing="0"
			cellpadding="0"
			border="0"
			width="<?php echo esc_attr( $attrs['widthButton'] ) . '%'; ?>"
		  >
			<tbody>
			  <tr>
				<td
				  style="margin: 10px; word-break: break-word;"
				  id="web-<?php echo esc_attr( $id ); ?>button"
				  class="web-button-wrap"
				>
				  <a
					href="<?php echo esc_attr( $attrs['pathUrl'] ); ?>"
					class="web-button"
					style='line-height: 21px;text-align: center; text-decoration: none; margin: 0px; float: left; width: -webkit-fill-available;
					<?php echo 'font-family: ' . wp_kses_post( $attrs['family'] ); ?>;
					<?php echo esc_attr( 'background-color: ' . $attrs['buttonBackgroundColor'] ); ?>;
					<?php echo esc_attr( 'font-size: ' . $attrs['fontSize'] . 'px' ); ?>;
					<?php echo esc_attr( 'color: ' . $attrs['textColor'] ); ?>;
					<?php echo esc_attr( 'font-weight: ' . $attrs['weight'] ); ?>;
					<?php echo esc_attr( 'padding: ' . $attrs['textPaddingTop'] . 'px ' . $attrs['textPaddingRight'] . 'px ' . $attrs['textPaddingBottom'] . 'px ' . $attrs['textPaddingLeft'] . 'px;' ); ?>
					<?php echo esc_attr( 'border-radius: ' . $attrs['borderRadiusTop'] . 'px ' . $attrs['borderRadiusRight'] . 'px ' . $attrs['borderRadiusBottom'] . 'px ' . $attrs['borderRadiusLeft'] . 'px;' ); ?>
					'
				  >
					<span style="text-align: center;vertical-align: middle;<?php echo esc_attr( 'line-height: ' . $attrs['heightButton'] ) . 'px'; ?>"><?php echo wp_kses_post( $attrs['text'] ); ?></span>
				  </a>
				</td>
			  </tr>
			</tbody>
		  </table>
		</td>
	  </tr>
	</tbody>
  </table>
