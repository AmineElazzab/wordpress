<?php
namespace YayMail\Page\Source;

defined( 'ABSPATH' ) || exit;

class DefaultElement {

	public $defaultDataElement = null;

	public function __construct() {
		$ImgUrl              = YAYMAIL_PLUGIN_URL . 'assets/dist/images';
		$textShippingAddress = __( 'Shipping Address', 'woocommerce' );
		$textBillingAddress  = __( 'Billing Address', 'woocommerce' );

		$this->defaultDataElement = array(
			array(
				'id'          => 1,
				'type'        => 'Logo',
				'nameIcon'    => 'picture',
				'nameElement' => 'Logo',
				'settingRow'  => array(
					'backgroundColor' => '#fff',
					'align'           => 'center',
					'pathImg'         => '',
					'paddingTop'      => 15,
					'paddingRight'    => 50,
					'paddingBottom'   => 15,
					'paddingLeft'     => 50,
					'width'           => 202,
					'url'             => '#',
				),
			),
			array(
				'id'          => 41,
				'type'        => 'YaymailLogo',
				'nameIcon'    => 'picture',
				'nameElement' => 'YaymailLogo',
				'settingRow'  => array(
					'backgroundColor' => '#fff',
					'align'           => 'center',
					'pathImg'         => '',
					'paddingTop'      => 15,
					'paddingRight'    => 50,
					'paddingBottom'   => 15,
					'paddingLeft'     => 50,
					'width'           => 202,
					'url'             => '#',
				),
			),
			array(
				'id'          => 21,
				'type'        => 'Images',
				'nameIcon'    => 'picture',
				'nameElement' => 'Image',
				'settingRow'  => array(
					'backgroundColor' => '#fff',
					'align'           => 'center',
					'pathImg'         => '',
					'paddingTop'      => 15,
					'paddingRight'    => 50,
					'paddingBottom'   => 15,
					'paddingLeft'     => 50,
					'width'           => 252,
					'url'             => '#',
				),
			),
			array(
				'id'          => 2,
				'type'        => 'Button',
				'nameIcon'    => 'plus-circle',
				'nameElement' => 'Button',
				'settingRow'  => array(
					'buttonType'            => 'default',
					'text'                  => 'Click me',
					'backgroundColor'       => '#fff',
					'align'                 => 'center',
					'widthButton'           => '50',
					'heightButton'          => '21',
					'paddingTop'            => 15,
					'paddingRight'          => 50,
					'paddingBottom'         => 15,
					'paddingLeft'           => 50,
					'textPaddingTop'        => 12,
					'textPaddingRight'      => 20,
					'textPaddingBottom'     => 12,
					'textPaddingLeft'       => 20,
					'borderRadiusTop'       => 5,
					'borderRadiusRight'     => 5,
					'borderRadiusBottom'    => 5,
					'borderRadiusLeft'      => 5,
					'pathUrl'               => '#',
					'buttonBackgroundColor' => '#7f54b3',
					'fontSize'              => 13,
					'textColor'             => '#fff',
					'weight'                => 'normal',
					'family'                => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
				),
			),
			array(
				'id'          => 3,
				'type'        => 'ElementText',
				'nameIcon'    => 'form',
				'nameElement' => 'Text',
				'settingRow'  => array(
					'content'         => '<p><span style="font-size: 18px;"><strong>This is a title</strong></span></p>
                                <p>&nbsp;</p>
                                <p style="font-size: 14px;"><span> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy.</span></p>
                                <p>&nbsp;</p>
                                <p style="font-size: 14px;"><span>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</span></p>
                                <p>&nbsp;</p>
                                <p style="font-size: 14px;"><span>Various versions have evolved over the years.</span></p>',
					'backgroundColor' => '#fff',
					'textColor'       => '#636363',
					'family'          => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
					'paddingTop'      => 15,
					'paddingRight'    => 50,
					'paddingBottom'   => 15,
					'paddingLeft'     => 50,
				),
			),
			array(
				'id'          => 4,
				'type'        => 'Title',
				'nameIcon'    => 'font-colors',
				'nameElement' => 'Title',
				'settingRow'  => array(
					'title'            => 'Enter your title here',
					'sizeTitle'        => 'default',
					'fontSizeTitle'    => '26px',
					'subTitle'         => 'Subtitle',
					'sizeSubTitle'     => 'default',
					'fontSizeSubTitle' => '',
					'backgroundColor'  => '#fff',
					'align'            => 'center',
					'paddingTop'       => 15,
					'paddingRight'     => 50,
					'paddingBottom'    => 15,
					'paddingLeft'      => 50,
					'textColor'        => 'rgb(68, 68, 68)',
					'family'           => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
				),
			),
			array(
				'id'          => 5,
				'type'        => 'SocialIcon',
				'nameIcon'    => 'share-alt',
				'nameElement' => 'Social Icon',
				'settingRow'  => array(
					'backgroundColor' => '#fff',
					'align'           => 'center',
					'widthSocialIcon' => 24,
					'iconSpacing'     => 5,
					'paddingTop'      => 15,
					'paddingRight'    => 50,
					'paddingBottom'   => 15,
					'paddingLeft'     => 50,
					'styleTheme'      => 'SolidDark',
					'iconSocialsArr'  => array(),
				),
			),
			array(
				'id'          => 6,
				'type'        => 'Video',
				'nameIcon'    => 'video-camera',
				'nameElement' => 'Video',
				'settingRow'  => array(
					'backgroundColor' => '#fff',
					'paddingTop'      => 15,
					'paddingRight'    => 50,
					'paddingBottom'   => 15,
					'paddingLeft'     => 50,
					'pathImg'         => '',
					'width'           => 100,
					'videoUrl'        => '',
					'height'          => 360,
				),
			),
			array(
				'id'          => 7,
				'type'        => 'ImageList',
				'nameIcon'    => 'pic-center',
				'nameElement' => 'Image List',
				'settingRow'  => array(
					'numberCol'         => 'three',
					'backgroundColor'   => '#fff',

					/* col one */
					'col1Align'         => 'center',
					'col1PathImg'       => '',
					'col1PaddingTop'    => 10,
					'col1PaddingRight'  => 10,
					'col1PaddingBottom' => 10,
					'col1PaddingLeft'   => 50,
					'col1Width'         => 100,
					'col1Url'           => '#',
					/* col two */
					'col2Align'         => 'center',
					'col2PathImg'       => '',
					'col2PaddingTop'    => 10,
					'col2PaddingRight'  => 30,
					'col2PaddingBottom' => 10,
					'col2PaddingLeft'   => 30,
					'col2Width'         => 100,
					'col2Url'           => '#',
					/* col three */
					'col3Align'         => 'center',
					'col3PathImg'       => '',
					'col3PaddingTop'    => 10,
					'col3PaddingRight'  => 50,
					'col3PaddingBottom' => 10,
					'col3PaddingLeft'   => 10,
					'col3Width'         => 100,
					'col3Url'           => '#',
				),
			),
			array(
				'id'          => 8,
				'type'        => 'ImageBox',
				'nameIcon'    => 'pic-left',
				'nameElement' => 'Image Box',
				'settingRow'  => array(
					'numberCol'         => 'two',
					'backgroundColor'   => '#fff',
					'textColor'         => '#636363',

					/* col 1 */
					'col1Align'         => 'center',
					'col1PathImg'       => '',
					'col1PaddingTop'    => 10,
					'col1PaddingRight'  => 10,
					'col1PaddingBottom' => 10,
					'col1PaddingLeft'   => 50,
					'col1Width'         => 242,
					'col1Url'           => '#',

					/* col 2 */
					'col2Content'       => '<p><span style="font-size: 18px;"><strong>This is a title</strong></span></p>
                                    <p>&nbsp;</p>
                                    <p style="font-size: 14px;"><span> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy.</span></p>
                                    <p>&nbsp;</p>
                                    <p style="font-size: 14px;"><span>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</span></p>
                                    ',
					'col2Family'        => 'Helvetica,Roboto,Arial,sans-serif',
					'col2PaddingTop'    => 10,
					'col2PaddingRight'  => 50,
					'col2PaddingBottom' => 10,
					'col2PaddingLeft'   => 10,
				),
			),
			array(
				'id'          => 9,
				'type'        => 'TextList',
				'nameIcon'    => 'menu',
				'nameElement' => 'Text List',
				'settingRow'  => array(
					'numberCol'           => 'two',
					'backgroundColor'     => '#fff',
					'textColor'           => '#636363',
					'buttonCol1'          => 'show',
					'buttonCol2'          => 'show',
					'buttonCol3'          => 'show',

					/*
					=== col 1 ===*/
					/* text */
					'col1TtContent'       => '<p><span style="font-size: 18px; color: #636363;"><strong>This is a title</strong></span></p>
                                    <p>&nbsp;</p>
                                    <p style="font-size: 14px;"><span> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy.</span></p>
                                    <p>&nbsp;</p>
                                    <p style="font-size: 14px;"><span>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</span></p>',

					'col1TtFamily'        => '"Helvetica",Helvetica,Roboto,Arial,sans-serif',
					'col1TtPaddingTop'    => 10,
					'col1TtPaddingRight'  => 10,
					'col1TtPaddingBottom' => 10,
					'col1TtPaddingLeft'   => 50,

					/* button */
					'col1BtButtonType'    => 'default',
					'col1BtText'          => 'Click me',
					'col1BtAlign'         => 'left',
					'col1BtWidthButton'   => '',
					'col1BtPaddingTop'    => 0,
					'col1BtPaddingRight'  => 0,
					'col1BtPaddingBottom' => 0,
					'col1BtPaddingLeft'   => 50,
					'col1BtPathUrl'       => '#',
					'col1BtBgColor'       => '#7f54b3',
					'col1BtFontSize'      => 13,
					'col1BtTextColor'     => '#fff',
					'col1BtWeight'        => 'normal',
					'col1BtFamily'        => '"Helvetica",Helvetica,Roboto,Arial,sans-serif',

					/*
					=== col 2 ===*/
					/* text */
					'col2TtContent'       => '<p><span style="font-size: 18px; color: #636363;"><strong>This is a title</strong></span></p>
                                    <p>&nbsp;</p>
                                    <p style="font-size: 14px;"><span> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy.</span></p>
                                    <p>&nbsp;</p>
                                    <p style="font-size: 14px;"><span>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</span></p>',

					'col2TtFamily'        => '"Helvetica",Helvetica,Roboto,Arial,sans-serif',
					'col2TtPaddingTop'    => 10,
					'col2TtPaddingRight'  => 50,
					'col2TtPaddingBottom' => 10,
					'col2TtPaddingLeft'   => 10,

					/* button */
					'col2BtButtonType'    => 'default',
					'col2BtText'          => 'Click me',
					'col2BtAlign'         => 'left',
					'col2BtWidthButton'   => '',
					'col2BtPaddingTop'    => 0,
					'col2BtPaddingRight'  => 0,
					'col2BtPaddingBottom' => 0,
					'col2BtPaddingLeft'   => 10,
					'col2BtPathUrl'       => '#',
					'col2BtBgColor'       => '#7f54b3',
					'col2BtFontSize'      => 13,
					'col2BtTextColor'     => '#fff',
					'col2BtWeight'        => 'normal',
					'col2BtFamily'        => '"Helvetica",Helvetica,Roboto,Arial,sans-serif',

					/*
					=== col 3 ===*/
					/* text */
					'col3TtContent'       => '<p><span style="font-size: 18px; color: #636363;"><strong>This is a title</strong></span></p>
                                    <p>&nbsp;</p>
                                    <p style="font-size: 14px;"><span> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy.</span></p>
                                    <p>&nbsp;</p>
                                    <p style="font-size: 14px;"><span>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</span></p>',

					'col3TtFamily'        => '"Helvetica",Helvetica,Roboto,Arial,sans-serif',
					'col3TtPaddingTop'    => 10,
					'col3TtPaddingRight'  => 50,
					'col3TtPaddingBottom' => 10,
					'col3TtPaddingLeft'   => 10,

					/* button */
					'col3BtButtonType'    => 'default',
					'col3BtText'          => 'Click me',
					'col3BtAlign'         => 'left',
					'col3BtWidthButton'   => '',
					'col3BtPaddingTop'    => 0,
					'col3BtPaddingRight'  => 40,
					'col3BtPaddingBottom' => 0,
					'col3BtPaddingLeft'   => 10,
					'col3BtPathUrl'       => '#',
					'col3BtBgColor'       => '#7f54b3',
					'col3BtFontSize'      => 13,
					'col3BtTextColor'     => '#fff',
					'col3BtWeight'        => 'normal',
					'col3BtFamily'        => '"Helvetica",Helvetica,Roboto,Arial,sans-serif',
				),
			),
			array(
				'id'          => 23,
				'type'        => 'HTMLCode',
				'nameIcon'    => 'code',
				'nameElement' => 'HTML',
				'settingRow'  => array(
					'HTMLContent'     => '',
					'backgroundColor' => '#fff',
					'textColor'       => '#636363',
					'family'          => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
					'fontSize'        => 14,
					'paddingTop'      => 15,
					'paddingRight'    => 50,
					'paddingBottom'   => 15,
					'paddingLeft'     => 50,
				),
			),
			// General
			array(
				'id'          => 10,
				'type'        => 'Space',
				'nameIcon'    => 'column-height',
				'nameElement' => 'Space',
				'settingRow'  => array(
					'backgroundColor' => '#fff',
					'height'          => 40,
				),
			),
			array(
				'id'          => 11,
				'type'        => 'Divider',
				'nameIcon'    => 'minus',
				'nameElement' => 'Divider',
				'settingRow'  => array(
					'backgroundColor' => '#fff',
					'align'           => 'center',
					'paddingTop'      => 15,
					'paddingRight'    => 50,
					'paddingBottom'   => 15,
					'paddingLeft'     => 50,
					'height'          => 6,
					'width'           => 100,
					'dividerColor'    => '#333',
					'dividerStyle'    => 'solid',
				),
			),
			array(
				'id'          => 22,
				'type'        => 'OneColumn',
				'nameIcon'    => 'menu',
				'nameElement' => 'One Column',
				'settingRow'  => array(
					'column1'            => array(),
					'content'            => '<table style="width: 100%; border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td>Column 1</td></tr></tbody></table>',
					'backgroundColor'    => '#fff',
					'backgroundImage'    => '',
					'backgroundPosition' => 'unset',
					'backgroundRepeat'   => 'unset',
					'backgroundSize'     => 'unset',
					'textColor'          => '#000000',
					'family'             => '"Helvetica",Helvetica,Roboto,Arial,sans-serif',
					'paddingTop'         => 15,
					'paddingRight'       => 0,
					'paddingBottom'      => 15,
					'paddingLeft'        => 0,
				),
			),
			array(
				'id'          => 12,
				'type'        => 'TwoColumns',
				'nameIcon'    => 'bars',
				'nameElement' => 'Two Columns',
				'settingRow'  => array(
					'column1'            => array(),
					'column2'            => array(),
					'content'            => '<table style="width: 100%; border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td style="width: 50%;">Column 1</td><td style="width: 50%;">Column 2</td></tr></tbody></table>',
					'backgroundColor'    => '#fff',
					'backgroundImage'    => '',
					'backgroundPosition' => 'unset',
					'backgroundRepeat'   => 'unset',
					'backgroundSize'     => 'unset',
					'textColor'          => '#000000',
					'family'             => '"Helvetica",Helvetica,Roboto,Arial,sans-serif',
					'paddingTop'         => 15,
					'paddingRight'       => 0,
					'paddingBottom'      => 15,
					'paddingLeft'        => 0,
					'widthCol1'          => 50,
					'widthCol2'          => 50,
				),
			),
			array(
				'id'          => 13,
				'type'        => 'ThreeColumns',
				'nameIcon'    => 'menu',
				'nameElement' => 'Three Columns',
				'settingRow'  => array(
					'column1'            => array(),
					'column2'            => array(),
					'column3'            => array(),
					'content'            => '<table style="width: 100%; border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td style="width: 33.3333%;">Column 1</td><td style="width: 33.3333%;">Column 2</td><td style="width: 33.3333%;">Column 3</td></tr></tbody></table>',
					'backgroundColor'    => '#fff',
					'backgroundImage'    => '',
					'backgroundPosition' => 'unset',
					'backgroundRepeat'   => 'unset',
					'backgroundSize'     => 'unset',
					'textColor'          => '#000000',
					'family'             => '"Helvetica",Helvetica,Roboto,Arial,sans-serif',
					'paddingTop'         => 15,
					'paddingRight'       => 0,
					'paddingBottom'      => 15,
					'paddingLeft'        => 0,
					'widthCol1'          => 33.3,
					'widthCol2'          => 33.3,
					'widthCol3'          => 33.3,
				),
			),
			array(
				'id'          => 20,
				'type'        => 'FourColumns',
				'nameIcon'    => 'menu',
				'nameElement' => 'Four Columns',
				'settingRow'  => array(
					'column1'            => array(),
					'column2'            => array(),
					'column3'            => array(),
					'column4'            => array(),
					'content'            => '<table style="width: 100%; border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td style="width: 33.3333%;">Column 1</td><td style="width: 33.3333%;">Column 2</td><td style="width: 33.3333%;">Column 3</td></tr></tbody></table>',
					'backgroundColor'    => '#fff',
					'backgroundImage'    => '',
					'backgroundPosition' => 'unset',
					'backgroundRepeat'   => 'unset',
					'backgroundSize'     => 'unset',
					'textColor'          => '#000000',
					'family'             => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
					'paddingTop'         => 15,
					'paddingRight'       => 0,
					'paddingBottom'      => 15,
					'paddingLeft'        => 0,
					'widthCol1'          => 25,
					'widthCol2'          => 25,
					'widthCol3'          => 25,
					'widthCol4'          => 25,
				),
			),

			// WooCommerce
			array(
				'id'          => 18,
				'type'        => 'ElementText',
				'nameIcon'    => 'font-colors',
				'nameElement' => 'Email Heading',
				'settingRow'  => array(
					'content'         => '<h1 style="font-size: 30px; font-weight: 300; line-height: normal; margin: 0; color: inherit;">Email Heading</h1>',
					'backgroundColor' => '#7f54b3',
					'textColor'       => '#ffffff',
					'family'          => "\'Helvetica Neue\',Helvetica,Roboto,Arial,sans-serif",
					'paddingTop'      => '41',
					'paddingRight'    => '48',
					'paddingBottom'   => '41',
					'paddingLeft'     => '48',
				),
			),
			array(
				'id'          => 17,
				'type'        => 'ElementText',
				'nameIcon'    => 'form',
				'nameElement' => 'Footer',
				'settingRow'  => array(
					'content'         => '<p><span style="font-size: 18px;"><strong>This is a Footer</strong></span></p>',
					'backgroundColor' => '#fff',
					'textColor'       => '#636363',
					'family'          => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
					'paddingTop'      => 15,
					'paddingRight'    => 50,
					'paddingBottom'   => 15,
					'paddingLeft'     => 50,
				),
			),

			array(
				'id'          => 14,
				'type'        => 'ShippingAddress',
				'nameIcon'    => 'car',
				'nameElement' => 'Shipping Address',
				'settingRow'  => array(
					'contentTitle'    => $textShippingAddress,
					'content'         => '<address style="padding: 12px;margin-bottom:0;order-color: inherit"><span style="font-size: 14px; color: inherit;">[yaymail_shipping_address]</span></address>',
					'titleColor'      => '#7f54b3',
					'backgroundColor' => '#fff',
					'borderColor'     => '#e5e5e5',
					'textColor'       => '#636363',
					'family'          => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
					'paddingTop'      => 15,
					'paddingRight'    => 50,
					'paddingBottom'   => 15,
					'paddingLeft'     => 50,
				),
			),
			array(
				'id'          => 15,
				'type'        => 'BillingAddress',
				'nameIcon'    => 'idcard',
				'nameElement' => 'Billing Address',
				'settingRow'  => array(
					'nameColumn'      => 'BillingAddress',
					'titleBilling'    => $textBillingAddress,
					'titleShipping'   => $textShippingAddress,
					'content'         => '<address style="padding: 12px; margin-bottom:0;order-color: inherit"><span style="font-size: 14px; color: inherit;">[yaymail_billing_address]</span></address>',
					'titleColor'      => '#7f54b3',
					'backgroundColor' => '#fff',
					'borderColor'     => '#e5e5e5',
					'textColor'       => '#636363',
					'family'          => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
					'paddingTop'      => 15,
					'paddingRight'    => 50,
					'paddingBottom'   => 15,
					'paddingLeft'     => 50,
				),
			),
			array(
				'id'          => 19,
				'type'        => 'BillingAddress',
				'nameIcon'    => 'idcard',
				'nameElement' => 'Billing Shipping Address',
				'settingRow'  => array(
					'nameColumn'           => 'BillingShippingAddress',
					'contentTitle'         => '[yaymail_billing_shipping_address_title]',
					'checkBillingShipping' => '[yaymail_check_billing_shipping_address]',
					'titleBilling'         => $textBillingAddress,
					'titleShipping'        => $textShippingAddress,
					'content'              => '[yaymail_billing_shipping_address_content]',
					'titleColor'           => '#7f54b3',
					'borderColor'          => '#e5e5e5',
					'backgroundColor'      => '#fff',
					'textColor'            => '#636363',
					'family'               => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
					'paddingTop'           => 15,
					'paddingRight'         => 50,
					'paddingBottom'        => 15,
					'paddingLeft'          => 50,
				),
			),
			array(
				'id'          => 16,
				'type'        => 'OrderItem',
				'nameIcon'    => 'table',
				'nameElement' => 'Order Item',
				'settingRow'  => array(
					'contentBefore'   => '[yaymail_items_border_before]',
					'contentAfter'    => '[yaymail_items_border_after]',
					'contentTitle'    => '[yaymail_items_border_title]',
					'content'         => '[yaymail_items_border_content]',
					'backgroundColor' => '#fff',
					'titleColor'      => '#7f54b3',
					'textColor'       => '#636363',
					'borderColor'     => '#e5e5e5',
					'family'          => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
					'paddingTop'      => 15,
					'paddingRight'    => 50,
					'paddingBottom'   => 15,
					'paddingLeft'     => 50,
				),
			),
			array(
				'id'          => 25,
				'type'        => 'Hook',
				'nameIcon'    => 'hook',
				'nameElement' => 'Hook',
				'settingRow'  => array(
					'content'         => '[woocommerce_email_before_order_table]',
					'backgroundColor' => '#fff',
					'textColor'       => '#636363',
					'family'          => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
					'paddingTop'      => 15,
					'paddingRight'    => 50,
					'paddingBottom'   => 15,
					'paddingLeft'     => 50,
				),
			),
			array(
				'id'          => 27,
				'type'        => 'OrderItemDownload',
				'nameIcon'    => 'table',
				'nameElement' => 'Order Item Download',
				'settingRow'  => array(
					'contentTitle'    => '[yaymail_items_downloadable_title]',
					'content'         => '[yaymail_items_downloadable_product]',
					'backgroundColor' => '#fff',
					'titleColor'      => '#7f54b3',
					'textColor'       => '#636363',
					'borderColor'     => '#e5e5e5',
					'family'          => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
					'paddingTop'      => 15,
					'paddingRight'    => 50,
					'paddingBottom'   => 15,
					'paddingLeft'     => 50,
				),
			),
		);

		if ( class_exists( 'WC_Shipment_Tracking_Actions' ) || class_exists( 'WC_Advanced_Shipment_Tracking_Actions' ) ) {
			array_push(
				$this->defaultDataElement,
				array(
					'id'          => 26,
					'type'        => 'TrackingItem',
					'nameIcon'    => 'wallet',
					'nameElement' => 'Tracking Item',
					'settingRow'  => array(
						'content'         => '[yaymail_order_meta:_wc_shipment_tracking_items]',
						'backgroundColor' => '#fff',
						'titleColor'      => '#7f54b3',
						'textColor'       => '#636363',
						'borderColor'     => '#e5e5e5',
						'family'          => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
						'paddingTop'      => 15,
						'paddingRight'    => 50,
						'paddingBottom'   => 15,
						'paddingLeft'     => 50,
					),
				)
			);
		}

		if ( class_exists( 'WC_Admin_Custom_Order_Fields' ) ) {
			array_push(
				$this->defaultDataElement,
				array(
					'id'          => 110,
					'type'        => 'AdditionalOrderDetails',
					'nameIcon'    => 'table',
					'nameElement' => 'Additional Order Details',
					'settingRow'  => array(
						'content'         => '[yaymail_order_meta:_wc_additional_order_details]',
						'backgroundColor' => '#fff',
						'titleColor'      => '#7f54b3',
						'textColor'       => '#636363',
						'borderColor'     => '#e5e5e5',
						'family'          => '"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif',
						'paddingTop'      => 15,
						'paddingRight'    => 50,
						'paddingBottom'   => 15,
						'paddingLeft'     => 50,
					),
				)
			);
		}
	}

}
