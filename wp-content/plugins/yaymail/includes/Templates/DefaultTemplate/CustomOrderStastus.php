<?php

namespace YayMail\Templates\DefaultTemplate;

defined( 'ABSPATH' ) || exit;

class CustomOrderStastus {

	protected static $instance = null;

	public static function getInstance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public static function getTemplates( $customOrder, $emailHeading ) {
		/*
		@@@ Html default send email.
		@@@ Note: Add characters '\' before special characters in a string.
		@@@ Example: font-family: \'Helvetica Neue\'...
		*/

		$emailTitle        = __( $emailHeading, 'woocommerce' );
		$emailtext         = esc_html__( 'Notification to let you know &mdash; order #', 'woocommerce' ) . esc_html( do_shortcode( '[yaymail_order_id]' ) ) . esc_html__( ' belonging to ', 'woocommerce' ) . esc_html( do_shortcode( '[yaymail_billing_first_name] [yaymail_billing_last_name]' ) );
		$additionalContent = __( 'Thanks for reading.', 'woocommerce' );
		if ( 'new_renewal_order' == $customOrder ) {
			$emailtext         = esc_html__( 'You have received a subscription renewal order from ', 'woocommerce' ) . esc_html( do_shortcode( '[yaymail_billing_first_name] [yaymail_billing_last_name]' ) ) . esc_html__( ' . Their order is as follows:', 'woocommerce' );
			$additionalContent = __( 'Congratulations on the sale.', 'woocommerce' );
		}
		if ( 'customer_processing_renewal_order' == $customOrder ) {
			$emailtext         = esc_html__( 'Hi ', 'woocommerce' ) . esc_html( do_shortcode( '[yaymail_billing_first_name]' ) . ', <br /><br/>' ) . esc_html__( 'Just to let you know &mdash; we\'ve received your subscription renewal order #', 'woocommerce' ) . esc_html( do_shortcode( '[yaymail_order_id]' ) ) . esc_html__( ', and it is now being processed:', 'woocommerce' );
			$additionalContent = __( 'Thanks for using ', 'woocommerce' ) . esc_html( do_shortcode( '[yaymail_domain]' ) . '!' );
		}
		if ( 'customer_completed_renewal_order' == $customOrder ) {
			$emailtext         = esc_html__( 'Hi ', 'woocommerce' ) . esc_html( do_shortcode( '[yaymail_billing_first_name]' ) . ', <br /><br/>' ) . esc_html__( 'We have finished processing your subscription renewal order.', 'woocommerce' );
			$additionalContent = __( 'Thanks for shopping with us.', 'woocommerce' );
		}
		if ( 'customer_on_hold_renewal_order' == $customOrder ) {
			$emailtext         = esc_html__( 'Hi ', 'woocommerce' ) . esc_html( do_shortcode( '[yaymail_billing_first_name]' ) . ', <br /><br/>' ) . esc_html__( 'Thanks for your renewal order. It\'s on-hold until we confirm that payment has been received. In the meantime, here\'s a reminder of your order:', 'woocommerce' );
			$additionalContent = __( 'We look forward to fulfilling your order soon.', 'woocommerce' );
		}
		if ( 'customer_renewal_invoice' == $customOrder ) {
			$emailtext         = esc_html__( 'Hi ', 'woocommerce' ) . esc_html( do_shortcode( '[yaymail_billing_first_name]' ) . ', <br /><br/>' );
			$additionalContent = __( 'Thanks for using ', 'woocommerce' ) . esc_html( do_shortcode( '[yaymail_domain]' ) . '!' );
		}

		$textShippingAddress = __( 'Shipping Address', 'woocommerce' );
		$textBillingAddress  = __( 'Billing Address', 'woocommerce' );

		/*
		@@@ Elements default when reset template.
		@@@ Note 1: Add characters '\' before special characters in a string.
		@@@ example 1: "family": "\'Helvetica Neue\',Helvetica,Roboto,Arial,sans-serif",

		@@@ Note 2: Add characters '\' before special characters in a string.
		@@@ example 2: "<h1 style=\"font-family: \'Helvetica Neue\',...."
		*/

		// Elements
		$elements =
		'[{
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
		},';
		if ( 'new_renewal_order' != $customOrder && 'customer_processing_renewal_order' != $customOrder && 'customer_completed_renewal_order' != $customOrder && 'customer_on_hold_renewal_order' != $customOrder ) {
			$elements .= '{
			"id": "802bfe24-7af8-48af-ac5e-6560a81345b3",
			"type": "ElementText",
			"nameElement": "Email Heading",
			"settingRow": {
			"content": "<h1 style=\"font-size: 30px; font-weight: 300; line-height: normal; margin: 0; color: inherit;\">' . $emailTitle . ' #[yaymail_order_id]</h1>",
			"backgroundColor": "#7f54b3",
			"textColor": "#ffffff",
			"family": "Helvetica,Roboto,Arial,sans-serif",
			"paddingTop": "36",
			"paddingRight": "48",
			"paddingBottom": "36",
			"paddingLeft": "48"
			}
		},';
		} else {
			$elements .= '{
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
		},';
		}
			$elements .= '{
			"id": "b035d1f1-0cfe-41c5-b79c-0478f144ef5f",
			"type": "ElementText",
			"nameElement": "Text",
			"settingRow": {
				"content": "<p style=\"margin: 0px;\"><span style=\"font-size: 14px;\">' . $emailtext . '</span></p>",
				"backgroundColor": "#fff",
				"textColor": "#636363",
				"family": "Helvetica,Roboto,Arial,sans-serif",
				"paddingTop": "47",
				"paddingRight": "50",
				"paddingBottom": "0",
				"paddingLeft": "50"
			}
		},
		{
			"id": "ad422370-f762-4a26-92de-c4cf3878h0oi",
			"type": "OrderItem",
			"nameElement": "Order Item",
			"settingRow": {
				"contentBefore": "[yaymail_items_border_before]",
				"contentAfter": "[yaymail_items_border_after]",
				"contentTitle": "[yaymail_items_border_title]",
				"content": "[yaymail_items_border_content]",
				"backgroundColor": "#fff",
				"titleColor" : "#7f54b3",
				"textColor": "#636363",
				"borderColor": "#e5e5e5",
				"family": "Helvetica,Roboto,Arial,sans-serif",
				"paddingTop": "15",
				"paddingRight": "50",
				"paddingBottom": "15",
				"paddingLeft": "50"
			}
		},
		{
			"id": "de242956-a617-4213-9107-138842oi4tch",
			"type": "BillingAddress",
			"nameElement": "Billing Shipping Address",
			"settingRow": {
				"nameColumn": "BillingShippingAddress",
				"contentTitle": "[yaymail_billing_shipping_address_title]",
				"checkBillingShipping": "[yaymail_billing_shipping_address_title]",
				"titleBilling": "' . $textBillingAddress . '",
				"titleShipping": "' . $textShippingAddress . '",
				"content": "[yaymail_billing_shipping_address_content]",
				"titleColor" : "#7f54b3",
				"backgroundColor": "#fff",
				"borderColor": "#e5e5e5",
				"textColor": "#636363",
				"family": "Helvetica,Roboto,Arial,sans-serif",
				"paddingTop": "15",
				"paddingRight": "50",
				"paddingBottom": "15",
				"paddingLeft": "50"
			}
		},
		{
			"id": "b39bf2e6-8c1a-4384-a5ec-37663da27c8d",
			"type": "ElementText",
			"nameElement": "Text",
			"settingRow": {
				"content": "<p><span style=\"font-size: 14px;\">' . $additionalContent . '</span></p>",
				"backgroundColor": "#fff",
				"textColor": "#636363",
				"family": "Helvetica,Roboto,Arial,sans-serif",
				"paddingTop": "0",
				"paddingRight": "50",
				"paddingBottom": "38",
				"paddingLeft": "50"
			}
		},
		{
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

		// Templates Cancelled Order
		$templates = array(
			$customOrder => array(),
		);

		$templates[ $customOrder ]['elements'] = $elements;
		return $templates;
	}
}
