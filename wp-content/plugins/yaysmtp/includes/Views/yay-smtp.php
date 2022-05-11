<?php

use YaySMTP\Helper\Utils;

if (!defined('ABSPATH')) {
  exit;
}
$templatePart = YAY_SMTP_PLUGIN_PATH . "includes/Views/template-part";
$currentMailer = Utils::getCurrentMailer();
$isMailerComplete = Utils::isMailerComplete();
$yaysmtpSettings = Utils::getYaySmtpSetting();

// SHOW/HIDE Amazon SES veriry Email Sender Description. - start
$regionAmazonSES = "us-east-1";
if (!empty($yaysmtpSettings)) {
  if (!empty($yaysmtpSettings["amazonses"]) && !empty($yaysmtpSettings["amazonses"]['region'])) {
    $regionAmazonSES = $yaysmtpSettings["amazonses"]['region'];
  }
}
$verifyAmazonSesEmailLink = "https://" . $regionAmazonSES . ".console.aws.amazon.com/ses/home?region=" . $regionAmazonSES . "#verified-senders-email:";
$amozonSesDesShow = "none";
$postmarkDesShow = "none";
$sparkPostDesShow = 'none';
$outlookmsDesShow = 'none';
if (Utils::getCurrentMailer() == "amazonses") {
  $amozonSesDesShow = "block";
} else if (Utils::getCurrentMailer() == "postmark") {
  $postmarkDesShow = "block";
} else if (Utils::getCurrentMailer() == "sparkpost") {
  $sparkPostDesShow = "block";
} else if (Utils::getCurrentMailer() == "outlookms") {
  $outlookmsDesShow = "block";
}
// SHOW/HIDE Amazon SES veriry Email Sender Description. - end

$yaySMTPerList = array(
  'mail' => 'Default',
  'sendgrid' => 'SendGrid',
  'sendinblue' => 'Sendinblue',
  'amazonses' => 'Amazon SES',
  'mailgun' => 'Mailgun',
  'smtpcom' => 'SMTP.com',
  'gmail' => 'Gmail',
  'zoho' => 'Zoho',
  'postmark' => 'Postmark',
  'sparkpost' => 'SparkPost',
  'mailjet' => 'Mailjet',
  'pepipost' => 'Pepipost',
  'sendpulse' => 'SendPulse',
  'outlookms' => 'Outlook Microsoft',
  'smtp' => 'Other SMTP',
);

