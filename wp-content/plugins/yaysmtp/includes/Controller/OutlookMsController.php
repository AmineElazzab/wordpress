<?php

namespace YaySMTP\Controller;

use YaySMTP\Helper\LogErrors;
use YaySMTP\Helper\Utils;

if (!defined('ABSPATH')) {
  exit;
}
class OutlookMsController {
  public $smtpObj;
  private $headers = array();
  private $body = array();

  public function getAccessToken() {
    $apiKey = "";
    $yaysmtpSettings = get_option('yaysmtp_settings');
    if (!empty($yaysmtpSettings) && is_array($yaysmtpSettings)) {
      if (!empty($yaysmtpSettings['outlookms']) && !empty($yaysmtpSettings['outlookms']['outlookms_access_token'])) {
        $apiKey = $yaysmtpSettings['outlookms']['outlookms_access_token']['access_token'];
      }
    }
    return $apiKey;
  }

  public function getUserInf() {
    $user = "";
    $yaysmtpSettings = get_option('yaysmtp_settings');
    if (!empty($yaysmtpSettings) && is_array($yaysmtpSettings)) {
      if (!empty($yaysmtpSettings['outlookms']) && !empty($yaysmtpSettings['outlookms']['outlookms_auth_email'])) {
        $user = $yaysmtpSettings['outlookms']['outlookms_auth_email'];
      }
    }
    return $user;
  }

  public function __construct($smtpObj) {
    $this->smtpObj = $smtpObj;

    new OutlookMsServicesController();

    $this->headers['content-type'] = 'application/json';
    $this->headers['Authorization'] = 'Bearer ' . $this->getAccessToken();

    $this->body['message']['subject'] = $smtpObj->Subject;

    $headers = $smtpObj->getCustomHeaders();

    $headersData = array();
    foreach ($headers as $head) {
      $nameHead = isset($head[0]) ? sanitize_text_field($head[0]) : false;
      $valueHead = isset($head[1]) ? $head[1] : false;
      if (!empty($nameHead)) {
        $headersData[] = array(
          'name' => $nameHead,
          'value' => Utils::saniVal($valueHead),
        );
      }
    }

    if (!empty($headersData)) {
      $this->body['message']['internetMessageHeaders'] = $headersData;
    }

    $userFrom = $this->getUserInf();
    $email_address = array(
      'emailAddress' => array(
        'name' => isset($userFrom['name']) ? sanitize_text_field($userFrom['name']) : '',
        'address' => isset($userFrom['email']) ? $userFrom['email'] : '',
      ),
    );

    $this->body['message']['from'] = $email_address;
    $this->body['message']['sender'] = $email_address;

    if (!empty($smtpObj->getToAddresses()) && is_array($smtpObj->getToAddresses())) {
      $dataRecips = array();
      foreach ($smtpObj->getToAddresses() as $toEmail) {
        $address = isset($toEmail[0]) ? $toEmail[0] : false;
        $name = isset($toEmail[1]) ? $toEmail[1] : false;
        $arrTo = array();
        $arrTo['address'] = $address;
        if (!empty($name)) {
          $arrTo['name'] = $name;
        }

        $dataRecips[] = array('emailAddress' => $arrTo);
      }
      if (!empty($dataRecips)) {
        $this->body['message']['toRecipients'] = $dataRecips;
      }
    }

    if (!empty($smtpObj->getCcAddresses()) && is_array($smtpObj->getCcAddresses())) {
      $dataRecips = array();
      foreach ($smtpObj->getCcAddresses() as $ccEmail) {
        $address = isset($ccEmail[0]) ? $ccEmail[0] : false;
        $name = isset($ccEmail[1]) ? $ccEmail[1] : false;
        $arrCc = array();
        $arrCc['address'] = $address;
        if (!empty($name)) {
          $arrCc['name'] = $name;
        }
        $dataRecips[] = array('emailAddress' => $arrCc);
      }
      if (!empty($dataRecips)) {
        $this->body['message']['ccRecipients'] = $dataRecips;
      }
    }

    if (!empty($smtpObj->getBccAddresses()) && is_array($smtpObj->getBccAddresses())) {
      $dataRecips = array();
      foreach ($smtpObj->getBccAddresses() as $bccEmail) {
        $address = isset($bccEmail[0]) ? $bccEmail[0] : false;
        $name = isset($bccEmail[1]) ? $bccEmail[1] : false;
        $arrBcc = array();
        $arrBcc['address'] = $address;
        if (!empty($name)) {
          $arrBcc['name'] = $name;
        }
        $dataRecips[] = array('emailAddress' => $arrBcc);
      }
      if (!empty($dataRecips)) {
        $this->body['message']['bccRecipients'] = $dataRecips;
      }
    }

    if ($smtpObj->ContentType === 'text/plain') {
      $content = $smtpObj->Body;
      $dataContent['contentType'] = 'text/plain';
      $dataContent['content'] = $content;
      $this->body['message']['body'] = array($dataContent);
    } else {
      $content = array(
        'text' => $smtpObj->AltBody,
        'html' => $smtpObj->Body,
      );

      if (!empty($content['html'])) {
        $dataContent = array(
          'contentType' => 'html',
          'content' => $content['html'],
        );
      } else {
        $dataContent = array(
          'contentType' => 'text',
          'content' => $content['text'],
        );
      }

      $this->body['message']['body'] = $dataContent;
    }

    // Reply to
    if ( ! empty( $smtpObj->getReplyToAddresses() ) ) {
			$dataReplyTo = array();

      foreach ( $smtpObj->getReplyToAddresses() as $emailReplys ) {
        if ( empty( $emailReplys ) || ! is_array( $emailReplys ) ) {
          continue;
        }

        $addrReplyTo = isset( $emailReplys[0] ) ? $emailReplys[0] : false;
        $nameReplyTo = isset( $emailReplys[1] ) ? $emailReplys[1] : false;

        if ( ! filter_var( $addrReplyTo, FILTER_VALIDATE_EMAIL ) ) {
          continue;
        }

        $dataReplyToTemp = array();
        $dataReplyToTemp['address'] = $addrReplyTo;
        if ( ! empty( $nameReplyTo ) ) {
          $dataReplyToTemp['name'] = $nameReplyTo;
        }

        $dataReplyTo[] = array( 'emailAddress' => $dataReplyToTemp );
      }

      if ( ! empty( $dataReplyTo ) ) {
        $this->body['message']['replyTo'] = $dataReplyTo;
      }
		}
  }

