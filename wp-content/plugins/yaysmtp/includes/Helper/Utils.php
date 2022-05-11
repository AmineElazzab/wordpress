<?php
namespace YaySMTP\Helper;

defined('ABSPATH') || exit;

class Utils {
  //getTemplatePart('temp-small/forder',array('groupedMetaKimonoPlans' => $groupedMetaPlans[MasterValues::MV_GROUP_KIMONO], 'sexAgeType' => $SEX_AGE_TYPE, 'planShopList' => $planShopList, 'planTypeKimonoMap' => $planTypeKimonoMap));
  public static function getTemplatePart($templateFolder, $slug = null, array $params = array()) {
    global $wp_query;
    //BN_PLUGIN_PATH . "/views/frontside/"."{$slug}.php";
    $_template_file = $templateFolder . "/{$slug}.php";
    if (is_array($wp_query->query_vars)) {
      extract($wp_query->query_vars, EXTR_SKIP);
    }
    extract($params, EXTR_SKIP);
    require $_template_file;
  }

  public static function saniVal($val) {
    return sanitize_text_field($val);
  }

  public static function saniValArray($array) {
    $newArray = array();
    foreach ($array as $key => $val) { // level 1
      if (is_array($val)) {
        foreach ($val as $key_1 => $val_1) { // level 2
          if (is_array($val_1)) {
            foreach ($val_1 as $key_2 => $val_2) { // level 3
              $newArray[$key][$key_1][$key_2] = (isset($array[$key][$key_1][$key_2])) ? sanitize_text_field($val_2) : '';
            }
          } else {
            $newArray[$key][$key_1] = (isset($array[$key][$key_1])) ? sanitize_text_field($val_1) : '';
          }
        }
      } else {
        $newArray[$key] = (isset($array[$key])) ? sanitize_text_field($val) : '';
      }
    }
    return $newArray;
  }

