<?php
namespace YaySMTP\Controller;

use YaySMTP\Helper\LogErrors;
use YaySMTP\Helper\Utils;

if (!defined('ABSPATH')) {
  exit;
}
class PostmarkController {
  private $smtpObj = 'postmark';
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
    $this->headers['Content-Type'] = 'application/json';
    $this->headers['X-Postmark-Server-Token'] = $this->getApiKey();

    $headers = $phpmailer->getCustomHeaders();
    foreach ($headers as $head) {
      $nameHead = isset($head[0]) ? $head[0] : false;
      $valueHead = isset($head[1]) ? $head[1] : false;
      if (empty($nameHead)) {
        $headersData = isset($this->body['Headers']) ? (array) $this->body['Headers'] : array();
        $headersData[$nameHead] = $valueHead;

        $this->body = array_merge($this->body, array('Headers' => $headersData));
      }
    }

    $this->body = array_merge($this->body, array('Subject' => $phpmailer->Subject));
    if (!empty($phpmailer->FromName)) {
      $this->body = array_merge($this->body, array('From' => $phpmailer->FromName . ' <' . $phpmailer->From . '>'));
    } else {
      $this->body = array_merge($this->body, array('From' => $phpmailer->From));
    }

    if (!empty($phpmailer->getToAddresses()) && is_array($phpmailer->getToAddresses())) {
      $bodyContentTo = array();
      foreach ($phpmailer->getToAddresses() as $toEmail) {
        $address = isset($toEmail[0]) ? $toEmail[0] : false;
        $bodyContentTo[] = $address;
      }
      if (!empty($bodyContentTo)) {
        $this->body = array_merge($this->body, array('To' => implode(',', $bodyContentTo)));
      }
    }

    if (!empty($phpmailer->getCcAddresses()) && is_array($phpmailer->getCcAddresses())) {
      $bodyContentCc = array();
      foreach ($phpmailer->getCcAddresses() as $ccEmail) {
        $address = isset($ccEmail[0]) ? $ccEmail[0] : false;
        $bodyContentCc[] = $address;
      }
      if (!empty($bodyContentCc)) {
        $this->body = array_merge($this->body, array('Cc' => implode(',', $bodyContentCc)));
      }
    }

    if (!empty($phpmailer->getBccAddresses()) && is_array($phpmailer->getBccAddresses())) {
      $bodyContentBcc = array();
      foreach ($phpmailer->getBccAddresses() as $bccEmail) {
        $address = isset($bccEmail[0]) ? $bccEmail[0] : false;
        $bodyContentBcc[] = $address;
      }
      if (!empty($bodyContentBcc)) {
        $this->body = array_merge($this->body, array('Bcc' => implode(',', $bodyContentBcc)));
      }
    }

    if ($phpmailer->ContentType === 'text/plain') {
      $contentTp = 'TextBody';
      if (!empty($phpmailer->Body)) {
        $this->body = array_merge($this->body, array($contentTp => $phpmailer->Body));
      }
    } else {
      $content = array(
        'TextBody' => $phpmailer->AltBody,
        'HtmlBody' => $phpmailer->Body,
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

        if ( ! filter_var( $addrReplyTo, FILTER_VALIDATE_EMAIL ) ) {
          continue;
        }

        $dataReplyTo[] = $phpmailer->addrFormat( $emailReplys );
      }

      if ( ! empty( $dataReplyTo ) ) {
        $this->body = array_merge($this->body, array('ReplyTo' => implode( ',', $dataReplyTo )));
      }
		}
  }

  public function send() {
    $apiLink = 'https://api.postmarkapp.com/email';
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
      'mailer' => 'Mailgun',
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
        $message = '[' . sanitize_key($errorResponse['code']) . ']: ' . $errorBody->Message;
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
}
