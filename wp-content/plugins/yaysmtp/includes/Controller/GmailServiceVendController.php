<?php
namespace YaySMTP\Controller;

use YaySMTP\Helper\Utils;

if (!defined('ABSPATH')) {
  exit;
}
class GmailServiceVendController {
  public function __construct() {
    if ($this->clientIDSerect()) {
      $this->getclientWebService();
    }
  }

  /**
   * Update access token in our DB.
   */
  public function saveAccessToken($token) {
    Utils::setYaySmtpSetting('gmail_access_token', $token, 'gmail');
  }

  /**
   * Update refresh token in our DB.
   */
  public function saveRefToken($token) {
    Utils::setYaySmtpSetting('gmail_refresh_token', $token, 'gmail');
  }

  public function saveAuthorizeCode($code) {
    Utils::setYaySmtpSetting('gmail_auth_code', $code, 'gmail');
  }

  public function clientIDSerect($currentMailer = "") {
    $currentMailer = !empty($currentMailer) ? $currentMailer : 'gmail';
    $settings = Utils::getYaySmtpSetting();
    return !empty($settings) && !empty($settings[$currentMailer]) && !empty($settings[$currentMailer]['client_id']) && !empty($settings[$currentMailer]['client_secret']);
  }

  public function tokenEmpty($currentMailer = "") {
    $currentMailer = !empty($currentMailer) ? $currentMailer : 'gmail';
    $settings = Utils::getYaySmtpSetting();
    return empty($settings) || empty($settings[$currentMailer]) || empty($settings[$currentMailer]['gmail_access_token']) || empty($settings[$currentMailer]['gmail_refresh_token']);
  }

  public function getclientWebService() {
    $Google_Client = $this->googleClientObj();
    $Google_Client->setScopes('https://mail.google.com/');
    $Google_Client->setApprovalPrompt('force');
    $Google_Client->setAccessType('offline');
    $Google_Client->setRedirectUri(add_query_arg(
      array(
        'page' => 'yaysmtp',
        'action' => 'serviceauthyaysmtp',
      ),
      admin_url('options-general.php')
    ));

    $this->saveAccessTokenWithCode($Google_Client);
    $this->saveAccessTokenExpire($Google_Client);
    return $Google_Client;
  }

  public function processAuthorizeServive() {
    if (isset($_GET['action']) && sanitize_key($_GET['action']) == "serviceauthyaysmtp") {
      $scopeMailGoogle = '';
      if (isset($_GET['scope'])) {
        $scopeMailGoogle = sanitize_text_field($_GET['scope']);
        if ($scopeMailGoogle == 'https://mail.google.com/') {
          $codeMailGoogle = '';
          if (isset($_GET['code'])) {
            $codeMailGoogle = urldecode($_GET['code']);
          }
          $this->saveAuthorizeCode($codeMailGoogle);
        }
      }

      wp_safe_redirect(
        Utils::getAdminPageUrl()
      );
    }
  }

  public function setUserInf($clientWebService) {
    $ServiceGmail = new \Google_Service_Gmail($clientWebService);

    try {
      $mail = $ServiceGmail->users->getProfile('me')->getEmailAddress();
    } catch (\Exception $e) {
      $mail = '';
    }

    Utils::setYaySmtpSetting('gmail_auth_email', $mail, 'gmail');
  }

  public function getSetting($name) {
    $settings = Utils::getYaySmtpSetting();
    if (!empty($settings) && !empty($settings['gmail']) && !empty($settings['gmail'][$name])) {
      return $settings['gmail'][$name];
    } else {
      return "";
    }
  }

  private function googleClientObj() {
    return new \Google_Client(array(
      'client_id' => $this->getSetting('client_id'),
      'client_secret' => $this->getSetting('client_secret'),
      'redirect_uris' => array(
        add_query_arg(
          array(
            'page' => 'yaysmtp',
            'action' => 'serviceauthyaysmtp',
          ),
          admin_url('options-general.php')
        ),
      ),
    ));
  }

  private function saveAccessTokenExpire($googleClient) {
    if (!empty($this->getSetting('gmail_access_token'))) {
      $googleClient->setAccessToken($this->getSetting('gmail_access_token'));
    }
    if ($googleClient->isAccessTokenExpired()) {
      $refTokenVal = $googleClient->getRefreshToken();
      if (!empty($this->getSetting('gmail_refresh_token')) && empty($refTokenVal)) {
        $refTokenVal = $this->getSetting('gmail_refresh_token');
      }
      if (!empty($refTokenVal)) {
        $googleClient->fetchAccessTokenWithRefreshToken($refTokenVal);
        $this->saveAccessToken($googleClient->getAccessToken());
        $this->saveRefToken($refTokenVal);
      }
    }
  }

  private function saveAccessTokenWithCode($googleClient) {
    if (!empty($this->getSetting('gmail_auth_code')) && $this->tokenEmpty()) {
      $googleClient->fetchAccessTokenWithAuthCode($this->getSetting('gmail_auth_code'));
      $this->setUserInf($googleClient);
      $this->saveRefToken($googleClient->getRefreshToken());
      $this->saveAccessToken($googleClient->getAccessToken());
    }
  }
}
