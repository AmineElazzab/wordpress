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
		  id="web-<?php echo esc_attr( $id ); ?>-title"
		  class="web-title"
		  align="<?php echo esc_attr( $attrs['align'] ); ?>"
		  style="word-break: break-word;
		  <?php echo esc_attr( 'padding: ' . $attrs['paddingTop'] . 'px ' . $attrs['paddingRight'] . 'px ' . $attrs['paddingBottom'] . 'px ' . $attrs['paddingLeft'] . 'px;' ); ?>
		  <?php echo esc_attr( 'color: ' . $attrs['textColor'] ); ?>;"
		>
		<?php if ( isset( $attrs['title'] ) ) : ?>
		  <h1
			style='margin: 0px; line-height: normal; font-size: 26px;
				<?php echo 'font-family: ' . wp_kses_post( $attrs['family'] ); ?>;
				<?php echo esc_attr( 'color: ' . $attrs['textColor'] ); ?>;
				<?php echo esc_attr( 'font-size: ' . $attrs['fontSizeTitle'] ); ?>;
			'
		  ><?php echo wp_kses_post( $attrs['title'] ); ?></h1>
		<?php endif; ?>
		  <div style="min-height: 8px"></div>
		<?php if ( isset( $attrs['subTitle'] ) ) : ?>
		  <h4
			style='font-weight: 500; margin-bottom: 0px; font-size: 16px; margin: 0px 0px 8px;
				<?php echo 'font-family: ' . wp_kses_post( $attrs['family'] ); ?>;
				<?php echo esc_attr( 'color: ' . $attrs['textColor'] ); ?>;
				<?php echo esc_attr( 'font-size: ' . $attrs['fontSizeSubTitle'] ); ?>;
			'
		  ><?php echo wp_kses_post( $attrs['subTitle'] ); ?></h4>
		<?php endif; ?>
		</td>
	  </tr>
	</tbody>
  </table>
