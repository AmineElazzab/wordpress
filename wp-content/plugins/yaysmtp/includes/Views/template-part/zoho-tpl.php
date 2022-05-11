<?php

use YaySMTP\Controller\ZohoServiceVendController;
use YaySMTP\Helper\Utils;

if (!defined('ABSPATH')) {
  exit;
}

$clientId = "";
$clientSecret = "";
$settings = $params['params'];
$error = "";
$checkAccess = false;
$mailer = "zoho";

$auth = new ZohoServiceVendController();

if (!empty($params)) {
  if (!empty($settings[$mailer])) {
    if (isset($settings[$mailer]['client_id'])) {
      $clientId = $settings[$mailer]['client_id'];
    }
    if (isset($settings[$mailer]['client_secret'])) {
      $clientSecret = $settings[$mailer]['client_secret'];
    }
  }
}

if (ZohoServiceVendController::isDiffInfo() || ZohoServiceVendController::isExpired()) {
  $error = "No access token. Please check your client secret or re-confirm authorization";
  // LogErrors::clearErr();
  ZohoServiceVendController::doResetToken();
}

if (!empty($params)) {
  if (!empty($settings[$mailer])) {
    if (empty($settings[$mailer]['access_token'])) {
      $error = "No access token. Please check your client secret or re-confirm authorization";
    } else {
      $checkAccess = true;
    }
  }
}

?>

<div class="yay-smtp-card yay-smtp-mailer-settings" data-mailer="<?php echo $mailer ?>" style="display: <?php echo $currentMailer == $mailer ? 'block' : 'none' ?>">
  <div class="yay-smtp-card-header">
    <div class="yay-smtp-card-title-wrapper">
      <h3 class="yay-smtp-card-title yay-smtp-card-header-item">
        Step 3: Config for Zoho
        <div class="yay-tooltip doc-setting">
          <a class="yay-smtp-button" href="https://yaycommerce.gitbook.io/yaysmtp/how-to-set-up-smtps/how-to-connect-zoho/" target="_blank">
          <svg viewBox="64 64 896 896" data-icon="book" width="15" height="15" fill="currentColor" aria-hidden="true" focusable="false" class=""><path d="M832 64H192c-17.7 0-32 14.3-32 32v832c0 17.7 14.3 32 32 32h640c17.7 0 32-14.3 32-32V96c0-17.7-14.3-32-32-32zm-260 72h96v209.9L621.5 312 572 347.4V136zm220 752H232V136h280v296.9c0 3.3 1 6.6 3 9.3a15.9 15.9 0 0 0 22.3 3.7l83.8-59.9 81.4 59.4c2.7 2 6 3.1 9.4 3.1 8.8 0 16-7.2 16-16V136h64v752z"></path></svg>
          </a>
          <span class="yay-tooltiptext yay-tooltip-bottom">Zoho Documentation</span>
        </div>
      </h3>
      <h3 class="yay-smtp-card-description yay-smtp-card-header-item">
        Zoho provides a trusted and secure login system. You can use Zoho personal or business email account to send emails.
      </h3>
    </div>
  </div>
  <div class="yay-smtp-card-body">
    <div class="setting-el">
      <div class="setting-label">
        <label for="yay_smtp_setting_zoho_client_id">Client ID</label>
      </div>
      <div class="setting-field">
        <input type="text" data-setting="client_id" id="yay_smtp_setting_zoho_client_id" class="yay-settings" value="<?php echo $clientId ?>">
      </div>
    </div>
    <div class="setting-el">
      <div class="setting-label">
        <label for="yay_smtp_setting_zoho_client_secret">Client Secret</label>
      </div>
      <div class="setting-field">
        <input data-setting="client_secret" type="password" spellcheck="false" id="yay_smtp_setting_zoho_client_secret" class="yay-settings" value="<?php echo $clientSecret ?>">
      </div>
    </div>
    <div class="setting-el">
      <div class="setting-label">
        <label for="yay_smtp_setting_zoho_authorized_redirect_uri">Authorized Redirect URI</label>
      </div>
      <div class="setting-field">
        <input type="text" readonly id="yay_smtp_setting_zoho_authorized_redirect_uri" value="<?php echo esc_attr(ZohoServiceVendController::getPluginAuthUrl()); ?>">
        <p class="setting-description">
          Put this URL into your Zoho Developer Console > "Authorized Redirect URIs" field.
        </p>
      </div>
    </div>
    <div class="setting-el">
      <div class="setting-label">
        <label><?php echo esc_html__('Authorization Connect') ?></label>
      </div>


      <?php if ($clientId === '' || $clientSecret === '') {
  echo '<p class="yay-zoho-not-authorize">Please fill in Client ID and Client Secret</p>';
} else {?>

      <?php if ($auth->tokenEmpty($mailer)) {?>

        <a class="yay-smtp-button-secondary" href="<?php if (Utils::getCurrentMailer() !== 'zoho') {
  echo '#';
} else {
  echo ZohoServiceVendController::generate_auth_code_url();
}
  ?>" >Confirm authorization</a>
        <div class="yay-tooltip icon-tootip-wrap">
          <span class="icon-inst-tootip"></span>
          <span class="yay-tooltiptext yay-tooltip-bottom"><?php echo __('Click the button to confirm authorization', 'yay-smtp'); ?></span>
        </div>
      <?php } else {?>

        <a href="#" class="yay-smtp-button-secondary yaysmtp-yoho-remove-auth">
					<?php echo __('Remove Authorization', 'yay-smtp'); ?>
        </a>

      <?php }?>


      <?php if ($error !== '') {
  echo '<p class="inline-error">' . esc_html__($error, 'yay-smtp') . '</p>';
}
  ?>

      <?php
if (Utils::isMailerComplete() && !ZohoServiceVendController::isExpired()) {
    echo '<p class="yay-zoho-success-authorize">You are authorized. Let\'s send some test mail</p>';
  }

  ?>


      <?php }?>
   </div>
  </div>
</div>