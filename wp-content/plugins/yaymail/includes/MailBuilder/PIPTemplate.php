<?php
namespace YayMail\MailBuilder;

use YayMail\Page\Source\CustomPostType;

defined( 'ABSPATH' ) || exit;

class PIPTemplate {

	public static function handle_trigger() {
		$list_hooks = array(
			'woocommerce_order_status_failed_to_processing_notification',
			'woocommerce_order_status_failed_to_completed_notification',
			'woocommerce_order_status_pending_to_processing_notification',
			'woocommerce_order_status_pending_to_completed_notification',
			'woocommerce_order_status_on-hold_to_processing_notification',
			'woocommerce_order_status_on-hold_to_completed_notification',
		);
		add_action( 'wc_pip_invoice_email_trigger', array( 'YayMail\MailBuilder\PIPTemplate', 'pip_invoice_email' ), 9 );
		add_action( 'wc_pip_send_email_invoice', array( 'YayMail\MailBuilder\PIPTemplate', 'pip_invoice_email' ), 9 );
		add_action( 'wc_pip_packing_list_email_trigger', array( 'YayMail\MailBuilder\PIPTemplate', 'pip_packing_list_email' ), 9 );
		add_action( 'wc_pip_send_email_packing_list', array( 'YayMail\MailBuilder\PIPTemplate', 'pip_packing_list_email' ), 9 );
		add_action( 'wc_pip_pick_list_email_trigger', array( 'YayMail\MailBuilder\PIPTemplate', 'pip_pick_list_email' ), 9 );
		add_action( 'wc_pip_send_email_pick_list', array( 'YayMail\MailBuilder\PIPTemplate', 'pip_pick_list_email' ), 9 );
		foreach ( $list_hooks as $key => $value ) {
			add_action( $value, array( 'YayMail\MailBuilder\PIPTemplate', 'pip_invoice_email' ), 9 );
			add_action( $value, array( 'YayMail\MailBuilder\PIPTemplate', 'pip_packing_list_email' ), 9 );
			add_action( $value, array( 'YayMail\MailBuilder\PIPTemplate', 'pip_pick_list_email' ), 9 );
		}
	}

