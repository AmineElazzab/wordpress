<?php
use YaySMTP\Helper\Utils;

if (!defined('ABSPATH')) {
  exit;
}

// style - start 
$yaysmtp_report_performed_cl = 'display: inline-flex; border: 1px solid #ccd0d4; width: 90%;';
$yaysmtp_mail_wrap = 'width: 30%; text-align: center; margin: 15px;';
$yaysmtp_mail_icon = 'margin-bottom: 15px;';
$yaysmtp_mail_number = 'font-size: 20px; font-weight: 500; margin-bottom: 15px;';
$yaysmtp_mail_percent_down = 'font-size: 15px;color: #d94f4f';
$yaysmtp_mail_percent_up = 'font-size: 15px;color: #4ab866';
$yaysmtp_top_emails_wrap_cl = 'margin-top: 35px;padding-bottom: 15px;border-bottom: 1px solid #ccd0d4;';
$yaysmtp_width = 'width: 90%;text-align: center;';
$yaysmtp_group_title = 'font-size: 17px;margin-top: 25px;width: 70%;text-align: left;';
$yaysmtp_group_content_wrap = 'display: inline-flex;width: 100%;';
$yaysmtp_group_content_item = 'display: inline-flex; margin-left: 30px;margin-right: 30px;margin-top: 25px;';
$yaysmtp_group_content_item_icon = 'margin-right: 8px;';
$yaysmtp_group_content_item_number = 'font-size: 16px;margin-top: 2px;';
$yaysmtp_group_button = 'font-size: 17px;margin-top: 25px;';
// style - end

// Last week
$yaysmtpRepStart = gmdate('Y-m-d', strtotime("last week"));
$yaysmtpRepStartDateO = new \DateTime($yaysmtpRepStart);
$yaysmtpRepStartDateO->modify('+6 day');
$yaysmtpRepEnd = $yaysmtpRepStartDateO->format('Y-m-d');
$mailReportData = Utils::getMailReportData($yaysmtpRepStart, $yaysmtpRepEnd);

// Prepare data for Subject mail group in last weekly
$mailReportGroupByData = Utils::getMailReportGroupByData('subject', $yaysmtpRepStart, $yaysmtpRepEnd, 5);

// 2 last week
$twoLastWeekStartDateO = new \DateTime($yaysmtpRepStart);
$twoLastWeekStartDateO->modify('-7 day');
$twoLastWeekStart = $twoLastWeekStartDateO->format('Y-m-d');
$twoLastWeekStartDateO->modify('+6 day');
$twoLastWeekEnd = $twoLastWeekStartDateO->format('Y-m-d');
$twoLastWeekReportData = Utils::getMailReportData($twoLastWeekStart, $twoLastWeekEnd);


$yaysmtpMailTotalPercent = Utils::percentClass($mailReportData['total_mail'], $twoLastWeekReportData['total_mail']);
$yaysmtpMailSentPercent = Utils::percentClass($mailReportData['sent_mail'], $twoLastWeekReportData['sent_mail']);
$yaysmtpMailFailedPercent = Utils::percentClass($mailReportData['failed_mail'], $twoLastWeekReportData['failed_mail']);

?>

