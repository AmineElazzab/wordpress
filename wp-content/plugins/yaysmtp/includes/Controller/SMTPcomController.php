<?php
namespace YaySMTP\Controller;

use YaySMTP\Helper\LogErrors;
use YaySMTP\Helper\Utils;

if (!defined('ABSPATH')) {
  exit;
}

class SMTPcomController {
  private $smtpObj = 'smtpcom';
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
    $this->headers['Accept'] = 'application/json';
    $this->headers['content-type'] = 'application/json';
    $this->headers['Authorization'] = 'Bearer ' . $this->getApiKey();
    $this->body = array_merge($this->body, array('channel' => $this->getChannel()));

    $headers = $phpmailer->getCustomHeaders();
    foreach ($headers as $header) {
      $name = isset($header[0]) ? $header[0] : false;
      $value = isset($header[1]) ? $header[1] : false;
      $headersData = isset($this->body['custom_headers']) ? (array) $this->body['custom_headers'] : array();
      $headersData[$name] = $value;
      $this->body = array_merge($this->body, array('custom_headers' => $headersData));
    }

    $this->body = array_merge($this->body, array('subject' => $phpmailer->Subject));

    if (filter_var($phpmailer->From, FILTER_VALIDATE_EMAIL)) {
      $from['address'] = $phpmailer->From;
      if (!empty($phpmailer->FromName)) {
        $from['name'] = $phpmailer->FromName;
      }
      $this->body = array_merge($this->body, array('originator' => array('from' => $from)));
    }

    if (!empty($phpmailer->getToAddresses()) && is_array($phpmailer->getToAddresses())) {
      $dataRecips['to'] = array();
      foreach ($phpmailer->getToAddresses() as $toEmail) {
        $address = isset($toEmail[0]) ? $toEmail[0] : false;
        $name = isset($toEmail[1]) ? $toEmail[1] : false;
        $arrTo = array();
        $arrTo['address'] = $address;
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
        $arrCc['address'] = $address;
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
        $arrBcc['address'] = $address;
        if (!empty($name)) {
          $arrBcc['name'] = $name;
        }
        $dataRecips['bcc'][] = $arrBcc;
      }
    }

    if (!empty($dataRecips)) {
      $this->body = array_merge($this->body, array('recipients' => $dataRecips));
    }

    if ($phpmailer->ContentType === 'text/plain') {
      $contentBody = $phpmailer->Body;

      if (!empty($contentBody)) {
        $bodyParts = array();
        $ctype = 'text/plain';
        $bodyParts[] = array(
          'type' => $ctype,
          'content' => $contentBody,
          'charset' => $phpmailer->CharSet,
          'encoding' => $phpmailer->Encoding,
        );
        $this->body = array_merge($this->body, array('body' => array('parts' => $bodyParts)));
      }

    } else {
      $contentBody = array(
        'text' => $phpmailer->AltBody,
        'html' => $phpmailer->Body,
      );

      if (!empty($contentBody)) {
        $bodyParts = array();
        foreach ($contentBody as $type => $body) {
          if (empty($body)) {
            continue;
          }

          if ($type === 'html') {
            $ctype = 'text/html';
          } else {
            $ctype = 'text/plain';
          }

          $bodyParts[] = array(
            'type' => $ctype,
            'content' => $body,
            'charset' => $phpmailer->CharSet,
            'encoding' => $phpmailer->Encoding,
          );
        }

        $this->body = array_merge($this->body, array('body' => array('parts' => $bodyParts)));
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

        $dataReplyTo['address'] = $addrReplyTo;
        if ( ! empty( $name ) ) {
          $dataReplyTo['name'] = $nameReplyTo;
        }
        break;
      }

      if ( ! empty( $dataReplyTo ) ) {
        $this->body = array_merge($this->body, array('originator' => array('reply_to' => $dataReplyTo)));
      }
    } 
  }

  public function send() {
    $resp = wp_safe_remote_post('https://api.smtp.com/v4/messages', array(
      'httpversion' => '1.1',
      'blocking' => true,
      'headers' => $this->headers,
      'body' => wp_json_encode($this->body),
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
      'mailer' => 'SMTP.com',
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
    $apiKey = '';
    $yaysmtpSettings = Utils::getYaySmtpSetting();
    if (!empty($yaysmtpSettings)) {
      if (!empty($yaysmtpSettings[$this->smtpObj]) && !empty($yaysmtpSettings[$this->smtpObj]['api_key'])) {
        $apiKey = $yaysmtpSettings[$this->smtpObj]['api_key'];
      }
    }
    return $apiKey;
  }

  public function getChannel() {
    $sender = '';
    $yaysmtpSettings = Utils::getYaySmtpSetting();
    if (!empty($yaysmtpSettings)) {
      if (!empty($yaysmtpSettings[$this->smtpObj]) && !empty($yaysmtpSettings[$this->smtpObj]['sender'])) {
        $sender = $yaysmtpSettings[$this->smtpObj]['sender'];
      }
    }
    return $sender;
  }
}
