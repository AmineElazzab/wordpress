<?php

namespace YaySMTP\Controller;

use YaySMTP\Helper\LogErrors;
use YaySMTP\Helper\Utils;

if (!defined('ABSPATH')) {
  exit;
}
class GmailController {
  public $smtpObj;

  public function __construct($smtpObj) {
    $this->smtpObj = $smtpObj;
  }

  public function send() {
    $servVend = new GmailServiceVendController();
    $Service_Gmail = new \Google_Service_Gmail($servVend->getclientWebService());

    try {
      $resp = $Service_Gmail->users_settings_sendAs->listUsersSettingsSendAs('me');
      $userSendFromAddr = array_map(function ($sendAsObject) {return $sendAsObject['sendAsEmail'];}, (array) $resp->getSendAs());
    } catch (\Exception $excp) {
      $userSendFromAddr = array();
    }

    if (!in_array($this->smtpObj->From, $userSendFromAddr, true)) {
      $profileMe = array('email' => $Service_Gmail->users->getProfile('me')->getEmailAddress());
      if (!empty($profileMe['email'])) {
        $this->smtpObj->From = $profileMe['email'];
        $this->smtpObj->Sender = $profileMe['email'];
      }
    }

    // Set wp_mail_from && wp_mail_from_name - start
    $currentFromEmail = Utils::getCurrentFromEmail();
    $currentFromName = Utils::getCurrentFromName();

    if (Utils::getForceFromEmail() == 1) {
      $this->smtpObj->From = $currentFromEmail;
      $this->smtpObj->Sender = $currentFromEmail;
    }

    if (Utils::getForceFromName() == 1) {
      $this->smtpObj->FromName = $currentFromName;
    }
    // Set wp_mail_from && wp_mail_from_name - end

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
      'mailer' => 'Gmail',
      'date_time' => current_time('mysql'),
      'status' => 0, // 0: false, 1: true, 2: waiting
      'content_type' => $this->smtpObj->ContentType,
      'body_content' => $this->smtpObj->Body,
    );

    try {
      $sent = false;
      $this->smtpObj->preSend();
      $Service_Gmail_Message = new \Google_Service_Gmail_Message();
      $Service_Gmail_Message->setRaw(str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($this->smtpObj->getSentMIMEMessage())));

      $response = $Service_Gmail->users_messages->send('me', $Service_Gmail_Message);
      if (method_exists($response, 'getId') && !empty($response->getId())) {$sent = true;}

      if ($sent) {
        LogErrors::clearErr();

        $dataLogsDB['date_time'] = current_time('mysql');
        $dataLogsDB['status'] = 1;
        Utils::insertEmailLogs($dataLogsDB);
      } else {
        $dataLogsDB['date_time'] = current_time('mysql');
        Utils::insertEmailLogs($dataLogsDB);
      }
      return $sent;

    } catch (\Exception $e) {
      LogErrors::clearErr();
      LogErrors::setErr('Mailer: Gmail');

      $mess = $e->getMessage();
      if (!is_string($mess)) {
        $mess = wp_json_encode($mess);
      } else {
        $mess = wp_strip_all_tags($mess, false);
      }

      LogErrors::setErr($mess);
    }
  }
}
