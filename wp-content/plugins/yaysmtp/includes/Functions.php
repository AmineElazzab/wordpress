<?php
namespace YaySMTP;

use YaySMTP\Helper\LogErrors;
use YaySMTP\Helper\Utils;

defined('ABSPATH') || exit;

class Functions {
  protected static $instance = null;

  public static function getInstance() {
    if (null == self::$instance) {
      self::$instance = new self;
      self::$instance->doHooks();
    }

    return self::$instance;
  }

  private function doHooks() {
    add_action('wp_ajax_yaysmtp_save_settings', array($this, 'saveSettings'));
    add_action('wp_ajax_yaysmtp_save_addition_settings', array($this, 'saveAdditionSettings'));
    add_action('wp_ajax_yaysmtp_send_mail', array($this, 'sendTestMail'));
    add_action('wp_ajax_yaysmtp_fallback_send_mail', array($this, 'sendTestMailFallback'));
    add_action('wp_ajax_yaysmtp_gmail_remove_auth', array($this, 'gmailRemoveAuth'));
    add_action('wp_ajax_yaysmtp_yoho_remove_auth', array($this, 'yohoRemoveAuth'));
    add_action('wp_ajax_yaysmtp_outlookms_remove_auth', array($this, 'outlookmsRemoveAuth'));
    add_action('wp_ajax_yaysmtp_email_logs', array($this, 'getListEmailLogs'));
    add_action('wp_ajax_yaysmtp_set_email_logs_setting', array($this, 'setYaySmtpEmailLogSetting'));
    add_action('wp_ajax_yaysmtp_delete_email_logs', array($this, 'deleteEmailLogs'));
    add_action('wp_ajax_yaysmtp_detail_email_logs', array($this, 'getEmailLog'));
    add_action('wp_ajax_yaysmtp_overview_chart', array($this, 'getEmailChart'));
  }

  private function __construct() {}

  public function saveSettings() {
    try {
      Utils::checkNonce();
      if (isset($_POST['settings'])) {
        $settings = Utils::saniValArray($_POST['settings']);
        $yaysmtpSettingsDB = get_option('yaysmtp_settings');

        $yaysmtpSettings = array();
        if (!empty($yaysmtpSettingsDB) && is_array($yaysmtpSettingsDB)) {
          $yaysmtpSettings = $yaysmtpSettingsDB;

          // Update "succ_sent_mail_last" option to SHOW/HIDE Debug Box on main page.
          if (isset($yaysmtpSettings['currentMailer'])) {
            $currentMailerDB = $yaysmtpSettings['currentMailer'];
            if (!empty($currentMailerDB) && $currentMailerDB != $settings['mailerProvider']) {
              $yaysmtpSettings['succ_sent_mail_last'] = true;
            }
          }
        }

        $yaysmtpSettings['fromEmail'] = $settings['fromEmail'];
        $yaysmtpSettings['fromName'] = $settings['fromName'];
        $yaysmtpSettings['forceFromEmail'] = $settings['forceFromEmail'];
        $yaysmtpSettings['forceFromName'] = $settings['forceFromName'];

        $yaysmtpSettings['currentMailer'] = $settings['mailerProvider'];
        if (!empty($settings['mailerProvider'])) {
          $mailerSettings = !empty($settings['mailerSettings']) ? $settings['mailerSettings'] : array();

          if (!empty($mailerSettings)) {
            foreach ($mailerSettings as $key => $val) {
              if ($key == "pass") {
                $yaysmtpSettings[$settings['mailerProvider']][$key] = Utils::encrypt($val, 'smtppass');
              } else {
                $yaysmtpSettings[$settings['mailerProvider']][$key] = $val;
              }
            }
          }
        }

        update_option('yaysmtp_settings', $yaysmtpSettings);

        wp_send_json_success(array('mess' => __('Settings saved.', 'yay-smtp')));
      }
      wp_send_json_error(array('mess' => __('Settings Failed.', 'yay-smtp')));
    } catch (\Exception $ex) {
      LogErrors::getMessageException($ex, true);
    } catch (\Error $ex) {
      LogErrors::getMessageException($ex, true);
    }
  }