// var_dump(Utils::getYaySmtpSetting()['sendpulse']);
?>
<!-- Mail Setting page - start -->
<div class="yay-smtp-wrap send-mail-settings-wrap">
  <div class="yay-smtp-header">
    <div class="yay-smtp-title">
      <h2>YaySMTP</h2>
    </div>
    <div class="yay-button-wrap">
      <div class="yay-tooltip">
        <button type="button" class="yay-smtp-button panel-tab-btn send-test-mail-panel">
          <svg viewBox="64 64 896 896" data-icon="mail" width="15" height="15" fill="currentColor" aria-hidden="true" focusable="false" class=""><path d="M928 160H96c-17.7 0-32 14.3-32 32v640c0 17.7 14.3 32 32 32h832c17.7 0 32-14.3 32-32V192c0-17.7-14.3-32-32-32zm-40 110.8V792H136V270.8l-27.6-21.5 39.3-50.5 42.8 33.3h643.1l42.8-33.3 39.3 50.5-27.7 21.5zM833.6 232L512 482 190.4 232l-42.8-33.3-39.3 50.5 27.6 21.5 341.6 265.6a55.99 55.99 0 0 0 68.7 0L888 270.8l27.6-21.5-39.3-50.5-42.7 33.2z"></path></svg>
          <span class="text">Send Test Email</span>
        </button>
        <!-- <span class="yay-tooltiptext yay-tooltip-bottom">Send test mail</span> -->
      </div>
      <div class="yay-tooltip">
        <a class="yay-smtp-button panel-tab-btn other mail-additional-setts-button">
          <svg enable-background="new 0 0 512 512" height="15" width="15" fill="currentColor" viewBox="0 0 512 512"><path d="m272.066 512h-32.133c-25.989 0-47.134-21.144-47.134-47.133v-10.871c-11.049-3.53-21.784-7.986-32.097-13.323l-7.704 7.704c-18.659 18.682-48.548 18.134-66.665-.007l-22.711-22.71c-18.149-18.129-18.671-48.008.006-66.665l7.698-7.698c-5.337-10.313-9.792-21.046-13.323-32.097h-10.87c-25.988 0-47.133-21.144-47.133-47.133v-32.134c0-25.989 21.145-47.133 47.134-47.133h10.87c3.531-11.05 7.986-21.784 13.323-32.097l-7.704-7.703c-18.666-18.646-18.151-48.528.006-66.665l22.713-22.712c18.159-18.184 48.041-18.638 66.664.006l7.697 7.697c10.313-5.336 21.048-9.792 32.097-13.323v-10.87c0-25.989 21.144-47.133 47.134-47.133h32.133c25.989 0 47.133 21.144 47.133 47.133v10.871c11.049 3.53 21.784 7.986 32.097 13.323l7.704-7.704c18.659-18.682 48.548-18.134 66.665.007l22.711 22.71c18.149 18.129 18.671 48.008-.006 66.665l-7.698 7.698c5.337 10.313 9.792 21.046 13.323 32.097h10.87c25.989 0 47.134 21.144 47.134 47.133v32.134c0 25.989-21.145 47.133-47.134 47.133h-10.87c-3.531 11.05-7.986 21.784-13.323 32.097l7.704 7.704c18.666 18.646 18.151 48.528-.006 66.665l-22.713 22.712c-18.159 18.184-48.041 18.638-66.664-.006l-7.697-7.697c-10.313 5.336-21.048 9.792-32.097 13.323v10.871c0 25.987-21.144 47.131-47.134 47.131zm-106.349-102.83c14.327 8.473 29.747 14.874 45.831 19.025 6.624 1.709 11.252 7.683 11.252 14.524v22.148c0 9.447 7.687 17.133 17.134 17.133h32.133c9.447 0 17.134-7.686 17.134-17.133v-22.148c0-6.841 4.628-12.815 11.252-14.524 16.084-4.151 31.504-10.552 45.831-19.025 5.895-3.486 13.4-2.538 18.243 2.305l15.688 15.689c6.764 6.772 17.626 6.615 24.224.007l22.727-22.726c6.582-6.574 6.802-17.438.006-24.225l-15.695-15.695c-4.842-4.842-5.79-12.348-2.305-18.242 8.473-14.326 14.873-29.746 19.024-45.831 1.71-6.624 7.684-11.251 14.524-11.251h22.147c9.447 0 17.134-7.686 17.134-17.133v-32.134c0-9.447-7.687-17.133-17.134-17.133h-22.147c-6.841 0-12.814-4.628-14.524-11.251-4.151-16.085-10.552-31.505-19.024-45.831-3.485-5.894-2.537-13.4 2.305-18.242l15.689-15.689c6.782-6.774 6.605-17.634.006-24.225l-22.725-22.725c-6.587-6.596-17.451-6.789-24.225-.006l-15.694 15.695c-4.842 4.843-12.35 5.791-18.243 2.305-14.327-8.473-29.747-14.874-45.831-19.025-6.624-1.709-11.252-7.683-11.252-14.524v-22.15c0-9.447-7.687-17.133-17.134-17.133h-32.133c-9.447 0-17.134 7.686-17.134 17.133v22.148c0 6.841-4.628 12.815-11.252 14.524-16.084 4.151-31.504 10.552-45.831 19.025-5.896 3.485-13.401 2.537-18.243-2.305l-15.688-15.689c-6.764-6.772-17.627-6.615-24.224-.007l-22.727 22.726c-6.582 6.574-6.802 17.437-.006 24.225l15.695 15.695c4.842 4.842 5.79 12.348 2.305 18.242-8.473 14.326-14.873 29.746-19.024 45.831-1.71 6.624-7.684 11.251-14.524 11.251h-22.148c-9.447.001-17.134 7.687-17.134 17.134v32.134c0 9.447 7.687 17.133 17.134 17.133h22.147c6.841 0 12.814 4.628 14.524 11.251 4.151 16.085 10.552 31.505 19.024 45.831 3.485 5.894 2.537 13.4-2.305 18.242l-15.689 15.689c-6.782 6.774-6.605 17.634-.006 24.225l22.725 22.725c6.587 6.596 17.451 6.789 24.225.006l15.694-15.695c3.568-3.567 10.991-6.594 18.244-2.304z"/><path d="m256 367.4c-61.427 0-111.4-49.974-111.4-111.4s49.973-111.4 111.4-111.4 111.4 49.974 111.4 111.4-49.973 111.4-111.4 111.4zm0-192.8c-44.885 0-81.4 36.516-81.4 81.4s36.516 81.4 81.4 81.4 81.4-36.516 81.4-81.4-36.515-81.4-81.4-81.4z"/></svg>
        </a>
        <span class="yay-tooltiptext yay-tooltip-bottom">Additional Settings</span>
      </div>
      <div class="yay-tooltip">
        <a class="yay-smtp-button panel-tab-btn other mail-logs-button" href="<?php //echo YAY_SMTP_SITE_URL . '/wp-admin/admin.php?page=yaysmtp&tab=email-logs' ?>">
          <svg width="15" height="15" fill="currentColor" aria-hidden="true" focusable="false" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" >
            <g>
              <g>
                <g>
                  <path d="M32,464V48c0-8.837,7.163-16,16-16h240v64c0,17.673,14.327,32,32,32h64v48h32v-64c0.025-4.253-1.645-8.341-4.64-11.36
                    l-96-96C312.341,1.645,308.253-0.024,304,0H48C21.49,0,0,21.491,0,48v416c0,26.51,21.49,48,48,48h112v-32H48
                    C39.164,480,32,472.837,32,464z"/>
                  <path d="M480,320h-32v32h32v32h-64v-96h96c0-17.673-14.327-32-32-32h-64c-17.673,0-32,14.327-32,32v96c0,17.673,14.327,32,32,32
                    h64c17.673,0,32-14.327,32-32v-32C512,334.327,497.673,320,480,320z"/>
                  <path d="M304,256c-35.346,0-64,28.654-64,64v32c0,35.346,28.654,64,64,64c35.346,0,64-28.654,64-64v-32
                    C368,284.654,339.346,256,304,256z M336,352c0,17.673-14.327,32-32,32c-17.673,0-32-14.327-32-32v-32c0-17.673,14.327-32,32-32
                    c17.673,0,32,14.327,32,32V352z"/>
                  <path d="M160,256h-32v144c0,8.837,7.163,16,16,16h80v-32h-64V256z"/>
                </g>
              </g>
            </g>
            <g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g>
          </svg>
        </a>
        <span class="yay-tooltiptext yay-tooltip-bottom">Email Logs</span>
      </div>
      <div class="yay-tooltip">
        <a class="yay-smtp-button panel-tab-btn other" href="https://yaycommerce.com/support/" target="_blank">
          <svg viewBox="64 64 896 896" data-icon="message" width="15" height="15" fill="currentColor" aria-hidden="true" focusable="false" class=""><path d="M464 512a48 48 0 1 0 96 0 48 48 0 1 0-96 0zm200 0a48 48 0 1 0 96 0 48 48 0 1 0-96 0zm-400 0a48 48 0 1 0 96 0 48 48 0 1 0-96 0zm661.2-173.6c-22.6-53.7-55-101.9-96.3-143.3a444.35 444.35 0 0 0-143.3-96.3C630.6 75.7 572.2 64 512 64h-2c-60.6.3-119.3 12.3-174.5 35.9a445.35 445.35 0 0 0-142 96.5c-40.9 41.3-73 89.3-95.2 142.8-23 55.4-34.6 114.3-34.3 174.9A449.4 449.4 0 0 0 112 714v152a46 46 0 0 0 46 46h152.1A449.4 449.4 0 0 0 510 960h2.1c59.9 0 118-11.6 172.7-34.3a444.48 444.48 0 0 0 142.8-95.2c41.3-40.9 73.8-88.7 96.5-142 23.6-55.2 35.6-113.9 35.9-174.5.3-60.9-11.5-120-34.8-175.6zm-151.1 438C704 845.8 611 884 512 884h-1.7c-60.3-.3-120.2-15.3-173.1-43.5l-8.4-4.5H188V695.2l-4.5-8.4C155.3 633.9 140.3 574 140 513.7c-.4-99.7 37.7-193.3 107.6-263.8 69.8-70.5 163.1-109.5 262.8-109.9h1.7c50 0 98.5 9.7 144.2 28.9 44.6 18.7 84.6 45.6 119 80 34.3 34.3 61.3 74.4 80 119 19.4 46.2 29.1 95.2 28.9 145.8-.6 99.6-39.7 192.9-110.1 262.7z"></path></svg>
        </a>
        <span class="yay-tooltiptext yay-tooltip-bottom">Support</span>
      </div>
      <div class="yay-tooltip">
        <a class="yay-smtp-button panel-tab-btn other" href="https://docs.yaycommerce.com/yaysmtp/how-to-set-up-smtps" target="_blank">
          <svg viewBox="64 64 896 896" data-icon="book" width="15" height="15" fill="currentColor" aria-hidden="true" focusable="false" class=""><path d="M832 64H192c-17.7 0-32 14.3-32 32v832c0 17.7 14.3 32 32 32h640c17.7 0 32-14.3 32-32V96c0-17.7-14.3-32-32-32zm-260 72h96v209.9L621.5 312 572 347.4V136zm220 752H232V136h280v296.9c0 3.3 1 6.6 3 9.3a15.9 15.9 0 0 0 22.3 3.7l83.8-59.9 81.4 59.4c2.7 2 6 3.1 9.4 3.1 8.8 0 16-7.2 16-16V136h64v752z"></path></svg>
        </a>
        <span class="yay-tooltiptext yay-tooltip-bottom">Documentation</span>
      </div>
    </div>
    <!-- Send test mail drawer - start -->
    <?php Utils::getTemplatePart($templatePart, "send-test-mail", array("isMailerComplete" => $isMailerComplete));?>
    <!-- Send test mail drawer - end -->
  </div>
  <div class="yay-smtp-content">
    <!-- Debug card - start -->
    <?php Utils::getTemplatePart($templatePart, "debug-card");?>
    <!-- Debug card - end -->
    <div class="yay-smtp-card">
      <div class="yay-smtp-card-header">
        <div class="yay-smtp-card-title-wrapper">
          <h3 class="yay-smtp-card-title yay-smtp-card-header-item">Step 1: Enter Email From</h3>
        </div>
      </div>
      <div class="yay-smtp-card-body">
        <div class="setting-from-email">
          <div class="setting-label">
            <label for="yay_smtp_setting_mail_from_email">From Email</label>
          </div>
          <div class="setting-field">
            <input type="text" id="yay_smtp_setting_mail_from_email" value="<?php echo Utils::getCurrentFromEmail(); ?>" />
            <p class="error-message-email" style="display:none"></p>
            <p class="setting-description">
              The email displayed in the "From" field.
            </p>
            <div>
              <input
                id="yay_smtp_setting_mail_force_from_email"
                type="checkbox"
                name="force_from_email"
                <?php checked(Utils::getForceFromEmail(), 1);?>
              />
              <label for="yay_smtp_setting_mail_force_from_email">Force From Email</label>
              <div class="yay-tooltip icon-tootip-wrap">
                <span class="icon-inst-tootip"></span>
                <span class="yay-tooltiptext yay-tooltip-bottom"><?php echo __('Always send emails with the above From Email address, overriding other plugins settings.', 'yay-smtp'); ?></span>
              </div>
            </div>
            <p class="setting-description yay-amazon-ses-des" style="display: <?php echo $amozonSesDesShow; ?>">
              Please note: If your account is still in Amazon SES sandbox mode.<br>
              - You can only send mail from verified email addresses.<br>
              <a href="<?php echo $verifyAmazonSesEmailLink; ?>" target="_blank" rel="noopener noreferrer">Click to verify Email From</a>
            </p>
            <p class="setting-description yay-postmark-des" style="display: <?php echo $postmarkDesShow; ?>">
              - You can only send mail from verified email addresses.<br>
              <a href="https://account.postmarkapp.com/signature_domains" target="_blank" rel="noopener noreferrer">Click to Sender Signature</a>
            </p>
            <p class="setting-description yay-sparkpost-des" style="display: <?php echo $sparkPostDesShow; ?>">
              - You can only send mail from verified sending domains.<br>
            </p>
            <p class="setting-description yay-outlookms-des" style="display: <?php echo $outlookmsDesShow; ?>">
              - You can only send mail from Microsoft email account<br>
            </p>
          </div>
        </div>
        <div class="setting-from-name">
          <div class="setting-label">
            <label for="yay_smtp_setting_mail_from_name">From Name</label>
          </div>
          <div class="setting-field">
            <input type="text" id="yay_smtp_setting_mail_from_name" value="<?php echo Utils::getCurrentFromName(); ?>"/>
            <p class="setting-description">
              The name displayed in emails
            </p>
            <div>
              <input
                id="yay_smtp_setting_mail_force_from_name"
                type="checkbox"
                name="force_from_name"
                <?php checked(Utils::getForceFromName(), 1);?>
              />
              <label for="yay_smtp_setting_mail_force_from_name">Force From Name</label>
              <div class="yay-tooltip icon-tootip-wrap">
                <span class="icon-inst-tootip"></span>
                <span class="yay-tooltiptext yay-tooltip-bottom"><?php echo __('Always send emails with the above From Name, overriding other plugins settings.', 'yay-smtp'); ?></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="yay-smtp-card">
      <div class="yay-smtp-card-header">
        <div class="yay-smtp-card-title-wrapper">
          <h3 class="yay-smtp-card-title yay-smtp-card-header-item">Step 2: Select Mailer</h3>
        </div>
        <div class="yay-smtp-mailer smtper-choose-wrap">
          <select class="yay-settings smtper-choose" id="yaysmtp_smtper_choose">
          <?php
