<?php

namespace YaySMTP\Controller;

use YaySMTP\Helper\LogErrors;
use YaySMTP\Helper\Utils;

if (!defined('ABSPATH')) {
  exit;
}

class ZohoController {
  public $apiLink = 'https://mail.zoho.com/api/accounts/6575615000000008002/messages';
  public $smtpObj;
  public $body = array();

  public function __construct($smtpObj) {
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
    $smtpObj->setFrom($from_email, $from_name, false);
    // Set wp_mail_from && wp_mail_from_name - end

    $this->smtpObj = $smtpObj;
  }

  /**
   * Get Account Id to use it in Zoho send mail api apiLink
   */
  public function get_account_id() {
    $yst = Utils::getYaySmtpSetting();

    $response = wp_remote_get('http://mail.zoho.com/api/accounts', array(
      'headers' => array(
        'Authorization' => 'Zoho-oauthtoken ' . $yst['zoho']['access_token'],
      )));

    $response_body = json_decode($response['body']);

    if ($response_body->status->code !== 200) {

      return '';

    } else {

      $account_id = $response_body->data[0]->accountId;
      return $account_id;

    }
  }

  /**
   * set Zoho send mail apiLink with Account Id
   */
  public function setUrl($account_id) {

    if ($account_id !== '' && isset($account_id)) {
      $this->apiLink = 'https://mail.zoho.com/api/accounts/' . $account_id . '/messages';
    } else {
      $this->apiLink = 'https://mail.zoho.com/api/accounts//messages';
    }
  }

  /**
   * Override send method
   */
  public function send() {

    $this->setUrl($this->get_account_id());

    if (ZohoServiceVendController::isDiffInfo()) {
      ZohoServiceVendController::doResetToken();
    } else if (ZohoServiceVendController::isExpired()) {
      $regenerate_url = 'https://accounts.zoho.com/oauth/v2/token?';
      $regenerate_url .= 'refresh_token=' . ZohoServiceVendController::getSetting('refresh_token');
      $regenerate_url .= '&client_id=' . ZohoServiceVendController::getSetting('client_id');
      $regenerate_url .= '&client_secret=' . ZohoServiceVendController::getSetting('client_secret');
      $regenerate_url .= '&grant_type=refresh_token';
      $response = wp_remote_post($regenerate_url);
      $response = json_decode($response['body']);
      $new_access_token = $response->access_token;
      Utils::setYaySmtpSetting('access_token', $new_access_token, 'zoho');
      Utils::setYaySmtpSetting('created_at', strtotime('now'), 'zoho');
    }
    $yst = Utils::getYaySmtpSetting();

    $this->headers['Authorization'] = 'Zoho-oauthtoken ' . $yst['zoho']['access_token'];
    $this->headers['content-type'] = 'application/json';
    $this->headers['accept'] = 'application/json';
    $this->body = array_merge($this->body, array('subject' => $this->smtpObj->Subject));
    $this->body = array_merge($this->body, array('fromAddress' => '"' . $this->smtpObj->FromName . '"' . ' <' . $this->smtpObj->From . '>'));
    $this->body = array_merge($this->body, array('ccAddress' => isset($this->smtpObj->cc) ? $this->smtpObj->cc : "" ));
    $this->body = array_merge($this->body, array('bccAddress' => isset($this->smtpObj->bcc) ? $this->smtpObj->bcc : "" ));
    $this->body = array_merge($this->body, array('content' => $this->smtpObj->Body));

    update_option('Shrief', $this->smtpObj->getToAddresses());

    $this->body = array_merge($this->body, array('toAddress' => get_option('Shrief')[0][0]));

    $params = array_merge(
      array(
        'timeout' => 15,
        'httpversion' => '1.1',
        'blocking' => true,
      ),
      array(
        'headers' => $this->headers,
        'body' => $this->getBody(),
      )
    );

    $response = wp_remote_post($this->apiLink, $params);

    /** is sent mail */
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
      'mailer' => 'Zoho',
      'date_time' => current_time('mysql'),
      'status' => 0, // 0: false, 1: true, 2: waiting
      'content_type' => $this->smtpObj->ContentType,
      'body_content' => $this->smtpObj->Body,
    );

    $is_sent = false;
    $res_body = json_decode($response['body']);
    if (is_object($res_body)) {
        $error = null;
        if (isset($res_body->data->errorCode)) $error = $res_body->data->errorCode;
        if (isset($res_body->data->moreInfo)) $error = $res_body->data->moreInfo;
    } else {
      $error = $res_body[1]->errorCode ? $res_body[1]->moreInfo : $res_body[1]->moreInfo;
    }
    if ($response['response']['code'] === 200) {
      $is_sent = true;

      LogErrors::clearErr();
      $dataLogsDB['date_time'] = current_time('mysql');
      $dataLogsDB['status'] = 1;
      Utils::insertEmailLogs($dataLogsDB);
    } elseif ($response['response']['code'] === 500) {
      LogErrors::clearErr();
      LogErrors::setErr('Please use your Zoho mail to send email. We do not support this type of mail address');
    } else {
      LogErrors::clearErr();
      LogErrors::setErr($error);

      $dataLogsDB['date_time'] = current_time('mysql');
      $dataLogsDB['reason_error'] = $error;
      Utils::insertEmailLogs($dataLogsDB);
    }

    return $is_sent;

  }

  /**
   * Set & get some send-mail informations
   */
  public function getBody() {
    $body = $this->body;
    return wp_json_encode($body);
  }
}