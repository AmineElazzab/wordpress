<?php

namespace YayMail\MailBuilder;

defined( 'ABSPATH' ) || exit;
/**
 * Settings Page
 */
class YaymailElement {
	protected static $instance = null;
	public static function getInstance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
			self::$instance->doHooks();
		}

		return self::$instance;
	}

	private function doHooks() {
		// BASIC
		add_action( 'YaymailLogo', array( $this, 'yaymail_logo' ), 100, 6 );
		add_action( 'YaymailImages', array( $this, 'yaymail_images' ), 100, 6 );
		add_action( 'YaymailElementText', array( $this, 'yaymail_element_text' ), 100, 6 );
		add_action( 'YaymailButton', array( $this, 'yaymail_button' ), 100, 6 );
		add_action( 'YaymailTitle', array( $this, 'yaymail_title' ), 100, 6 );
		add_action( 'YaymailSocialIcon', array( $this, 'yaymail_social_icon' ), 100, 6 );
		add_action( 'YaymailVideo', array( $this, 'yaymail_video' ), 100, 6 );
		add_action( 'YaymailHTMLCode', array( $this, 'yaymail_html_code' ), 100, 6 );
		add_action( 'YaymailImageList', array( $this, 'yaymail_image_list' ), 100, 6 );
		add_action( 'YaymailImageBox', array( $this, 'yaymail_image_box' ), 100, 6 );
		add_action( 'YaymailTextList', array( $this, 'yaymail_text_list' ), 100, 6 );
		// GENERAL
		add_action( 'YaymailSpace', array( $this, 'yaymail_space' ), 100, 6 );
		add_action( 'YaymailDivider', array( $this, 'yaymail_divider' ), 100, 6 );
		add_action( 'YaymailOneColumn', array( $this, 'yaymail_one_column' ), 100, 6 );
		add_action( 'YaymailTwoColumns', array( $this, 'yaymail_two_column' ), 100, 6 );
		add_action( 'YaymailThreeColumns', array( $this, 'yaymail_three_column' ), 100, 6 );
		add_action( 'YaymailFourColumns', array( $this, 'yaymail_four_column' ), 100, 6 );
		// WOOCOMMERCE
		add_action( 'YaymailShippingAddress', array( $this, 'yaymail_shipping_address' ), 100, 6 );
		add_action( 'YaymailBillingAddress', array( $this, 'yaymail_billing_address' ), 100, 6 );
		add_action( 'YaymailOrderItem', array( $this, 'yaymail_order_item' ), 100, 6 );
		add_action( 'YaymailOrderItemDownload', array( $this, 'yaymail_order_item_download' ), 100, 6 );
		add_action( 'YaymailHook', array( $this, 'yaymail_hook' ), 100, 6 );

	}

	public function yaymail_logo( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/Logo.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html = preg_replace( $reg, '', $html );
		echo wp_kses_post( $html );
	}

	public function yaymail_images( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/Images.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html = preg_replace( $reg, '', $html );
		echo wp_kses_post( $html );
	}

	public function yaymail_element_text( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/ElementText.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html                       = preg_replace( $reg, '', $html );
		$allowed_html_tags          = wp_kses_allowed_html( 'post' );
		$allowed_html_tags['style'] = array();
		echo wp_kses( $html, $allowed_html_tags );
	}

	public function yaymail_button( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/Button.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html = preg_replace( $reg, '', $html );
		echo wp_kses_post( $html );
	}
	public function yaymail_title( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/Title.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html = preg_replace( $reg, '', $html );
		echo wp_kses_post( $html );
	}

	public function yaymail_social_icon( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/SocialIcon.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html = preg_replace( $reg, '', $html );
		echo wp_kses_post( $html );
	}

	public function yaymail_video( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/Video.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html = preg_replace( $reg, '', $html );
		echo wp_kses_post( $html );
	}

	public function yaymail_shipping_address( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/ShippingAddress.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html = preg_replace( $reg, '', $html );
		echo wp_kses_post( $html );
	}

	public function yaymail_billing_address( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/BillingAddress.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html = preg_replace( $reg, '', $html );
		echo wp_kses_post( $html );
	}

	public function yaymail_order_item( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/OrderItem.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html = preg_replace( $reg, '', $html );
		$allowed_html_tags = wp_kses_allowed_html('post');
		$allowed_html_tags['style'] = array();
		echo wp_kses($html, $allowed_html_tags);
	}

	public function yaymail_html_code( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/HTML.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html = preg_replace( $reg, '', $html );
		echo wp_kses_post( $html );
	}

	public function yaymail_image_list( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/ImageList.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html = preg_replace( $reg, '', $html );
		echo wp_kses_post( $html );
	}

	public function yaymail_image_box( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/ImageBox.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html = preg_replace( $reg, '', $html );
		echo wp_kses_post( $html );
	}

	public function yaymail_text_list( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/TextList.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html = preg_replace( $reg, '', $html );
		echo wp_kses_post( $html );
	}

	public function yaymail_order_item_download( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/OrderItemDownload.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html = preg_replace( $reg, '', $html );
		echo wp_kses_post( $html );
	}

	public function yaymail_hook( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/Hook.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html                       = preg_replace( $reg, '', $html );
		$allowed_html_tags          = wp_kses_allowed_html( 'post' );
		$allowed_html_tags['style'] = array();
		echo wp_kses( $html, $allowed_html_tags );
	}

	public function yaymail_space( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/Space.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html = preg_replace( $reg, '', $html );
		echo wp_kses_post( $html );
	}

	public function yaymail_divider( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/Divider.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html = preg_replace( $reg, '', $html );
		echo wp_kses_post( $html );
	}

	public function yaymail_one_column( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/OneColumn.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html = preg_replace( $reg, '', $html );
		echo wp_kses_post( $html );
	}

	public function yaymail_two_column( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/TwoColumn.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html = preg_replace( $reg, '', $html );
		echo wp_kses_post( $html );
	}

	public function yaymail_three_column( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/ThreeColumn.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html = preg_replace( $reg, '', $html );
		echo wp_kses_post( $html );
	}

	public function yaymail_four_column( $args, $attrs, $general_attrs, $id, $postID = '', $isInColumns = false ) {
		ob_start();
		include YAYMAIL_PLUGIN_PATH . 'includes/Templates/Elements/YayMail/FourColumn.php';
		$html = ob_get_contents();
		ob_end_clean();
		$html = do_shortcode( $html );
		// Replace shortcode cannot do_shortcode
		$reg  = '/\[yaymail.*?\]/m';
		$html = preg_replace( $reg, '', $html );
		echo wp_kses_post( $html );
	}
}