foreach ($yaySMTPerList as $val => $text) {
  $selected = '';
  if ($val === $currentMailer) {
    $selected = 'selected';
  }
  echo '<option value="' . esc_attr($val) . '" ' . $selected . '>' . esc_attr($text) . '</option>';
}
?>
          </select>
        </div>
      </div>
    </div>
    <!-- Mailer Settings-->
    <div class="mailer-settings-component">
      <?php
Utils::getTemplatePart($templatePart, 'mail-tpl', array('currentMailer' => $currentMailer));
Utils::getTemplatePart($templatePart, 'sendgrid-tpl', array('currentMailer' => $currentMailer, 'params' => $yaysmtpSettings));
Utils::getTemplatePart($templatePart, 'sendinblue-tpl', array('currentMailer' => $currentMailer, 'params' => $yaysmtpSettings));
Utils::getTemplatePart($templatePart, 'gmail-tpl', array('currentMailer' => $currentMailer, 'params' => $yaysmtpSettings));
Utils::getTemplatePart($templatePart, 'other-smtp-tpl', array('currentMailer' => $currentMailer, 'params' => $yaysmtpSettings));
Utils::getTemplatePart($templatePart, 'zoho-tpl', array('currentMailer' => $currentMailer, 'params' => $yaysmtpSettings));
Utils::getTemplatePart($templatePart, 'mailgun-tpl', array('currentMailer' => $currentMailer, 'params' => $yaysmtpSettings));
Utils::getTemplatePart($templatePart, 'smtp-com-tpl', array('currentMailer' => $currentMailer, 'params' => $yaysmtpSettings));
Utils::getTemplatePart($templatePart, 'amazonses-tpl', array('currentMailer' => $currentMailer, 'params' => $yaysmtpSettings));
Utils::getTemplatePart($templatePart, 'postmark-tpl', array('currentMailer' => $currentMailer, 'params' => $yaysmtpSettings));
Utils::getTemplatePart($templatePart, 'sparkpost-tpl', array('currentMailer' => $currentMailer, 'params' => $yaysmtpSettings));
Utils::getTemplatePart($templatePart, 'mailjet-tpl', array('currentMailer' => $currentMailer, 'params' => $yaysmtpSettings));
Utils::getTemplatePart($templatePart, 'pepipost-tpl', array('currentMailer' => $currentMailer, 'params' => $yaysmtpSettings));
Utils::getTemplatePart($templatePart, 'sendpulse-tpl', array('currentMailer' => $currentMailer, 'params' => $yaysmtpSettings));
Utils::getTemplatePart($templatePart, 'outlook-ms-tpl', array('currentMailer' => $currentMailer, 'params' => $yaysmtpSettings));
?>
    </div>
  </div>
  <div>
    <button type="button" class="yay-smtp-button yay-smtp-save-settings-action">Save Changes</button>
  </div>
</div>
<!-- Mail Setting page - end -->


<!-- Mail Logs page - start -->
<?php Utils::getTemplatePart(YAY_SMTP_PLUGIN_PATH . "includes/Views", "mail-logs");?>
<!-- Mail Logs page - end -->

<!-- Additional settings page - start -->
<?php Utils::getTemplatePart(YAY_SMTP_PLUGIN_PATH . "includes/Views", "additional-setting", array('params' => $yaysmtpSettings));?>
<!-- Additional settings page - end -->



