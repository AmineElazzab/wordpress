<?php
namespace YaySMTP;

defined('ABSPATH') || exit;

/**
 * Plugin activate/deactivate logic
 */
class Plugin {
  protected static $instance = null;

  public static function getInstance() {
    if (null == self::$instance) {
      self::$instance = new self;
      self::$instance->doHooks();
    }

    return self::$instance;
  }

  private function doHooks() {
    Helper\Installer::getInstance();
    Page\Settings::getInstance();
    PluginCore::getInstance();
    Functions::getInstance();
  }

  private function __construct() {}

  /** Plugin activated hook */
  public static function activate() {}

  /** Plugin deactivate hook */
  public static function deactivate() {}
}
