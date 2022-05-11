<?php

defined( 'ABSPATH' ) || exit;
add_action(
	'admin_notices',
	function() {
		if ( current_user_can( 'activate_plugins' ) ) {
			?>
				<div class="notice notice-error is-dismissible">
				<p>
					<strong><?php _e('It looks like you have another YayMail version installed, please delete it before activating this new version. All current settings and data are still preserved.', 'yaymail' ); ?>
					<a href="https://docs.yaycommerce.com/yaymail/getting-started/how-to-update-yaymail"><?php _e( 'Read more details.', 'yaymail' ); ?></a>
					</strong>
				</p>
				</div>
			<?php
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
		}
	}
);