  public function saveAdditionSettings() {
    try {
      Utils::checkNonce();
      if (isset($_POST['params'])) {
        $params = Utils::saniValArray($_POST['params']);
        foreach ($params as $key => $val) {
          if ("fallback_smtp_pass" == $key) {
            $valPass = Utils::encrypt($val, 'smtppass');
            Utils::setYaySmtpSetting($key, $valPass);
          } else {
            Utils::setYaySmtpSetting($key, $val);
          }
        }
        wp_send_json_success(array(
          'mess' => __('Save Addition Settings Successful', 'yay-smtp'),
        ));
      }
      wp_send_json_error(array('mess' => __('Save Addition Settings Failed.', 'yay-smtp')));
    } catch (\Exception $ex) {
      LogErrors::getMessageException($ex, true);
    } catch (\Error $ex) {
      LogErrors::getMessageException($ex, true);
    }
  }
  
  public function sendTestMail() {
    try {
      Utils::checkNonce();
      if (isset($_POST['emailAddress'])) {
        $emailAddress = sanitize_email($_POST['emailAddress']);
        // check email
        if (!is_email($emailAddress)) {
          wp_send_json_error(array('mess' => __('Invalid email format!', 'yay-smtp')));
        }

        $headers = "Content-Type: text/html\r\n";
        $subjectEmail = __('YaySMTP - Test email was sent successfully!', 'yay-smtp');

        $html = __('Yay!<br><br>Your test email was sent successfully!<br>Thanks for using <a href="https://yaycommerce.com/yaysmtp-wordpress-mail-smtp/">YaySMTP</a><br><br>Feel free to check out our plugins that enhance your WooCommerce <a href="https://wordpress.org/plugins/yaymail/">transactional emails</a> and <a href="https://yaycommerce.com/yaycurrency-woocommerce-multi-currency-switcher/">multi-currency</a>.<br><br>Don’t let distance define your business.<br><br>Cheers,<br>YayCommerce', 'yay-smtp');

        if (!empty($emailAddress)) {
          $sendMailSucc = wp_mail($emailAddress, $subjectEmail, $html, $headers);
          if ($sendMailSucc) {
            Utils::setYaySmtpSetting('succ_sent_mail_last', true);
            wp_send_json_success(array('mess' => __('Email has been sent.', 'yay-smtp')));
          } else {
            Utils::setYaySmtpSetting('succ_sent_mail_last', false);
            if (Utils::getCurrentMailer() == "smtp") {
              LogErrors::clearErr();
              LogErrors::setErr('This error may be caused by: Incorrect From email, SMTP Host, Post, Username or Password.');
              $debugText = implode("<br>", LogErrors::getErr());
            } else {
              $debugText = implode("<br>", LogErrors::getErr());
            }
            wp_send_json_error(array('debugText' => $debugText, 'mess' => __('Email sent failed.', 'yay-smtp')));
          }
        }
      } else {
        wp_send_json_error(array('mess' => __('Email Address is not empty.', 'yay-smtp')));
      }
      wp_send_json_error(array('mess' => __('Error send mail!', 'yay-smtp')));
    } catch (\Exception $ex) {
      LogErrors::getMessageException($ex, true);
    } catch (\Error $ex) {
      LogErrors::getMessageException($ex, true);
    }
  }

