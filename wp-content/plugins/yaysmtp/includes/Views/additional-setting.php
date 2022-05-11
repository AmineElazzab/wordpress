<?php
use YaySMTP\Helper\Utils;

if (!defined('ABSPATH')) {
  exit;
}

$yaysmtp_mail_report_choose = "no";
$yaysmtp_mail_report_type = "weekly";
$has_mail_fallback = 'no';
$fallback_force_from_email = 'yes';
$fallback_force_from_name = 'no';
$fallback_host = "";
$fallback_encryption = "tls";
$fallback_port = "";
$fallback_auth = "yes";
$fallback_user = "";
$fallback_pass = "";

if (!empty($params['params'])) {
  $settings = $params['params'];
  if (isset($settings['mail_report_choose'])) {
    $yaysmtp_mail_report_choose = $settings['mail_report_choose'];
  }
  if (!empty($settings['mail_report_type'])) {
    $yaysmtp_mail_report_type = $settings['mail_report_type'];
  }
  if (isset($settings['fallback_has_setting_mail'])) {
    $has_mail_fallback = $settings['fallback_has_setting_mail'];
  }
  if (isset($settings['fallback_force_from_email'])) {
    $fallback_force_from_email = $settings['fallback_force_from_email'];
  }
  if (isset($settings['fallback_force_from_name'])) {
    $fallback_force_from_name = $settings['fallback_force_from_name'];
  }
  if (isset($settings['fallback_host'])) {
    $fallback_host = $settings['fallback_host'];
  }
  if (isset($settings['fallback_auth_type'])) {
    $fallback_encryption = $settings['fallback_auth_type'];
  }
  if (isset($settings['fallback_port'])) {
    $fallback_port = $settings['fallback_port'];
  }
  if (isset($settings['fallback_auth'])) {
    $fallback_auth = $settings['fallback_auth'];
  }
  if (isset($settings['fallback_smtp_user'])) {
    $fallback_user = $settings['fallback_smtp_user'];
  }
  if (isset($settings['fallback_smtp_pass'])) {
    $fallback_pass = Utils::decrypt($settings['fallback_smtp_pass'], 'smtppass');
  }

}

?>

