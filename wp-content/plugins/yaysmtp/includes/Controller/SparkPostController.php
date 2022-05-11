<?php
namespace YaySMTP\Controller;

use YaySMTP\Helper\LogErrors;
use YaySMTP\Helper\Utils;

if (!defined('ABSPATH')) {
  exit;
}
class SparkPostController {
  private $smtpObj = 'sparkpost';
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
    $this->headers['Content-Type'] = 'application/json';
    $this->headers['Authorization'] = $this->getApiKey();

    $headers = $phpmailer->getCustomHeaders();
    $headersData = array();
    foreach ($headers as $head) {
      $nameHead = isset($head[0]) ? $head[0] : false;
      $valueHead = isset($head[1]) ? $head[1] : false;
      if (empty($nameHead)) {
        $headersData[$nameHead] = $valueHead;
      }
    }
    if (!empty($headersData)) {
      $this->body['content']['headers'] = $headersData;
    }

    $this->body['content']['subject'] = $phpmailer->Subject;

    if (!empty($phpmailer->FromName)) {
      $dataFrom['name'] = $phpmailer->FromName;
    }
    $dataFrom['email'] = $phpmailer->From;
    $this->body['content']['from'] = $dataFrom;

    // Recipients - start
    $dataHeaderTo = array();
    if (!empty($phpmailer->getToAddresses()) && is_array($phpmailer->getToAddresses())) {
      foreach ($phpmailer->getToAddresses() as $toEmail) {
        if (empty($toEmail[1])) {
          $dataHeaderTo[] = $toEmail[0];
        } else {
          $dataHeaderTo[] = sprintf('%s <%s>', $toEmail[1], $toEmail[0]);
        }
      }
    }
    $dataHeaderTo = implode(', ', $dataHeaderTo);

    $bodyContentAddress = array();
    if (!empty($phpmailer->getToAddresses()) && is_array($phpmailer->getToAddresses())) {
      foreach ($phpmailer->getToAddresses() as $toEmail) {
        $address = array(
          'address' => array(
            'email' => $toEmail[0],
            'name' => isset($toEmail[1]) ? $toEmail[1] : '',
          ),
        );

        if (!empty($dataHeaderTo)) {
          $address['address']['header_to'] = $dataHeaderTo;
          unset($address['address']['name']);
        }

        $bodyContentAddress[] = $address;
      }
    }

    if (!empty($phpmailer->getCcAddresses()) && is_array($phpmailer->getCcAddresses())) {
      foreach ($phpmailer->getCcAddresses() as $toEmail) {
        $address = array(
          'address' => array(
            'email' => $toEmail[0],
            'name' => isset($toEmail[1]) ? $toEmail[1] : '',
          ),
        );

        if (!empty($dataHeaderTo)) {
          $address['address']['header_to'] = $dataHeaderTo;
          unset($address['address']['name']);
        }

        $bodyContentAddress[] = $address;
      }
    }

    if (!empty($phpmailer->getBccAddresses()) && is_array($phpmailer->getBccAddresses())) {
      foreach ($phpmailer->getBccAddresses() as $toEmail) {
        $address = array(
          'address' => array(
            'email' => $toEmail[0],
            'name' => isset($toEmail[1]) ? $toEmail[1] : '',
          ),
        );

        if (!empty($dataHeaderTo)) {
          $address['address']['header_to'] = $dataHeaderTo;
          unset($address['address']['name']);
        }

        $bodyContentAddress[] = $address;
      }
    }

    $this->body['recipients'] = $bodyContentAddress;
    // Recipients - end

    if ($phpmailer->ContentType === 'text/plain') {
      if (!empty($phpmailer->Body)) {
        $this->body['content']['text'] = $phpmailer->Body;
      }
    } else if ($phpmailer->ContentType === 'multipart/alternative') {
      $this->body['content']['html'] = $phpmailer->Body;
      $this->body['content']['text'] = $phpmailer->AltBody;
    } else {
      $this->body['content']['html'] = $phpmailer->Body;
    }

    // Sandbox
    if (isset($this->body['content']['from']['email'])) {
      $emailFrom = $this->body['content']['from']['email'];
      $emailSlice = array_slice(explode('@', $emailFrom), -1);
      if ('sparkpostbox.com' === $emailSlice[0]) {
        $this->body['options']['sandbox'] = true;
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

        if ( ! filter_var( $addrReplyTo, FILTER_VALIDATE_EMAIL ) ) {
          continue;
        }

        $dataReplyTo[] = $phpmailer->addrFormat( $emailReplys );
      }

      if ( ! empty( $dataReplyTo ) ) {
        $this->body['content']['reply_to'] = implode( ',', $dataReplyTo );
      }
    }
  }

  public function send() {
    $apiLink = 'https://api.sparkpost.com';
    if ('EU' === $this->getRegion()) {
      $apiLink = 'https://api.eu.sparkpost.com';
    }
    $apiLink .= '/api/v1/transmissions';

    // echo "<pre>";
    // print_r($this->body);
    // echo "</pre>";
    // die;

    $resp = wp_safe_remote_post($apiLink, array(
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
      'mailer' => 'SparkPost',
      'date_time' => current_time('mysql'),
      'status' => 0, // 0: false, 1: true, 2: waiting
      'content_type' => $this->smtper->ContentType,
      'body_content' => $this->smtper->Body,
    );

    $sent = false;
    if (is_wp_error($resp) || 200 !== wp_remote_retrieve_response_code($resp)) {
      $errorBody = json_decode($resp['body']);
      $errorResponse = $resp['response'];
      $message = '';

      if (!empty($errorBody)) {
        $message = '[' . sanitize_key($errorResponse['code']) . ']: ' . $errorBody->errors[0]->message;
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

  public function getRegion() {
    $region = '';
    $yaysmtpSettings = Utils::getYaySmtpSetting();
    if (!empty($yaysmtpSettings)) {
      if (!empty($yaysmtpSettings[$this->smtpObj]) && !empty($yaysmtpSettings[$this->smtpObj]['region'])) {
        $region = $yaysmtpSettings[$this->smtpObj]['region'];
      }
    }
    return $region;
  }
}