  public function sendTestMailFallback() {
    Utils::setYaySmtpSetting('flag_test_mail_fallback', 'no');
    try {
      Utils::checkNonce();
      if (isset($_POST['emailAddress'])) {
        $emailAddress = sanitize_email($_POST['emailAddress']);
        // check email
        if (!is_email($emailAddress)) {
          Utils::setYaySmtpSetting('flag_test_mail_fallback', 'no');
          wp_send_json_error(array('mess' => __('Invalid email format!', 'yay-smtp')));
        }

        $headers = "Content-Type: text/html\r\n";
        $subjectEmail = __('YaySMTP - Fallback test email was sent successfully!', 'yay-smtp');

        $html = __('Yay!<br><br>Your test email was sent successfully!<br>Thanks for using <a href="https://yaycommerce.com/yaysmtp-wordpress-mail-smtp/">YaySMTP</a><br><br>Feel free to check out our plugins that enhance your WooCommerce <a href="https://wordpress.org/plugins/yaymail/">transactional emails</a> and <a href="https://yaycommerce.com/yaycurrency-woocommerce-multi-currency-switcher/">multi-currency</a>.<br><br>Don’t let distance define your business.<br><br>Cheers,<br>YayCommerce', 'yay-smtp');

        if (!empty($emailAddress)) {
          Utils::setYaySmtpSetting('flag_test_mail_fallback', 'yes');
          $sendMailSucc = wp_mail($emailAddress, $subjectEmail, $html, $headers);
          if ($sendMailSucc) {
            Utils::setYaySmtpSetting('flag_test_mail_fallback', 'no');
            wp_send_json_success(array('mess' => __('Email has been sent.', 'yay-smtp')));
          } else {
            Utils::setYaySmtpSetting('flag_test_mail_fallback', 'no');
            wp_send_json_error(array('mess' => __('Email sent failed. This error may be caused by: Incorrect From email, SMTP Host, Post, Username or Password.', 'yay-smtp')));
          }
        }
        Utils::setYaySmtpSetting('flag_test_mail_fallback', 'no');
      } else {
        Utils::setYaySmtpSetting('flag_test_mail_fallback', 'no');
        wp_send_json_error(array('mess' => __('Email Address is not empty.', 'yay-smtp')));
      }
      Utils::setYaySmtpSetting('flag_test_mail_fallback', 'no');      
      wp_send_json_error(array('mess' => __('Error send mail!', 'yay-smtp')));
    } catch (\Exception $ex) {
      Utils::setYaySmtpSetting('flag_test_mail_fallback', 'no');
      LogErrors::getMessageException($ex, true);
    } catch (\Error $ex) {
      Utils::setYaySmtpSetting('flag_test_mail_fallback', 'no');
      LogErrors::getMessageException($ex, true);
    }
  }

  public function gmailRemoveAuth() {
    $setting = Utils::getYaySmtpSetting();

    if (!empty($setting) && !empty($setting['gmail'])) {
      $oldGmailSetting = $setting['gmail'];
      foreach ($oldGmailSetting as $key => $val) {
        if (!in_array($key, array('client_id', 'client_secret'), true)) {
          unset($oldGmailSetting[$key]);
        }
      }

      Utils::setYaySmtpSetting('gmail', $oldGmailSetting);
    }

  }

  public function outlookmsRemoveAuth() {
    $setting = Utils::getYaySmtpSetting();

    if (!empty($setting) && !empty($setting['outlookms'])) {
      $oldSetting = $setting['outlookms'];
      foreach ($oldSetting as $key => $val) {
        if (!in_array($key, array('client_id', 'client_secret'), true)) {
          unset($oldSetting[$key]);
        }
      }

      Utils::setYaySmtpSetting('outlookms', $oldSetting);
    }

  }

  public function yohoRemoveAuth() {
    $setting = Utils::getYaySmtpSetting();

    if (!empty($setting) && !empty($setting['zoho'])) {
      $oldSetting = $setting['zoho'];

      foreach ($oldSetting as $key => $val) {
        // Unset everything except Client ID and Client Secret.
        if (!in_array($key, array('client_id', 'client_secret'), true)) {
          unset($oldSetting[$key]);
        }
      }

      Utils::setYaySmtpSetting('zoho', $oldSetting);

    }
  }

