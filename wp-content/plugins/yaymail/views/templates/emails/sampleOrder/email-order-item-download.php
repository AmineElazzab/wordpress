<?php

defined( 'ABSPATH' ) || exit;
use YayMail\Page\Source\CustomPostType;

$sent_to_admin   = ( isset( $sent_to_admin ) ? true : false );
$plain_text      = ( isset( $plain_text ) ? $plain_text : '' );
$email           = ( isset( $email ) ? $email : '' );
$postID          = CustomPostType::postIDByTemplate( $this->template );
$text_link_color = get_post_meta( $postID, '_yaymail_email_textLinkColor_settings', true ) ? get_post_meta( $postID, '_yaymail_email_textLinkColor_settings', true ) : '#7f54b3';
$borderColor     = isset( $atts['bordercolor'] ) && $atts['bordercolor'] ? 'border-color:' . html_entity_decode( $atts['bordercolor'], ENT_QUOTES, 'UTF-8' ) : 'border-color:inherit';
$textColor       = isset( $atts['textcolor'] ) && $atts['textcolor'] ? 'color:' . html_entity_decode( $atts['textcolor'], ENT_QUOTES, 'UTF-8' ) : 'color:inherit';
$columns         = apply_filters(
	'woocommerce_email_downloads_columns',
	array(
		'download-product' => __( 'Product', 'woocommerce' ),
		'download-expires' => __( 'Expires', 'woocommerce' ),
		'download-file'    => __( 'Download', 'woocommerce' ),
	)
);
?>

<!-- Table Items has Border -->
<table class="yaymail_builder_table_items_border yaymail_builder_table_item_download" cellspacing="0" cellpadding="6" border="1" style="width: 100% !important;<?php echo esc_attr( $borderColor ); ?>;color: inherit;flex-direction:inherit;" width="100%">
	<thead>
		<tr style="word-break: normal;<?php echo esc_attr( $textColor ); ?>">
			<th class="td" scope="col" style="text-align:left;<?php echo esc_attr( $borderColor ); ?>;">
				<?php esc_html_e( 'Product', 'woocommerce' ); ?>
			</th>
			<th class="td" scope="col" style="text-align:left;<?php echo esc_attr( $borderColor ); ?>;">
				<?php esc_html_e( 'Expires', 'woocommerce' ); ?>
			</th>
			<th class="td" scope="col" style="text-align:left;<?php echo esc_attr( $borderColor ); ?>;">
				<?php esc_html_e( 'Download', 'woocommerce' ); ?>
			</th>
		</tr>
	</thead>
		<tfoot>
			<tr style="<?php echo esc_attr( $textColor ); ?>">
				<td class="td" style="<?php echo esc_attr( $borderColor ); ?>;">
					<a href="" style="color:<?php echo esc_attr( $text_link_color ); ?>" > <?php esc_html_e( 'Downloadable Product', 'yaymail' ); ?></a>
				</td>
				<td class="td" style="<?php echo esc_attr( $borderColor ); ?>;">
					<time datetime="2021-02-13" title="1613174400"> <?php echo wp_kses_post( wc_format_datetime( new WC_DateTime() ) ); ?></time>
				</td>
				<td class="td" style="<?php echo esc_attr( $borderColor ); ?>;">
					<a href="" style="color:<?php echo esc_attr( $text_link_color ); ?>" ><?php esc_html_e( 'Download.doc', 'yaymail' ); ?></a>
				</td>
			</tr>
		</tfoot>
</table>
