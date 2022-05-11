<?php
namespace YaySMTP\Controller;

use YaySMTP\Helper\LogErrors;
use YaySMTP\Helper\Utils;

if (!defined('ABSPATH')) {
  exit;
}
class MailgunController {
  private $smtpObj = 'mailgun';
  private $headers = array();
  private $body = array();
  private $smtper;

  public function __construct($phpmailer) {
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
    $phpmailer->setFrom($from_email, $from_name, false);
    // Set wp_mail_from && wp_mail_from_name - end

    $this->smtper = $phpmailer;
    $this->headers['Authorization'] = 'Basic ' . base64_encode('api:' . $this->getApiKey());

    $headers = $phpmailer->getCustomHeaders();
    foreach ($headers as $header) {
      $name = isset($header[0]) ? $header[0] : false;
      $value = isset($header[1]) ? $header[1] : false;
      $this->body = array_merge($this->body, array('h:' . $name => Utils::saniVal($value)));
    }

    $this->body = array_merge($this->body, array('subject' => $phpmailer->Subject));
    if (!empty($phpmailer->FromName)) {
      $this->body = array_merge($this->body, array('from' => $phpmailer->FromName . ' <' . $phpmailer->From . '>'));
    } else {
      $this->body = array_merge($this->body, array('from' => $phpmailer->From));
    }

    if (!empty($phpmailer->getToAddresses()) && is_array($phpmailer->getToAddresses())) {
      $bodyContentTo = array();
      foreach ($phpmailer->getToAddresses() as $toEmail) {
        $address = isset($toEmail[0]) ? $toEmail[0] : false;
        $name = isset($toEmail[1]) ? $toEmail[1] : false;
        if (!empty($name)) {
          $bodyContentTo[] = $name . ' <' . $address . '>';
        } else {
          $bodyContentTo[] = $address;
        }
      }
      if (!empty($bodyContentTo)) {
        $this->body = array_merge($this->body, array('to' => implode(', ', $bodyContentTo)));
      }
    }

    if (!empty($phpmailer->getCcAddresses()) && is_array($phpmailer->getCcAddresses())) {
      $bodyContentCc = array();
      foreach ($phpmailer->getCcAddresses() as $ccEmail) {
        $address = isset($ccEmail[0]) ? $ccEmail[0] : false;
        $name = isset($ccEmail[1]) ? $ccEmail[1] : false;
        if (!empty($name)) {
          $bodyContentCc[] = $name . ' <' . $address . '>';
        } else {
          $bodyContentCc[] = $address;
        }
      }
      if (!empty($bodyContentCc)) {
        $this->body = array_merge($this->body, array('cc' => implode(', ', $bodyContentCc)));
      }
    }

    if (!empty($phpmailer->getBccAddresses()) && is_array($phpmailer->getBccAddresses())) {
      $bodyContentBcc = array();
      foreach ($phpmailer->getBccAddresses() as $bccEmail) {
        $address = isset($bccEmail[0]) ? $bccEmail[0] : false;
        $name = isset($bccEmail[1]) ? $bccEmail[1] : false;
        if (!empty($name)) {
          $bodyContentBcc[] = $name . ' <' . $address . '>';
        } else {
          $bodyContentBcc[] = $address;
        }
      }
      if (!empty($bodyContentBcc)) {
        $this->body = array_merge($this->body, array('bcc' => implode(', ', $bodyContentBcc)));
      }
    }

    if ($phpmailer->ContentType === 'text/plain') {
      $contentTp = 'text';
      if (!empty($content)) {
        $this->body = array_merge($this->body, array($contentTp => $phpmailer->Body));
      }
    } else {
      $content = array(
        'text' => $phpmailer->AltBody,
        'html' => $phpmailer->Body,
      );
      foreach ($content as $type => $mail) {
        if (empty($mail)) {
          continue;
        }
        $this->body = array_merge($this->body, array($type => $mail));
      }
    }

    // Reply to
    if ( ! empty( $phpmailer->getReplyToAddresses() ) ) {
			$dataReplyTo = array();

      foreach ( $phpmailer->getReplyToAddresses() as $emailReplys ) {
        if ( empty( $emailReplys ) || ! is_array( $emailReplys ) ) {
          continue;
        }

        $addrReplyTo = isset( $emailReplys[0] ) ? $emailReplys[0] : false;
        $nameReplyTo = isset( $emailReplys[1] ) ? $emailReplys[1] : false;

        if ( ! filter_var( $addrReplyTo, FILTER_VALIDATE_EMAIL ) ) {
          continue;
        }

        if ( ! empty( $nameReplyTo ) ) {
          $dataReplyTo[] = $nameReplyTo . ' <' . $addrReplyTo . '>';
        } else {
          $dataReplyTo[] = $addrReplyTo;
        }

      }

      if ( ! empty( $dataReplyTo ) ) {
        $this->body = array_merge($this->body, array('h:Reply-To' => implode( ',', $dataReplyTo )));
      }
		}
  }

