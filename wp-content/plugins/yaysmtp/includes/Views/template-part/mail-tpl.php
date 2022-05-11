<?php
if (!defined('ABSPATH')) {
  exit;
}
$mailer = "mail";
?>
<div class="yay-smtp-card yay-smtp-mailer-settings" data-mailer="<?php echo $mailer ?>" style="display: <?php echo $currentMailer == 'mail' ? 'block' : 'none' ?>">
  <div class="yay-smtp-card-header">
    <div class="yay-smtp-card-title-wrapper">
      <h3 class="yay-smtp-card-title yay-smtp-card-header-item">Default</h3>
      <h3 class="yay-smtp-card-description yay-smtp-card-header-item">
      Please make sure your server have enabled PHP Mail() Function.
      </h3>
    </div>
  </div>
</div>
