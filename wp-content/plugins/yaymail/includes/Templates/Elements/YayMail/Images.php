<?php
$pathImg = '';
if ( '' != $attrs['pathImg'] ) {
	$pathImg = $attrs['pathImg'];
} else {
	$pathImg = YAYMAIL_PLUGIN_URL . 'assets/dist/images/woocommerce-logo.png';
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
		  id="web<?php echo esc_attr( $id ); ?>-img"
		  align="<?php echo esc_attr( $attrs['align'] ); ?>"
		  class="web-img-wrap"
		  style="word-break: break-word;
			<?php echo esc_attr( 'padding: ' . $attrs['paddingTop'] . 'px ' . $attrs['paddingRight'] . 'px ' . $attrs['paddingBottom'] . 'px ' . $attrs['paddingLeft'] . 'px;' ); ?> ">
		  <a
			href="<?php echo esc_attr( $attrs['url'] ); ?>"
			target="_blank"
			style="border: none; text-decoration: none;"
		  >
			<img
			  class="web-img"
			  border="0"
			  tabindex="0"
			  src="<?php echo esc_attr( $pathImg ); ?>"
			  width="<?php echo esc_attr( $attrs['width'] ); ?>"
			  height="auto"
			/>
		  </a>
		</td>
	  </tr>
	</tbody>
  </table>
