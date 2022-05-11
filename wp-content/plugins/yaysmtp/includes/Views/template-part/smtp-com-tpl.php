<?php
if (!defined('ABSPATH')) {
  exit;
}

$smtpcomApiKey = "";
$sender = "";
$settings = $params['params'];
$mailer = "smtpcom";
if (!empty($params) && !empty($settings[$mailer])) {
  if (isset($settings[$mailer]['api_key'])) {
    $smtpcomApiKey = $settings[$mailer]['api_key'];
  }
  if (isset($settings[$mailer]['sender'])) {
    $sender = $settings[$mailer]['sender'];
  }
}
?>

<div class="yay-smtp-card yay-smtp-mailer-settings" data-mailer="<?php echo $mailer ?>" style="display: <?php echo $currentMailer == $mailer ? 'block' : 'none' ?>">
  <div class="yay-smtp-card-header">
    <div class="yay-smtp-card-title-wrapper">
      <h3 class="yay-smtp-card-title yay-smtp-card-header-item">
        Step 3: Config for SMTP.com
        <div class="yay-tooltip doc-setting">
          <a class="yay-smtp-button" href="https://yaycommerce.gitbook.io/yaysmtp/how-to-set-up-smtps/how-to-connect-smtp.com/" target="_blank">
          <svg viewBox="64 64 896 896" data-icon="book" width="15" height="15" fill="currentColor" aria-hidden="true" focusable="false" class=""><path d="M832 64H192c-17.7 0-32 14.3-32 32v832c0 17.7 14.3 32 32 32h640c17.7 0 32-14.3 32-32V96c0-17.7-14.3-32-32-32zm-260 72h96v209.9L621.5 312 572 347.4V136zm220 752H232V136h280v296.9c0 3.3 1 6.6 3 9.3a15.9 15.9 0 0 0 22.3 3.7l83.8-59.9 81.4 59.4c2.7 2 6 3.1 9.4 3.1 8.8 0 16-7.2 16-16V136h64v752z"></path></svg>
          </a>
          <span class="yay-tooltiptext yay-tooltip-bottom">SMTP.com Documentation</span>
        </div>
      </h3>
      <h3 class="yay-smtp-card-description yay-smtp-card-header-item">
        It's time to take your email delivery seriously. See why 100,000+ businesses have trusted SMTP.com for over 23 years.
      </h3>
    </div>
  </div>
  <div class="yay-smtp-card-body">
    <div class="setting-el">
      <div class="setting-label">
        <label for="yay_smtp_setting_smtpcom_api_key">API Key</label>
      </div>
      <div class="setting-field">
        <input data-setting="api_key" type="password" spellcheck="false" id="yay_smtp_setting_smtpcom_api_key" class="yay-settings" value="<?php echo $smtpcomApiKey ?>">
        <p class="setting-description">
          Click here to
          <a href="https://my.smtp.com/settings/api" target="_blank" rel="noopener noreferrer">Get API Key</a>
        </p>
      </div>
    </div>
    <div class="setting-el">
      <div class="setting-label">
        <label for="yay_smtp_setting_smtpcom_sender">Sender Name</label>
      </div>
      <div class="setting-field">
        <input type="text" data-setting="sender" id="yay_smtp_setting_smtpcom_sender" class="yay-settings" value="<?php echo $sender ?>">
        <p class="setting-description">
          Click here to
          <a href="https://my.smtp.com/senders/" target="_blank" rel="noopener noreferrer">Get Sender Name</a>
        </p>
      </div>
    </div>
  </div>
</div>