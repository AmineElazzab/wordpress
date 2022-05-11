<?php
namespace YayMail\Page\Source;

defined( 'ABSPATH' ) || exit;

class UpdateElement {

	public $updateElements = null;

	public function __construct() {
		$this->updateElements = array();

		//Filter to add new updated Element's properties
		add_filter(
			'yaymail_addon_get_updated_elements',
			function( $element ) {
				$result = array_merge(
					$element,
					array()
				);
				return $result;
			}
		);
		$this->updateElements = apply_filters( 'yaymail_addon_get_updated_elements', $this->updateElements );
	}
	public function merge_new_props_to_elements( $yaymail_elements ) {
		if ( is_array( $yaymail_elements ) || is_object( $yaymail_elements ) ) {
			foreach ( $yaymail_elements as $key => $element ) {
				if ( isset( $this->updateElements[ $element['type'] ] ) ) {
					$yaymail_elements[ $key ]['settingRow'] = wp_parse_args( $yaymail_elements[ $key ]['settingRow'], $this->updateElements[ $element['type'] ] );
				}
			}
		}
		return $yaymail_elements;
	}
}
