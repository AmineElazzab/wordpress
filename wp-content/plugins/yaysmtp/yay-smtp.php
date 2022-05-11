<?php
/**
 * Plugin Name: YaySMTP - Simple WP SMTP Mail
 * Plugin URI: https://yaycommerce.com/yaysmtp
 * Description: This plugin helps you send emails from your WordPress website via your preferred SMTP server.
 * Version: 2.1.1
 * Author: YayCommerce
 * Author URI: https://yaycommerce.com
 * Text Domain: yaysmtp
 * Domain Path: /i18n/languages/
 */

namespace YaySMTP;

defined('ABSPATH') || exit;

if (!defined('YAY_SMTP_PREFIX')) {
  define('YAY_SMTP_PREFIX', 'yay-smtp');
}
if (!defined('YAY_SMTP_VERSION')) {
  define('YAY_SMTP_VERSION', '2.1.1');
}

if (!defined('YAY_SMTP_DOMAIN')) {
  define('YAY_SMTP_DOMAIN', 'yay-smtp');
}

if (!defined('YAY_SMTP_PLUGIN_URL')) {
  define('YAY_SMTP_PLUGIN_URL', plugin_dir_url(__FILE__));
}

if (!defined('YAY_SMTP_PLUGIN_PATH')) {
  define('YAY_SMTP_PLUGIN_PATH', plugin_dir_path(__FILE__));
}

if (!defined('YAY_SMTP_PLUGIN_BASENAME')) {
  define('YAY_SMTP_PLUGIN_BASENAME', plugin_basename(__FILE__));
}

if (!defined('YAY_SMTP_SITE_URL')) {
  define('YAY_SMTP_SITE_URL', site_url());
}

if (!defined('YAY_SMTP_PLUGIN_NAME')) {
  define('YAY_SMTP_PLUGIN_NAME', 'yay-smtp');
}

spl_autoload_register(function ($class) {
  $prefix = __NAMESPACE__; // project-specific namespace prefix
  $base_dir = __DIR__ . '/includes'; // base directory for the namespace prefix

  $len = strlen($prefix);
  if (strncmp($prefix, $class, $len) !== 0) { // does the class use the namespace prefix?
    return; // no, move to the next registered autoloader
  }

  $relative_class_name = substr($class, $len);

  // replace the namespace prefix with the base directory, replace namespace
  // separators with directory separators in the relative class name, append
  // with .php
  $file = $base_dir . str_replace('\\', '/', $relative_class_name) . '.php';

  if (file_exists($file)) {
    require $file;
  }
});

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor_amazon/autoload.php';

$base_dir_vendor_league = __DIR__ . '/vendor/league/src/';
require_once $base_dir_vendor_league . 'Tool/RequiredParameterTrait.php';
require_once $base_dir_vendor_league . 'Tool/QueryBuilderTrait.php';
require_once $base_dir_vendor_league . 'Tool/ArrayAccessorTrait.php';
require_once $base_dir_vendor_league . 'Tool/BearerAuthorizationTrait.php';
require_once $base_dir_vendor_league . 'Tool/GuardedPropertyTrait.php';
require_once $base_dir_vendor_league . 'Tool/MacAuthorizationTrait.php';
require_once $base_dir_vendor_league . 'Tool/ProviderRedirectTrait.php';
require_once $base_dir_vendor_league . 'Tool/RequestFactory.php';
require_once $base_dir_vendor_league . 'OptionProvider/OptionProviderInterface.php';
require_once $base_dir_vendor_league . 'Provider/ResourceOwnerInterface.php';
require_once $base_dir_vendor_league . 'Token/AccessTokenInterface.php';
require_once $base_dir_vendor_league . 'Token/ResourceOwnerAccessTokenInterface.php';
require_once $base_dir_vendor_league . 'OptionProvider/PostAuthOptionProvider.php';
require_once $base_dir_vendor_league . 'Grant/Exception/InvalidGrantException.php';
require_once $base_dir_vendor_league . 'Grant/AbstractGrant.php';
require_once $base_dir_vendor_league . 'Grant/AuthorizationCode.php';
require_once $base_dir_vendor_league . 'Grant/ClientCredentials.php';
require_once $base_dir_vendor_league . 'Grant/GrantFactory.php';
require_once $base_dir_vendor_league . 'Grant/Password.php';
require_once $base_dir_vendor_league . 'Grant/RefreshToken.php';
require_once $base_dir_vendor_league . 'OptionProvider/HttpBasicAuthOptionProvider.php';
require_once $base_dir_vendor_league . 'Provider/Exception/IdentityProviderException.php';
require_once $base_dir_vendor_league . 'Provider/AbstractProvider.php';
require_once $base_dir_vendor_league . 'Provider/GenericProvider.php';
require_once $base_dir_vendor_league . 'Provider/GenericResourceOwner.php';
require_once $base_dir_vendor_league . 'Token/AccessToken.php';

if (version_compare(get_bloginfo('version'), '5.5-alpha', '<')) {
  if (!class_exists('\PHPMailer', false)) {
    require_once ABSPATH . 'wp-includes/class-phpmailer.php';
  }
} else {
  if (!class_exists('\PHPMailer\PHPMailer\PHPMailer', false)) {
    require_once ABSPATH . 'wp-includes/PHPMailer/PHPMailer.php';
  }
  if (!class_exists('\PHPMailer\PHPMailer\Exception', false)) {
    require_once ABSPATH . 'wp-includes/PHPMailer/Exception.php';
  }
  if (!class_exists('\PHPMailer\PHPMailer\SMTP', false)) {
    require_once ABSPATH . 'wp-includes/PHPMailer/SMTP.php';
  }
}

function init() {
  Dashboard::getInstance();
  Schedule::getInstance();
  Plugin::getInstance();
  ImportSettingsOtherPlugins::getInstance();
}
add_action('plugins_loaded', 'YaySMTP\\init');

register_activation_hook(__FILE__, array('YaySMTP\\Plugin', 'activate'));
register_deactivation_hook(__FILE__, array('YaySMTP\\Plugin', 'deactivate'));
