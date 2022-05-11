<?php

defined( 'ABSPATH' ) || exit;
$sent_to_admin = ( isset( $sent_to_admin ) ? true : false );
if ( is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' )
			 || is_plugin_active( 'woocommerce-shipment-tracking/woocommerce-shipment-tracking.php' )
			 || is_plugin_active( 'woocommerce-order-status-manager/woocommerce-order-status-manager.php' )
			 || is_plugin_active( 'woocommerce-admin-custom-order-fields/woocommerce-admin-custom-order-fields.php' )
			 || is_plugin_active( 'woo-advanced-shipment-tracking/woocommerce-advanced-shipment-tracking.php' )
			 || is_plugin_active( 'yith-woocommerce-subscription-premium/init.php' )
			 || is_plugin_active( 'yith-woocommerce-subscription/init.php' )
			) {
	?>

				<div id="yaymail-notice" class="notice-info notice is-dismissible">
					<h4 style="color: #000"><?php esc_html_e( 'Please upgrade to YayMail Pro to customize emails with:', 'yaymail' ); ?></h4>
					<ul style="list-style: inside;">
						<?php
							ob_start();
							$_path = YAYMAIL_PLUGIN_PATH . '/includes/Page/Source/DisplayNotice.php';
							include $_path;
						?>
					</ul>
					<p style="padding-left:0">
						<a href="https://yaycommerce.com/yaymail-woocommerce-email-customizer/ " target="_blank" data="upgradenow" class="button button-primary" style="margin-right: 5px"><?php esc_html_e( 'Upgrade Now', 'yaymail' ); ?></a>
						<a href="javascript:;" data="later" class="button yaymail-nothank" style="margin-right: 5px"><?php esc_html_e( 'No, thanks', 'yaymail' ); ?></a>
					</p>
				</div>
			<?php
}
if ( is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
	?>
		<li><?php esc_html_e( 'Woocommerce Subscriptions (PRO)', 'yaymail' ); ?></li>
	<?php
}

if ( is_plugin_active( 'woocommerce-shipment-tracking/woocommerce-shipment-tracking.php' ) ) {
	?>
		<li><?php esc_html_e( 'Woocommerce Shipment Tracking (PRO)', 'yaymail' ); ?></li>
	<?php
}

if ( is_plugin_active( 'woocommerce-order-status-manager/woocommerce-order-status-manager.php' ) ) {
	?>
		<li><?php esc_html_e( 'Woocommerce Order Status Manager (PRO)', 'yaymail' ); ?></li>
	<?php
}

if ( is_plugin_active( 'woo-advanced-shipment-tracking/woocommerce-advanced-shipment-tracking.php' )
	|| is_plugin_active( 'yith-woocommerce-subscription-premium/init.php' )
	|| is_plugin_active( 'yith-woocommerce-subscription/init.php' )
	) {
	?>
		<li><?php esc_html_e( 'Woocommerce Custom Email Tempaltes (PRO)', 'yaymail' ); ?></li>
	<?php
}

if ( is_plugin_active( 'woocommerce-admin-custom-order-fields/woocommerce-admin-custom-order-fields.php' ) ) {
	?>
		<li><?php esc_html_e( 'Woocommerce Admin Custom Order fields (PRO)', 'yaymail' ); ?></li>
	<?php
}




