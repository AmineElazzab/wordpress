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
		  id="web-<?php echo esc_attr( $id ); ?>-billing-address"
		  class="web-billing-address"
		  align="left"
		  style='font-size: 13px; line-height: 22px; word-break: break-word;
		  <?php echo 'font-family: ' . wp_kses_post( $attrs['family'] ); ?>;
		  <?php echo esc_attr( 'color: ' . $attrs['textColor'] ); ?>;
		  <?php echo esc_attr( 'padding: ' . $attrs['paddingTop'] . 'px ' . $attrs['paddingRight'] . 'px ' . $attrs['paddingBottom'] . 'px ' . $attrs['paddingLeft'] . 'px;' ); ?>
		  '
		>
		<div style="min-height: 10px;height: 100%;display: inline-block;width: 100%;">
			<div
			  style="<?php echo esc_attr( 'color: ' . $attrs['titleColor'] ); ?>"
			>
			<?php if ( 'BillingShippingAddress' === $attrs['nameColumn'] ) : ?>
			  <div>
				<table 
				  style='width: 100%; color: inherit;
				  <?php echo esc_attr( 'color: ' . $attrs['titleColor'] ); ?>;
				  <?php echo esc_attr( 'font-family: ' . $attrs['family'] ); ?>;
				  ' 
				>
				<?php echo wp_kses_post( $attrs['contentTitle'] ); ?>
				</table>
			  </div>
			<?php else : ?>
			  <h2 style="color: inherit; display: block; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px;">
				<?php echo wp_kses_post( $attrs['titleBilling'] ); ?>
			  </h2>
			</div>
			<?php endif; ?>
		  </div>
		  <?php if ( 'BillingShippingAddress' === $attrs['nameColumn'] ) : ?>
		  <div class="billing-shipping-address-container" style="<?php echo esc_attr( 'border-color: ' . $attrs['borderColor'] ); ?>" >
					<?php echo wp_kses_post( $attrs['content'] ); ?>
		  </div>
		  <?php else : ?>
		  <div style="<?php echo esc_attr( 'border: 1px solid ' . $attrs['borderColor'] ); ?>">
					<?php echo wp_kses_post( $attrs['content'] ); ?>
		  </div>
		  <?php endif; ?>
		</td>
	  </tr>
	</tbody>
  </table>