  public function getListEmailLogs() {
    try {
      Utils::checkNonce();
      if (isset($_POST['params'])) {
        $params = Utils::saniValArray($_POST['params']);
        global $wpdb;

        $yaySmtpEmailLogSetting = Utils::getYaySmtpEmailLogSetting();
        $showSubjectColumn = isset($yaySmtpEmailLogSetting) && isset($yaySmtpEmailLogSetting['show_subject_cl']) ? (int) $yaySmtpEmailLogSetting['show_subject_cl'] : 1;
        $showToColumn = isset($yaySmtpEmailLogSetting) && isset($yaySmtpEmailLogSetting['show_to_cl']) ? (int) $yaySmtpEmailLogSetting['show_to_cl'] : 1;
        $showStatusColumn = isset($yaySmtpEmailLogSetting) && isset($yaySmtpEmailLogSetting['show_status_cl']) ? (int) $yaySmtpEmailLogSetting['show_status_cl'] : 1;
        $showDatetimeColumn = isset($yaySmtpEmailLogSetting) && isset($yaySmtpEmailLogSetting['show_datetime_cl']) ? (int) $yaySmtpEmailLogSetting['show_datetime_cl'] : 1;
        $showActionColumn = isset($yaySmtpEmailLogSetting) && isset($yaySmtpEmailLogSetting['show_action_cl']) ? (int) $yaySmtpEmailLogSetting['show_action_cl'] : 1;
        $showStatus = isset($yaySmtpEmailLogSetting) && isset($yaySmtpEmailLogSetting['status']) ? $yaySmtpEmailLogSetting['status'] : "all";

        $showColSettings = array(
          'showSubjectCol' => $showSubjectColumn,
          'showToCol' => $showToColumn,
          'showStatusCol' => $showStatusColumn,
          'showDatetimeCol' => $showDatetimeColumn,
          'showActionCol' => $showActionColumn,
        );

        $limit = !empty($params['limit']) && is_numeric($params['limit']) ? (int) $params['limit'] : 10;
        $page = !empty($params['page']) && is_numeric($params['page']) ? (int) $params['page'] : 1;
        $offset = ($page - 1) * $limit;

        $valSearch = !empty($params['valSearch']) ? $params['valSearch'] : "";
        $sortField = !empty($params['sortField']) ? $params['sortField'] : "date_time";
        $sortVal = "DESC";
        if (!empty($params['sortVal']) && $params['sortVal'] == 'ascending') {
          $sortVal = "ASC";
        }

        $status = !empty($params['status']) ? $params['status'] : $showStatus;
        if ($status == 'sent') {
          $statusWhere = 'status = 1';
        } elseif ($status == 'not_send') {
          $statusWhere = 'status = 0 OR status =2';
        } elseif ($status == 'empty') {
          $statusWhere = 'status <> 1 AND status <> 0 and status <> 2';
        } else {
          $statusWhere = 'status = 1 OR status = 0 OR status = 2';
        }

        // Result ALL
        //SELECT * FROM `wp_yaysmtp_email_logs` WHERE subject LIKE "%khoata91%" OR email_to LIKE "%khoata91%"
        if (!empty($valSearch)) {
          $subjectWhere = 'subject LIKE "%' . $valSearch . '%"';
          $toEmailWhere = 'email_to LIKE "%' . $valSearch . '%"';
          $whereQuery = "{$subjectWhere} OR {$toEmailWhere}";
          if (!empty($statusWhere)) {
            $whereQuery = "(" . $whereQuery . ") AND (" . $statusWhere . ")";
          }
          $sqlRepareAll = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}yaysmtp_email_logs WHERE $whereQuery ORDER BY $sortField $sortVal");

          $sqlRepare = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}yaysmtp_email_logs WHERE $whereQuery ORDER BY $sortField $sortVal LIMIT %d OFFSET %d",
            $limit,
            $offset);
        } else {
          $sqlRepareAll = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}yaysmtp_email_logs WHERE $statusWhere ORDER BY $sortField $sortVal");

