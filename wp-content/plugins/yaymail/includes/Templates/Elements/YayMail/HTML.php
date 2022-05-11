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
		  id="web<?php echo esc_attr( $id ); ?>-html"
		  class="web-html-code"
		  :style="{
			'fontFamily': emailContent.settingRow.family,
			'fontSize': emailContent.settingRow.fontSize  + 'px',
			'color': emailContent.settingRow.textColor,
			'padding': emailContent.settingRow.paddingTop + 'px ' + emailContent.settingRow.paddingRight + 'px ' + emailContent.settingRow.paddingBottom + 'px ' + emailContent.settingRow.paddingLeft + 'px'
		  }"
		  style='word-break: break-word;
		  font-family: <?php echo wp_kses_post( $attrs['family'] ); ?>;
		  <?php echo esc_attr( 'font-size: ' . $attrs['fontSize'] . 'px' ); ?>;
		  <?php echo esc_attr( 'padding: ' . $attrs['paddingTop'] . 'px ' . $attrs['paddingRight'] . 'px ' . $attrs['paddingBottom'] . 'px ' . $attrs['paddingLeft'] . 'px;' ); ?>
		  '
		>
		  <div class="nta-html-code-content-wrap" style="width:100%">
			<?php if ( '' === $attrs['HTMLContent'] ) : ?>
			<div class="nta-html-code-no-content">
			  <a-icon type="code" style="color: #c2cbd2;font-size: 22px;display: block;text-align: center;padding: 10px 0;"></a-icon>
			</div>
			<?php else : ?>
			<div class="nta-html-code-content" style="min-height: 10px;width:100%" v-html="emailContent.settingRow.HTMLContent">
				<?php echo wp_kses_post( $attrs['HTMLContent'] ); ?>
			</div>
			<?php endif; ?>
		  </div>
		</td>
	  </tr>
	</tbody>
  </table>
