<?php
namespace YaySMTP;

defined('ABSPATH') || exit;

use YaySMTP\Helper\Utils;

class Schedule {
  protected static $instance = null;

  public static function getInstance() {
    if (null == self::$instance) {
      self::$instance = new self;
      self::$instance->doHooks();
    }

    return self::$instance;
  }

  private function doHooks() {
    add_filter('cron_schedules', array($this, 'yaysmtp_datetime_custom_cron_schedule'), 5, 1);
    add_filter('cron_schedules', array($this, 'yaysmtp_datetime_monthly_cron_schedule'), 5, 1);

    add_action('yaysmtp_delete_email_log_schedule_hook', array($this, 'delete_email_log_schedule'));
    if (!wp_next_scheduled('yaysmtp_delete_email_log_schedule_hook')) {
      wp_schedule_event(time(), 'yaysmtp_specific_delete_time', 'yaysmtp_delete_email_log_schedule_hook');
    }

    $hasMailReportSett = $this->hasMailReportSett();
    $mailReportSett = $this->getMailReportSett();
    if("yes" == $hasMailReportSett){
      if("weekly" == $mailReportSett){
        add_action('yaysmtp_send_email_report_weekly_schedule_hook', array($this, 'send_mail_report_weekly'));
        if (!wp_next_scheduled('yaysmtp_send_email_report_weekly_schedule_hook')) {
          wp_schedule_event(strtotime('monday this week'), 'weekly', 'yaysmtp_send_email_report_weekly_schedule_hook');
        }
        
        wp_clear_scheduled_hook( 'yaysmtp_send_email_report_monthly_schedule_hook' );
      }elseif("monthly" == $mailReportSett){
        add_action('yaysmtp_send_email_report_monthly_schedule_hook', array($this, 'send_mail_report_monthly'));
        if (!wp_next_scheduled('yaysmtp_send_email_report_monthly_schedule_hook')) {
          wp_schedule_event(strtotime('first day of this month'), 'yaysmtp_monthly_time', 'yaysmtp_send_email_report_monthly_schedule_hook');
        }
  
        wp_clear_scheduled_hook( 'yaysmtp_send_email_report_weekly_schedule_hook' );
      }
    }else{
      wp_clear_scheduled_hook( 'yaysmtp_send_email_report_weekly_schedule_hook' );
      wp_clear_scheduled_hook( 'yaysmtp_send_email_report_monthly_schedule_hook' );
    }
    
  }

  private function __construct() {}

  public function yaysmtp_datetime_custom_cron_schedule($schedules) {
    $emailLogSetting = Utils::getYaySmtpEmailLogSetting();
    $deleteDatetimeSetting = isset($emailLogSetting) && isset($emailLogSetting['email_log_delete_time']) ? (int) $emailLogSetting['email_log_delete_time'] : 60;
    $disPlayText = 'Every ' . $deleteDatetimeSetting . ' days';
    $schedules['yaysmtp_specific_delete_time'] = array(
      'interval' => 86400 * $deleteDatetimeSetting, // Every 6 hours
      'display' => __($disPlayText),
    );
    return $schedules;
  }

  public function yaysmtp_datetime_monthly_cron_schedule($schedules) {
    $schedules['yaysmtp_monthly_time'] = array(
      'interval' => MONTH_IN_SECONDS,
      'display'  => __( 'Once Monthly' )
    );
    return $schedules;
  }

  public function delete_email_log_schedule() {
    global $wpdb;
    $wpdb->query($wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'yaysmtp_email_logs'));
  }

  public function send_mail_report_weekly(){
    $headers = "Content-Type: text/html\r\n";
    $subjectEmail = __('YaySMTP - Mail Weekly Report', 'yay-smtp');
    $emailAddress = Utils::getAdminEmail();
    $html = Utils::getTemplateHtml(
      "weekly-mail",
      YAY_SMTP_PLUGIN_PATH . "includes/Views/template-mail"
    );
    wp_mail($emailAddress, $subjectEmail, $html, $headers);
  }

  public function send_mail_report_monthly(){
    $headers = "Content-Type: text/html\r\n";
    $subjectEmail = __('YaySMTP - Mail Monthly Report', 'yay-smtp');
    $emailAddress = Utils::getAdminEmail();
    $html = Utils::getTemplateHtml(
      "monthly-mail",
      YAY_SMTP_PLUGIN_PATH . "includes/Views/template-mail"
    );
    wp_mail($emailAddress, $subjectEmail, $html, $headers);
  }

  public function getMailReportSett() {
    $result = "weekly";
    $yaysmtpSettings = Utils::getYaySmtpSetting();
    if (!empty($yaysmtpSettings) && !empty($yaysmtpSettings['mail_report_type'])) {
      $result = $yaysmtpSettings['mail_report_type'];
    }
    return $result;
  }

  public function hasMailReportSett() {
    $result = "no";
    $yaysmtpSettings = Utils::getYaySmtpSetting();
    if (!empty($yaysmtpSettings) && !empty($yaysmtpSettings['mail_report_choose'])) {
      $result = $yaysmtpSettings['mail_report_choose'];
    }
    return $result;
  }
}
