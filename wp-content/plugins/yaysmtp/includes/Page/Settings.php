<?php
namespace YaySMTP\Page;

use YaySMTP\Helper\Utils;

defined('ABSPATH') || exit;

class Settings {
  protected static $instance = null;
  private $hook_suffix;

  public static function getInstance() {
    if (null == self::$instance) {
      self::$instance = new self;
      self::$instance->doHooks();
    }

    return self::$instance;
  }

  private $pageId = null;

  private function doHooks() {
    $this->hook_suffix = array('yay_smtp_main_page');
    add_action('admin_menu', array($this, 'settingsMenu'));
    add_filter('plugin_action_links_' . YAY_SMTP_PLUGIN_BASENAME, array($this, 'pluginActionLinks'));
    add_action('admin_enqueue_scripts', array($this, 'enqueueAdminScripts'));
  }

  private function __construct() {}

  public function settingsMenu() {
    $this->hook_suffix['yay_smtp_main_page'] = add_menu_page(
      __('YaySMTP Manager', 'yay-smtp'),
      __('YaySMTP', 'yay-smtp'),
      'manage_options',
      'yaysmtp',
      array($this, 'settingsPage'),
      'dashicons-email'
    );
  }

  public function pluginActionLinks($links) {
    $action_links = array(
      'settings' => '<a href="' . admin_url('admin.php?page=yaysmtp') . '" aria-label="' . esc_attr__('YaySMTP', 'yay-smtp') . '">' . esc_html__('Settings', 'yay-smtp') . '</a>',
    );
    return array_merge($action_links, $links);
  }

  public function settingsPage() {
    // $tab = !empty($_GET['tab']) ? sanitize_key($_GET['tab']) : '';
    // if ($tab == 'email-logs') {
    //   include_once YAY_SMTP_PLUGIN_PATH . 'includes/Views/mail-logs.php';
    // } else {
    include_once YAY_SMTP_PLUGIN_PATH . 'includes/Views/yay-smtp.php';
    // }
  }

  public function enqueueAdminScripts($screenId) {
    $scriptId = $this->getPageId();
    wp_enqueue_style('yay_smtp_style', YAY_SMTP_PLUGIN_URL . 'assets/css/yay-smtp-admin.css', array(), YAY_SMTP_VERSION);
    // if ($screenId == $this->hook_suffix['yay_smtp_main_page']) {
    $succ_sent_mail_last = "yes";
    $yaysmtpSettings = Utils::getYaySmtpSetting();
    if (!empty($yaysmtpSettings) && isset($yaysmtpSettings['succ_sent_mail_last']) && $yaysmtpSettings['succ_sent_mail_last'] == false) {
      $succ_sent_mail_last = "no";
    }

    wp_enqueue_script($scriptId, YAY_SMTP_PLUGIN_URL . 'assets/js/yay-smtp-admin.js', array(), YAY_SMTP_VERSION, true);
    $yaysmtp_settings = get_option('yaysmtp_settings');
    wp_localize_script($scriptId, 'yaySmtpWpData', array(
      'YAY_SMTP_PLUGIN_PATH' => YAY_SMTP_PLUGIN_PATH,
      'YAY_SMTP_PLUGIN_URL' => YAY_SMTP_PLUGIN_URL,
      'YAY_SMTP_SITE_URL' => YAY_SMTP_SITE_URL,
      'YAY_ADMIN_AJAX' => admin_url('admin-ajax.php'),
      'ajaxNonce' => wp_create_nonce("ajax-nonce"),
      'currentMailer' => Utils::getCurrentMailer(),
      'yaysmtpSettings' => (!empty($yaysmtp_settings) && is_array($yaysmtp_settings)) ? $yaysmtp_settings : array(),
      'succ_sent_mail_last' => $succ_sent_mail_last,
    ));
    wp_enqueue_media();

    wp_enqueue_style('yay_smtp_select2', YAY_SMTP_PLUGIN_URL . 'assets/css/select2.min.css', array(), YAY_SMTP_VERSION);
    wp_enqueue_script('yay_smtp_select2', YAY_SMTP_PLUGIN_URL . 'assets/js/select2.min.js', array(), YAY_SMTP_VERSION, true);
    // }

    wp_enqueue_style('yay_smtp_daterangepicker', YAY_SMTP_PLUGIN_URL . 'assets/css/daterangepicker_custom.css', array(), YAY_SMTP_VERSION);
    wp_enqueue_script('yay_smtp_chart', YAY_SMTP_PLUGIN_URL . 'assets/js/chart.min.js', array(), YAY_SMTP_VERSION, true);
    wp_enqueue_script('yay_smtp_moment', YAY_SMTP_PLUGIN_URL . 'assets/js/moment.min.js', array(), YAY_SMTP_VERSION, true);
    wp_enqueue_script('yay_smtp_daterangepicker', YAY_SMTP_PLUGIN_URL . 'assets/js/daterangepicker_custom.min.js', array(), YAY_SMTP_VERSION, true);
    wp_enqueue_script('yay_smtp_other', YAY_SMTP_PLUGIN_URL . 'assets/js/other-smtp-admin.js', array(), YAY_SMTP_VERSION, true);
  }

  public function getPageId() {
    if (null == $this->pageId) {
      $this->pageId = YAY_SMTP_PREFIX . '-settings';
    }
    return $this->pageId;
  }
}