	public function pip_packing_list_email( $object ) {
		global $wp_filter;
		if ( is_int( $object ) || $object instanceof \WC_Order ) {
			$wc_order = wc_get_order( $object );
			if ( ! $wc_order ) {
				return;
			}
			$object = wc_pip()->get_document( 'packing-list', array( 'order' => $wc_order ) );
		}
		if ( ! $object || 0 === $object->get_items_count() ) {
			return;
		}
		$template      = 'pip_email_packing_list';
		$address_email = get_option( 'admin_email' );
		if ( ! isset( $object->order ) || ! $object->order || ! $address_email ) {
			return;
		}

		$postID          = CustomPostType::postIDByTemplate( $template );
		$template_status = get_post_meta( $postID, '_yaymail_status', true );
		if ( $template_status ) {
			$callbacks = $wp_filter['woocommerce_order_status_failed_to_processing_notification']->callbacks[10];
			foreach ( $callbacks as $key => $value ) {
				if ( $value['function'][0] instanceof \WC_PIP_Email_Packing_List ) {
					remove_action( 'woocommerce_order_status_failed_to_processing_notification', $key );
				}
			}
			$callbacks = $wp_filter['woocommerce_order_status_failed_to_completed_notification']->callbacks[10];
			foreach ( $callbacks as $key => $value ) {
				if ( $value['function'][0] instanceof \WC_PIP_Email_Packing_List ) {
					remove_action( 'woocommerce_order_status_failed_to_completed_notification', $key );
				}
			}
			$callbacks = $wp_filter['woocommerce_order_status_pending_to_processing_notification']->callbacks[10];
			foreach ( $callbacks as $key => $value ) {
				if ( $value['function'][0] instanceof \WC_PIP_Email_Packing_List ) {
					remove_action( 'woocommerce_order_status_pending_to_processing_notification', $key );
				}
			}
			$callbacks = $wp_filter['woocommerce_order_status_pending_to_completed_notification']->callbacks[10];
			foreach ( $callbacks as $key => $value ) {
				if ( $value['function'][0] instanceof \WC_PIP_Email_Packing_List ) {
					remove_action( 'woocommerce_order_status_pending_to_completed_notification', $key );
				}
			}
			$callbacks = $wp_filter['woocommerce_order_status_on-hold_to_processing_notification']->callbacks[10];
			foreach ( $callbacks as $key => $value ) {
				if ( $value['function'][0] instanceof \WC_PIP_Email_Packing_List ) {
					remove_action( 'woocommerce_order_status_on-hold_to_processing_notification', $key );
				}
			}
			$callbacks = $wp_filter['woocommerce_order_status_on-hold_to_completed_notification']->callbacks[10];
			foreach ( $callbacks as $key => $value ) {
				if ( $value['function'][0] instanceof \WC_PIP_Email_Packing_List ) {
					remove_action( 'woocommerce_order_status_on-hold_to_completed_notification', $key );
				}
			}
			$callbacks = $wp_filter['wc_pip_packing_list_email_trigger']->callbacks[10];
			foreach ( $callbacks as $key => $value ) {
				if ( $value['function'][0] instanceof \WC_PIP_Email_Packing_List ) {
					remove_action( 'wc_pip_packing_list_email_trigger', $key );
				}
			}
			$callbacks = $wp_filter['wc_pip_send_email_packing_list']->callbacks[10];
			foreach ( $callbacks as $key => $value ) {
				if ( $value['function'][0] instanceof \WC_PIP_Email_Packing_List ) {
					remove_action( 'wc_pip_send_email_packing_list', $key );
				}
			}
			$site_title   = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
			$order_number = $object->order->get_order_number();
			$order_number = ! empty( $order_number ) ? $order_number : '';

			$order_timestamp = ( $object->order->get_date_created( 'edit' ) ) ? $object->order->get_date_created( 'edit' )->getOffsetTimestamp() : 0;
			$order_date      = ! empty( $order_timestamp ) && $order_timestamp > 0 ? date_i18n( wc_date_format(), $order_timestamp ) : '';

			$invoice_number = $object instanceof \WC_PIP_Document ? $object->get_invoice_number() : '';
			// translators: woocommerce-pip.
			$email_subject  = sprintf( __( '[%1$s] Packing List for invoice %2$s - order %3$s from %4$s', 'woocommerce-pip' ), $site_title, $invoice_number, $order_number, $order_date );
			$args           = (array) $object;
			$args['object'] = $object;
			ob_start();
			include YAYMAIL_PLUGIN_PATH . 'views/templates/pip-mail-template.php';
			$html = ob_get_contents();
			ob_end_clean();
			wc_mail( $address_email, $email_subject, $html );
		}
	}
	public function pip_pick_list_email( $object ) {
		global $wp_filter;
		$address_email = get_option( 'admin_email' );
		if ( ! $object || ! isset( $object->order_ids ) || ! $object->order_ids || ! is_array( $object->order_ids ) || ! $address_email ) {
			return;
		}
		$template = 'pip_email_pick_list';

		$postID          = CustomPostType::postIDByTemplate( $template );
		$template_status = get_post_meta( $postID, '_yaymail_status', true );
		if ( $template_status ) {
			$callbacks = $wp_filter['wc_pip_pick_list_email_trigger']->callbacks[10];
			foreach ( $callbacks as $key => $value ) {
				if ( $value['function'][0] instanceof \WC_PIP_Email_Pick_List ) {
					remove_action( 'wc_pip_pick_list_email_trigger', $key );
				}
			}
			$callbacks = $wp_filter['wc_pip_send_email_pick_list']->callbacks[10];
			foreach ( $callbacks as $key => $value ) {
				if ( $value['function'][0] instanceof \WC_PIP_Email_Pick_List ) {
					remove_action( 'wc_pip_send_email_pick_list', $key );
				}
			}
			$site_title   = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
			$orders_count = count( $object->order_ids );
			// translators: woocommerce-pip.
			$orders       = sprintf( _n( '%d order', '%d orders', $orders_count, 'woocommerce-pip' ), $orders_count );

			// translators: woocommerce-pip.
			$email_subject  = sprintf( __( '[%1$s] Pick List for %2$s', 'woocommerce-pip' ), $site_title, $orders );
			$args           = (array) $object;
			$args['object'] = $object;
			ob_start();
			include YAYMAIL_PLUGIN_PATH . 'views/templates/pip-mail-template.php';
			$html = ob_get_contents();
			ob_end_clean();
			wc_mail( $address_email, $email_subject, $html );
		}
	}
	public function pip_invoice_email( $object ) {
		global $wp_filter;
		if ( is_int( $object ) || $object instanceof \WC_Order ) {
			$wc_order = wc_get_order( $object );
			if ( ! $wc_order ) {
				return;
			}
			$object = wc_pip()->get_document( 'invoice', array( 'order' => $wc_order ) );
		}
		if ( ! $object || ! isset( $object->order ) || ! $object->order instanceof \WC_Order ) {
			return;
		}
		$template      = 'pip_email_invoice';
		$address_email = $object->order->get_billing_email( 'edit' );
		if ( ! $address_email || ! is_email( $address_email ) ) {
			return;
		}

		$postID          = CustomPostType::postIDByTemplate( $template );
		$template_status = get_post_meta( $postID, '_yaymail_status', true );
		if ( $template_status ) {
			$callbacks = $wp_filter['woocommerce_order_status_failed_to_processing_notification']->callbacks[10];
			foreach ( $callbacks as $key => $value ) {
				if ( $value['function'][0] instanceof \WC_PIP_Email_Invoice ) {
					remove_action( 'woocommerce_order_status_failed_to_processing_notification', $key );
				}
			}
			$callbacks = $wp_filter['woocommerce_order_status_failed_to_completed_notification']->callbacks[10];
			foreach ( $callbacks as $key => $value ) {
				if ( $value['function'][0] instanceof \WC_PIP_Email_Invoice ) {
					remove_action( 'woocommerce_order_status_failed_to_completed_notification', $key );
				}
			}
			$callbacks = $wp_filter['woocommerce_order_status_pending_to_processing_notification']->callbacks[10];
			foreach ( $callbacks as $key => $value ) {
				if ( $value['function'][0] instanceof \WC_PIP_Email_Invoice ) {
					remove_action( 'woocommerce_order_status_pending_to_processing_notification', $key );
				}
			}
			$callbacks = $wp_filter['woocommerce_order_status_pending_to_completed_notification']->callbacks[10];
			foreach ( $callbacks as $key => $value ) {
				if ( $value['function'][0] instanceof \WC_PIP_Email_Invoice ) {
					remove_action( 'woocommerce_order_status_pending_to_completed_notification', $key );
				}
			}
			$callbacks = $wp_filter['woocommerce_order_status_on-hold_to_processing_notification']->callbacks[10];
			foreach ( $callbacks as $key => $value ) {
				if ( $value['function'][0] instanceof \WC_PIP_Email_Invoice ) {
					remove_action( 'woocommerce_order_status_on-hold_to_processing_notification', $key );
				}
			}
			$callbacks = $wp_filter['woocommerce_order_status_on-hold_to_completed_notification']->callbacks[10];
			foreach ( $callbacks as $key => $value ) {
				if ( $value['function'][0] instanceof \WC_PIP_Email_Invoice ) {
					remove_action( 'woocommerce_order_status_on-hold_to_completed_notification', $key );
				}
			}
			$callbacks = $wp_filter['wc_pip_invoice_email_trigger']->callbacks[10];
			foreach ( $callbacks as $key => $value ) {
				if ( $value['function'][0] instanceof \WC_PIP_Email_Invoice ) {
					remove_action( 'wc_pip_invoice_email_trigger', $key );
				}
			}
			$callbacks = $wp_filter['wc_pip_send_email_invoice']->callbacks[10];
			foreach ( $callbacks as $key => $value ) {
				if ( $value['function'][0] instanceof \WC_PIP_Email_Invoice ) {
					remove_action( 'wc_pip_send_email_invoice', $key );
				}
			}
			$site_title   = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
			$order_number = $object->order->get_order_number();
			$order_number = ! empty( $order_number ) ? $order_number : '';

			$order_timestamp = ( $object->order->get_date_created( 'edit' ) ) ? $object->order->get_date_created( 'edit' )->getOffsetTimestamp() : 0;
			$order_date      = ! empty( $order_timestamp ) && $order_timestamp > 0 ? date_i18n( wc_date_format(), $order_timestamp ) : '';

			$invoice_number = $object instanceof \WC_PIP_Document ? $object->get_invoice_number() : '';

			// translators: woocommerce-pip.
			$email_subject  = sprintf( __( '[%1$s] Invoice %2$s for order %3$s from %4$s', 'woocommerce-pip' ), $site_title, $invoice_number, $order_number, $order_date );
			$args           = (array) $object;
			$args['object'] = $object;
			ob_start();
			include YAYMAIL_PLUGIN_PATH . 'views/templates/pip-mail-template.php';
			$html = ob_get_contents();
			ob_end_clean();
			wc_mail( $address_email, $email_subject, $html );
		}
	}
}
