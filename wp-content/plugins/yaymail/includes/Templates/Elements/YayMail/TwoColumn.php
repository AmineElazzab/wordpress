<?php
	$cloneAttrs = $attrs;
?>
<table
	width="<?php esc_attr_e( $general_attrs['tableWidth'], 'woocommerce' ); ?>"
	width="tableWidth"
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
	<?php echo ! $isInColumns ? esc_attr( 'min-width:' . $general_attrs['tableWidth'] . 'px' ) : ''; ?>
	"
	class="web-main-row nta-row-two-column"
	id="web<?php echo esc_attr( $id ); ?>"
  >
	<tbody>
	  <tr>
		<td
		  id="web-<?php echo esc_attr( $id ); ?>-two-columns"
		  class="web-two-columns"
		  align="left"
		  style='font-size: 13px;  line-height: 22px; word-break: break-word;
		  font-family: <?php echo wp_kses_post( $attrs['family'] ); ?>;
		  <?php echo esc_attr( 'color: ' . $attrs['textColor'] ); ?>;
		  <?php echo esc_attr( 'max-width: ' . $general_attrs['tableWidth'] ); ?>;
		  <?php echo esc_attr( 'padding: ' . $attrs['paddingTop'] . 'px ' . $attrs['paddingRight'] . 'px ' . $attrs['paddingBottom'] . 'px ' . $attrs['paddingLeft'] . 'px;' ); ?>;
		  '
		>
		  <div class="web-two-columns-row" style="min-height: 10px">
			<table style="width: 100%">
			  <tr>
				<td valign="top" 
					class="nta-two-column-items nta-two-column-left"  
					style="min-width: 25px;position: relative;min-height: 1px;
					<?php echo esc_attr( 'width: ' . $attrs['widthCol1'] . '%' ); ?>;
					" 
				>
				 <?php
					foreach ( $attrs['column1'] as $key => $el ) {
						do_action( 'Yaymail' . $el['type'], $args, $el['settingRow'], $general_attrs, $el['id'], '', $isInColumns = true );
					}
					?>
				</td>
				<td valign="top" 
					class="nta-two-column-items nta-two-column-right" 
					style="min-width: 25px;position: relative;min-height: 1px;
					<?php echo esc_attr( 'width: ' . $attrs['widthCol2'] . '%' ); ?>
					" 
				>
					<?php
					foreach ( $attrs['column2'] as $key => $el ) {
						do_action( 'Yaymail' . $el['type'], $args, $el['settingRow'], $general_attrs, $el['id'], '', $isInColumns = true );
					}
					?>
				</td>
			  </tr>
			</table>
		  </div>
		</td>
	  </tr>
	</tbody>
  </table>