  public function send() {
    $userFrom = $this->getUserInf();

    // Set wp_mail_from && wp_mail_from_name - start
    $currentFromEmail = Utils::getCurrentFromEmail();
    $currentFromName = Utils::getCurrentFromName();
    if (!empty($userFrom['email'])) {
      $this->smtpObj->From = $userFrom['email'];
      $this->smtpObj->Sender = $userFrom['email'];
    }

    if (Utils::getForceFromEmail() == 1) {
      $this->smtpObj->From = $currentFromEmail;
      $this->smtpObj->Sender = $currentFromEmail;
    }

    if (Utils::getForceFromName() == 1) {
      $this->smtpObj->FromName = $currentFromName;
    }

    // Set wp_mail_from && wp_mail_from_name - end

    $response = wp_safe_remote_post('https://graph.microsoft.com/v1.0/me/sendMail', array(
      'httpversion' => '1.1',
      'blocking' => true,
      'headers' => $this->headers,
      'body' => wp_json_encode($this->body),
      'timeout' => ini_get('max_execution_time') ? (int) ini_get('max_execution_time') : 30,
    ));

    if (is_wp_error($response)) {
      $errors = $response->get_error_messages();
      foreach ($errors as $error) {
        LogErrors::setErr($error);
      }
      return;
    }

    $emailTo = array();
    if (!empty($this->smtpObj->getToAddresses()) && is_array($this->smtpObj->getToAddresses())) {
      foreach ($this->smtpObj->getToAddresses() as $toEmail) {
        if (!empty($toEmail[0])) {
          $emailTo[] = $toEmail[0];
        }
      }
    }

    $dataLogsDB = array(
      'subject' => $this->smtpObj->Subject,
      'email_from' => $this->smtpObj->From,
      'email_to' => $emailTo, // require is array
      'mailer' => 'Outlook Microsoft',
      'date_time' => current_time('mysql'),
      'status' => 0, // 0: false, 1: true, 2: waiting
      'content_type' => $this->smtpObj->ContentType,
      'body_content' => $this->smtpObj->Body,
    );

    // var_dump($response);
    $sent = false;
    if (!empty($response['response']) && !empty($response['response']['code'])) {
      $code = (int) $response['response']['code'];
      $codeSucArrs = array(200, 201, 202, 203, 204, 205, 206, 207, 208, 300, 301, 302, 303, 304, 305, 306, 307, 308);
      if (!in_array($code, $codeSucArrs) && !empty($response['response'])) {
        $errorBody = json_decode($response['body']);
        $message = '';
        if (!empty($errorBody)) {
          $message = '[' . $code . ']: ' . $errorBody->error->message;
        }
        LogErrors::clearErr();
        LogErrors::setErr('Mailer: Outlook Microsoft');
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