          $sqlRepare = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}yaysmtp_email_logs WHERE $statusWhere ORDER BY $sortField $sortVal LIMIT %d OFFSET %d",
            $limit,
            $offset);
        }
        $resultQueryAll = $wpdb->get_results($sqlRepareAll);
        $totalItems = count($resultQueryAll);

        // Result Custom
        $results = $wpdb->get_results($sqlRepare);

        $emailLogsList = array();
        foreach ($results as $result) {
          $emailTo = maybe_unserialize($result->email_to);
          $emailEl = array(
            'id' => $result->id,
            'subject' => $result->subject,
            'email_from' => $result->email_from,
            'email_to' => $emailTo,
            'mailer' => $result->mailer,
            'date_time' => $result->date_time,
            'status' => $result->status,
          );
          $emailLogsList[] = $emailEl;
        }

        wp_send_json_success(array(
          'data' => $emailLogsList,
          'totalItem' => $totalItems,
          'totalPage' => $limit < 0 ? 1 : ceil($totalItems / $limit),
          'currentPage' => $page,
          'limit' => $limit,
          'showColSettings' => $showColSettings,
          'mess' => __('Successful', 'yay-smtp'),
        ));
      }
      wp_send_json_error(array('mess' => __('Failed.', 'yay-smtp')));
    } catch (\Exception $ex) {
      LogErrors::getMessageException($ex, true);
    } catch (\Error $ex) {
      LogErrors::getMessageException($ex, true);
    }
  }

  public function setYaySmtpEmailLogSetting() {
    try {
      Utils::checkNonce();
      if (isset($_POST['params'])) {
        $params = Utils::saniValArray($_POST['params']);
        foreach ($params as $key => $val) {
          Utils::setYaySmtpEmailLogSetting($key, $val);
        }
        wp_send_json_success(array(
          'mess' => __('Save Settings Successful', 'yay-smtp'),
        ));
      }
      wp_send_json_error(array('mess' => __('Save Settings Failed.', 'yay-smtp')));
    } catch (\Exception $ex) {
      LogErrors::getMessageException($ex, true);
    } catch (\Error $ex) {
      LogErrors::getMessageException($ex, true);
    }
  }

  public function deleteEmailLogs() {
    try {
      Utils::checkNonce();
      if (isset($_POST['params'])) {
        global $wpdb;
        $params = Utils::saniValArray($_POST['params']);
        $ids = isset($params['ids']) ? $params['ids'] : ""; // '1,2,3'

        if (empty($ids)) {
          wp_send_json_error(array('mess' => __('No email log id found', 'yay-smtp')));
        }

        $table = $wpdb->prefix . 'yaysmtp_email_logs';
        $deleted = $wpdb->query("DELETE FROM $table WHERE ID IN ($ids)");

        if ($wpdb->last_error !== '') {
          wp_send_json_error(array('mess' => __($wpdb->last_error, 'yay-smtp')));
        }

        if (!$deleted) {
          wp_send_json_error(array('mess' => __('Something wrong, Email logs not deleted', 'yay-smtp')));
        }

        wp_send_json_success(array(
          'mess' => __('Delete successful.', 'yay-smtp'),
        ));
      }
      wp_send_json_error(array('mess' => __('No email log id found.', 'yay-smtp')));

    } catch (\Exception $ex) {
      LogErrors::getMessageException($ex, true);
    } catch (\Error $ex) {
      LogErrors::getMessageException($ex, true);
    }
  }

  public function getEmailLog() {
    try {
      Utils::checkNonce();
      if (isset($_POST['params'])) {
        global $wpdb;
        $params = Utils::saniValArray($_POST['params']);
        $id = isset($params['id']) ? (int) $params['id'] : "";

        if (empty($id)) {
          wp_send_json_error(array('mess' => __('No email log id found', 'yay-smtp')));
        }

        $table = $wpdb->prefix . 'yaysmtp_email_logs';
        $sqlRepare = $wpdb->prepare("Select * FROM $table WHERE id = $id");
        $resultQuery = $wpdb->get_row($sqlRepare);

        if ($wpdb->last_error !== '') {
          wp_send_json_error(array('mess' => __($wpdb->last_error, 'yay-smtp')));
        }

        if (!empty($resultQuery)) {
          $emailTo = maybe_unserialize($resultQuery->email_to);
          $resultArr = array(
            'id' => $resultQuery->id,
            'subject' => $resultQuery->subject,
            'email_from' => $resultQuery->email_from,
            'email_to' => $emailTo,
            'mailer' => $resultQuery->mailer,
            'date_time' => $resultQuery->date_time,
            'status' => $resultQuery->status,
          );

          if (!empty($resultQuery->content_type)) {
            $resultArr['content_type'] = $resultQuery->content_type;
            $resultArr['body_content'] = maybe_serialize($resultQuery->body_content);
          }

          if (!empty($resultQuery->reason_error)) {
            $resultArr['reason_error'] = $resultQuery->reason_error;
          }

          wp_send_json_success(array(
            'mess' => __('Get email log #' . $id . ' successful.', 'yay-smtp'),
            'data' => $resultArr,
          ));
        } else {
          wp_send_json_error(array('mess' => __('No email log found.', 'yay-smtp')));
        }

      }
      wp_send_json_error(array('mess' => __('No email log id found.', 'yay-smtp')));

    } catch (\Exception $ex) {
      LogErrors::getMessageException($ex, true);
    } catch (\Error $ex) {
      LogErrors::getMessageException($ex, true);
    }
  }

  public function getEmailChart() {
    try {
      Utils::checkNonce();

      $reqs = Utils::saniValArray($_POST['params']);
      $from = isset($reqs['from']) ? $reqs['from'] : 'first day of this month';
      $to = isset($reqs['to']) ? $reqs['to'] : '';

      $startDateOrg = new \DateTime($from);
      $endDateOrg = new \DateTime($to);

      $startDate = new \DateTime($from);
      // $startDate = new \DateTime('15 days ago');
      $endDate = new \DateTime($to);

      $dateModifier = $startDate->diff($endDate)->m >= 11 ? '+1 month' : '+1 day';
      $groupBy = $dateModifier === '+1 month' ? 'month' : 'day';

      $data = Utils::getChartData($groupBy, '', $startDate->format('Y-m-d'), $endDate->format('Y-m-d'));

      $labels = array();
      $successData = array();
      $failData = array();
      $successTotal = 0;
      $failTotal = 0;

      // initialize data
      for ($i = $startDate; $i <= $endDate; $i->modify($dateModifier)) {
        $date = $i->format('Y-m-d');
        $labels[$date] = $date;
        $successData[$date] = 0;
        $failData[$date] = 0;
      }

      // fillup real data
      if (!empty($data['successData'])) {
        foreach ($data['successData'] as $row) {
          if ('month' == $groupBy) {
            $date = new \DateTime($row->date_time);
            $date->modify('first day of this month');
            $date = $date->format('Y-m-d');
          } else {
            $date = date('Y-m-d', strtotime($row->date_time));
          }

          $successData[$date] = (int) $row->total_emails;
          $successTotal += (int) $row->total_emails;
        }
      }

      if (!empty($data['failData'])) {
        foreach ($data['failData'] as $row) {
          if ('month' == $groupBy) {
            $date = new \DateTime($row->date_time);
            $date->modify('first day of this month');
            $date = $date->format('Y-m-d');
          } else {
            $date = date('Y-m-d', strtotime($row->date_time));
          }

          $failData[$date] = (int) $row->total_emails;
          $failTotal += (int) $row->total_emails;
        }
      }

      $topMailList = Utils::getMailReportGroupByData('subject', $startDateOrg->format('Y-m-d'), $endDateOrg->format('Y-m-d'), 10);
      $topMailListOutput = array();
      if(!empty($topMailList)){
        foreach($topMailList as $title => $mail){
          $el = array(
            'title' => $title,
            'sent' => !empty($mail['total_sent']) ? $mail['total_sent'] : 0,
            'failed' => !empty($mail['total_failed']) ? $mail['total_failed'] : 0,
          );
          array_push($topMailListOutput, $el);   
        }
      }

      // Total Sales
      $response = array(
        'labels' => array_values($labels),
        'datasets' => array(
          array(
            'label' => __('Email Sent', 'yay-smtp'),
            'borderColor' => '#2A8CE7',
            'backgroundColor' => '#2A8CE7',
            'order' => 1,
            'data' => array_values($successData),
          ),
          array(
            'label' => __('Email Fail', 'yay-smtp'),
            'borderColor' => '#d94f4f',
            'backgroundColor' => '#d94f4f',
            'order' => 0,
            // 'type' => 'line',
            'data' => array_values($failData),
          ),
        ),
        'successTotal' => $successTotal,
        'failTotal' => $failTotal,
        'topMailList' => $topMailListOutput
      );

      wp_send_json_success(array(
        'mess' => __('successful.', 'yay-smtp'),
        'data' => $response,
      ));
    } catch (\Exception $ex) {
      LogErrors::getMessageException($ex, true);
    } catch (\Error $ex) {
      LogErrors::getMessageException($ex, true);
    }
  }
}