<div class="yay-smtp-wrap yaysmtp-additional-settings-wrap" style="display:none">
  <div class="yay-button-first-header">
    <div class="yay-button-header-child-left">
      <span class="dashicons dashicons-arrow-left-alt"></span>
      <span><a class="mail-setting-redirect">Back to Settings page</a></span>
    </div>
  </div>
  <div class="yay-smtp-card">
    <div class="yay-smtp-card-header">
      <div class="yay-smtp-card-title-wrapper">
        <h3 class="yay-smtp-card-title yay-smtp-card-header-item">
          <?php echo esc_html__( 'Additional Settings', 'yay-smtp' ) ?>
        </h3>
      </div>
    </div>
    <div class="yay-smtp-card-body">
      <div class="setting-mail-report setting-el">
        <div class="setting-label">
          <label for="yaysmtp_addition_setts_report_cb"><?php echo esc_html__( 'Email Notifications', 'yay-smtp' ) ?></label>
        </div>
        <div class="yaysmtp-addition-setts-report-cb">
          <div class="additional-settings-title"><input id="yaysmtp_addition_setts_report_cb" type="checkbox" <?php echo $yaysmtp_mail_report_choose == "yes" ? "checked" : "" ?>/></div>
          <div>
            <label for="yaysmtp_addition_setts_report_cb">
              <?php echo esc_html__( 'Receive SMTP email delivery summary via email.', 'yay-smtp' ) ?>
            </label>
          </div>
        </div>
        <div class="yaysmtp-addition-setts-report-detail">
          <label class="radio-setting">
            <input type="radio" id="yaysmtp_addition_setts_report_weekly" name="yaysmtp_addition_setts_mail_report"  value="weekly" <?php echo $yaysmtp_mail_report_type == "weekly" ? "checked" : "" ?>>
            <?php echo esc_html__( 'Weekly', 'yay-smtp' ) ?>
          </label>
          <label class="radio-setting">
            <input type="radio" id="yaysmtp_addition_setts_report_monthly" name="yaysmtp_addition_setts_mail_report" value="monthly" <?php echo $yaysmtp_mail_report_type == "monthly" ? "checked" : "" ?>>
            <?php echo esc_html__( 'Monthly', 'yay-smtp' ) ?>
          </label>
        </div>
      </div>
      <div class="setting-mail-fallback setting-el">
        <div class="setting-label">
          <label for="yaysmtp_setting_mail_fallback"><?php echo esc_html__( 'Fallback Carrier', 'yay-smtp' ) ?></label>
        </div>
        <div class="yaysmtp-setting-mail-fallback-wrap">
          <div class="mail-fallback-title"><input id="yaysmtp_setting_mail_fallback" class="yaysmtp-setting-mail-fallback" type="checkbox" <?php echo $has_mail_fallback == "yes" ? "checked" : "" ?>/></div>
          <div>
            <label for="yaysmtp_setting_mail_fallback">
              <?php echo esc_html__( 'Configure a secondary email service provider to send WordPress emails. Automatically used after the first mailer has 3 failed attempts.', 'yay-smtp' ) ?>
            </label>
          </div>
        </div>

        <div class="yaysmtp-fallback-setting-detail-wrap" style="display: <?php echo $has_mail_fallback == 'yes' ? 'flex' : 'none' ?>">
          <div class="yaysmtp-fallback-setting-opt-wrap">
            <div class="yay-smtp-card-header yaysmtp-fallback-setting-detail-header">
              <?php echo esc_html__( 'Fallback PHPMailer Settings', 'yay-smtp' ) ?>
            </div>
            <div class="yay-smtp-card-body">
              <div class="setting-from-email">
                <div class="setting-label">
                  <label for="yaysmtp_fallback_from_email">From Email (fallback)</label>
                </div>
                <div>
                  <input type="text" id="yaysmtp_fallback_from_email" value="<?php echo Utils::getCurrentFromEmailFallback(); ?>" />
                  <p class="error-message-email" style="display:none"></p>
                  <p class="setting-description">
                    The email displayed in the "From" field.
                  </p>
                  <div>
                    <input
                      id="yaysmtp_fallback_force_from_email"
                      type="checkbox"
                      <?php echo $fallback_force_from_email == "yes" ? "checked" : "" ?>
                    />
                    <label for="yaysmtp_fallback_force_from_email">Force From Email (fallback)</label>
                    <div class="yay-tooltip icon-tootip-wrap">
                      <span class="icon-inst-tootip"></span>
                      <span class="yay-tooltiptext yay-tooltip-bottom"><?php echo __('Always send emails with the above From Email address, overriding other plugins settings.', 'yay-smtp'); ?></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="setting-from-name">
                <div class="setting-label">
                  <label for="yaysmtp_fallback_from_name">From Name (fallback)</label>
                </div>
                <div>
                  <input type="text" id="yaysmtp_fallback_from_name" value="<?php echo Utils::getCurrentFromNameFallback(); ?>"/>
                  <p class="setting-description">
                    The name displayed in emails
                  </p>
                  <div>
                    <input
                      id="yaysmtp_fallback_force_from_name"
                      type="checkbox"
                      <?php echo $fallback_force_from_name == "yes" ? "checked" : "" ?>
                    />
                    <label for="yaysmtp_fallback_force_from_name">Force From Name (fallback)</label>
                    <div class="yay-tooltip icon-tootip-wrap">
                      <span class="icon-inst-tootip"></span>
                      <span class="yay-tooltiptext yay-tooltip-bottom"><?php echo __('Always send emails with the above From Name, overriding other plugins settings.', 'yay-smtp'); ?></span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="setting-el">
                <div class="setting-label">
                  <label for="yaysmtp_fallback_host">SMTP Host (fallback)</label>
                </div>
                <div>
                  <input type="text" id="yaysmtp_fallback_host" value="<?php echo $fallback_host; ?>">
                </div>
              </div>
              <div class="setting-el">
                <div class="setting-label">
                  <label for="yaysmtp_fallback_encryption_tls">Encryption Type (fallback)</label>
                </div>
                <div>
                  <label class="radio-setting">
                    <input type="radio" id="yaysmtp_fallback_encryption_ssl" name="yaysmtp-fallback-encryption" value="ssl" <?php echo $fallback_encryption == "ssl" ? "checked" : "" ?>>
                    SSL
                  </label>
                  <label class="radio-setting">
                    <input type="radio" id="yaysmtp_fallback_encryption_tls" name="yaysmtp-fallback-encryption" value="tls" <?php echo $fallback_encryption == "tls" ? "checked" : "" ?>>
                    TLS
                  </label>
                  <p class="setting-description">
                    TLS is the recommended option if your SMTP provider supports it.
                  </p>
                </div>
              </div>
              <div class="setting-el">
                <div class="setting-label">
                  <label for="yaysmtp_fallback_port">SMTP Port (fallback)</label>
                </div>
                <div>
                  <input type="number" id="yaysmtp_fallback_port" value="<?php echo $fallback_port; ?>">
                  <p class="setting-description">
                    Port of your mail server. Usually is 25, 465, 587
                  </p>
                </div>
              </div>
              <div class="setting-el">
                <div class="setting-label">
                  <label for="yaysmtp_fallback_auth">SMTP Authentication (fallback)</label>
                </div>
                <div>
                  <label class="switch">
                    <input type="checkbox" id="yaysmtp_fallback_auth" <?php echo $fallback_auth == "yes" ? "checked" : "" ?>>
                    <span class="slider round"></span>
                  </label>
                  <label class="toggle-label">
                    <span class="setting-toggle-fallback-checked">ON</span>
                    <span class="setting-toggle-fallback-unchecked">OFF</span>
                  </label>
                </div>
              </div>
              <div class="yaysmtp_fallback_auth_det" style="display: <?php echo $fallback_auth == 'yes' ? 'block' : 'none' ?>">
                <div class="setting-el">
                  <div class="setting-label">
                    <label for="yaysmtp_fallback_smtp_user">SMTP Username (fallback)</label>
                  </div>
                  <div>
                    <input type="text" id="yaysmtp_fallback_smtp_user" value="<?php echo $fallback_user; ?>">
                  </div>
                </div>
                <div class="setting-el">
                  <div class="setting-label">
                    <label for="yaysmtp_fallback_smtp_pass">SMTP Password (fallback)</label>
                  </div>
                  <div>
                    <input type="password" spellcheck="false" id="yaysmtp_fallback_smtp_pass" value="<?php echo $fallback_pass; ?>">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="yaysmtp-fallback-send-test-mail-wrap">
            <div class="yay-smtp-card-header yaysmtp-fallback-setting-detail-header">
              <?php echo esc_html__( 'Test fallback email', 'yay-smtp' ) ?>
            </div>
            <div class="yay-smtp-card-body">
              <div class="setting-label">
                <label for="yaysmtp_fallback_test_mail_address"><?php echo esc_html__( 'To Email', 'yay-smtp' ) ?></label>
              </div>
              <div class="setting-field">
                <input type="text" id="yaysmtp_fallback_test_mail_address" class="yaysmtp-fallback-test-mail-address" value=<?php echo Utils::getAdminEmail() ?>>
                <div class="error-message-email" style="display:none"></div>
              </div>
              <div>
                <p class="setting-description">
                  <?php echo esc_html__( 'Before sending test email, please make sure to set up fallback properly and save changes.', 'yay-smtp' ) ?>
                </p>
                <button type="button" class="yaysmtp-fallback-send-mail-action" <?php echo !Utils::isFullSettingsFallbackSmtp() ? "disabled" : "" ?>>Send Email</button>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>
  <div>
    <button type="button" class="yay-smtp-button yaysmtp-additional-settings-btn"><?php echo esc_html__( 'Save Changes', 'yay-smtp' ) ?></button>
  </div>
</div>





