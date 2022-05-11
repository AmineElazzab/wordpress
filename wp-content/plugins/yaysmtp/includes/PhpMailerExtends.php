<?php
namespace YaySMTP;

use YaySMTP\Helper\Utils;

defined('ABSPATH') || exit;

class PhpMailerExtends extends \PHPMailer\PHPMailer\PHPMailer {
  public function send() {
    $currentMailer = Utils::getCurrentMailer();
    if (!$this->preSend()) {
      return false;
    }

    $useFallbackSmtp = Utils::conditionUseFallbackSmtp(); 

    if( $useFallbackSmtp ){
      try {
        $result = $this->postSend();

        $emailTo = array();
        if (!empty($this->getToAddresses()) && is_array($this->getToAddresses())) {
          foreach ($this->getToAddresses() as $toEmail) {
            if (!empty($toEmail[0])) {
              $emailTo[] = $toEmail[0];
            }
          }
        }

        $dataLogsDB = array(
          'subject' => $this->Subject,
          'email_from' => $this->From,
          'email_to' => $emailTo, // require is array
          'mailer' => 'Fallback',
          'date_time' => current_time('mysql'),
          'status' => 0, // 0: false, 1: true, 2: waiting
          'content_type' => $this->ContentType,
          'body_content' => $this->Body,
        );

        if ($result == true) {
          $dataLogsDB['date_time'] = current_time('mysql');
          $dataLogsDB['status'] = 1;
          Utils::insertEmailLogs($dataLogsDB);
        } else {
          $dataLogsDB['date_time'] = current_time('mysql');
          Utils::insertEmailLogs($dataLogsDB);
        }

        return $result;
      } catch (\Exception $exc) {
        $this->mailHeader = '';
        $this->setError($exc->getMessage());
        if ($this->exceptions) {
          throw $exc;
        }

        return false;
      }
    }else{
      if ($currentMailer === 'mail' || $currentMailer === 'smtp') {
        try {
          $result = $this->postSend();
  
          $emailTo = array();
          if (!empty($this->getToAddresses()) && is_array($this->getToAddresses())) {
            foreach ($this->getToAddresses() as $toEmail) {
              if (!empty($toEmail[0])) {
                $emailTo[] = $toEmail[0];
              }
            }
          }
  
          $dataLogsDB = array(
            'subject' => $this->Subject,
            'email_from' => $this->From,
            'email_to' => $emailTo, // require is array
            'mailer' => 'Default',
            'date_time' => current_time('mysql'),
            'status' => 0, // 0: false, 1: true, 2: waiting
            'content_type' => $this->ContentType,
            'body_content' => $this->Body,
          );
  
          if ($currentMailer === 'smtp') {
            $dataLogsDB['mailer'] = 'Other SMTP';
          }
  
          if ($result == true) {
            $dataLogsDB['date_time'] = current_time('mysql');
            $dataLogsDB['status'] = 1;
            Utils::insertEmailLogs($dataLogsDB);
          } else {
            $dataLogsDB['date_time'] = current_time('mysql');
            Utils::insertEmailLogs($dataLogsDB);
          }
  
          return $result;
        } catch (\Exception $exc) {
          $this->mailHeader = '';
          $this->setError($exc->getMessage());
          if ($this->exceptions) {
            throw $exc;
          }
  
          return false;
        }
      } else {
        if ($this->getSMTPerObj($currentMailer)) {
          return $this->getSMTPerObj($currentMailer)->send();
        }
      }
    }
    

    return false;
  }

  public function getSMTPerObj($provider) {
    $providers = array(
      'sendgrid' => 'Sendgrid',
      'sendinblue' => 'Sendinblue',
      'gmail' => 'Gmail',
      'zoho' => 'Zoho',
      'mailgun' => 'Mailgun',
      'smtpcom' => 'SMTPcom',
      'amazonses' => 'AmazonSES',
      'postmark' => 'Postmark',
      'sparkpost' => 'SparkPost',
      'mailjet' => 'Mailjet',
      'pepipost' => 'PepiPost',
      'sendpulse' => 'SendPulse',
      'outlookms' => 'OutlookMs',
    );
    $tyleFile = $providers[$provider] . 'Controller';
    return $this->getObject($tyleFile);
  }

  protected function getObject($fileType) {
    $obj = null;
    try {
      $class = 'YaySMTP\Controller\\' . $fileType;
      $file = YAY_SMTP_PLUGIN_PATH . 'includes/Controller/' . $fileType . '.php';
      if (file_exists($file) && class_exists($class)) {
        $obj = $this ? new $class($this) : new $class();
      }
    } catch (\Exception $e) {
    }

    return $obj;
  }
}
