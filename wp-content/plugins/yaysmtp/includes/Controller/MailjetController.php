<?php
namespace YaySMTP\Controller;

use YaySMTP\Helper\LogErrors;
use YaySMTP\Helper\Utils;

if (!defined('ABSPATH')) {
  exit;
}
class MailjetController {
  private $smtpObj = 'mailjet';
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

    $apiKey = $this->getApiKey();
    $secretKey = $this->getSecretKey();
    $this->headers['Authorization'] = 'Basic ' . base64_encode($apiKey . ':' . $secretKey);

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
      $this->body['Messages'][0]['Headers'] = $headersData;
    }

    $this->body['Messages'][0]['Subject'] = $phpmailer->Subject;

    if (!empty($phpmailer->FromName)) {
      $dataFrom['Name'] = $phpmailer->FromName;
    }
    $dataFrom['Email'] = $phpmailer->From;
    $this->body['Messages'][0]['From'] = $dataFrom;

    // Recipients - start
    if (!empty($phpmailer->getToAddresses()) && is_array($phpmailer->getToAddresses())) {
      $dataRecips['To'] = array();
      foreach ($phpmailer->getToAddresses() as $toEmail) {
        $address = isset($toEmail[0]) ? $toEmail[0] : false;
        $name = isset($toEmail[1]) ? $toEmail[1] : false;
        $arrTo = array();
        $arrTo['Email'] = $address;
        if (!empty($name)) {
          $arrTo['Name'] = $name;
        }
        $dataRecips['To'][] = $arrTo;
      }
    }

    if (!empty($phpmailer->getCcAddresses()) && is_array($phpmailer->getCcAddresses())) {
      $dataRecips['Cc'] = array();
      foreach ($phpmailer->getCcAddresses() as $ccEmail) {
        $address = isset($ccEmail[0]) ? $ccEmail[0] : false;
        $name = isset($ccEmail[1]) ? $ccEmail[1] : false;
        $arrCc = array();
        $arrCc['Email'] = $address;
        if (!empty($name)) {
          $arrCc['Name'] = $name;
        }
        $dataRecips['Cc'][] = $arrCc;
      }
    }

    if (!empty($phpmailer->getBccAddresses()) && is_array($phpmailer->getBccAddresses())) {
      $dataRecips['Bcc'] = array();
      foreach ($phpmailer->getBccAddresses() as $bccEmail) {
        $address = isset($bccEmail[0]) ? $bccEmail[0] : false;
        $name = isset($bccEmail[1]) ? $bccEmail[1] : false;
        $arrBcc = array();
        $arrBcc['Email'] = $address;
        if (!empty($name)) {
          $arrBcc['Name'] = $name;
        }
        $dataRecips['Bcc'][] = $arrBcc;
      }
    }

    if (!empty($dataRecips)) {
      foreach ($dataRecips as $type => $type_recipients) {
        $this->body['Messages'][0][$type] = $type_recipients;
      }
    }
    // Recipients - end

    if ($phpmailer->ContentType === 'text/plain') {
      $content = $phpmailer->Body;
      $this->body['Messages'][0]['TextPart'] = $content;
    } else {
      $content = array(
        'text' => $phpmailer->AltBody,
        'html' => $phpmailer->Body,
      );
      if (!empty($content['text'])) {
        $this->body['Messages'][0]['TextPart'] = $content['text'];
      }
      if (!empty($content['html'])) {
        $this->body['Messages'][0]['HTMLPart'] = $content['html'];
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

        $dataReplyTo['Email'] = $addrReplyTo;
        if (!empty($nameReplyTo)) {
          $dataReplyTo['Name'] = $nameReplyTo;
        }
        break;
      }

      if ( ! empty( $dataReplyTo ) ) {
        $this->body['Messages'][0]['ReplyTo'] = $dataReplyTo;
      }
		}

  }

  public function send() {
    $apiLink = 'https://api.mailjet.com/v3.1/send';

    // echo "<pre>";
    // print_r(wp_json_encode($this->body));
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
      'mailer' => 'Mailjet',
      'date_time' => current_time('mysql'),
      'status' => 0, // 0: false, 1: true, 2: waiting
      'content_type' => $this->smtper->ContentType,
      'body_content' => $this->smtper->Body,
    );

    $sent = false;
    if (is_wp_error($resp) || 200 !== wp_remote_retrieve_response_code($resp)) {
      $errorBody = $resp['body'];
      $errorResponse = $resp['response'];
      $message = '';

      if (!empty($errorBody)) {
        $message = '[' . sanitize_key($errorResponse['code']) . ']: ' . $errorBody;
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
    $apiKey = '';
    $yaysmtpSettings = Utils::getYaySmtpSetting();
    if (!empty($yaysmtpSettings)) {
      if (!empty($yaysmtpSettings[$this->smtpObj]) && !empty($yaysmtpSettings[$this->smtpObj]['api_key'])) {
        $apiKey = $yaysmtpSettings[$this->smtpObj]['api_key'];
      }
    }
    return $apiKey;
  }

  public function getSecretKey() {
    $secretKey = '';
    $yaysmtpSettings = Utils::getYaySmtpSetting();
    if (!empty($yaysmtpSettings)) {
      if (!empty($yaysmtpSettings[$this->smtpObj]) && !empty($yaysmtpSettings[$this->smtpObj]['secret_key'])) {
        $secretKey = $yaysmtpSettings[$this->smtpObj]['secret_key'];
      }
    }
    return $secretKey;
  }
}
