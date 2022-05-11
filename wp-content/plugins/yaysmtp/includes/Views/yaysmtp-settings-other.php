<?php
use YaySMTP\Helper\Utils;

if (!defined('ABSPATH')) {
  exit;
}

$yaysmtpImportPlugins = Utils::getYaysmtpImportPlugins();

$yaySmtpImportTitle = __( 'We have found previous SMTP settings from other plugin on your site. Please choose one plugin\'s settings that you want to import to YaySMTP.', 'yay-smtp' );
if(empty($yaysmtpImportPlugins)){
  $yaySmtpImportTitle = __( 'We have found no previous SMTP settings from other plugins on your site.', 'yay-smtp' );
}
?>

<div class="yay-smtp-wrap yaysmtp-settings-wrap">
  <div class="yay-smtp-card">
    <div class="yay-smtp-card-header">
      <div class="yay-smtp-card-title-wrapper">
        <h3 class="yay-smtp-card-title yay-smtp-card-header-item">
          <?php echo esc_html__( 'Import SMTP settings to YaySMTP', 'yay-smtp' ) ?>
        </h3>
        <h3 class="yay-smtp-card-description yay-smtp-card-header-item">
          <?php echo $yaySmtpImportTitle; ?>
        </h3>
      </div>
    </div>
    <div class="yay-smtp-card-body">
      <input type="hidden" class="yaysmtp-import-plugin-choose">
      <?php if (!empty($yaysmtpImportPlugins)) { 
          foreach ($yaysmtpImportPlugins as $plugin) { ?>
            <div class="yay-smtper-plugin" data-plugin="<?php echo $plugin['val']; ?>">
              <div class="icon-smtp"><img src="<?php echo YAY_SMTP_PLUGIN_URL . 'assets/img/' . $plugin['img'] ?>" height="25" width="25"></div>
              <div class="title-smtp"><span><?php echo $plugin['title']; ?><span></div>
            </div>
      <?php } 
      }
      ?>
    </div>
  </div>
  <div>
    <?php if(!empty($yaysmtpImportPlugins)) { ?>
    <button type="button" class="yay-smtp-button yaysmtp-import-settings-btn"><?php echo esc_html__( 'Import Settings', 'yay-smtp' ) ?></button>
    <?php } ?>
  </div>
</div>





