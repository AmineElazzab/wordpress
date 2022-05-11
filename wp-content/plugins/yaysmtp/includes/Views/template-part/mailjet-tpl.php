<?php
if (!defined('ABSPATH')) {
  exit;
}

$apiKey = "";
$secretKey = "";
$settings = $params['params'];
$mailer = "mailjet";

if (!empty($params)) {
  if (!empty($settings[$mailer])) {
    if (isset($settings[$mailer]['api_key'])) {
      $apiKey = $settings[$mailer]['api_key'];
    }
    if (isset($settings[$mailer]['secret_key'])) {
      $secretKey = $settings[$mailer]['secret_key'];
    }
  }
}

?>

<div class="yay-smtp-card yay-smtp-mailer-settings" data-mailer="<?php echo $mailer ?>" style="display: <?php echo $currentMailer == $mailer ? 'block' : 'none' ?>">
  <div class="yay-smtp-card-header">
    <div class="yay-smtp-card-title-wrapper">
      <h3 class="yay-smtp-card-title yay-smtp-card-header-item">
        Config for Mailjet
        <div class="yay-tooltip doc-setting">
          <a class="yay-smtp-button" href="https://yaycommerce.gitbook.io/yaysmtp/how-to-set-up-smtps/how-to-connect-mailjet/" target="_blank">
          <svg viewBox="64 64 896 896" data-icon="book" width="15" height="15" fill="currentColor" aria-hidden="true" focusable="false" class=""><path d="M832 64H192c-17.7 0-32 14.3-32 32v832c0 17.7 14.3 32 32 32h640c17.7 0 32-14.3 32-32V96c0-17.7-14.3-32-32-32zm-260 72h96v209.9L621.5 312 572 347.4V136zm220 752H232V136h280v296.9c0 3.3 1 6.6 3 9.3a15.9 15.9 0 0 0 22.3 3.7l83.8-59.9 81.4 59.4c2.7 2 6 3.1 9.4 3.1 8.8 0 16-7.2 16-16V136h64v752z"></path></svg>
          </a>
          <span class="yay-tooltiptext yay-tooltip-bottom">Mailjet Documentation</span>
        </div>
      </h3>
      <h3 class="yay-smtp-card-description yay-smtp-card-header-item">
        Mailjet is an EASY-TO-USE email platform. It's never been easier to get your emails into the inbox! Mailjet is europe's leading email solution, with over 130,000 customers in 150 countries.
      </h3>
    </div>
  </div>
  <div class="yay-smtp-card-body">
    <div class="setting-el">
      <div class="setting-label">
        <label for="yay_smtp_setting_mailjet_api_key">API Key</label>
      </div>
      <div class="setting-field">
        <input type="text" data-setting="api_key" id="yay_smtp_setting_mailjet_api_key" class="yay-settings" value="<?php echo $apiKey ?>">
        <p class="setting-description">
          Click here to
          <a href="https://app.mailjet.com/account/api_keys" target="_blank" rel="noopener noreferrer">Get API Key</a>
        </p>
      </div>
    </div>
    <div class="setting-el">
      <div class="setting-label">
        <label for="yay_smtp_setting_mailjet_secret_key">Secret Key</label>
      </div>
      <div class="setting-field">
        <input data-setting="secret_key" type="password" spellcheck="false" id="yay_smtp_setting_mailjet_secret_key" class="yay-settings" value="<?php echo $secretKey ?>">
        <p class="setting-description">
          Click here to
          <a href="https://app.mailjet.com/account/api_keys" target="_blank" rel="noopener noreferrer">Get Secret Key</a>
        </p>
      </div>
    </div>
  </div>
</div>