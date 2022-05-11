<?php
namespace YayMail\Templates\DefaultTemplate;

defined( 'ABSPATH' ) || exit;

class CustomerResetPassword {

	protected static $instance = null;

	public static function getInstance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public static function getTemplates() {
		/*
		@@@ Html default send email.
		@@@ Note: Add characters '\' before special characters in a string.
		@@@ Example: font-family: \'Helvetica Neue\'...
		*/
		$emailTitle        = __( 'Password Reset Request', 'woocommerce' );
		$emailHi           = esc_html__( 'Hi ', 'woocommerce' ) . esc_html( do_shortcode( '[yaymail_customer_username]' ) . ',' );
		$emailtext         = esc_html__( 'Someone has requested a new password for the following account on ', 'woocommerce' ) . esc_html( do_shortcode( '[yaymail_site_name]' ) . ':' );
		$emailtext_1       = esc_html__( 'If you didn\'t make this request, just ignore this email. If you\'d like to proceed:', 'woocommerce' );
		$textUserName      = __( 'Username', 'woocommerce' );
		$additionalContent = __( 'Thanks for reading.', 'woocommerce' );

		/*
		@@@ Elements default when reset template.
		@@@ Note 1: Add characters '\' before special characters in a string.
		@@@ example 1: "family": "\'Helvetica Neue\',Helvetica,Roboto,Arial,sans-serif",

		@@@ Note 2: Add characters '\' before special characters in a string.
		@@@ example 2: "<h1 style=\"font-family: \'Helvetica Neue\',...."
		*/

		// Elements
		$elements =
		'[
        {
        "id": "8ffa62b5-7258-42cc-ba53-7ae69638c1fe",
        "type": "Logo",
        "nameElement": "Logo",
        "settingRow": {
          "backgroundColor": "#ECECEC",
          "align": "center",
          "pathImg": "",
          "paddingTop": "15",
          "paddingRight": "0",
          "paddingBottom": "15",
          "paddingLeft": "0",
          "width": "172",
          "url": "#"
        }
      }, {
        "id": "802bfe24-7af8-48af-ac5e-6560a81345b3",
        "type": "ElementText",
        "nameElement": "Email Heading",
        "settingRow": {
          "content": "<h1 style=\"font-size: 30px; font-weight: 300; line-height: normal; margin: 0; color: inherit;\">' . $emailTitle . '</h1>",
          "backgroundColor": "#7f54b3",
          "textColor": "#ffffff",
          "family": "Helvetica,Roboto,Arial,sans-serif",
          "paddingTop": "36",
          "paddingRight": "48",
          "paddingBottom": "36",
          "paddingLeft": "48"
        }
      }, {
        "id": "b035d1f1-0cfe-41c5-b79c-0478f144ef5f",
        "type": "ElementText",
        "nameElement": "Text",
        "settingRow": {
          "content": "<p style=\"margin: 0 0 16px;\"><span style=\"font-size: 14px;\">' . $emailHi . '</span></p>\n<p style=\"margin: 0 0 16px;\"><span style=\"font-size: 14px;\">' . $emailtext . '</span></p>\n<p style=\"margin: 0 0 16px;\"><span style=\"font-size: 14px;\">' . $textUserName . ': [yaymail_customer_username]</span></p>\n<p style=\"margin: 0 0 16px;\"><span style=\"font-size: 14px;\">' . $emailtext_1 . '</span></p>\n<p style=\"margin: 0 0 16px;\"><span style=\"font-size: 14px;\">[yaymail_password_reset_url]</span></p>\n<p style=\"margin: 0 0 16px;\"><span style=\"font-size: 14px;\">' . $additionalContent . '</span></p>",
          "backgroundColor": "#fff",
          "textColor": "#636363",
          "family": "Helvetica,Roboto,Arial,sans-serif",
          "paddingTop": "32",
          "paddingRight": "48",
          "paddingBottom": "32",
          "paddingLeft": "48"
        }
      }, {
        "id": "b39bf2e6-8c1a-4384-a5ec-37663da27c8ds",
        "type": "ElementText",
        "nameElement": "Footer",
        "settingRow": {
          "content": "<p style=\"font-size: 14px;margin: 0px 0px 16px; text-align: center;\">[yaymail_site_name]&nbsp;- Built with <a style=\"color: #7f54b3; font-weight: normal; text-decoration: underline;\" href=\"https://woocommerce.com\" target=\"_blank\" rel=\"noopener\">WooCommerce</a></p>",
          "backgroundColor": "#ececec",
          "textColor": "#8a8a8a",
          "family": "Helvetica,Roboto,Arial,sans-serif",
          "paddingTop": "15",
          "paddingRight": "50",
          "paddingBottom": "15",
          "paddingLeft": "50"
        }
      }]';

		// Templates New Order
		$templates = array(
			'customer_reset_password' => array(),
		);

		$templates['customer_reset_password']['elements'] = $elements;
		return $templates;
	}
}
