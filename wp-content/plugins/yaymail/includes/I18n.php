<?php
namespace YayMail;

defined( 'ABSPATH' ) || exit;
/**
 * I18n Logic
 */
class I18n {

	protected static $instance = null;

	public static function getInstance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
			self::$instance->doHooks();
		}

		return self::$instance;
	}

	private function doHooks() {
		add_action( 'plugins_loaded', array( $this, 'loadPluginTextdomain' ) );
	}

	private function __construct() {}

	public function loadPluginTextdomain() {
		load_plugin_textdomain(
			'yaymail',
			false,
			YAYMAIL_PLUGIN_URL . 'i18n/languages/'
		);
	}

	public static function jsTranslate() {
		return array(
			'ELEMENTS' => __( 'ELEMENTS', 'yaymail' ),
			'SETTINGS' => __( 'SETTINGS', 'yaymail' ),
			'WooCommerceEmailCustomizer' => __( 'WooCommerce Email Customizer', 'yaymail' ),
			'BACKGROUNDCOLOR' => __( 'BACKGROUND COLOR', 'yaymail' ),
			'SelectColor' => __( 'Select Color', 'yaymail' ),
			'Basic' => __( 'Basic', 'yaymail' ),
			'General' => __( 'General', 'yaymail' ),
			'WooCommerce' => __( 'WooCommerce', 'yaymail' ),
			'DIRECTIONRTL' => __( 'DIRECTION RTL', 'yaymail' ),
			'CONTAINERWIDTHPX' => __( 'CONTAINER WIDTH (PX)', 'yaymail' ),
			'Emailwidthmustbe' => __( 'Email width must be 480px (min) - 900px (max)', 'yaymail' ),
			'Pleaseinputcontainerwidth' => __( 'Please input container width!', 'yaymail' ),
			'DISPLAYPAYMENTINSTRUCTIONANDDETAILS' => __( 'DISPLAY PAYMENT INSTRUCTION AND DETAILS', 'yaymail' ),
			'Yes' => __( 'Yes', 'yaymail' ),
			'No' => __( 'No', 'yaymail' ),
			'Onlyforcustomer' => __( 'Only for customer', 'yaymail' ),
			'SHOWPRODUCTIMAGE' => __( 'SHOW PRODUCT IMAGE', 'yaymail' ),
			'PRODUCTIMAGEPOSITION' => __( 'PRODUCT IMAGE POSITION', 'yaymail' ),
			'IMAGESIZE' => __( 'IMAGE SIZE', 'yaymail' ),
			'Thumbnail' => __( 'Thumbnail', 'yaymail' ),
			'Full' => __( 'Full', 'yaymail' ),
			'IMAGEHEIGHT' => __( 'IMAGE HEIGHT (PX)', 'yaymail' ),
			'Imageheightmustbe' => __( 'Image height must be 30px (min) - 300px (max)', 'yaymail' ),
			'Pleaseinputimgageheight' => __( 'Please input imgage height!', 'yaymail' ),
			'IMAGEWIDTH' => __( 'IMAGE WIDTH (PX)', 'yaymail' ),
			'Imagewidthmustbe' => __( 'Image width must be 30px (min) - 300px (max)', 'yaymail' ),
			'Pleaseinputimgagewidth' => __( 'Please input imgage width!', 'yaymail' ),
			'SHOWPRODUCTSKU' => __( 'SHOWPRODUCT SKU', 'yaymail' ),
			'SHOWPRODUCTDESCRIPTION' => __( 'SHOW PRODUCT DESCRIPTION', 'yaymail' ),
			'CUSTOMCSS' => __( 'CUSTOM CSS', 'yaymail' ),
			'Refresh' => __( 'Refresh', 'yaymail' ),
			'Cancel' => __( 'Cancel', 'yaymail' ),
			'Changeemailsubjectandformname' => __( 'Change email subject and form name', 'yaymail' ),
			'Clickhere' => __( 'Click here', 'yaymail' ),
			'Pleasecheckagain' => __( 'Please check again', 'yaymail' ),
			'Save' => __( 'Save', 'yaymail' ),
			'PleaserefreshpagetoapplyCustomCSS' => __( 'Please refresh page to apply Custom CSS. Make sure to save your changes before refreshing.', 'yaymail' ),
			'EmailSettings' => __( 'Email Settings', 'yaymail' ),
			'BACKGROUNDCOLOR' => __( 'BACKGROUND COLOR', 'yaymail' ),
			'SelectColor' => __( 'Select Color', 'yaymail' ),
			'EMAILCONTENTBACKGROUNDCOLOR' => __( 'EMAIL CONTENT BACKGROUND COLOR', 'yaymail' ),
			'ResetDefault' => __( 'Reset Default', 'yaymail' ),
			'TEXTLINKCOLOR' => __( 'TEXT LINK COLOR', 'yaymail' ),
			'EnableDisableTemplates' => __( 'Enable/Disable Templates', 'yaymail' ),
			'ResetTemplates' => __( 'Reset Templates', 'yaymail' ),
			'RESETALLTEMPLATESTODEFAULT' => __( 'RESET ALL TEMPLATES TO DEFAULT', 'yaymail' ),
			'Reset' => __( 'Reset', 'yaymail' ),
			'Yessure' => __( 'Yes, sure!', 'yaymail' ),
			'Allyoursavedtemplatewillberesettodefault' => __( 'All your saved template will be reset to default', 'yaymail' ),
			'ImportExportTemplates' => __( 'Import/Export Templates', 'yaymail' ),
			'EXPORT' => __( 'EXPORT', 'yaymail' ),
			'ExportTemplates' => __( 'Export Templates', 'yaymail' ),
			'IMPORTTEMPLATES' => __( 'IMPORT TEMPLATES', 'yaymail' ),
			'ChooseFile' => __( 'Choose File1', 'yaymail' ),
			'Import' => __( 'Import', 'yaymail' ),
			'Nofilechosen' => __( 'No file chosen', 'yaymail' ),
			'BACKTOWORDPRESS' => __( 'BACK TO DASHBOARD', 'yaymail' ),
			'Sampleordertoshow' => __( 'Sample order to show', 'yaymail' ),
			'Shortcodes' => __( 'Shortcodes', 'yaymail' ),
			'generals' => __( 'generals', 'yaymail' ),
			'orderDetails' => __( 'order Details', 'yaymail' ),
			'shippings' => __( 'shippings', 'yaymail' ),
			'billings' => __( 'billings', 'yaymail' ),
			'Vendor' => __( 'Vendor', 'yaymail' ),
			'payments' => __( 'payments', 'yaymail' ),
			'newusers' => __( 'new users', 'yaymail' ),
			'resetpassword' => __( 'reset password', 'yaymail' ),
			'ORDERTAXES' => __( 'ORDER TAXES', 'yaymail' ),
			'CUSTOMORDERMETA' => __( 'CUSTOM ORDER META', 'yaymail' ),
			'AUTOMATEWOO' => __( 'AUTOMATEWOO', 'yaymail' ),
			'SendEmail' => __( 'Send Email', 'yaymail' ),
			'Emailaddressfortest' => __( 'Email address for test', 'yaymail' ),
			'Emailsentsuccessfully' => __( 'Yay! Email sent successfully.', 'yaymail' ),
			'Tosendemailstoinboxwerecommend' => __( 'To send emails to inbox, we recommend:', 'yaymail' ),
			'YaySMTPSimpleWPSMTPMail' => __( 'YaySMTP – Simple WP SMTP Mail', 'yaymail' ),
			'Details' => __( 'Details', 'yaymail' ),
			'FreeInstallNow' => __( 'Free Install Now', 'yaymail' ),
			'Areyousureyouwanttoleave' => __( 'Are you sure you want to leave?', 'yaymail' ),
			'Leave' => __( 'Leave', 'yaymail' ),
			'Copytemplate' => __( 'Copy template', 'yaymail' ),
			'CopyFrom' => __( 'Copy From', 'yaymail' ),
			'Areyousureyouwanttoresetthistemplate' => __( 'Are you sure you want to reset this template?', 'yaymail' ),
			'Allchangesyoumadewontbesaved' => __( 'All changes you made won\'t be saved.', 'yaymail' ),
			'Preview' => __( 'Preview', 'yaymail' ),
			'Emailpreview' => __( 'Email preview', 'yaymail' ),
			'Desktoppreview' => __( 'Desktop preview', 'yaymail' ),
			'Mobilepreview' => __( 'Mobile preview', 'yaymail' ),
		);
	}
}