  public static function isJson($string) {
    return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() === JSON_ERROR_NONE) ? true : false;
  }

  public static function checkNonce() {
    $nonce = sanitize_text_field($_POST['nonce']);
    if (!wp_verify_nonce($nonce, 'ajax-nonce')) {
      wp_send_json_error(array('mess' => 'Nonce is invalid'));
    }
  }

  public static function getCurrentMailer() {
    $mailer = "mail";
    $yaysmtpSettings = get_option('yaysmtp_settings');
    if (!empty($yaysmtpSettings) && is_array($yaysmtpSettings)) {
      if (!empty($yaysmtpSettings['currentMailer'])) {
        $mailer = $yaysmtpSettings['currentMailer'];
      }
    }
    return $mailer;
  }

  public static function getCurrentFromEmail() {
    $mailer = get_option('admin_email');
    $yaysmtpSettings = get_option('yaysmtp_settings');
    if (!empty($yaysmtpSettings) && is_array($yaysmtpSettings)) {
      if (!empty($yaysmtpSettings['fromEmail'])) {
        $mailer = $yaysmtpSettings['fromEmail'];
      }
    }
    return $mailer;
  }

  public static function getCurrentFromName() {
    $mailer = get_bloginfo('name');
    $yaysmtpSettings = get_option('yaysmtp_settings');
    if (!empty($yaysmtpSettings) && is_array($yaysmtpSettings)) {
      if (!empty($yaysmtpSettings['fromName'])) {
        $mailer = $yaysmtpSettings['fromName'];
      }
    }
    return str_replace('\\', '', $mailer );
  }

  public static function getCurrentFromEmailFallback() {
    $result = get_option('admin_email');
    $yaysmtpSettings = get_option('yaysmtp_settings');
    if (!empty($yaysmtpSettings) && is_array($yaysmtpSettings)) {
      if (!empty($yaysmtpSettings['fallback_from_email'])) {
        $result = $yaysmtpSettings['fallback_from_email'];
      }
    }
    return $result;
  }

  public static function getCurrentFromNameFallback() {
    $result = get_bloginfo('name');
    $yaysmtpSettings = get_option('yaysmtp_settings');
    if (!empty($yaysmtpSettings) && is_array($yaysmtpSettings)) {
      if (!empty($yaysmtpSettings['fallback_from_name'])) {
        $result = $yaysmtpSettings['fallback_from_name'];
      }
    }
    return str_replace('\\', '', $result );
  }

  public static function getFallbackHasSettingMail() {
    $result = false;
    $yaysmtpSettings = get_option('yaysmtp_settings');
    if (!empty($yaysmtpSettings) && is_array($yaysmtpSettings)) {
      if (!empty($yaysmtpSettings['fallback_has_setting_mail']) && 'yes' == $yaysmtpSettings['fallback_has_setting_mail']) {
        $result = true;
      }
    }
    return $result;
  }

  public static function getForceFromName() {
    $forceFromName = 0;
    $yaysmtpSettings = get_option('yaysmtp_settings');
    if (!empty($yaysmtpSettings) && is_array($yaysmtpSettings)) {
      if (isset($yaysmtpSettings['forceFromName'])) {
        $forceFromName = $yaysmtpSettings['forceFromName'];
      }
    }
    return $forceFromName;
  }

  public static function getForceFromEmail() {
    $forceFromEmail = 1;
    $yaysmtpSettings = get_option('yaysmtp_settings');
    if (!empty($yaysmtpSettings) && is_array($yaysmtpSettings)) {
      if (isset($yaysmtpSettings['forceFromEmail'])) {
        $forceFromEmail = $yaysmtpSettings['forceFromEmail'];
      }
    }
    return $forceFromEmail;
  }

  public static function getAdminEmail() {
    return get_option('admin_email');
  }

  public static function getAdminFromName() {
    return get_bloginfo('name');
  }

  public static function getAllMailerSetting() {
    return array(
      "mail" => array(),
      "smtp" => array('host', 'port'),
      "sendgrid" => array("api_key"),
      "sendinblue" => array("api_key"),
      "gmail" => array('client_id', 'client_secret', 'gmail_access_token', 'gmail_refresh_token'),
      "zoho" => array('client_id', 'client_secret', 'access_token'),
      "mailgun" => array("api_key", "domain"),
      "smtpcom" => array("api_key", "sender"),
      "amazonses" => array("region", "access_key_id", "secret_access_key"),
      "postmark" => array("api_key"),
      "sparkpost" => array("api_key"),
      "mailjet" => array('api_key', 'secret_key'),
      "pepipost" => array("api_key"),
      "sendpulse" => array('api_key', 'secret_key'),
      "outlookms" => array('client_id', 'client_secret', 'outlookms_access_token', 'outlookms_refresh_token'),
    );
  }

  public static function isMailerComplete() {
    $isComplete = true;
    $currentMailer = self::getCurrentMailer();
    if ($currentMailer == "mail") {
      return true;
    }

    $mailerSettingAll = self::getAllMailerSetting();

    $yaysmtpSettings = get_option('yaysmtp_settings');
    if (!empty($yaysmtpSettings) && is_array($yaysmtpSettings) && !empty($mailerSettingAll[$currentMailer])) {
      $settingArrRequireds = $mailerSettingAll[$currentMailer];
      if (!empty($yaysmtpSettings[$currentMailer])) {
        foreach ($settingArrRequireds as $setting) {
          if (empty($yaysmtpSettings[$currentMailer][$setting])) {
            $isComplete = false;
          }
        }
      }
    }
    return $isComplete;
  }

  /** ----------------------------------- Auth - start -----------------------*/

  public static function getYaySmtpSetting() {
    $rst = array();
    $yaysmtpSettings = get_option('yaysmtp_settings');
    if (!empty($yaysmtpSettings) && is_array($yaysmtpSettings)) {
      $rst = $yaysmtpSettings;
    }
    return $rst;
  }

  public static function setYaySmtpSetting($key, $value = "", $mailer = "") {
    if (empty($mailer) && !empty($key)) { // Update: fromEmail / fromName / currentMailer. Ex: ['fromEmail' => 'admin']
      $setting = self::getYaySmtpSetting();
      $setting[$key] = $value;
      update_option('yaysmtp_settings', $setting);
    } elseif (!empty($mailer) && !empty($key)) { // Update settings of mailer. Ex: ['sendgrid' => ['api_key' => '123abc']]
      $setting = self::getYaySmtpSetting();
      $setting[$mailer][$key] = $value;
      update_option('yaysmtp_settings', $setting);
    }
  }

  public static function getYaySmtpEmailLogSetting() {
    $rst = array();
    $yaysmtpSettings = get_option('yaysmtp_email_log_settings');
    if (!empty($yaysmtpSettings) && is_array($yaysmtpSettings)) {
      $rst = $yaysmtpSettings;
    }
    return $rst;
  }

  public static function setYaySmtpEmailLogSetting($key, $value = "") {
    if (!empty($key)) {
      $setting = self::getYaySmtpEmailLogSetting();
      $setting[$key] = $value;
      update_option('yaysmtp_email_log_settings', $setting);
    }
  }

  public static function getAdminPageUrl($page = '') {
    if (empty($page)) {
      $page = 'yaysmtp';
    }

    return add_query_arg(
      'page',
      $page,
      self::adminUrl('admin.php')
    );
  }

  public static function adminUrl($path = '', $scheme = 'admin') {
    return \admin_url($path, $scheme);
  }

  /** ----------------------------------- Auth - end -----------------------*/

  public static function encrypt($string, $class = '') {
    return base64_encode($string . '-' . substr(sha1($class . $string . 'yay_smtp123098'), 0, 6));
  }

  public static function decrypt($string, $class = '') {
    $parts = explode('-', base64_decode($string));
    
    $numberLast = count($parts) - 1;
    $sha1 = $parts[$numberLast];
    $result = 0;

    $stringArrTemp = array();
    for($i = 0; $i < $numberLast; $i++){
      array_push($stringArrTemp, $parts[$i]);     
    }

    $result = implode("-", $stringArrTemp);

    return substr(sha1($class . $result . 'yay_smtp123098'), 0, 6) === $sha1 ? $result : 0;
  }

  public static function insertEmailLogs($data) {
    $emailLogSetting = Utils::getYaySmtpEmailLogSetting();
    $saveSetting = isset($emailLogSetting) && isset($emailLogSetting['save_email_log']) ? $emailLogSetting['save_email_log'] : 'yes';
    $infTypeSetting = isset($emailLogSetting) && isset($emailLogSetting['email_log_inf_type']) ? $emailLogSetting['email_log_inf_type'] : 'basic_inf';

    if ($saveSetting == 'yes' && !empty($data) && is_array($data['email_to'])) {
      global $wpdb;
      $tableName = $wpdb->prefix . "yaysmtp_email_logs";
      $content = array(
        'subject' => $data['subject'],
        'email_from' => $data['email_from'],
        'email_to' => maybe_serialize($data['email_to']),
        'mailer' => $data['mailer'],
        'date_time' => $data['date_time'],
        'status' => $data['status'],
      );

      if (!empty($data['reason_error'])) {
        $content['reason_error'] = $data['reason_error'];
      }

      if ($infTypeSetting != 'basic_inf') {
        $content['content_type'] = $data['content_type'];
        $content['body_content'] = maybe_serialize($data['body_content']);
      }

      $wpdb->insert($tableName, $content);
    }
  }

  public static function getChartData($groupBy = 'day', $year = '', $start = '', $end = '') {
    global $wpdb;

    $startDate = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : $start;
    $endDate = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : $end;
    $currentYear = date('Y');

    if (!$startDate) {
      $startDate = date('Y-m-d', strtotime(date('Ym', current_time('timestamp')) . '01'));

      if ($groupBy == 'year') {
        $startDate = $year . '-01-01';
      }
    }

    if (!$endDate) {
      $endDate = date('Y-m-d', current_time('timestamp'));

      if ($groupBy == 'year' && ($year < $currentYear)) {
        $endDate = $year . '-12-31';
      }
    }

    $dateWhere = '';

    if ('day' == $groupBy) {
      $group_by_query = 'YEAR(el.date_time), MONTH(el.date_time), DAY(el.date_time)';
      $dateWhere = " AND DATE(el.date_time) >= '$startDate' AND DATE(el.date_time) <= '$endDate'";
    } else {
      $group_by_query = 'YEAR(el.date_time), MONTH(el.date_time)';
      $dateWhere = " AND DATE(el.date_time) >= '$startDate' AND DATE(el.date_time) <= '$endDate'";
    }

    $dateWhere = $dateWhere;

    $sqlMailSuccess = "SELECT
      COUNT(el.id) as total_emails,
      el.date_time as date_time
      FROM {$wpdb->prefix}yaysmtp_email_logs el
      WHERE
      el.status = 1
      $dateWhere
      GROUP BY $group_by_query";

    $mailSuccessData = $wpdb->get_results($sqlMailSuccess);

    $sqlMailFail = "SELECT
      COUNT(el.id) as total_emails,
      el.date_time as date_time
      FROM {$wpdb->prefix}yaysmtp_email_logs el
      WHERE
      el.status = 0
      $dateWhere
      GROUP BY $group_by_query";

    $mailFailData = $wpdb->get_results($sqlMailFail);

    $data = array(
      'successData' => $mailSuccessData,
      'failData' => $mailFailData,
    );
    return $data;
  }

  public static function getMailBankSettingsTable() {
    global $wpdb;
    $tablePrefix = $wpdb->prefix;
    if ( is_multisite() ) {
      $tableExist = $wpdb->query(
        'SHOW TABLES LIKE "' . $wpdb->base_prefix . 'mail_bank_meta"'
      );

      if(!empty($tableExist)){
        $settings = $wpdb->get_var(
          $wpdb->prepare(
            'SELECT meta_value FROM ' . $wpdb->base_prefix . 'mail_bank_meta WHERE meta_key=%s', 'settings'
          )
        );
        $dataArray = maybe_unserialize( $settings );
        if ( isset( $dataArray['fetch_settings'] ) && 'network_site' === $dataArray['fetch_settings'] ) {
          $tablePrefix = $wpdb->base_prefix;
        }
      }
    }

    $result = null;
    $tableExist2 = $wpdb->query(
      'SHOW TABLES LIKE "' . $tablePrefix . 'mail_bank_meta"'
    );
    if(!empty($tableExist2)){
      $result = $wpdb->get_var(
        $wpdb->prepare(
          'SELECT meta_value FROM ' . $tablePrefix . 'mail_bank_meta WHERE meta_key=%s', 'email_configuration'
        )
      );
    }
    
    return maybe_unserialize( $result );
  }

  public static function getYaysmtpImportPlugins(){
    $yaysmtpImportPlugins = array();
    $esyWpSmtpSettings = get_option( 'swpsmtp_options', array() );
    $wpMailSettings = get_option( 'wp_mail_smtp', array() );
    $smtpMailerSettings = get_option( 'smtp_mailer_options', array() );
    $wpSmtpsettings = get_option( 'wp_smtp_options', array() );
    $mailBankSettings = Utils::getMailBankSettingsTable();
    $postSmtpSettings = get_option( 'postman_options', array() );

    if(!empty($esyWpSmtpSettings)){
      $setts = array(
        "val" => "easywpsmtp",
        "title" => "Easy WP SMTP",
        "img" => "easywpsmtp.png"
      );
      array_push($yaysmtpImportPlugins, $setts);
    }
    if(!empty($wpMailSettings)){
      $setts = array(
        "val" => "wpmailsmtp",
        "title" => __("WP Mail SMTP", "yay-smtp"),
        "img" => "wpmailsmtp.png"
      );
      array_push($yaysmtpImportPlugins, $setts);
    }
    if(!empty($smtpMailerSettings)){
      $setts = array(
        "val" => "smtpmailer",
        "title" => __("SMTP Mailer", "yay-smtp"),
        "img" => "smtpmailer.png"
      );
      array_push($yaysmtpImportPlugins, $setts);
    }
    if(!empty($wpSmtpsettings)){
      $setts = array(
        "val" => "wpsmtp",
        "title" => __("WP SMTP", "yay-smtp"),
        "img" => "wpsmtp.png"
      );
      array_push($yaysmtpImportPlugins, $setts);
    }
    if(!empty($mailBankSettings)){
      $setts = array(
        "val" => "mailbank",
        "title" => __("Mail Bank", "yay-smtp"),
        "img" => "mailbank.png"
      );
      array_push($yaysmtpImportPlugins, $setts);
    }
    if(!empty($postSmtpSettings)){
      $setts = array(
        "val" => "postsmtp",
        "title" => __("Post SMTP Mailer", "yay-smtp"),
        "img" => "postsmtp.png"
      );
      array_push($yaysmtpImportPlugins, $setts);
    }

    return $yaysmtpImportPlugins;
  }

  public static function getTemplateHtml( $template_name, $template_path = '' ) {
    ob_start();
    $_template_file = $template_path . "/{$template_name}.php";
    include $_template_file;
    return ob_get_clean();
  }

  public static function getMailReportData($start = '', $end = '') {
    global $wpdb;

    $startDate = $start;
    $endDate = $end;
    if (!$startDate) {
      $startDate = date('Y-m-d', strtotime(date('Ym', current_time('timestamp')) . '01'));
    }
    if (!$endDate) {
      $endDate = date('Y-m-d', current_time('timestamp'));
    }

    $dateWhere = " AND DATE(el.date_time) >= '$startDate' AND DATE(el.date_time) <= '$endDate'";
    
    $sqlMailSuccess = "SELECT
      COUNT(el.id) as total_emails
      FROM {$wpdb->prefix}yaysmtp_email_logs el
      WHERE
      el.status = 1
      $dateWhere";

    $mailSuccessData = (int) $wpdb->get_var($sqlMailSuccess);

    $sqlMailFail = "SELECT
      COUNT(el.id) as total_emails
      FROM {$wpdb->prefix}yaysmtp_email_logs el
      WHERE
      el.status = 0
      $dateWhere";

    $mailFailData = (int) $wpdb->get_var($sqlMailFail);

    $data = array(
      'total_mail' => $mailSuccessData + $mailFailData,
      'sent_mail' => $mailSuccessData,
      'failed_mail' => $mailFailData,
    );

    return $data;
  }

  public static function percentClass( $current = 0, $last = 0 ) {
		$percent = 0;
		$class   = 'up';
		if ( 0 == $current ) {
			$percent = $last * 100;
			$class   = 'down';
		} elseif ( 0 == $last ) {
			$percent = $current * 100;
		} elseif ( $current > $last ) {
			$percent = ( $current - $last ) / $last * 100;
		} elseif ( $current < $last ) {
			$percent = ( $last - $current ) / $last * 100;
			$class   = 'down';
		}

		$result = array(
			'percent' => round( $percent, 1 ),
			'class'   => $class,
		);

		return $result;
  }
  
  public static function getMailReportGroupByData($groupBy = 'subject', $start = '', $end = '', $limit = 5) {
    global $wpdb;

    $startDate = $start;
    $endDate = $end;
    if (!$startDate) {
      $startDate = date('Y-m-d', strtotime(date('Ym', current_time('timestamp')) . '01'));
    }
    if (!$endDate) {
      $endDate = date('Y-m-d', current_time('timestamp'));
    }

    $dateWhere = "DATE(el.date_time) >= '$startDate' AND DATE(el.date_time) <= '$endDate'";

    $groupByQuery = 'el.subject, el.status';
    $groupByQueryLimit = 'el.subject';
    if('subject' == $groupBy){
      $groupByQuery = 'el.subject, el.status';
      $groupByQueryLimit = 'el.subject';
    }

    // Get 5 items have the most number
    $sqlMailLimit = "SELECT
      el.subject, COUNT(el.id) as total_emails
      FROM {$wpdb->prefix}yaysmtp_email_logs el
      WHERE $dateWhere
      GROUP BY $groupByQueryLimit
      ORDER BY total_emails DESC
      LIMIT $limit";

    $mailReportGroupByDataLimitQuery = $wpdb->get_results($sqlMailLimit);

    $mailReportGroupByDataLimitData = array();
    if(!empty($mailReportGroupByDataLimitQuery)){
      foreach($mailReportGroupByDataLimitQuery as $el){
        $title = trim($el->subject);
        $mailReportGroupByDataLimitData[$title] = (int) $el->total_emails;
      }
    }

    // Get total items
    $sqlMail = "SELECT
      el.subject, COUNT(el.id) as total_emails, el.status
      FROM {$wpdb->prefix}yaysmtp_email_logs el
      WHERE $dateWhere
      GROUP BY $groupByQuery
      ORDER BY total_emails DESC";

    $mailReportGroupByDataQuery = $wpdb->get_results($sqlMail);

    $mailReportGroupByData = array();
    if(!empty($mailReportGroupByDataQuery)){
      foreach($mailReportGroupByDataQuery as $el){
        $title = trim($el->subject);
        $status = (int) $el->status;
  
        $totalSent = 0;
        $totalFailed = 0;
        if(1 == $status){
          $totalSent = (int) $el->total_emails;
          $mailReportGroupByData[$title]['total_sent'] = $totalSent;
        }elseif(0 == $status){
          $totalFailed = (int) $el->total_emails;
          $mailReportGroupByData[$title]['total_failed'] = $totalFailed;
        }
      }
    }
    
    // Merge data
    if(!empty($mailReportGroupByDataLimitData) && !empty($mailReportGroupByData)){
      foreach($mailReportGroupByDataLimitData as $title => $el){
        $mailReportGroupByDataLimitData[$title] = $mailReportGroupByData[$title];
      }
    }
    

    return $mailReportGroupByDataLimitData;
  }

  public static function conditionUseFallbackSmtp($force = false) {
    if(self::isTestMailFallback()){
      return true;
    }

    if($force){
      return true;
    }

    if(!self::getFallbackHasSettingMail()){
      return false;
    }

    if(!self::isFullSettingsFallbackSmtp()){
      return false;
    }
    
    global $wpdb;
    $result = false;

    $query = "SELECT *
      FROM {$wpdb->prefix}yaysmtp_email_logs
      ORDER BY date_time DESC
      LIMIT 3";

    $sqlResult = $wpdb->get_results($query);
    $countFailedEmail = 0;
    if(!empty($sqlResult)){
      foreach($sqlResult as $mail){
        $status = (int) $mail->status;
        $mailer = $mail->mailer;
        if(0 == $status && 'Fallback' != $mailer){
          ++$countFailedEmail;
        }
      }
    }
  
    if(3 == $countFailedEmail){
      $result = true;
    }

		return $result;
  }

  public static function isFullSettingsFallbackSmtp() {
    $settings = Utils::getYaySmtpSetting();
    if(!empty($settings)){
      if (empty($settings['fallback_host'])) {
        return false;
      }
      if (empty($settings['fallback_port'])) {
        return false;
      }
      if (empty($settings['fallback_auth_type'])) {
        return false;
      }
      
      if (empty($settings['fallback_auth'])) {
        return false;
      }else if (!empty($settings['fallback_auth']) && 'yes' == $settings['fallback_auth']) {
        if (empty($settings['fallback_smtp_user'])) {
          return false;
        }
        if (empty($settings['fallback_smtp_pass'])) {
          return false;
        }
      }
    } else {
      return false;
    }
   
    return true;
  }

  public static function isTestMailFallback() {
    $result = false;
    $yaysmtpSettings = get_option('yaysmtp_settings');
    if (!empty($yaysmtpSettings) && is_array($yaysmtpSettings)) {
      if (!empty($yaysmtpSettings['flag_test_mail_fallback']) && 'yes' == $yaysmtpSettings['flag_test_mail_fallback']) {
        $result = true;
      }
    }
    return $result;
  }

}
