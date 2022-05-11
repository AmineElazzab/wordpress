<?php $img_url = YAYMAIL_PLUGIN_URL . 'assets/dist/images/woocommerce-logo.png';
switch ( $attrs['numberCol'] ) {
	case 'one':
		$width = '100%';
		break;
	case 'two':
		$width = '50%';
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
  style="display: table; <?php echo esc_attr( 'background-color: ' . $attrs['backgroundColor'] ); ?>; <?php echo ! $isInColumns ? esc_attr( 'min-width:' . $general_attrs['tableWidth'] . 'px' ) : esc_attr( 'width: 100%' ); ?>"
  class="web-main-row"
  id="web<?php echo esc_attr( $id ); ?>"
  >
  <tbody>
	  <tr>
		<td class="web-wrap-col"
			valign="top"
			style="<?php echo esc_attr( 'width: ' . $width ); ?>"
		>
		  <!--===== column 1 ===== -->
		  <table
			cellspacing="0"
			cellpadding="0"
			border="0"
			align="left"
			style="display: table; margin: 0px; width: 100%; padding: 0px;
			<?php echo esc_attr( 'background-color: ' . $attrs['backgroundColor'] ); ?>
			"
		  >
			<tbody>
			  <tr>
				<td
				  align="<?php echo esc_attr( $attrs['col1Align'] ); ?>"
				  id="web-<?php echo esc_attr( $id ); ?>-col-1"
				  class="web-col-1-wrap"
				  style="word-break: break-word;
					<?php echo esc_attr( 'padding: ' . $attrs['col1PaddingTop'] . 'px ' . $attrs['col1PaddingRight'] . 'px ' . $attrs['col1PaddingBottom'] . 'px ' . $attrs['col1PaddingLeft'] . 'px;' ); ?>
				  "
				>
				  <a
					href="<?php echo esc_attr( $attrs['col1Url'] ); ?>"
					target="_blank"
					style="border: none; text-decoration: none;"
				  >
					<img
					  class="web-image-list"
					  border="0"
					  src="
					  <?php
						if ( '' !== $attrs['col1PathImg'] ) {
							echo esc_attr( $attrs['col1PathImg'] );
						} else {
							echo esc_attr( $img_url );
						}
						?>
						"
					  width="<?php echo esc_attr( $attrs['col1Width'] ); ?>"
					  height="auto"
					/>
				  </a>
				</td>
			  </tr>
			</tbody>
		  </table>
		</td>
		<?php if ( 'two' === $attrs['numberCol'] ) : ?>
		<td
			valign="top"
			class="web-wrap-col"
			style="<?php echo esc_attr( 'width: ' . $width ); ?>"
		>
		  <!--===== column 2 ==== -->
		  <table
			cellspacing="0"
			cellpadding="0"
			border="0"
			align="left"
			style="display: table; margin: 0px; width: 100%; padding: 0px;
			<?php echo esc_attr( 'background-color: ' . $attrs['backgroundColor'] ); ?>
			"
		  >
			<tbody>
			  <tr>
				<td
				  id="web-<?php echo esc_attr( $id ); ?>-col-2"
				  class="web-col-2-wrap"
				  align="left"
				  style='font-size: 13px;  line-height: 22px; word-break: break-word;
				  font-family: <?php echo wp_kses_post( $attrs['col2Family'] ); ?>;
				  <?php echo esc_attr( 'color: ' . $attrs['textColor'] ); ?>;
				  <?php echo esc_attr( 'padding: ' . $attrs['col2PaddingTop'] . 'px ' . $attrs['col2PaddingRight'] . 'px ' . $attrs['col2PaddingBottom'] . 'px ' . $attrs['col2PaddingLeft'] . 'px;' ); ?>
				  '
				>
				  <div">
				  <?php echo wp_kses_post( $attrs['col2Content'] ); ?>
				  </div>
				</td>
			  </tr>
			</tbody>
		  </table>
		</td>
		<?php endif; ?>
	  </tr>
	</tbody>
  </table>
