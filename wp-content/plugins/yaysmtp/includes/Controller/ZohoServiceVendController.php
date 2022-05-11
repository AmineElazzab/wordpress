<?php
namespace YaySMTP\Controller;

use YaySMTP\Helper\LogErrors;
use YaySMTP\Helper\Utils;

if (!defined('ABSPATH')) {
  exit;
}

class ZohoServiceVendController {
  public $currentMailer = 'zoho';

  public function updAuthCode($code) {
    Utils::setYaySmtpSetting('auth_code', $code, $this->currentMailer);
  }

  public function clientIDSerect($currentMailer = "") {
    $currentMailer = !empty($currentMailer) ? $currentMailer : $this->currentMailer;
    $settings = Utils::getYaySmtpSetting();
    return !empty($settings) && !empty($settings[$currentMailer]) && !empty($settings[$currentMailer]['client_id']) && !empty($settings[$currentMailer]['client_secret']);
  }

  public function __construct() {
    if ($this->clientIDSerect()) {
      $this->auth_info = $this->get_auth_info();
    }

  }

  public static function getPluginAuthUrl() {
    $auth_url = add_query_arg(
      array(
        'page' => 'yaysmtp'),
      admin_url('options-general.php')
    );
    return $auth_url;
  }

  /**
   *  Generate the apiLink for user to get the auth CODE from zoho
   */
  public static function generate_auth_code_url() {

    $cl_id = self::getSetting('client_id');

    $apiLink = 'https://accounts.zoho.com/oauth/v2/auth';

    $scope = 'ZohoMail.accounts.READ,ZohoMail.messages.ALL';

    $response_type = 'code';

    $access_type = 'offline';

    $redirect_uri = YAY_SMTP_SITE_URL . '/wp-admin/options-general.php?page=yaysmtp';

    return $apiLink . '?scope=' . $scope . '&client_id=' . $cl_id . '&response_type=' . $response_type . '&access_type=' . $access_type . '&prompt=consent' . '&redirect_uri=' . $redirect_uri;

  }

