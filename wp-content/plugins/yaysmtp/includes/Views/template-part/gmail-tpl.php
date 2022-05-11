<?php
use YaySMTP\Controller\GmailServiceVendController;

if (!defined('ABSPATH')) {
  exit;
}

$clientId = "";
$clientSecret = "";
$settings = $params['params'];
$mailer = "gmail";
$email = "";

$auth = new GmailServiceVendController();
$clientWebService = $auth->getclientWebService();

if (!empty($params)) {
  if (!empty($settings[$mailer])) {
    if (isset($settings[$mailer]['client_id'])) {
      $clientId = $settings[$mailer]['client_id'];
    }
    if (isset($settings[$mailer]['client_secret'])) {
      $clientSecret = $settings[$mailer]['client_secret'];
    }
    if (isset($settings[$mailer]['gmail_auth_email'])) {
      $email = $settings[$mailer]['gmail_auth_email'];
    }
  }
}

$pluginAuthUrl = add_query_arg(
  array(
    'page' => 'yaysmtp',
    'action' => 'serviceauthyaysmtp',
  ),
  admin_url('options-general.php')
);

$urlAuth = '#';
if (class_exists('\Google_Client', false) && $clientWebService instanceof \Google_Client && !empty($clientWebService)) {
  $urlAuth = filter_var($clientWebService->createAuthUrl(), FILTER_SANITIZE_URL);
}

?>

<div class="yay-smtp-card yay-smtp-mailer-settings" data-mailer="<?php echo $mailer ?>" style="display: <?php echo $currentMailer == $mailer ? 'block' : 'none' ?>">
  <div class="yay-smtp-card-header">
    <div class="yay-smtp-card-title-wrapper">
      <h3 class="yay-smtp-card-title yay-smtp-card-header-item">
        Step 3: Config for Gmail
        <div class="yay-tooltip doc-setting">
          <a class="yay-smtp-button" href="https://yaycommerce.gitbook.io/yaysmtp/how-to-set-up-smtps/how-to-connect-gmail/" target="_blank">
          <svg viewBox="64 64 896 896" data-icon="book" width="15" height="15" fill="currentColor" aria-hidden="true" focusable="false" class=""><path d="M832 64H192c-17.7 0-32 14.3-32 32v832c0 17.7 14.3 32 32 32h640c17.7 0 32-14.3 32-32V96c0-17.7-14.3-32-32-32zm-260 72h96v209.9L621.5 312 572 347.4V136zm220 752H232V136h280v296.9c0 3.3 1 6.6 3 9.3a15.9 15.9 0 0 0 22.3 3.7l83.8-59.9 81.4 59.4c2.7 2 6 3.1 9.4 3.1 8.8 0 16-7.2 16-16V136h64v752z"></path></svg>
          </a>
          <span class="yay-tooltiptext yay-tooltip-bottom">Gmail Documentation</span>
        </div>
      </h3>
      <h3 class="yay-smtp-card-description yay-smtp-card-header-item">
        Gmail API provides a trusted and secure login system. But it has rate limitations, so only recommended if you send a low number of emails.
      </h3>
    </div>
  </div>
  <div class="yay-smtp-card-body">
    <div class="setting-el">
      <div class="setting-label">
        <label for="yay_smtp_setting_gmail_client_id">Client ID</label>
      </div>
      <div class="setting-field">
        <input type="text" data-setting="client_id" id="yay_smtp_setting_gmail_client_id" class="yay-settings" value="<?php echo $clientId ?>">
      </div>
    </div>
    <div class="setting-el">
      <div class="setting-label">
        <label for="yay_smtp_setting_gmail_client_secret">API Key</label>
      </div>
      <div class="setting-field">
        <input data-setting="client_secret" type="password" spellcheck="false" id="yay_smtp_setting_gmail_client_secret" class="yay-settings" value="<?php echo $clientSecret ?>">
      </div>
    </div>
    <div class="setting-el">
      <div class="setting-label">
        <label for="yay_smtp_setting_gmail_authorized_redirect_uri">Authorized Redirect URI</label>
      </div>
      <div class="setting-field">
        <input type="text" readonly id="yay_smtp_setting_gmail_authorized_redirect_uri" value="<?php echo esc_attr($pluginAuthUrl); ?>" onclick="this.select()">
        <p class="setting-description">
          Put this URL into your Google API Credentials > "Authorized redirect URIs" field
        </p>
      </div>
    </div>
    <div class="setting-el">
      <div class="setting-label">
        <label>Authorization Connect</label>
      </div>
      <div class="setting-field">


      <?php if ($auth->clientIDSerect($mailer)) {?>
        <?php if ($auth->tokenEmpty($mailer)) {?>
          <a href="<?php echo esc_url($urlAuth); ?>" class="yay-smtp-button-secondary">
            <?php echo __('Confirm authorization', 'yay-smtp'); ?>
          </a>
          <div class="yay-tooltip icon-tootip-wrap">
            <span class="icon-inst-tootip"></span>
            <span class="yay-tooltiptext yay-tooltip-bottom"><?php echo __('Click the button to confirm authorization', 'yay-smtp'); ?></span>
          </div>
        <?php } else {?>
          <a href="#" class="yay-smtp-button-secondary yaysmtp-gmail-remove-auth">
          <?php
if (!empty($email)) {
  echo __('Remove Authorization (' . $email . ')', 'yay-smtp');
} else {
  echo __('Remove Authorization', 'yay-smtp');
}
  ?>
        </a>

        <div class="yay-tooltip icon-tootip-wrap">
          <span class="icon-inst-tootip"></span>
          <span class="yay-tooltiptext yay-tooltip-bottom"><?php echo __('You will be able to use another Google account after removing this connection', 'yay-smtp'); ?></span>
        </div>
        <?php }?>

      <?php } else {?>
        <p class="inline-error">
				  <?php echo __('Put your Client ID and Client Secret, then save them.', 'yay-smtp'); ?>
			  </p>
      <?php }?>

      </div>
    </div>
  </div>
</div>