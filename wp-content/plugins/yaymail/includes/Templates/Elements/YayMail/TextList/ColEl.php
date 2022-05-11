<table
  cellspacing="0"
  cellpadding="0"
  border="0"
  align="left"
  style="display: table; margin: 0px; width: 100%; padding: 0px;
  <?php echo esc_attr( 'background-color: ' . $attrs['backgroundColor'] ); ?>
  "
  id="web-<?php echo esc_attr( $id ); ?>-col-<?php echo esc_attr( $col ); ?>" 
  class="'web-col-<?php echo esc_attr( $col ); ?>-wrap'"
>
  <tbody>
	<tr 
		class="web-row-text"
	>
	  <td
		align="left"
		style='line-height: 22px; word-break: break-word;
		<?php echo esc_attr( 'color: ' . $attrs['textColor'] ); ?>;
		font-family: <?php echo wp_kses_post( $attrs[ 'col' . $col . 'TtFamily' ] ); ?>;
		<?php echo esc_attr( 'padding: ' . $attrs[ 'col' . $col . 'TtPaddingTop' ] . 'px ' . $attrs[ 'col' . $col . 'TtPaddingRight' ] . 'px ' . $attrs[ 'col' . $col . 'TtPaddingBottom' ] . 'px ' . $attrs[ 'col' . $col . 'TtPaddingLeft' ] . 'px;' ); ?>
		'
	  >
		<div style="min-height: 15px">
		<?php echo wp_kses_post( $attrs[ 'col' . $col . 'TtContent' ] ); ?>
		</div>
	  </td>
	</tr>
	<?php
	if ( 'show' === $attrs[ 'buttonCol' . $col ] ) :
		;
		?>
	<tr
		class="web-row-button"
	>
	  <td
		style="
			<?php echo esc_attr( 'padding: ' . $attrs[ 'col' . $col . 'BtPaddingTop' ] . 'px ' . $attrs[ 'col' . $col . 'BtPaddingRight' ] . 'px ' . $attrs[ 'col' . $col . 'BtPaddingBottom' ] . 'px ' . $attrs[ 'col' . $col . 'BtPaddingLeft' ] . 'px;' ); ?>
		"
	  >
		<table
		  align="<?php echo esc_attr( $attrs[ 'col' . $col . 'BtAlign' ] ); ?>"
		  cellspacing="0"
		  cellpadding="0"
		  border="0"
		  width="<?php echo esc_attr( $attrs[ 'col' . $col . 'BtWidthButton' ] . '%' ); ?>"
		>
		  <tbody>
			<tr>
			  <td
				style="margin: 10px; word-break: break-word;"
				class="web-button-wrap"
			  >
				<a
				  href="<?php echo esc_attr( $attrs[ 'col' . $col . 'BtPathUrl' ] ); ?>"
				  class="web-button"
				  style='line-height: 21px; border-radius: 6px; text-align: center; text-decoration: none; display: block; margin: 0px; padding: 12px 20px;
				  font-family: <?php echo wp_kses_post( $attrs[ 'col' . $col . 'BtFamily' ] ); ?>;
				  <?php echo esc_attr( 'background-color: ' . $attrs[ 'col' . $col . 'BtBgColor' ] ); ?>;
				  <?php echo esc_attr( 'font-size: ' . $attrs[ 'col' . $col . 'BtFontSize' ] . 'px' ); ?>;
				  <?php echo esc_attr( 'color: ' . $attrs[ 'col' . $col . 'BtTextColor' ] ); ?>;
				  <?php echo esc_attr( 'font-weight: ' . $attrs[ 'col' . $col . 'BtWeight' ] ); ?>
				  '
				><?php echo wp_kses_post( $attrs[ 'col' . $col . 'BtText' ] ); ?></a>
			 </td>
			</tr>
		  </tbody>
		</table>
		</td>
	</tr>
	<?php endif; ?>
	</tbody>
</table>
				
