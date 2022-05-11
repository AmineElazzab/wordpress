<?php

namespace YayMail\Helper;

defined( 'ABSPATH' ) || exit;
class Helper {


	public static function checkNonce() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
		if ( ! wp_verify_nonce( $nonce, 'email-nonce' ) ) {
			wp_send_json_error( array( 'mess' => __( 'Nonce is invalid', 'yaymail' ) ) );
		}
	}

	public static function sanitize_array( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'self::sanitize_array', $var );
		} else {
			return is_scalar( $var ) ? wp_kses_allowed_html( $var ) : $var;
		}
	}

	public static function unsanitize_array( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'self::unsanitize_array', $var );
		} else {
			return html_entity_decode( $var, ENT_QUOTES, 'UTF-8' );
		}
	}

	// Fix bugs for only Customer Invoice (core code of Woocommerce)
	public static function getCustomerInvoiceSubject( $objEmail ) {
		if ( $objEmail->object && $objEmail->object->has_status( array( 'completed', 'processing' ) ) ) {
			$subject = $objEmail->get_option( 'subject_paid', $objEmail->get_default_subject( true ) );
			return apply_filters( 'woocommerce_email_subject_customer_invoice_paid', $objEmail->format_string( $subject ), $objEmail->object, $objEmail );
		}

		$subject = $objEmail->get_option( 'subject', $objEmail->get_default_subject() );
		return apply_filters( 'woocommerce_email_subject_customer_invoice', $objEmail->format_string( $subject ), $objEmail->object, $objEmail );
	}

	// Get Subject for email WC_Email_New_Booking (Woo Bookings plugin)
	public static function getNewBookingSubject( $objEmail ) {
		$subject = $objEmail->get_option( 'subject', $objEmail->subject );
		return apply_filters( 'woocommerce_email_subject_' . $objEmail->id, $objEmail->format_string( $subject ), $objEmail->object );
	}

	// Check Key Exist
	public static function checkKeyExist( $array, $key, $valueDefault ) {
		if ( isset( $array->$key ) ) {
			return $key;
		} else {
			return $valueDefault;
		}
	}

	public static function preventXSS( $yaymail_elements ) {
		foreach ( $yaymail_elements as $key => $value ) {
			if ( isset( $value['settingRow']['content'] ) ) {
				$yaymail_elements[ $key ]['settingRow']['content'] = wp_kses_post( html_entity_decode( $value['settingRow']['content'], ENT_COMPAT, 'UTF-8' ) );
			}
			if ( isset( $value['settingRow']['contentTitle'] ) ) {
				$yaymail_elements[ $key ]['settingRow']['contentTitle'] = wp_kses_post( html_entity_decode( $value['settingRow']['contentTitle'], ENT_COMPAT, 'UTF-8' ) );
			}
			if ( isset( $value['settingRow']['contentAfter'] ) ) {
				$yaymail_elements[ $key ]['settingRow']['contentAfter'] = wp_kses_post( html_entity_decode( $value['settingRow']['contentAfter'], ENT_COMPAT, 'UTF-8' ) );
			}
			if ( isset( $value['settingRow']['contentBefore'] ) ) {
				$yaymail_elements[ $key ]['settingRow']['contentBefore'] = wp_kses_post( html_entity_decode( $value['settingRow']['contentBefore'], ENT_COMPAT, 'UTF-8' ) );
			}
			if ( isset( $value['settingRow']['col1TtContent'] ) ) {
				$yaymail_elements[ $key ]['settingRow']['col1TtContent'] = wp_kses_post( html_entity_decode( $value['settingRow']['col1TtContent'], ENT_COMPAT, 'UTF-8' ) );
			}
			if ( isset( $value['settingRow']['col2TtContent'] ) ) {
				$yaymail_elements[ $key ]['settingRow']['col2TtContent'] = wp_kses_post( html_entity_decode( $value['settingRow']['col2TtContent'], ENT_COMPAT, 'UTF-8' ) );
			}
			if ( isset( $value['settingRow']['col3TtContent'] ) ) {
				$yaymail_elements[ $key ]['settingRow']['col3TtContent'] = wp_kses_post( html_entity_decode( $value['settingRow']['col3TtContent'], ENT_COMPAT, 'UTF-8' ) );
			}
			if ( isset( $value['settingRow']['HTMLContent'] ) ) {
				$yaymail_elements[ $key ]['settingRow']['HTMLContent'] = wp_kses_post( html_entity_decode( $value['settingRow']['HTMLContent'], ENT_COMPAT, 'UTF-8' ) );
			}
			if ( isset( $value['settingRow']['col2Content'] ) ) {
				$yaymail_elements[ $key ]['settingRow']['col2Content'] = wp_kses_post( html_entity_decode( $value['settingRow']['col2Content'], ENT_COMPAT, 'UTF-8' ) );
			}

			// for column
			// column1
			if ( isset( $value['settingRow']['column1'] ) ) {
				foreach ( $value['settingRow']['column1'] as $key1 => $value1 ) {
					if ( isset( $value1['settingRow']['content'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column1'][ $key1 ]['settingRow']['content'] = wp_kses_post( html_entity_decode( $value1['settingRow']['content'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['contentTitle'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column1'][ $key1 ]['settingRow']['contentTitle'] = wp_kses_post( html_entity_decode( $value1['settingRow']['contentTitle'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['contentAfter'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column1'][ $key1 ]['settingRow']['contentAfter'] = wp_kses_post( html_entity_decode( $value1['settingRow']['contentAfter'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['contentBefore'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column1'][ $key1 ]['settingRow']['contentBefore'] = wp_kses_post( html_entity_decode( $value1['settingRow']['contentBefore'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['col1TtContent'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column1'][ $key1 ]['settingRow']['col1TtContent'] = wp_kses_post( html_entity_decode( $value1['settingRow']['col1TtContent'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['col2TtContent'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column1'][ $key1 ]['settingRow']['col2TtContent'] = wp_kses_post( html_entity_decode( $value1['settingRow']['col2TtContent'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['col3TtContent'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column1'][ $key1 ]['settingRow']['col3TtContent'] = wp_kses_post( html_entity_decode( $value1['settingRow']['col3TtContent'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['HTMLContent'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column1'][ $key1 ]['settingRow']['HTMLContent'] = wp_kses_post( html_entity_decode( $value1['settingRow']['HTMLContent'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['col2Content'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column1'][ $key1 ]['settingRow']['col2Content'] = wp_kses_post( html_entity_decode( $value1['settingRow']['col2Content'], ENT_COMPAT, 'UTF-8' ) );
					}
				}
			}
			// column2
			if ( isset( $value['settingRow']['column2'] ) ) {
				foreach ( $value['settingRow']['column2'] as $key1 => $value1 ) {
					if ( isset( $value1['settingRow']['content'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column2'][ $key1 ]['settingRow']['content'] = wp_kses_post( html_entity_decode( $value1['settingRow']['content'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['contentTitle'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column2'][ $key1 ]['settingRow']['contentTitle'] = wp_kses_post( html_entity_decode( $value1['settingRow']['contentTitle'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['contentAfter'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column2'][ $key1 ]['settingRow']['contentAfter'] = wp_kses_post( html_entity_decode( $value1['settingRow']['contentAfter'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['contentBefore'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column2'][ $key1 ]['settingRow']['contentBefore'] = wp_kses_post( html_entity_decode( $value1['settingRow']['contentBefore'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['col1TtContent'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column2'][ $key1 ]['settingRow']['col1TtContent'] = wp_kses_post( html_entity_decode( $value1['settingRow']['col1TtContent'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['col2TtContent'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column2'][ $key1 ]['settingRow']['col2TtContent'] = wp_kses_post( html_entity_decode( $value1['settingRow']['col2TtContent'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['col3TtContent'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column2'][ $key1 ]['settingRow']['col3TtContent'] = wp_kses_post( html_entity_decode( $value1['settingRow']['col3TtContent'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['HTMLContent'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column2'][ $key1 ]['settingRow']['HTMLContent'] = wp_kses_post( html_entity_decode( $value1['settingRow']['HTMLContent'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['col2Content'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column2'][ $key1 ]['settingRow']['col2Content'] = wp_kses_post( html_entity_decode( $value1['settingRow']['col2Content'], ENT_COMPAT, 'UTF-8' ) );
					}
				}
			}
			// column3
			if ( isset( $value['settingRow']['column3'] ) ) {
				foreach ( $value['settingRow']['column3'] as $key1 => $value1 ) {
					if ( isset( $value1['settingRow']['content'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column3'][ $key1 ]['settingRow']['content'] = wp_kses_post( html_entity_decode( $value1['settingRow']['content'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['contentTitle'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column3'][ $key1 ]['settingRow']['contentTitle'] = wp_kses_post( html_entity_decode( $value1['settingRow']['contentTitle'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['contentAfter'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column3'][ $key1 ]['settingRow']['contentAfter'] = wp_kses_post( html_entity_decode( $value1['settingRow']['contentAfter'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['contentBefore'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column3'][ $key1 ]['settingRow']['contentBefore'] = wp_kses_post( html_entity_decode( $value1['settingRow']['contentBefore'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['col1TtContent'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column3'][ $key1 ]['settingRow']['col1TtContent'] = wp_kses_post( html_entity_decode( $value1['settingRow']['col1TtContent'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['col2TtContent'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column3'][ $key1 ]['settingRow']['col2TtContent'] = wp_kses_post( html_entity_decode( $value1['settingRow']['col2TtContent'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['col3TtContent'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column3'][ $key1 ]['settingRow']['col3TtContent'] = wp_kses_post( html_entity_decode( $value1['settingRow']['col3TtContent'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['HTMLContent'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column3'][ $key1 ]['settingRow']['HTMLContent'] = wp_kses_post( html_entity_decode( $value1['settingRow']['HTMLContent'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['col2Content'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column3'][ $key1 ]['settingRow']['col2Content'] = wp_kses_post( html_entity_decode( $value1['settingRow']['col2Content'], ENT_COMPAT, 'UTF-8' ) );
					}
				}
			}

			// column4
			if ( isset( $value['settingRow']['column4'] ) ) {
				foreach ( $value['settingRow']['column4'] as $key1 => $value1 ) {
					if ( isset( $value1['settingRow']['content'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column4'][ $key1 ]['settingRow']['content'] = wp_kses_post( html_entity_decode( $value1['settingRow']['content'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['contentTitle'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column4'][ $key1 ]['settingRow']['contentTitle'] = wp_kses_post( html_entity_decode( $value1['settingRow']['contentTitle'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['contentAfter'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column4'][ $key1 ]['settingRow']['contentAfter'] = wp_kses_post( html_entity_decode( $value1['settingRow']['contentAfter'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['contentBefore'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column4'][ $key1 ]['settingRow']['contentBefore'] = wp_kses_post( html_entity_decode( $value1['settingRow']['contentBefore'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['col1TtContent'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column4'][ $key1 ]['settingRow']['col1TtContent'] = wp_kses_post( html_entity_decode( $value1['settingRow']['col1TtContent'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['col2TtContent'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column4'][ $key1 ]['settingRow']['col2TtContent'] = wp_kses_post( html_entity_decode( $value1['settingRow']['col2TtContent'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['col3TtContent'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column4'][ $key1 ]['settingRow']['col3TtContent'] = wp_kses_post( html_entity_decode( $value1['settingRow']['col3TtContent'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['HTMLContent'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column4'][ $key1 ]['settingRow']['HTMLContent'] = wp_kses_post( html_entity_decode( $value1['settingRow']['HTMLContent'], ENT_COMPAT, 'UTF-8' ) );
					}
					if ( isset( $value1['settingRow']['col2Content'] ) ) {
						$yaymail_elements[ $key ]['settingRow']['column4'][ $key1 ]['settingRow']['col2Content'] = wp_kses_post( html_entity_decode( $value1['settingRow']['col2Content'], ENT_COMPAT, 'UTF-8' ) );
					}
				}
			}
		}
		return $yaymail_elements;
	}
}