  /**
   * Access to Auth 2.0 Zoho API to get access token
   */
  public function get_tokens() {

    $redirect_uri = YAY_SMTP_SITE_URL . '/wp-admin/options-general.php?page=yaysmtp';

    $apiLink = 'https://accounts.zoho.com/oauth/v2/token';

    $query = array(
      'code' => $this->getSetting('auth_code'),
      'client_id' => $this->getSetting('client_id'),
      'client_secret' => $this->getSetting('client_secret'),
      'redirect_uri' => $redirect_uri,
      'grant_type' => 'authorization_code',
    );

    $request_url = $apiLink . '?' . http_build_query($query);

    try {
      $response = wp_remote_post($request_url);
      return $response['body'];
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  /**
   * Get all information to pass in the auth code apiLink
   */
  public function get_auth_info() {

    if (!empty($this->auth_info)) {
      return $this->auth_info;
    }

    $params = array();
    $params['client_id'] = $this->getSetting('client_id');
    $params['client_secret'] = $this->getSetting('client_secret');
    $params['redirect_uri'] = self::getPluginAuthUrl();
    $params['access_type'] = 'offline';
    $params['scope'] = 'ZohoMail.messages.ALL';
    $params['response_type'] = 'code';

    return $params;
  }

  /**
   * Check whether the Access Token is expired or not having the Access Token
   */
  public static function isExpired() {

    $now = strtotime('now');

    if (self::getSetting('created_at') === '' || self::getSetting('expires_in') === '') {
      return true;
    }

    if (self::getSetting('created_at') + self::getSetting('expires_in') < $now || self::getSetting('created_at') === 0 || self::getSetting('expires_in') === 0) {
      return true;
    }

    return false;
  }

  /**
   * Saving all informations to Database
   */
  public function processAuthorizeServive() {

    if (isset($_GET['code']) && isset($_GET['page'])) {
      if (!$this->clientIDSerect()) {
        LogErrors::setErr(
          esc_html__('Have Client ID and Client Secret both valid and saved.', 'yay-smtp')
        );
      }

      $code = '';

      if (isset($_GET['code'])) {
        $code = urldecode($_GET['code']);
      }

      // Let's try to get the access token.
      if (!empty($code)) {

        $this->updAuthCode($code);

        $tokens = $this->get_tokens();
        $tokens = json_decode($tokens);

        if (isset($tokens->error)) {
          LogErrors::setErr($tokens->error);
        } else {

          Utils::setYaySmtpSetting('token_client_id', $this->getSetting('client_id'), 'zoho');
          Utils::setYaySmtpSetting('token_client_secret', $this->getSetting('client_secret'), 'zoho');
          Utils::setYaySmtpSetting('access_token', $tokens->access_token, 'zoho');
          Utils::setYaySmtpSetting('refresh_token', isset($tokens->refresh_token) ? $tokens->refresh_token : '', 'zoho');
          Utils::setYaySmtpSetting('token_type', $tokens->token_type, 'zoho');
          Utils::setYaySmtpSetting('created_at', strtotime('now'), 'zoho');
          Utils::setYaySmtpSetting('expires_in', $tokens->expires_in, 'zoho');
        }
      } else {
        LogErrors::setErr(
          esc_html__('No code received', 'yay-smtp')
        );
      }
    } else {
      if (self::isExpired()) {
        if (self::getSetting('refresh_token')) {
          $regenerate_url = 'https://accounts.zoho.com/oauth/v2/token?';
          $regenerate_url .= 'refresh_token=' . self::getSetting('refresh_token');
          $regenerate_url .= '&client_id=' . self::getSetting('client_id');
          $regenerate_url .= '&client_secret=' . self::getSetting('client_secret');
          $regenerate_url .= '&grant_type=refresh_token';
          $response = wp_remote_post($regenerate_url);
          $response = json_decode($response['body']);
          $new_access_token = $response->access_token;
          Utils::setYaySmtpSetting('access_token', $new_access_token, 'zoho');
          Utils::setYaySmtpSetting('created_at', strtotime('now'), 'zoho');
        }

      } else {
        return;
      }
    }
  }

  /**
   * Get zoho setting
   */
  public static function getSetting($name) {
    $settings = Utils::getYaySmtpSetting();
    if (!empty($settings) && !empty($settings['zoho']) && !empty($settings['zoho'][$name])) {
      return $settings['zoho'][$name];
    } else {
      return "";
    }
  }

  /**
   * Check whether user saves a new Client Id or Client Secret (for resetting token purpose)
   * @return boolen
   */
  public static function isDiffInfo() {
    if (self::getSetting('client_id') !== self::getSetting('token_client_id') || self::getSetting('client_secret') !== self::getSetting('token_client_secret')) {
      return true;
    }

    return false;
  }

  /**
   * Check is authorized ?
   * @return boolean
   */
  public function tokenEmpty($currentMailer = "") {
    $currentMailer = !empty($currentMailer) ? $currentMailer : $this->currentMailer;
    $settings = Utils::getYaySmtpSetting();
    return empty($settings) || empty($settings[$currentMailer]) || empty($settings[$currentMailer]['access_token']);
  }

  /**
   * Reset all token and referenced information
   */
  public static function doResetToken() {
    Utils::setYaySmtpSetting('access_token', '', 'zoho');
    Utils::setYaySmtpSetting('refresh_token', '', 'zoho');
    Utils::setYaySmtpSetting('created_at', 0, 'zoho');
    Utils::setYaySmtpSetting('expires_in', 0, 'zoho');
    Utils::setYaySmtpSetting('token_client_id', '', 'zoho');
    Utils::setYaySmtpSetting('token_client_secret', '', 'zoho');
  }

}
