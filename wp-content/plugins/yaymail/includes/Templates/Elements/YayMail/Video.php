<?php $img_url = YAYMAIL_PLUGIN_URL . 'assets/dist/images/play.png'; ?>
<table
	width="<?php esc_attr_e( $general_attrs['tableWidth'], 'woocommerce' ); ?>"
	cellspacing="0"
	cellpadding="0"
	border="0"
	align="center"
	style="display: table; background-color: <?php echo esc_attr( $attrs['backgroundColor'] ); ?>; <?php echo ! $isInColumns ? esc_attr( 'min-width:' . $general_attrs['tableWidth'] . 'px' ) : esc_attr( 'width: 100%' ); ?>;"
	class="web-main-row"
	id="web<?php echo esc_attr( $id ); ?>"
  >
  <tbody>
	  <tr>
		<td
		  id="web-<?php echo esc_attr( $id ); ?>-video"
		  class="web-video-wrap"
		  style="<?php echo esc_attr( 'padding: ' . $attrs['paddingTop'] . 'px ' . $attrs['paddingRight'] . 'px ' . $attrs['paddingBottom'] . 'px ' . $attrs['paddingLeft'] . 'px;' ); ?>"
		>
		  <a
			href="<?php echo esc_attr( $attrs['videoUrl'] ); ?>"
			target="_blank"
			style="border: none; text-decoration: none; display: block;"
		  >
		  <?php if ( '' !== $attrs['pathImg'] ) : ?>
			<div 
			  style="text-align: center;background-size: cover;margin:auto;background-position:center;
				<?php echo esc_attr( 'background-image: url(' . $attrs['pathImg'] . ');' ); ?>
				<?php echo esc_attr( 'width: ' . $attrs['width'] . '%;' ); ?>
				<?php echo esc_attr( 'height: ' . $attrs['height'] . 'px;' ); ?>
				<?php echo esc_attr( 'line-height: ' . $attrs['height'] . 'px;' ); ?>
			  "
			>
			  <img
				style="border-collapse:collapse;border: none;"
				src="<?php echo esc_attr( $img_url ); ?>"
			  />
			</div>
		<?php else : ?>
			<div 
			  style="text-align: center;
			  <?php echo esc_attr( 'width: ' . $attrs['width'] . '%;' ); ?>
			  <?php echo esc_attr( 'height: ' . $attrs['height'] . 'px;' ); ?>
			  <?php echo esc_attr( 'line-height: ' . $attrs['height'] . 'px;' ); ?>
			  "
			>
			  <img
				style="border-collapse:collapse;border: none;"
				src="<?php echo esc_attr( $img_url ); ?>"
			  />
			</div>
		<?php endif; ?>
		  </a>
		</td>
	  </tr>
	</tbody>
  </table>
