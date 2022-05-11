<?php
if (!defined('ABSPATH')) {
  exit;
}

$srcTotalIcon = YAY_SMTP_PLUGIN_URL . 'assets/img/mail-icon.png';
$srcSentIcon = YAY_SMTP_PLUGIN_URL . 'assets/img/sent-icon.png';
$srcFailedIcon = YAY_SMTP_PLUGIN_URL . 'assets/img/failed-icon.png';
?>
<div class="yaysmtp-analytics-email-wrap">
  <div class="filter-wrap">
    <div class="dashicons filter-icon"></div>
    <input id="yaysmtp_daterangepicker" type="text" value=""/>
  </div>
  <div class="yaysmtp-chart-sumary">
    <div class="total-mail-wrap">
      <div class="total-mail-icon">
        <img src="<?php echo $srcTotalIcon ?>" height="19" width="26">
      </div>
      <div class="total-mail">0</div>total
    </div>
    <div class="sent-mail-wrap">
      <div class="sent-mail-icon">
        <img src="<?php echo $srcSentIcon ?>" height="19" width="19">
      </div>
      <div class="sent-mail">0</div>sent
    </div>
    <div class="failed-mail-wrap">
      <div class="failed-mail-icon">
        <img src="<?php echo $srcFailedIcon ?>" height="19" width="19">
      </div>
      <div class="failed-mail">0</div>failed
    </div>
  </div>
  <div class="yaysmtp-chart-wrap"><canvas id="yaysmtpCharts" width="400" height="400"></canvas></div>
  <div class="top-mail-table-wrap">
    <div class="top-mail-title">
      <h3>Top Emails</h3>
    </div>
    <div class="top-mail-table">
      <table>
        <thead>
          <tr>
            <th class="table-header" > 
              Subject
            </th>
            <th class="table-header" > 
              Total
            </th>
            <th class="table-header" > 
              Sent
            </th>
            <th class="table-header" > 
              Failed
            </th>
          </tr>
        </thead>
        <tbody class="top-mail-body">
        </tbody>
      </table>
    </div>
    <div class="top-mail-table-empty">
      <p>No data</p>
    </div>
    
  </div>
</div>
