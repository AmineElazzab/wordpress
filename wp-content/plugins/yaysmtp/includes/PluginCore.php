<?php
namespace YaySMTP;

use YaySMTP\Controller\GmailServiceVendController;
use YaySMTP\Controller\OutlookMsServicesController;
use YaySMTP\Controller\ZohoServiceVendController;
use YaySMTP\Helper\Utils;

defined('ABSPATH') || exit;

class PluginCore {
  protected static $instance = null;

  public static function getInstance() {
    if (null == self::$instance) {
      self::$instance = new self;
      self::$instance->doHooks();
    }
    return self::$instance;
  }

  private function doHooks() {
    $this->getProcessor();
    global $phpmailer;
    $phpmailer = new PhpMailerExtends();
    add_action('init', array($this, 'actionForSmtpsHasAuth'));
  }

  private function __construct() {}

  public function getProcessor() {
    add_action('phpmailer_init', array($this, 'doSmtperInit'));
    add_filter('wp_mail_from', array($this, 'getFromAddress'));
    add_filter('wp_mail_from_name', array($this, 'getFromName'));
  }

  public function actionForSmtpsHasAuth() {
    if (is_admin()) {
      $currentEmail = Utils::getCurrentMailer();
      if ($currentEmail === 'gmail') {
        $gmailService = new GmailServiceVendController();
        $gmailService->processAuthorizeServive();
      } elseif ($currentEmail === 'zoho') {
        $zohoService = new ZohoServiceVendController();
        $zohoService->processAuthorizeServive();
      } elseif ($currentEmail === 'outlookms') {
        $outlookmsService = new OutlookMsServicesController();
        $outlookmsService->processAuthorizeServive();
      }
    }

  }

  public function getDefaultMailFrom() {
    $sitename = \wp_parse_url(\network_home_url(), PHP_URL_HOST);
    if ('www.' === substr($sitename, 0, 4)) {
      $sitename = substr($sitename, 4);
    }

    $from_email = 'wordpress@' . $sitename;

    return $from_email;
  }

  public function getFromAddress($email) {
    $emailDefault = $this->getDefaultMailFrom();
    $fromEmail = Utils::getCurrentFromEmail();
    if (Utils::getForceFromEmail() == 1) {
      return $fromEmail;
    }
    if (!empty($emailDefault) && $email !== $emailDefault) {
      return $email;
    }

    return $fromEmail;
  }

  public function getFromName($name) {
    $nameDefault = 'WordPress';
    $forceFromName = Utils::getForceFromName();
    if ($forceFromName == 0 && $name !== $nameDefault) {
      return $name;
    }

    return Utils::getCurrentFromName();
  }

  public function doSmtperInit($obj) {
    $currentMailer = Utils::getCurrentMailer();

    $obj->Mailer = $currentMailer;

    $settings = Utils::getYaySmtpSetting();
    $smtpSettings = (!empty($settings) && !empty($settings['smtp'])) ? $settings['smtp'] : array();

    $useFallbackSmtp = Utils::conditionUseFallbackSmtp(); 

    if( $useFallbackSmtp ){
      $obj->Mailer = 'smtp';
      if (!empty($settings['fallback_host'])) {
        $obj->Host = $settings['fallback_host'];
      }

      if (!empty($settings['fallback_port'])) {
        $obj->Port = (int) $settings['fallback_port'];
      }

      $obj->SMTPSecure = !empty($settings['fallback_auth_type']) && $settings['fallback_auth_type'] == 'ssl' ? 'ssl' : 'tls';

      if (!empty($settings['fallback_auth']) && $settings['fallback_auth'] == 'yes') {
        $obj->SMTPAuth = true;

        if (!empty($settings['fallback_smtp_user'])) {
          $obj->Username = $settings['fallback_smtp_user'];
        }

        if (!empty($settings['fallback_smtp_pass'])) {
          $obj->Password = Utils::decrypt($settings['fallback_smtp_pass'], 'smtppass');
        }
      }

      // Set wp_mail_from && wp_mail_from_name - start
      $currentFromEmail = Utils::getCurrentFromEmailFallback();
      $currentFromName = Utils::getCurrentFromNameFallback();
      $from_email = apply_filters('wp_mail_from', $currentFromEmail);
      $from_name = apply_filters('wp_mail_from_name', $currentFromName);
      if (isset($settings['fallback_force_from_email']) && 'yes' == $settings['fallback_force_from_email']) {
        $from_email = $currentFromEmail;
      }
      if (isset($settings['fallback_force_from_name']) && 'yes' == $settings['fallback_force_from_name']) {
        $from_name = $currentFromName;
      }

      $obj->setFrom($from_email, $from_name, false);
      // Set wp_mail_from && wp_mail_from_name - end
    }else{
      if ('smtp' == $currentMailer) {
        if (!empty($smtpSettings['host'])) {
          $obj->Host = $smtpSettings['host'];
        }
  
        if (!empty($smtpSettings['port'])) {
          $obj->Port = (int) $smtpSettings['port'];
        }
  
        $obj->SMTPSecure = !empty($smtpSettings['encryption']) && $smtpSettings['encryption'] == 'ssl' ? 'ssl' : 'tls';

        if (!empty($smtpSettings['auth']) && $smtpSettings['auth'] == 'yes') {
          $obj->SMTPAuth = true;
  
          if (!empty($smtpSettings['user'])) {
            $obj->Username = $smtpSettings['user'];
          }
  
          if (!empty($smtpSettings['pass'])) {
            $obj->Password = Utils::decrypt($smtpSettings['pass'], 'smtppass');
          }
        }
  
        // Set wp_mail_from && wp_mail_from_name - start
        $currentFromEmail = Utils::getCurrentFromEmail();
        $currentFromName = Utils::getCurrentFromName();
        $from_email = apply_filters('wp_mail_from', $currentFromEmail);
        $from_name = apply_filters('wp_mail_from_name', $currentFromName);
        if (Utils::getForceFromEmail() == 1) {
          $from_email = $currentFromEmail;
        }
        if (Utils::getForceFromName() == 1) {
          $from_name = $currentFromName;
        }
  
        $obj->setFrom($from_email, $from_name, false);
        // Set wp_mail_from && wp_mail_from_name - end
  
      } else {
        $obj->SMTPSecure  = '';
      }
    }
    
  }
}
