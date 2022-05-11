<?php
use YaySMTP\Helper\Utils;

if (!defined('ABSPATH')) {
  exit;
}

$host = "";
$encryption = "tls";
$port = "";
$auth = "yes";
$user = "";
$pass = "";

$settings = $params['params'];
$mailer = "smtp";
if (!empty($params)) {
  if (!empty($settings[$mailer])) {
    if (isset($settings[$mailer]['host'])) {
      $host = $settings[$mailer]['host'];
    }
    if (isset($settings[$mailer]['encryption'])) {
      $encryption = $settings[$mailer]['encryption'];
    }
    if (isset($settings[$mailer]['port'])) {
      $port = $settings[$mailer]['port'];
    }
    if (isset($settings[$mailer]['auth'])) {
      $auth = $settings[$mailer]['auth'];
    }
    if (isset($settings[$mailer]['user'])) {
      $user = $settings[$mailer]['user'];
    }
    if (isset($settings[$mailer]['pass'])) {
      $pass = Utils::decrypt($settings[$mailer]['pass'], 'smtppass');
    }
  }
}
?>

<div class="yay-smtp-card yay-smtp-mailer-settings" data-mailer="<?php echo $mailer ?>" style="display: <?php echo $currentMailer == $mailer ? 'block' : 'none' ?>">
  <div class="yay-smtp-card-header">
    <div class="yay-smtp-card-title-wrapper">
      <h3 class="yay-smtp-card-title yay-smtp-card-header-item">
        Step 3: Config for Other SMTP
        <div class="yay-tooltip doc-setting">
          <a class="yay-smtp-button" href="https://yaycommerce.gitbook.io/yaysmtp/how-to-set-up-smtps/how-to-connect-other-smtp/" target="_blank">
          <svg viewBox="64 64 896 896" data-icon="book" width="15" height="15" fill="currentColor" aria-hidden="true" focusable="false" class=""><path d="M832 64H192c-17.7 0-32 14.3-32 32v832c0 17.7 14.3 32 32 32h640c17.7 0 32-14.3 32-32V96c0-17.7-14.3-32-32-32zm-260 72h96v209.9L621.5 312 572 347.4V136zm220 752H232V136h280v296.9c0 3.3 1 6.6 3 9.3a15.9 15.9 0 0 0 22.3 3.7l83.8-59.9 81.4 59.4c2.7 2 6 3.1 9.4 3.1 8.8 0 16-7.2 16-16V136h64v752z"></path></svg>
          </a>
          <span class="yay-tooltiptext yay-tooltip-bottom">Other SMTP Documentation</span>
        </div>
      </h3>
      <h3 class="yay-smtp-card-description yay-smtp-card-header-item">
        Use SMTP from your hosting provider or email service (Gmail, Hotmail, Yahoo, etc).
      </h3>
    </div>
  </div>
  <div class="yay-smtp-card-body">
    <div class="setting-el">
      <div class="setting-label">
        <label for="yay_smtp_setting_smtp_host">SMTP Host</label>
      </div>
      <div class="setting-field">
        <input type="text" data-setting="host" id="yay_smtp_setting_smtp_host" class="yay-settings" value="<?php echo $host; ?>">
      </div>
    </div>
    <div class="setting-el">
      <div class="setting-label">
        <label for="yay_smtp_setting_smtp_encryption_tls">Encryption Type</label>
      </div>
      <div class="setting-field">
        <!-- <input type="radio" data-setting="encryption" id="yay_smtp_setting_smtp_encryption_none" name="yay_smtp_setting_smtp_encryption" class="yay-settings" value="none"> -->
        <label class="radio-setting">
          <input type="radio" data-setting="encryption" id="yay_smtp_setting_smtp_encryption_ssl" name="yay_smtp_setting_smtp_encryption" class="yay-settings" value="ssl" <?php echo $encryption == "ssl" ? "checked" : "" ?>>
          SSL
        </label>
        <label class="radio-setting">
          <input type="radio" data-setting="encryption" id="yay_smtp_setting_smtp_encryption_tls" name="yay_smtp_setting_smtp_encryption" class="yay-settings" value="tls" <?php echo $encryption == "tls" ? "checked" : "" ?>>
          TLS
        </label>
        <p class="setting-description">
          TLS is the recommended option if your SMTP provider supports it.
        </p>
      </div>
    </div>
    <div class="setting-el">
      <div class="setting-label">
        <label for="yay_smtp_setting_smtp_port">SMTP Port</label>
      </div>
      <div class="setting-field">
        <input type="number" data-setting="port" id="yay_smtp_setting_smtp_port" class="yay-settings" value="<?php echo $port; ?>">
        <p class="setting-description">
          Port of your mail server. Usually is 25, 465, 587
        </p>
      </div>
    </div>
    <div class="setting-el">
      <div class="setting-label">
        <label for="yay_smtp_setting_smtp_auth">SMTP Authentication</label>
      </div>
      <div class="setting-field">
        <label class="switch">
          <input type="checkbox" data-setting="auth" id="yay_smtp_setting_smtp_auth" class="yay-settings" <?php echo $auth == "yes" ? "checked" : "" ?>>
          <span class="slider round"></span>
        </label>
        <label class="toggle-label">
          <span class="setting-toggle-checked">ON</span>
          <span class="setting-toggle-unchecked">OFF</span>
        </label>
        <!--<p class="setting-description">
          The recommended option is ON.
        </p>-->
      </div>
    </div>
    <div class="yay_smtp_setting_auth_det" style="display: <?php echo $auth == 'yes' ? 'block' : 'none' ?>">
      <div class="setting-el">
        <div class="setting-label">
          <label for="yay_smtp_setting_smtp_user">SMTP Username</label>
        </div>
        <div class="setting-field">
          <input type="text" data-setting="user" id="yay_smtp_setting_smtp_user" class="yay-settings" value="<?php echo $user; ?>">
        </div>
      </div>
      <div class="setting-el">
        <div class="setting-label">
          <label for="yay_smtp_setting_pass">SMTP Password</label>
        </div>
        <div class="setting-field">
          <input data-setting="pass" type="password" spellcheck="false" id="yay_smtp_setting_pass" class="yay-settings" value="<?php echo $pass; ?>">
        </div>
      </div>
    </div>
  </div>
</div>