  public function send() {
    $apiLink = 'https://api.mailgun.net/v3/';
    if ('EU' === $this->getRegion()) {
      $apiLink = 'https://api.eu.mailgun.net/v3/';
    }
    $apiLink .= $this->getDomain() . '/messages';

    $resp = wp_safe_remote_post($apiLink, array(
      'httpversion' => '1.1',
      'blocking' => true,
      'headers' => $this->headers,
      'body' => $this->body,
      'timeout' => ini_get('max_execution_time') ? (int) ini_get('max_execution_time') : 30,
    ));

    if (is_wp_error($resp)) {
      $errors = $resp->get_error_messages();
      foreach ($errors as $error) {
        LogErrors::setErr($error);
      }
      return;
    }

    $emailTo = array();
    if (!empty($this->smtper->getToAddresses()) && is_array($this->smtper->getToAddresses())) {
      foreach ($this->smtper->getToAddresses() as $toEmail) {
        if (!empty($toEmail[0])) {
          $emailTo[] = $toEmail[0];
        }
      }
    }

    $dataLogsDB = array(
      'subject' => $this->smtper->Subject,
      'email_from' => $this->smtper->From,
      'email_to' => $emailTo, // require is array
      'mailer' => 'Mailgun',
      'date_time' => current_time('mysql'),
      'status' => 0, // 0: false, 1: true, 2: waiting
      'content_type' => $this->smtper->ContentType,
      'body_content' => $this->smtper->Body,
    );

    $sent = false;
    if (!empty($resp['response']) && !empty($resp['response']['code'])) {
      $code = (int) $resp['response']['code'];
      $codeSucArrs = array(200, 201, 202, 203, 204, 205, 206, 207, 208, 300, 301, 302, 303, 304, 305, 306, 307, 308);
      if (!in_array($code, $codeSucArrs) && !empty($resp['response'])) {
        $error = $resp['response'];
        $message = '';
        if (!empty($error)) {
          $message = '[' . sanitize_key($error['code']) . ']: ' . $error['message'];
        }
        LogErrors::clearErr();
        LogErrors::setErr('Mailer: ' . $this->smtpObj);
        LogErrors::setErr($message);

        $dataLogsDB['date_time'] = current_time('mysql');
        $dataLogsDB['reason_error'] = $message;
        Utils::insertEmailLogs($dataLogsDB);
      } else {
        $sent = true;
        LogErrors::clearErr();

        $dataLogsDB['date_time'] = current_time('mysql');
        $dataLogsDB['status'] = 1;
        Utils::insertEmailLogs($dataLogsDB);
      }
    }

    return $sent;
  }

  public function getApiKey() {
    $apiKey = "";
    $yaysmtpSettings = Utils::getYaySmtpSetting();
    if (!empty($yaysmtpSettings)) {
      if (!empty($yaysmtpSettings[$this->smtpObj]) && !empty($yaysmtpSettings[$this->smtpObj]['api_key'])) {
        $apiKey = $yaysmtpSettings[$this->smtpObj]['api_key'];
      }
    }
    return $apiKey;
  }

  public function getDomain() {
    $domain = "";
    $yaysmtpSettings = Utils::getYaySmtpSetting();
    if (!empty($yaysmtpSettings)) {
      if (!empty($yaysmtpSettings[$this->smtpObj]) && !empty($yaysmtpSettings[$this->smtpObj]['domain'])) {
        $domain = $yaysmtpSettings[$this->smtpObj]['domain'];
      }
    }
    return $domain;
  }

  public function getRegion() {
    $region = "US";
    $yaysmtpSettings = Utils::getYaySmtpSetting();
    if (!empty($yaysmtpSettings)) {
      if (!empty($yaysmtpSettings[$this->smtpObj]) && !empty($yaysmtpSettings[$this->smtpObj]['region'])) {
        $region = $yaysmtpSettings[$this->smtpObj]['region'];
      }
    }
    return $region;
  }
}
