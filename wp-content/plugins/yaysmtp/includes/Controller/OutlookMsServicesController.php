<?php
namespace YaySMTP\Controller;

use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;
use YaySMTP\Helper\Utils;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

if (!defined('ABSPATH')) {
  exit;
}
class OutlookMsServicesController {
  public function __construct() {
    if ($this->clientIDSerect()) {
      $this->getclientWebService();
    }
  }

  /**
   * Update access token in our DB.
   */
  public function saveAccessToken($token) {
    Utils::setYaySmtpSetting('outlookms_access_token', $token, 'outlookms');
  }

  /**
   * Update refresh token in our DB.
   */
  public function saveRefToken($token) {
    Utils::setYaySmtpSetting('outlookms_refresh_token', $token, 'outlookms');
  }

  public function saveAuthorizeCode($code) {
    Utils::setYaySmtpSetting('outlookms_auth_code', $code, 'outlookms');
  }

  public function clientIDSerect($currentMailer = "") {
    $currentMailer = !empty($currentMailer) ? $currentMailer : 'outlookms';
    $settings = Utils::getYaySmtpSetting();
    return !empty($settings) && !empty($settings[$currentMailer]) && !empty($settings[$currentMailer]['client_id']) && !empty($settings[$currentMailer]['client_secret']);
  }

  public function tokenEmpty($currentMailer = "") {
    $currentMailer = !empty($currentMailer) ? $currentMailer : 'outlookms';
    $settings = Utils::getYaySmtpSetting();
    return empty($settings) || empty($settings[$currentMailer]) || empty($settings[$currentMailer]['outlookms_access_token']) || empty($settings[$currentMailer]['outlookms_refresh_token']);
  }

  public function getclientWebService() {
    try {
      $client = $this->msClientObj();
      $this->saveAccessTokenWithCode($client);
      $this->saveAccessTokenExpire($client);
      return $client;
    } catch ( IdentityProviderException $e) {
      return false;
    }
  }

  public function processAuthorizeServive() {
    if (isset($_GET['code']) && isset($_GET['state'])) {
      $code = '';
      if (isset($_GET['code']) && isset($_GET['state'])) {
        $code = sanitize_text_field($_GET['code']);
      }
      $this->saveAuthorizeCode($code);

      wp_safe_redirect(
        Utils::getAdminPageUrl()
      );
    }
  }

  public function setUserInf($clientWebService) {
    try {
      $accessToken = new AccessToken((array) $this->getSetting('outlookms_access_token'));
      $owner = $clientWebService->getResourceOwner($accessToken);
      $data = $owner->toArray();
      $mail = array(
        'name' => $data['displayName'],
        'email' => $data['userPrincipalName'],
      );
    } catch (\Exception $e) {
      $mail = '';
    }

    Utils::setYaySmtpSetting('outlookms_auth_email', $mail, 'outlookms');
  }

  public function getSetting($name) {
    $settings = Utils::getYaySmtpSetting();
    if (!empty($settings) && !empty($settings['outlookms']) && !empty($settings['outlookms'][$name])) {
      return $settings['outlookms'][$name];
    } else {
      return "";
    }
  }

  private function msClientObj() {
    return new GenericProvider(
      array(
        'clientId' => $this->getSetting('client_id'),
        'clientSecret' => $this->getSetting('client_secret'),
        'redirectUri' => admin_url('options-general.php'),
        'urlAuthorize' => 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize',
        'urlAccessToken' => 'https://login.microsoftonline.com/common/oauth2/v2.0/token',
        'urlResourceOwnerDetails' => 'https://graph.microsoft.com/v1.0/me',
        'scopeSeparator' => ' ',
      )
    );

  }

  private function saveAccessTokenExpire($msClient) {
    if (!empty($this->getSetting('outlookms_access_token'))) {
      $accessToken = new AccessToken((array) $this->getSetting('outlookms_access_token'));
      if ($accessToken->hasExpired()) {
        $newAccessToken = $msClient->getAccessToken(
          'refresh_token', array('refresh_token' => $accessToken->getRefreshToken())
        );

        $this->saveAccessToken($newAccessToken->jsonSerialize());
        $this->saveRefToken($newAccessToken->getRefreshToken());
      }

    }
  }

  private function saveAccessTokenWithCode($msClient) {
    if (!empty($this->getSetting('outlookms_auth_code')) && $this->tokenEmpty()) {
      $accessToken = $msClient->getAccessToken(
        'authorization_code', array('code' => $this->getSetting('outlookms_auth_code'))
      );

      $this->saveAccessToken($accessToken->jsonSerialize());
      $this->saveRefToken($accessToken->getRefreshToken());
      $this->setUserInf($msClient);
      $this->saveAuthorizeCode('');
    }
  }
}
