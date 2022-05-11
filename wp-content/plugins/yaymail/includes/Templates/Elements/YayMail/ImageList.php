<?php
$img_url = YAYMAIL_PLUGIN_URL . 'assets/dist/images/woocommerce-logo.png';
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
  style="display: table; <?php echo esc_attr( 'background-color: ' . $attrs['backgroundColor'] ); ?>; <?php echo ! $isInColumns ? esc_attr( 'min-width:' . $general_attrs['tableWidth'] . 'px' ) : esc_attr( 'width: 100%' ); ?>"
  class="web-main-row"
  id="web<?php echo esc_attr( $id ); ?>"
  >
  <tbody>
	  <tr>
		<td 
		  style="<?php echo esc_attr( 'width: ' . $width ); ?>"
		  valign="top"
		  class="web-wrap-col"
		>
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
					  class="web-image"
					  border="0"
					  tabindex="0"
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
			  
		<?php if ( 'two' === $attrs['numberCol'] || 'three' === $attrs['numberCol'] ) : ?>
		<td 
		  style="<?php echo esc_attr( 'width: ' . $width ); ?>"
		  valign="top"
		  class="web-wrap-col"
		>
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
				  align="<?php echo esc_attr( $attrs['col2Align'] ); ?>"
				  id="web-<?php echo esc_attr( $id ); ?>-col-2"
				  class="web-col-2-wrap"
				  style="word-break: break-word;
					<?php echo esc_attr( 'padding: ' . $attrs['col2PaddingTop'] . 'px ' . $attrs['col2PaddingRight'] . 'px ' . $attrs['col2PaddingBottom'] . 'px ' . $attrs['col2PaddingLeft'] . 'px;' ); ?>
				  "
				>
				  <a
					href="<?php echo esc_attr( $attrs['col2Url'] ); ?>"
					target="_blank"
					style="border: none; text-decoration: none;"
				  >
					<img
					  class="web-image"
					  border="0"
					  tabindex="0"
					  src="
					  <?php
						if ( '' !== $attrs['col2PathImg'] ) {
							echo esc_attr( $attrs['col2PathImg'] );
						} else {
							echo esc_attr( $img_url );
						}
						?>
						"
					  width="<?php echo esc_attr( $attrs['col2Width'] ); ?>"
					  height="auto"
					/>
				  </a>
				</td>
			  </tr>
			</tbody>
		  </table>
		</td>
		<?php endif; ?>
		<?php if ( 'three' === $attrs['numberCol'] ) : ?>
		<td class="web-wrap-col"
			valign="top"
			style="<?php echo esc_attr( 'width: ' . $width ); ?>"
		>
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
				  align="<?php echo esc_attr( $attrs['col3Align'] ); ?>"
				  id="'web-<?php echo esc_attr( $id ); ?>-col-3'"
				  class="web-col-3-wrap"
				  style="word-break: break-word;
					<?php echo esc_attr( 'padding: ' . $attrs['col3PaddingTop'] . 'px ' . $attrs['col3PaddingRight'] . 'px ' . $attrs['col3PaddingBottom'] . 'px ' . $attrs['col3PaddingLeft'] . 'px;' ); ?>
				  "
				>
				  <a
					href="<?php echo esc_attr( $attrs['col3Url'] ); ?>"
					target="_blank"
					style="border: none; text-decoration: none;"
				  >
					<img
					  class="web-image"
					  border="0"
					  tabindex="0"
					  src="
					  <?php
						if ( '' !== $attrs['col3PathImg'] ) {
							echo esc_attr( $attrs['col3PathImg'] );
						} else {
							echo esc_attr( $img_url );
						}
						?>
						"
					  width="<?php echo esc_attr( $attrs['col3Width'] ); ?>"
					  height="auto"
					/>
				  </a>
				</td>
			  </tr>
			</tbody>
		  </table>
		</td>
		<?php endif; ?>
	  </tr>
	</tbody>
  </table>
