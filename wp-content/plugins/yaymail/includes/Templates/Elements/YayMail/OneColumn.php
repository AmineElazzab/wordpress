<?php
	$cloneAttrs = $attrs;
?>
<table
	width="<?php esc_attr_e( $general_attrs['tableWidth'], 'woocommerce' ); ?>"
	cellspacing="0"
	cellpadding="0"
	border="0"
	align="center"
	style="display: table;
	<?php echo esc_attr( 'background-color: ' . $attrs['backgroundColor'] ); ?>;
	<?php echo esc_attr( 'background-image: url(' . $attrs['backgroundImage'] . ')' ); ?>;
	<?php echo esc_attr( 'background-position: ' . $attrs['backgroundPosition'] ); ?>;
	<?php echo esc_attr( 'background-size: ' . $attrs['backgroundSize'] ); ?>;
	<?php echo esc_attr( 'background-repeat: ' . $attrs['backgroundRepeat'] ); ?>;
	<?php echo esc_attr( 'width: ' . $general_attrs['tableWidth'] ); ?>;
	<?php echo ! $isInColumns ? esc_attr( 'min-width:' . $general_attrs['tableWidth'] . 'px' ) : ''; ?>
	"
	class="web-main-row nta-row-one-column"
	id="web<?php echo esc_attr( $id ); ?>"
  >
	<tbody>
	  <tr>
		<td
		  id="web-<?php echo esc_attr( $id ); ?>-one-columns"
		  class="web-one-columns"
		  align="left"
		  style='font-size: 13px;  line-height: 22px; word-break: break-word;
		  font-family: <?php echo wp_kses_post( $attrs['family'] ); ?>;
		  <?php echo esc_attr( 'color: ' . $attrs['textColor'] ); ?>;
		  <?php echo esc_attr( 'max-width: ' . $general_attrs['tableWidth'] ); ?>;
		  <?php echo esc_attr( 'padding: ' . $attrs['paddingTop'] . 'px ' . $attrs['paddingRight'] . 'px ' . $attrs['paddingBottom'] . 'px ' . $attrs['paddingLeft'] . 'px;' ); ?>
		  '
		>
		  <div class="web-one-columns-row" style="min-height: 10px">
			<div class="nta-one-column-items nta-one-column-left"  style="min-width: 25px;position: relative;min-height: 1px;">
				<?php
				foreach ( $attrs['column1'] as $key => $el ) {
					do_action( 'Yaymail' . $el['type'], $args, $el['settingRow'], $general_attrs, $el['id'], '', $isInColumns = true );
				}
				?>
			</div>
		  </div>
		</td>
	  </tr>
	</tbody>
  </table>