<div>
  <p>Howdy,</p>
  <div>
    <p>Here’s the summary of <?php echo get_bloginfo('name'); ?> email deliverability with YaySMTP.</p>
    <div style="<?php echo $yaysmtp_report_performed_cl ?>">
      <div style="<?php echo $yaysmtp_mail_wrap ?>">
        <div style="<?php echo $yaysmtp_mail_icon ?>">
          <img src="<?php echo YAY_SMTP_PLUGIN_URL . 'assets/img/mail-icon.png' ?>" height="25" width="32">
        </div>
        <div style="<?php echo $yaysmtp_mail_number ?>"><?php echo $mailReportData['total_mail']; ?></div>
        <?php 
        $totalMailText = "- ";
        $yaysmtp_mail_percent = $yaysmtp_mail_percent_down;
        if($yaysmtpMailTotalPercent['class'] == 'up') {
          $totalMailText = "+ ";
          $yaysmtp_mail_percent = $yaysmtp_mail_percent_up;
        }
        ?>
        <div style="<?php echo $yaysmtp_mail_percent ?>">
          <?php 
          echo $totalMailText . $yaysmtpMailTotalPercent['percent'] . ' %';
          ?>
        </div>
      </div>
      <div style="<?php echo $yaysmtp_mail_wrap ?>">
        <div style="<?php echo $yaysmtp_mail_icon ?>">
          <img src="<?php echo YAY_SMTP_PLUGIN_URL . 'assets/img/sent-icon.png' ?>" height="25" width="25">
        </div>
        <div style="<?php echo $yaysmtp_mail_number ?>"><?php echo $mailReportData['sent_mail']; ?></div>
        <?php 
        $sentMailText = "- ";
        $yaysmtp_sent_mail_percent = $yaysmtp_mail_percent_down;
        if($yaysmtpMailSentPercent['class'] == 'up') {
          $sentMailText = "+ ";
          $yaysmtp_sent_mail_percent = $yaysmtp_mail_percent_up;
        }
        ?>
        <div style="<?php echo $yaysmtp_sent_mail_percent ?>">
          <?php 
          echo $sentMailText . $yaysmtpMailSentPercent['percent'] . ' %';
          ?>
        </div>
      </div>
      <div style="<?php echo $yaysmtp_mail_wrap ?>">
        <div style="<?php echo $yaysmtp_mail_icon ?>">
          <img src="<?php echo YAY_SMTP_PLUGIN_URL . 'assets/img/failed-icon.png' ?>" height="25" width="25">
        </div>
        <div style="<?php echo $yaysmtp_mail_number ?>"><?php echo $mailReportData['failed_mail']; ?></div>
        <?php 
        $failedMailText = "- ";
        $yaysmtp_failed_mail_percent = $yaysmtp_mail_percent_down;
        if($yaysmtpMailFailedPercent['class'] == 'up') {
          $failedMailText = "+ ";
          $yaysmtp_failed_mail_percent = $yaysmtp_mail_percent_up;
        }
        ?>
        <div style="<?php echo $yaysmtp_failed_mail_percent ?>">
          <?php 
          echo $failedMailText . $yaysmtpMailFailedPercent['percent'] . ' %';
          ?>
        </div>
      </div>
    </div>
    <div style="<?php echo $yaysmtp_width; ?>">
      <div style="<?php echo $yaysmtp_top_emails_wrap_cl; ?>">
        <h2>Top Emails Last Week</h2>
      </div>
      <?php if(!empty($mailReportGroupByData)) { 
              foreach($mailReportGroupByData as $groupTitle => $mailGroup) {
                $total_sent = !empty($mailGroup['total_sent']) ? $mailGroup['total_sent'] : 0;
                $total_failed = !empty($mailGroup['total_failed']) ? $mailGroup['total_failed'] : 0;
                $total_mail = $total_sent + $total_failed;
                if(0 < intval($total_sent)){
      ?>
                <div style="<?php echo $yaysmtp_group_content_wrap;?>">
                  <div style="<?php echo $yaysmtp_group_title;?>"><?php echo $groupTitle; ?></div>
                  <div style="<?php echo $yaysmtp_group_content_item; ?>">
                    <div style="<?php echo $yaysmtp_group_content_item_icon; ?>">
                      <img src="<?php echo YAY_SMTP_PLUGIN_URL . 'assets/img/sent-icon.png' ?>" height="22" width="22">
                    </div>
                    <div style="<?php echo $yaysmtp_group_content_item_number; ?>"><?php echo $total_sent; ?></div>
                  </div>
                </div>  
      <?php     }
              } 
          } ?>
      <div style="<?php echo $yaysmtp_group_button; ?>"><a href="<?php echo YAY_SMTP_SITE_URL . '/wp-admin'; ?>">View Details</a></div>
    </div>
  </div>
  <div><br>Cheers,<br>YayCommerce </div>
</div>




