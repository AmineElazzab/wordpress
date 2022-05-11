<?php
namespace YaySMTP\Controller;

use YaySMTP\Helper\LogErrors;
use YaySMTP\Helper\Utils;

if (!defined('ABSPATH')) {
  exit;
}
class SendPulseController {
  private $smtpObj = 'sendpulse';
  private $body = array();
  private $smtper;
  private $token;

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

    $this->token = $this->getAccessTokenDB();

    if (empty($this->token) || $this->isExpiredAccessToken()) {
      if (!$this->getAccessToken()) {
        LogErrors::clearErr();
        LogErrors::setErr('Could not connect to api, check your ID and SECRET');
        return;
      }
    }

    $this->smtper = $phpmailer;
    $this->body = array_merge($this->body, array('subject' => $phpmailer->Subject));

    if (!empty($phpmailer->FromName)) {
      $dataFrom['name'] = $phpmailer->FromName;
    }
    $dataFrom['email'] = $phpmailer->From;
    $this->body = array_merge($this->body, array('from' => $dataFrom));

    if (!empty($phpmailer->getToAddresses()) && is_array($phpmailer->getToAddresses())) {
      $dataRecips = array();
      foreach ($phpmailer->getToAddresses() as $toEmail) {
        $address = isset($toEmail[0]) ? $toEmail[0] : false;
        $name = isset($toEmail[1]) ? $toEmail[1] : false;
        $arrTo = array();
        $arrTo['email'] = $address;
        if (!empty($name)) {
          $arrTo['name'] = $name;
        }
        $dataRecips[] = $arrTo;
      }
      $this->body = array_merge($this->body, array('to' => $dataRecips));
    }

    if (!empty($phpmailer->getCcAddresses()) && is_array($phpmailer->getCcAddresses())) {
      $dataRecips = array();
      foreach ($phpmailer->getCcAddresses() as $ccEmail) {
        $address = isset($ccEmail[0]) ? $ccEmail[0] : false;
        $name = isset($ccEmail[1]) ? $ccEmail[1] : false;
        $arrCc = array();
        $arrCc['email'] = $address;
        if (!empty($name)) {
          $arrCc['name'] = $name;
        }
        $dataRecips[] = $arrCc;
      }
      $this->body = array_merge($this->body, array('cc' => $dataRecips));
    }

    if (!empty($phpmailer->getBccAddresses()) && is_array($phpmailer->getBccAddresses())) {
      $dataRecips = array();
      foreach ($phpmailer->getBccAddresses() as $bccEmail) {
        $address = isset($bccEmail[0]) ? $bccEmail[0] : false;
        $name = isset($bccEmail[1]) ? $bccEmail[1] : false;
        $arrBcc = array();
        $arrBcc['email'] = $address;
        if (!empty($name)) {
          $arrBcc['name'] = $name;
        }
        $dataRecips[] = $arrBcc;
      }
      $this->body = array_merge($this->body, array('bcc' => $dataRecips));
    }

    if ($phpmailer->ContentType === 'text/plain') {
      $this->body = array_merge($this->body, array('text' => $phpmailer->Body));
    } else {
      $content = array(
        'text' => $phpmailer->AltBody,
        'html' => $phpmailer->Body,
      );
      if (!empty($content['text'])) {
        $this->body = array_merge($this->body, array('text' => $content['text']));
      }
      if (!empty($content['html'])) {
        $this->body = array_merge($this->body, array('html' => $content['html']));
      }
    }
  }

  private function getAccessToken() {
    $param = array(
      'grant_type' => 'client_credentials',
      'client_id' => $this->getApiKey(),
      'client_secret' => $this->getSecretKey(),
    );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_POST, count($param));
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($param));
    curl_setopt($curl, CURLOPT_URL, 'https://api.sendpulse.com/oauth/access_token');
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($curl);
    $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $headerCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $responseBody = substr($response, $headerSize);

    curl_close($curl);

    $resultBody = json_decode($responseBody);

    if ($headerCode != 200) {
      return false;
    }

    $this->token = $resultBody->access_token;
    $this->saveAccessToken($this->token);
    Utils::setYaySmtpSetting('created_at', strtotime('now'), 'sendpulse');
    return true;
  }

  public function send() {
    $curl = curl_init();
    if (!empty($this->body['html'])) {
      $this->body['html'] = base64_encode($this->body['html']);
    }

    $param = array(
      'email' => serialize($this->body),
    );

    if (!empty($this->token)) {
      $token = $this->token;
      $headers = array('Authorization: Bearer ' . $token);
      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }
    curl_setopt($curl, CURLOPT_POST, count($param));
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($param));

    curl_setopt($curl, CURLOPT_URL, 'https://api.sendpulse.com/smtp/emails');
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($curl);
    $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $headerCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $responseBody = substr($response, $headerSize);

    curl_close($curl);

    $sent = false;
    if ($this->isExpiredAccessToken()) {
      if ($this->getAccessToken()) {
        $sendPulse = new SendPulseController($this->smtper);
        $sendPulse->send();
      };
    } else {
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
        'mailer' => 'SendPulse',
        'date_time' => current_time('mysql'),
        'status' => 0, // 0: false, 1: true, 2: waiting
        'content_type' => $this->smtper->ContentType,
        'body_content' => $this->smtper->Body,
      );

      $respBodyObj = json_decode($responseBody);

      if (200 !== $headerCode) {
        $message = '';
        if (!empty($respBodyObj)) {
          $message = '[' . $headerCode . ']: ' . $respBodyObj->message;
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

  public function saveAccessToken($token) {
    Utils::setYaySmtpSetting('access_token', $token, 'sendpulse');
  }

  public function getAccessTokenDB() {
    $apiKey = '';
    $yaysmtpSettings = Utils::getYaySmtpSetting();
    if (!empty($yaysmtpSettings)) {
      if (!empty($yaysmtpSettings[$this->smtpObj]) && !empty($yaysmtpSettings[$this->smtpObj]['access_token'])) {
        $apiKey = $yaysmtpSettings[$this->smtpObj]['access_token'];
      }
    }
    return $apiKey;
  }

  public function getCreatedTimes() {
    $createdAt = '';
    $yaysmtpSettings = Utils::getYaySmtpSetting();
    if (!empty($yaysmtpSettings)) {
      if (!empty($yaysmtpSettings[$this->smtpObj]) && !empty($yaysmtpSettings[$this->smtpObj]['created_at'])) {
        $createdAt = $yaysmtpSettings[$this->smtpObj]['created_at'];
      }
    }
    return $createdAt;
  }

  public function isExpiredAccessToken() {
    $now = strtotime('now');
    if ($this->getCreatedTimes() === '') {
      return true;
    }

    if ($this->getCreatedTimes() + 3600 < $now) {
      return true;
    }

    return false;
  }
}
