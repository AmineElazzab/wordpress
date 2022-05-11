<?php
namespace YaySMTP\Controller;

use YaySMTP\Helper\LogErrors;
use YaySMTP\Helper\Utils;

if (!defined('ABSPATH')) {
  exit;
}
class SendinblueController {
  private $headers = array();
  private $body = array();
  private $smtper;

  private function getApiKey() {
    $apiKey = "";
    $yaysmtpSettings = get_option('yaysmtp_settings');
    if (!empty($yaysmtpSettings) && is_array($yaysmtpSettings)) {
      if (!empty($yaysmtpSettings['sendinblue']) && !empty($yaysmtpSettings['sendinblue']['api_key'])) {
        $apiKey = $yaysmtpSettings['sendinblue']['api_key'];
      }
    }
    return $apiKey;
  }

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
    $headers = $phpmailer->getCustomHeaders();
    $this->headers['api-key'] = $this->getApiKey();
    $this->headers['content-type'] = 'application/json';
    $this->headers['accept'] = 'application/json';
    foreach ($headers as $header) {
      $nameHead = isset($header[0]) ? $header[0] : false;
      $valueHead = isset($header[1]) ? $header[1] : false;
      if (empty($nameHead)) {
        return;
      }
      $headersData = isset($this->body['headers']) ? (array) $this->body['headers'] : array();
      $headersData[$nameHead] = Utils::saniVal($valueHead);
      $this->body = array_merge($this->body, array('headers' => $headersData));
    }

    $this->body = array_merge($this->body, array('subject' => $phpmailer->Subject));
    $this->body['sender'] = array(
      'email' => $phpmailer->From,
      'name' => !empty($phpmailer->FromName) ? Utils::saniVal($phpmailer->FromName) : '',
    );

    if (!empty($phpmailer->getToAddresses()) && is_array($phpmailer->getToAddresses())) {
      $dataRecips['to'] = array();
      foreach ($phpmailer->getToAddresses() as $toEmail) {
        $address = isset($toEmail[0]) ? $toEmail[0] : false;
        $name = isset($toEmail[1]) ? $toEmail[1] : false;
        $arrTo = array();
        $arrTo['email'] = $address;
        if (!empty($name)) {
          $arrTo['name'] = $name;
        }
        $dataRecips['to'][] = $arrTo;
      }
    }

    if (!empty($phpmailer->getCcAddresses()) && is_array($phpmailer->getCcAddresses())) {
      $dataRecips['cc'] = array();
      foreach ($phpmailer->getCcAddresses() as $ccEmail) {
        $address = isset($ccEmail[0]) ? $ccEmail[0] : false;
        $name = isset($ccEmail[1]) ? $ccEmail[1] : false;
        $arrCc = array();
        $arrCc['email'] = $address;
        if (!empty($name)) {
          $arrCc['name'] = $name;
        }
        $dataRecips['cc'][] = $arrCc;
      }
    }

    if (!empty($phpmailer->getBccAddresses()) && is_array($phpmailer->getBccAddresses())) {
      $dataRecips['bcc'] = array();
      foreach ($phpmailer->getBccAddresses() as $bccEmail) {
        $address = isset($bccEmail[0]) ? $bccEmail[0] : false;
        $name = isset($bccEmail[1]) ? $bccEmail[1] : false;
        $arrBcc = array();
        $arrBcc['email'] = $address;
        if (!empty($name)) {
          $arrBcc['name'] = $name;
        }
        $dataRecips['bcc'][] = $arrBcc;
      }
    }

    if (!empty($dataRecips)) {
      foreach ($dataRecips as $type => $type_recipients) {
        $this->body[$type] = $type_recipients;
      }
    }

    if ($phpmailer->ContentType === 'text/plain') {
      $content = $phpmailer->Body;
      $this->body['textContent'] = $content;
    } else {
      $content = array(
        'text' => $phpmailer->AltBody,
        'html' => $phpmailer->Body,
      );
      if (!empty($content['text'])) {
        $this->body['textContent'] = $content['text'];
      }
      if (!empty($content['html'])) {
        $this->body['htmlContent'] = $content['html'];
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

        $dataReplyTo['email'] = $addrReplyTo;
        if ( ! empty( $name ) ) {
          $dataReplyTo['name'] = $nameReplyTo;
        }
      }

      if ( ! empty( $dataReplyTo ) ) {
        $this->body = array_merge($this->body, array('replyTo' => $dataReplyTo));
      }
    }
    
  }

  public function send() {
    $params = array(
      'httpversion' => '1.1',
      'blocking' => true,
      'headers' => $this->headers,
      'body' => wp_json_encode($this->body),
      'timeout' => ini_get('max_execution_time') ? (int) ini_get('max_execution_time') : 30,
    );
    $resp = wp_safe_remote_post('https://api.sendinblue.com/v3/smtp/email', $params);

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
      'mailer' => 'Sendinblue',
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
        LogErrors::setErr('Mailer: Sendinblue');
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
}
