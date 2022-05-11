<?php
namespace YaySMTP;

use YaySMTP\Helper\Utils;

defined('ABSPATH') || exit;

class Dashboard {
  protected static $instance = null;

  public static function getInstance() {
    if (null == self::$instance) {
      self::$instance = new self;
      self::$instance->doHooks();
    }

    return self::$instance;
  }

  private function doHooks() {
    add_action('wp_dashboard_setup', array($this, 'init'));
  }

  private function __construct() {}

  public function init() {
    wp_add_dashboard_widget('yaysmtp_analytics_email', __('YaySMTP Stats', 'yay-smtp'), array($this, 'analyticsEmailWidget'), null, null, 'normal', 'high');
  }

  public function analyticsEmailWidget() {
    $templatePart = YAY_SMTP_PLUGIN_PATH . "includes/Views/template-part";
    Utils::getTemplatePart($templatePart, 'analytics-email-widget-tpl', array());
  }
}
