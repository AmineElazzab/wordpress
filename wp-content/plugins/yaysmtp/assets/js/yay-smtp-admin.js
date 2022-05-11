(function($) {
  $(document).ready(function() {
    // Load Email Logs List
    let yaySMTPPage = yaySMTPGetParam("page");
    let yaySMTPEmailLogTab = "email-logs"; //yaySMTPGetParam("tab");

    // Catch Pagination per page event
    $(".yay-smtp-wrap.mail-logs .pag-per-page-sel").change(function() {
      if (yaySMTPPage == "yaysmtp" && yaySMTPEmailLogTab == "email-logs") {
        let param = searchConditionBasicCurrent();
        param.sortField = yay_smtp_sort_field;
        param.sortVal = yay_smtp_sort_val;
        yaySMTPEmailLogsList(param);
      }
    });

    // Catch current page event
    $(".yay-smtp-wrap.mail-logs .pag-page-current").change(function() {
      if (yaySMTPPage == "yaysmtp" && yaySMTPEmailLogTab == "email-logs") {
        let param = searchConditionBasicCurrent();
        param.sortField = yay_smtp_sort_field;
        param.sortVal = yay_smtp_sort_val;
        yaySMTPEmailLogsList(param);
      }
    });

    // Catch previous page event
    $(".yay-smtp-wrap.mail-logs .pagination-link.previous-btn").click(
      function() {
        if (yaySMTPPage == "yaysmtp" && yaySMTPEmailLogTab == "email-logs") {
          let limit = $(".yay-smtp-wrap.mail-logs .pag-per-page-sel").val();
          let page = $(".yay-smtp-wrap.mail-logs .pag-page-current").val();
          let valSearch = $(
            ".yay-smtp-wrap.mail-logs .yay-button-wrap .search .search-imput"
          ).val();
          let status;
          if ($("#yaysmtp_logs_status_not_send").is(":checked")) {
            if ($("#yaysmtp_logs_status_sent").is(":checked")) {
              status = "all";
            } else {
              status = "not_send";
            }
          } else {
            if ($("#yaysmtp_logs_status_sent").is(":checked")) {
              status = "sent";
            } else {
              status = "empty";
            }
          }
          let param = {
            page: parseInt(page) - 1,
            limit: parseInt(limit),
            valSearch: valSearch,
            status: status
          };
          param.sortField = yay_smtp_sort_field;
          param.sortVal = yay_smtp_sort_val;
          yaySMTPEmailLogsList(param);
        }
      }
    );

    // Catch next page event
    $(".yay-smtp-wrap.mail-logs .pagination-link.next-btn").click(function() {
      if (yaySMTPPage == "yaysmtp" && yaySMTPEmailLogTab == "email-logs") {
        let limit = $(".yay-smtp-wrap.mail-logs .pag-per-page-sel").val();
        let page = $(".yay-smtp-wrap.mail-logs .pag-page-current").val();
        let valSearch = $(
          ".yay-smtp-wrap.mail-logs .yay-button-wrap .search .search-imput"
        ).val();
        let status;
        if ($("#yaysmtp_logs_status_not_send").is(":checked")) {
          if ($("#yaysmtp_logs_status_sent").is(":checked")) {
            status = "all";
          } else {
            status = "not_send";
          }
        } else {
          if ($("#yaysmtp_logs_status_sent").is(":checked")) {
            status = "sent";
          } else {
            status = "empty";
          }
        }
        let param = {
          page: parseInt(page) + 1,
          limit: parseInt(limit),
          valSearch: valSearch,
          status: status
        };
        param.sortField = yay_smtp_sort_field;
        param.sortVal = yay_smtp_sort_val;
        yaySMTPEmailLogsList(param);
      }
    });

    // Catch search even
    var yaysmtpTimeout = null;
    $(".yay-smtp-wrap.mail-logs .yay-button-wrap .search .search-imput").keyup(
      function(e) {
        clearTimeout(yaysmtpTimeout);
        yaysmtpTimeout = setTimeout(function() {
          if (yaySMTPPage == "yaysmtp" && yaySMTPEmailLogTab == "email-logs") {
            let param = searchConditionBasicCurrent();
            param.sortField = yay_smtp_sort_field;
            param.sortVal = yay_smtp_sort_val;
            yaySMTPEmailLogsList(param);
          }
        }, 1000);
      }
    );

    // Catch components-dropdown-button even
    $(".yay-smtp-wrap.mail-logs .components-dropdown-button").click(function() {
      if ($(this).hasClass("is-opened")) {
        $(this).removeClass("is-opened");
        $(
          ".yay-smtp-wrap.mail-logs .components-popover.components-dropdown__content"
        ).removeClass("is-opened");
      } else {
        $(this).addClass("is-opened");
        $(
          ".yay-smtp-wrap.mail-logs .components-popover.components-dropdown__content"
        ).addClass("is-opened");
      }
    });

    // Catch yaysmtp_logs_subject_control column
    $("#yaysmtp_logs_subject_control").click(function() {
      let show_subj_cl;
      if ($(this).is(":checked")) {
        $(".yay-smtp-wrap.mail-logs .yay-smtp-content .subject-col").show();
        show_subj_cl = 1;
      } else {
        $(".yay-smtp-wrap.mail-logs .yay-smtp-content .subject-col").hide();
        show_subj_cl = 0;
      }

      //Update DB
      $.ajax({
        url: yaySmtpWpData.YAY_ADMIN_AJAX,
        type: "POST",
        // async: false,
        data: {
          action: "yaysmtp_set_email_logs_setting",
          nonce: yaySmtpWpData.ajaxNonce,
          params: { show_subject_cl: show_subj_cl }
        },
        beforeSend: function() {},
        success: function(result) {}
      });
    });

    // Catch yaysmtp_logs_to_control column
    $("#yaysmtp_logs_to_control").click(function() {
      let show_to_cl;
      if ($(this).is(":checked")) {
        $(".yay-smtp-wrap.mail-logs .yay-smtp-content .to-col").show();
        show_to_cl = 1;
      } else {
        $(".yay-smtp-wrap.mail-logs .yay-smtp-content .to-col").hide();
        show_to_cl = 0;
      }
      //Update DB
      $.ajax({
        url: yaySmtpWpData.YAY_ADMIN_AJAX,
        type: "POST",
        // async: false,
        data: {
          action: "yaysmtp_set_email_logs_setting",
          nonce: yaySmtpWpData.ajaxNonce,
          params: { show_to_cl: show_to_cl }
        },
        beforeSend: function() {},
        success: function(result) {}
      });
    });

    // Catch yaysmtp_logs_status_control column
    $("#yaysmtp_logs_status_control").click(function() {
      let show_status_cl;
      if ($(this).is(":checked")) {
        $(".yay-smtp-wrap.mail-logs .yay-smtp-content .status-col").show();
        show_status_cl = 1;
      } else {
        $(".yay-smtp-wrap.mail-logs .yay-smtp-content .status-col").hide();
        show_status_cl = 0;
      }
      //Update DB
      $.ajax({
        url: yaySmtpWpData.YAY_ADMIN_AJAX,
        type: "POST",
        // async: false,
        data: {
          action: "yaysmtp_set_email_logs_setting",
          nonce: yaySmtpWpData.ajaxNonce,
          params: { show_status_cl: show_status_cl }
        },
        beforeSend: function() {},
        success: function(result) {}
      });
    });

    // Catch yaysmtp_logs_datetime_control column
    $("#yaysmtp_logs_datetime_control").click(function() {
      let show_datetime_cl;
      if ($(this).is(":checked")) {
        $(".yay-smtp-wrap.mail-logs .yay-smtp-content .datetime-col").show();
        show_datetime_cl = 1;
      } else {
        $(".yay-smtp-wrap.mail-logs .yay-smtp-content .datetime-col").hide();
        show_datetime_cl = 0;
      }
      //Update DB
      $.ajax({
        url: yaySmtpWpData.YAY_ADMIN_AJAX,
        type: "POST",
        // async: false,
        data: {
          action: "yaysmtp_set_email_logs_setting",
          nonce: yaySmtpWpData.ajaxNonce,
          params: { show_datetime_cl: show_datetime_cl }
        },
        beforeSend: function() {},
        success: function(result) {}
      });
    });

    // Catch yaysmtp_logs_action_control column
    $("#yaysmtp_logs_action_control").click(function() {
      let show_action_cl;
      if ($(this).is(":checked")) {
        $(".yay-smtp-wrap.mail-logs .yay-smtp-content .action-col").show();
        show_action_cl = 1;
      } else {
        $(".yay-smtp-wrap.mail-logs .yay-smtp-content .action-col").hide();
        show_action_cl = 0;
      }
      //Update DB
      $.ajax({
        url: yaySmtpWpData.YAY_ADMIN_AJAX,
        type: "POST",
        // async: false,
        data: {
          action: "yaysmtp_set_email_logs_setting",
          nonce: yaySmtpWpData.ajaxNonce,
          params: { show_action_cl: show_action_cl }
        },
        beforeSend: function() {},
        success: function(result) {}
      });
    });

    // Catch sent control
    $("#yaysmtp_logs_status_sent").click(function() {
      let status;
      if ($(this).is(":checked")) {
        if ($("#yaysmtp_logs_status_not_send").is(":checked")) {
          status = "all";
        } else {
          status = "sent";
        }
      } else {
        if ($("#yaysmtp_logs_status_not_send").is(":checked")) {
          status = "not_send";
        } else {
          status = "empty";
        }
      }

      //Update DB
      $.ajax({
        url: yaySmtpWpData.YAY_ADMIN_AJAX,
        type: "POST",
        // async: false,
        data: {
          action: "yaysmtp_set_email_logs_setting",
          nonce: yaySmtpWpData.ajaxNonce,
          params: { status: status }
        },
        beforeSend: function() {},
        success: function(result) {}
      });

      let limit = $(".yay-smtp-wrap.mail-logs .pag-per-page-sel").val();
      let page = $(".yay-smtp-wrap.mail-logs .pag-page-current").val();
      let valSearch = $(
        ".yay-smtp-wrap.mail-logs .yay-button-wrap .search .search-imput"
      ).val();
      let param = {
        page: parseInt(page) - 1,
        limit: parseInt(limit),
        valSearch: valSearch,
        status: status
      };
      param.sortField = yay_smtp_sort_field;
      param.sortVal = yay_smtp_sort_val;
      yaySMTPEmailLogsList(param);
    });

    // Catch not send control
    $("#yaysmtp_logs_status_not_send").click(function() {
      let status;
      if ($(this).is(":checked")) {
        if ($("#yaysmtp_logs_status_sent").is(":checked")) {
          status = "all";
        } else {
          status = "not_send";
        }
      } else {
        if ($("#yaysmtp_logs_status_sent").is(":checked")) {
          status = "sent";
        } else {
          status = "empty";
        }
      }

      //Update DB
      $.ajax({
        url: yaySmtpWpData.YAY_ADMIN_AJAX,
        type: "POST",
        // async: false,
        data: {
          action: "yaysmtp_set_email_logs_setting",
          nonce: yaySmtpWpData.ajaxNonce,
          params: { status: status }
        },
        beforeSend: function() {},
        success: function(result) {}
      });

      let limit = $(".yay-smtp-wrap.mail-logs .pag-per-page-sel").val();
      let page = $(".yay-smtp-wrap.mail-logs .pag-page-current").val();
      let valSearch = $(
        ".yay-smtp-wrap.mail-logs .yay-button-wrap .search .search-imput"
      ).val();
      let param = {
        page: parseInt(page),
        limit: parseInt(limit),
        valSearch: valSearch,
        status: status
      };
      param.sortField = yay_smtp_sort_field;
      param.sortVal = yay_smtp_sort_val;
      yaySMTPEmailLogsList(param);
    });

    // Sorting - start
    var yay_smtp_sort_field = "";
    var yay_smtp_sort_val = "";
    $(".yay-smtp-content thead th.is-sortable").click(function() {
      let sortField = $(this).attr("data-sort-col");
      let sortVal = $(this).attr("data-sort");
      let sortValReal = "descending";
      if (sortVal == "descending") {
        sortValReal = "ascending";
        $(".yay-smtp-content .table-header button svg path").attr(
          "d",
          "M6.5 12.4L12 8l5.5 4.4-.9 1.2L12 10l-4.5 3.6-1-1.2z"
        );
      } else {
        $(".yay-smtp-content .table-header button svg path").attr(
          "d",
          "M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"
        );
      }
      $(this).attr("data-sort", sortValReal);

      // Update global val for yay_smtp_sort_field, yay_smtp_sort_val
      yay_smtp_sort_field = sortField;
      yay_smtp_sort_val = sortValReal;

      let otherCol = $(".yay-smtp-content thead th.is-sortable").not(this);
      $.each(otherCol, function() {
        $(this).attr("data-sort", "none");
      });

      let param = searchConditionBasicCurrent();
      param.sortField = yay_smtp_sort_field;
      param.sortVal = yay_smtp_sort_val;

      yaySMTPEmailLogsList(param);
    });
    // Sorting -end

    // Delete item action
    $(".yay-smtp-wrap.mail-logs").on(
      "click",
      ".yaysmtp-delete-btn",
      function() {
        if (confirm("Are you sure to delete this item?")) {
          let idMailLog = $(this).attr("data-id");
          $.ajax({
            url: yaySmtpWpData.YAY_ADMIN_AJAX,
            type: "POST",
            data: {
              action: "yaysmtp_delete_email_logs",
              nonce: yaySmtpWpData.ajaxNonce,
              params: {
                ids: idMailLog
              }
            },
            beforeSend: function() {
              yaySMTPspinner("yay-smtp-wrap", true);
            },
            success: function(result) {
              if (result.success) {
                let param = searchConditionBasicCurrent();
                param.sortField = yay_smtp_sort_field;
                param.sortVal = yay_smtp_sort_val;
                yaySMTPEmailLogsList(param);
              }
              yaySMTPNotification(result.data.mess, "yay-smtp-wrap");
              yaySMTPspinner("yay-smtp-wrap", false);
            }
          });
        }
      }
    );

    // Delete item selected action
    $(".yay-smtp-wrap.mail-logs").on(
      "click",
      ".bulk-action-control .delete-selected-button",
      function() {
        let idMailLogs = [];
        let mailLogCheckeds = $(".yay-smtp-wrap.mail-logs").find(
          ".checkbox-control-input-el:checked"
        );

        mailLogCheckeds.each(function() {
          let id = $(this).val();
          idMailLogs.push(id);
        });

        if (idMailLogs.length > 0) {
          $.ajax({
            url: yaySmtpWpData.YAY_ADMIN_AJAX,
            type: "POST",
            data: {
              action: "yaysmtp_delete_email_logs",
              nonce: yaySmtpWpData.ajaxNonce,
              params: {
                ids: idMailLogs.join(",")
              }
            },
            beforeSend: function() {
              yaySMTPspinner("yay-smtp-wrap", true);
            },
            success: function(result) {
              if (result.success) {
                let param = searchConditionBasicCurrent();
                param.sortField = yay_smtp_sort_field;
                param.sortVal = yay_smtp_sort_val;
                yaySMTPEmailLogsList(param);
              }
              yaySMTPNotification(result.data.mess, "yay-smtp-wrap");
              yaySMTPspinner("yay-smtp-wrap", false);
            }
          });
        }
      }
    );

    //Catch input-check-all event
    $(".yay-smtp-wrap.mail-logs #input-check-all").click(function() {
      if ($(this).is(":checked")) {
        $(".yay-smtp-wrap.mail-logs .checkbox-control-input-el").prop(
          "checked",
          true
        );
        $(".yay-smtp-wrap.mail-logs .delete-selected-button").prop(
          "disabled",
          false
        );
      } else {
        $(".yay-smtp-wrap.mail-logs .checkbox-control-input-el").prop(
          "checked",
          false
        );
        $(".yay-smtp-wrap.mail-logs .delete-selected-button").prop(
          "disabled",
          true
        );
      }
    });

    // Disable/Enable "Selected delete" button on ready page
    if (
      $(".yay-smtp-wrap.mail-logs").find(".checkbox-control-input-el:checked")
        .length > 0
    ) {
      $(".yay-smtp-wrap.mail-logs .delete-selected-button").prop(
        "disabled",
        false
      );
    } else {
      $(".yay-smtp-wrap.mail-logs .delete-selected-button").prop(
        "disabled",
        true
      );
    }

    //Catch checkbox-control-input-el element event
    $(".yay-smtp-wrap.mail-logs").on(
      "click",
      ".checkbox-control-input-el",
      function() {
        let mailLogsLength = $(
          ".yay-smtp-wrap.mail-logs .checkbox-control-input-el"
        ).length;
        let mailLogsCheckedLength = $(".yay-smtp-wrap.mail-logs").find(
          ".checkbox-control-input-el:checked"
        ).length;
        if (mailLogsCheckedLength > 0) {
          $(".yay-smtp-wrap.mail-logs .delete-selected-button").prop(
            "disabled",
            false
          );
        } else {
          $(".yay-smtp-wrap.mail-logs .delete-selected-button").prop(
            "disabled",
            true
          );
        }

        if ($(this).is(":checked")) {
          if (mailLogsCheckedLength == mailLogsLength) {
            $(".yay-smtp-wrap.mail-logs #input-check-all").prop(
              "checked",
              true
            );
          } else {
            $(".yay-smtp-wrap.mail-logs #input-check-all").prop(
              "checked",
              false
            );
          }
        } else {
          $(".yay-smtp-wrap.mail-logs #input-check-all").prop("checked", false);
        }
      }
    );

    // View Mail log icon click
    $(".yay-smtp-wrap.mail-logs").on(
      "click",
      ".yaysmtp-view-btn, td.subject-col a",
      function(e) {
        e.preventDefault();

        // Clean "is-active" class that no "this" elment.
        let otherCol = $(".yay-smtp-wrap.mail-logs")
          .find(".yaysmtp-view-btn, td.subject-col a")
          .not(this);
        $.each(otherCol, function() {
          $(this).removeClass("is-active");
        });

        if ($(this).hasClass("is-active")) {
          // Close drawer
          $(".yay-smtp-wrap.mail-logs")
            .find(".yay-smtp-mail-detail-drawer")
            .css("width", "0");
          $(".yay-smtp-wrap.mail-logs")
            .find(".yay-smtp-mail-detail-drawer")
            .removeClass("is-open");
          $(this).removeClass("is-active");
        } else {
          // Open drawer
          /* Set the width of the side navigation to 35% */
          $(".yay-smtp-wrap.mail-logs")
            .find(".yay-smtp-mail-detail-drawer")
            .css("width", "65%");
          $(".yay-smtp-wrap.mail-logs")
            .find(".yay-smtp-mail-detail-drawer")
            .addClass("is-open");
          $(this).addClass("is-active");

          // Load email log detail
          let idEmailLog = $(this).attr("data-id");
          $.ajax({
            url: yaySmtpWpData.YAY_ADMIN_AJAX,
            type: "POST",
            data: {
              action: "yaysmtp_detail_email_logs",
              nonce: yaySmtpWpData.ajaxNonce,
              params: {
                id: idEmailLog
              }
            },
            beforeSend: function() {
              yaySMTPspinner("yay-smtp-mail-detail-drawer", true);
            },
            success: function(result) {
              if (result.success) {
                let data = result.data.data;
                let status = "Success";
                let status_cl = "email-success";
                if (parseInt(data.status) == 0) {
                  status = "Fail";
                  status_cl = "email-fail";
                } else if (parseInt(data.status) == 2) {
                  status = "Waiting";
                  status_cl = "email-waiting";
                }

                let emailTo = data.email_to;
                $(".yay-smtp-wrap.mail-logs")
                  .find(
                    ".yay-smtp-mail-detail-drawer .yay-smtp-activity-panel-header-title"
                  )
                  .html("Email log #" + data.id);

                $(".yay-smtp-wrap.mail-logs")
                  .find(".yay-smtp-mail-detail-drawer .datetime-el .content")
                  .html(data.date_time);

                $(".yay-smtp-wrap.mail-logs")
                  .find(".yay-smtp-mail-detail-drawer .from-el .content")
                  .html(data.email_from);

                $(".yay-smtp-wrap.mail-logs")
                  .find(".yay-smtp-mail-detail-drawer .to-el .content")
                  .html(emailTo.toString());

                $(".yay-smtp-wrap.mail-logs")
                  .find(".yay-smtp-mail-detail-drawer .subject-el .content")
                  .html(data.subject);

                $(".yay-smtp-wrap.mail-logs")
                  .find(".yay-smtp-mail-detail-drawer .mailer-el .content")
                  .html(data.mailer);

                $(".yay-smtp-wrap.mail-logs")
                  .find(".yay-smtp-mail-detail-drawer .status-el .content")
                  .html(status);

                if (typeof data.reason_error !== "undefined") {
                  $(".yay-smtp-wrap.mail-logs")
                    .find(
                      ".yay-smtp-mail-detail-drawer .status-el .reason_error"
                    )
                    .html(data.reason_error);
                } else {
                  $(".yay-smtp-wrap.mail-logs")
                    .find(
                      ".yay-smtp-mail-detail-drawer .status-el .reason_error"
                    )
                    .html("");
                }

                if (typeof data.body_content !== "undefined") {
                  $(".yay-smtp-wrap.mail-logs")
                    .find(".yay-smtp-mail-detail-drawer .mail-body-el")
                    .show();
                  $(".yay-smtp-wrap.mail-logs")
                    .find(
                      ".yay-smtp-mail-detail-drawer .mail-body-content-detail"
                    )
                    .html(data.body_content);
                } else {
                  $(".yay-smtp-wrap.mail-logs")
                    .find(".yay-smtp-mail-detail-drawer .mail-body-el")
                    .hide();
                }

                $(".yay-smtp-wrap.mail-logs")
                  .find(".yay-smtp-mail-detail-drawer .status-el mark")
                  .removeClass();
                $(".yay-smtp-wrap.mail-logs")
                  .find(".yay-smtp-mail-detail-drawer .status-el mark")
                  .addClass("email-status")
                  .addClass(status_cl);
              }
              yaySMTPspinner("yay-smtp-mail-detail-drawer", false);
            }
          });
        }
      }
    );

    // Close Mail log panel
    $(".yay-smtp-mail-detail-drawer .closebtn").click(function() {
      $(".yay-smtp-wrap.mail-logs")
        .find(".yay-smtp-mail-detail-drawer")
        .css("width", "0");
      $(".yay-smtp-wrap.mail-logs")
        .find(".yay-smtp-mail-detail-drawer")
        .removeClass("is-open");
      $(".yay-smtp-wrap.mail-logs .yaysmtp-view-btn").removeClass("is-active");
    });

    // Mail log settigns drawer
    $(".yay-smtp-button.yaysmtp-email-log-settings").click(function(e) {
      e.preventDefault();
      if ($(this).hasClass("is-active")) {
        // Close drawer
        $(".yay-smtp-wrap.mail-logs")
          .find(".yay-smtp-mail-log-settings-drawer")
          .css("width", "0");
        $(".yay-smtp-wrap.mail-logs")
          .find(".yay-smtp-mail-log-settings-drawer")
          .removeClass("is-open");
        $(this).removeClass("is-active");
      } else {
        // Open drawer
        /* Set the width of the side navigation to 35% */
        $(".yay-smtp-wrap.mail-logs")
          .find(".yay-smtp-mail-log-settings-drawer")
          .css("width", "30%");
        $(".yay-smtp-wrap.mail-logs")
          .find(".yay-smtp-mail-log-settings-drawer")
          .addClass("is-open");
        $(this).addClass("is-active");
      }
    });

    // Close Mail log panel
    $(".yay-smtp-mail-log-settings-drawer .closebtn").click(function() {
      $(".yay-smtp-wrap.mail-logs")
        .find(".yay-smtp-mail-log-settings-drawer")
        .css("width", "0");
      $(".yay-smtp-wrap.mail-logs")
        .find(".yay-smtp-mail-log-settings-drawer")
        .removeClass("is-open");

      $(".yay-smtp-button.yaysmtp-email-log-settings").removeClass("is-active");
    });

    // Save Email logs setting
    $(".yay-smtp-email-log-settings-save-action").click(function() {
      let logSettings = {};
      if ($("#yay_smtp_mail_log_setting_save").is(":checked")) {
        logSettings["save_email_log"] = "yes";
      } else {
        logSettings["save_email_log"] = "no";
      }

      logSettings["email_log_inf_type"] = $(
        '.yay-smtp-wrap.mail-logs input[name="information_type"]:checked'
      ).val();

      logSettings["email_log_delete_time"] = parseInt(
        $(".yay-smtp-email-log-setting-delete-time").val()
      );

      $.ajax({
        url: yaySmtpWpData.YAY_ADMIN_AJAX,
        type: "POST",
        data: {
          action: "yaysmtp_set_email_logs_setting",
          nonce: yaySmtpWpData.ajaxNonce,
          params: logSettings
        },
        beforeSend: function() {
          yaySMTPspinner("yay-smtp-wrap", true);
        },
        success: function(result) {
          yaySMTPNotification(result.data.mess, "yay-smtp-wrap");
          yaySMTPspinner("yay-smtp-wrap", false);
        }
      });
    });

    // Current SMTP Mailer
    var currentYaySMTPMailer = yaySmtpWpData.currentMailer;

    // Panel click
    $(".send-test-mail-panel").click(function() {
      if ($(this).hasClass("is-active")) {
        // Close drawer
        $(".yay-smtp-wrap")
          .find(".yay-smtp-test-mail-drawer")
          .css("width", "0");
        $(".yay-smtp-wrap")
          .find(".yay-smtp-test-mail-drawer")
          .removeClass("is-open");
        $(this).removeClass("is-active");
      } else {
        // Open drawer
        /* Set the width of the side navigation to 35% */
        $(".yay-smtp-wrap")
          .find(".yay-smtp-test-mail-drawer")
          .css("width", "35%");
        $(".yay-smtp-wrap")
          .find(".yay-smtp-test-mail-drawer")
          .addClass("is-open");
        $(this).addClass("is-active");
      }
    });

    // Close panel
    $(".yay-smtp-test-mail-drawer .closebtn").click(function() {
      $(".yay-smtp-wrap")
        .find(".yay-smtp-test-mail-drawer")
        .css("width", "0");
      $(".yay-smtp-wrap")
        .find(".yay-smtp-test-mail-drawer")
        .removeClass("is-open");
      $(".send-test-mail-panel").removeClass("is-active");
    });

    //Close panel event when click outside the element
    $(document).mousedown(function(e) {
      // Close Test email drawer - start
      let container = $(".yay-smtp-test-mail-drawer");
      let testMailPanel = $(".send-test-mail-panel");
      let svgMail = $('svg[data-icon="mail"]');
      let iconMailText = $(".send-test-mail-panel span.text");
      // if the target of the click isn't the container nor a descendant of the container
      if (
        container.hasClass("is-open") &&
        testMailPanel.hasClass("is-active") &&
        !container.is(e.target) &&
        container.has(e.target).length === 0 &&
        !testMailPanel.is(e.target) &&
        !svgMail.is(e.target) &&
        !iconMailText.is(e.target)
      ) {
        $(".yay-smtp-wrap")
          .find(".yay-smtp-test-mail-drawer")
          .css("width", "0");
        $(".yay-smtp-wrap")
          .find(".yay-smtp-test-mail-drawer")
          .removeClass("is-open");
        $(".send-test-mail-panel").removeClass("is-active");
      }
      // Close Test email drawer - end

      // Close Email Logs Settings Popup - start
      let containerEmailSettingPopup = $(
        ".yay-smtp-wrap.mail-logs .components-popover.components-dropdown__content"
      );
      let mailLogSettingsPopupButton = $(
        ".yay-smtp-wrap.mail-logs .components-dropdown-button"
      );
      let iconMailLogSettings = $(
        ".yay-smtp-wrap.mail-logs span.dashicons-ellipsis"
      );
      // if the target of the click isn't the container nor a descendant of the container
      if (
        containerEmailSettingPopup.hasClass("is-opened") &&
        mailLogSettingsPopupButton.hasClass("is-opened") &&
        containerEmailSettingPopup.has(e.target).length === 0 &&
        !containerEmailSettingPopup.is(e.target) &&
        !mailLogSettingsPopupButton.is(e.target) &&
        !iconMailLogSettings.is(e.target)
      ) {
        mailLogSettingsPopupButton.removeClass("is-opened");
        containerEmailSettingPopup.removeClass("is-opened");
      }
      // Close Email Logs Settings Popup - end

      // Close email log detail drawer - start
      let containerEmailLogDetail = $(".yay-smtp-mail-detail-drawer");
      if (
        containerEmailLogDetail.hasClass("is-open") &&
        !containerEmailLogDetail.is(e.target) &&
        containerEmailLogDetail.has(e.target).length === 0
      ) {
        $(".yay-smtp-wrap.mail-logs")
          .find(".yay-smtp-mail-detail-drawer")
          .css("width", "0");
        $(".yay-smtp-wrap.mail-logs")
          .find(".yay-smtp-mail-detail-drawer")
          .removeClass("is-open");
        $(".yay-smtp-wrap.mail-logs .yaysmtp-view-btn").removeClass(
          "is-active"
        );
      }
      // Close email log detail drawer - end

      // Close email log settings drawer - start
      let mailLogSettingsDrawerButton = $(
        ".yay-smtp-wrap.mail-logs .yaysmtp-email-log-settings"
      );
      let containerEmailLogSettings = $(".yay-smtp-mail-log-settings-drawer");

      if (
        containerEmailLogSettings.hasClass("is-open") &&
        mailLogSettingsDrawerButton.hasClass("is-active") &&
        !containerEmailLogSettings.is(e.target) &&
        containerEmailLogSettings.has(e.target).length === 0 &&
        !mailLogSettingsDrawerButton.is(e.target)
      ) {
        $(".yay-smtp-wrap.mail-logs")
          .find(".yay-smtp-mail-log-settings-drawer")
          .css("width", "0");
        $(".yay-smtp-wrap.mail-logs")
          .find(".yay-smtp-mail-log-settings-drawer")
          .removeClass("is-open");
        $(".yay-smtp-button.yaysmtp-email-log-settings").removeClass(
          "is-active"
        );
      }
      // Close email log settings drawer - end
    });

    // Click SMTP Mailer input
    $(".yay-smtp-mailer .smtper-choose").change(function() {
      let mailer = $(this).val();

      $(".mailer-settings-component .yay-smtp-mailer-settings").hide();
      $('.yay-smtp-mailer-settings[data-mailer="' + mailer + '"]').show();

      // SHOW/HIDE Amazon SES veriry Email Sender Description.
      if (mailer == "amazonses") {
        $(".yay-amazon-ses-des").show();
        $(".yay-postmark-des").hide();
        $(".yay-sparkpost-des").hide();
        $(".yay-outlookms-des").hide();
      } else if (mailer == "postmark") {
        $(".yay-postmark-des").show();
        $(".yay-amazon-ses-des").hide();
        $(".yay-sparkpost-des").hide();
        $(".yay-outlookms-des").hide();
      } else if (mailer == "sparkpost") {
        $(".yay-sparkpost-des").show();
        $(".yay-amazon-ses-des").hide();
        $(".yay-postmark-des").hide();
        $(".yay-outlookms-des").hide();
      } else if (mailer == "outlookms") {
        $(".yay-sparkpost-des").hide();
        $(".yay-amazon-ses-des").hide();
        $(".yay-postmark-des").hide();
        $(".yay-outlookms-des").show();
      } else {
        $(".yay-amazon-ses-des").hide();
        $(".yay-postmark-des").hide();
        $(".yay-sparkpost-des").hide();
        $(".yay-outlookms-des").hide();
      }
    });

    //Validate email input when click event
    $(
      ".yay-smtp-test-mail-address, #yay_smtp_setting_mail_from_email, #yaysmtp_fallback_from_email, #yaysmtp_fallback_test_mail_address"
    ).on("input", function() {
      let elMessage = $(this).siblings(".error-message-email");
      if ($(this).val().length > 0) {
        elMessage.html("").hide();
        validateEmail($(this).val(), elMessage);
      } else {
        elMessage.html("Email Address is not empty!").show();
      }
    });

    //Init append Icon Mail error on Menu WP
    if (
      $("a.toplevel_page_yaysmtp").length > 0 &&
      yaySmtpWpData.succ_sent_mail_last == "no"
    ) {
      $("a.toplevel_page_yaysmtp .wp-menu-name").append(
        '<span class="icon-yaysmtp-sent-mail-error">!</span>'
      );
    }

    // Switch ON/OFF
    if ($(".yay-smtp-card .setting-field .switch input").is(":checked")) {
      $(".yay-smtp-card .setting-field .setting-toggle-checked").show();
    } else {
      $(".yay-smtp-card .setting-field .setting-toggle-unchecked").show();
    }

    $(".yay-smtp-card .setting-field .switch input").click(function() {
      if ($(this).is(":checked")) {
        $(".yay-smtp-card .setting-field .setting-toggle-checked").show();
        $(".yay-smtp-card .setting-field .setting-toggle-unchecked").hide();
        $(".yay-smtp-card .yay_smtp_setting_auth_det").show();
      } else {
        $(".yay-smtp-card .setting-field .setting-toggle-checked").hide();
        $(".yay-smtp-card .setting-field .setting-toggle-unchecked").show();
        $(".yay-smtp-card .yay_smtp_setting_auth_det").hide();
      }
    });

    $(".yay-smtp-wrap.mail-logs .setting-field .switch input").click(
      function() {
        if ($(this).is(":checked")) {
          $(".yay-smtp-wrap.mail-logs .setting-el-other-wrap").show();
          // $(".yay-smtp-wrap.mail-logs .yay-smtp-mail-logs-wrap").show();
        } else {
          $(".yay-smtp-wrap.mail-logs .setting-el-other-wrap").hide();
          // $(".yay-smtp-wrap.mail-logs .yay-smtp-mail-logs-wrap").hide();
        }
      }
    );

    // Switch Fallback Mail ON/OFF
    if ($("#yaysmtp_fallback_auth").is(":checked")) {
      $(".yay-smtp-card .setting-toggle-fallback-checked").show();
    } else {
      $(".yay-smtp-card .setting-toggle-fallback-unchecked").show();
    }

    $("#yaysmtp_fallback_auth").click(function() {
      if ($(this).is(":checked")) {
        $(".yay-smtp-card .setting-toggle-fallback-checked").show();
        $(".yay-smtp-card .setting-toggle-fallback-unchecked").hide();
        $(".yay-smtp-card .yaysmtp_fallback_auth_det").show();
      } else {
        $(".yay-smtp-card .setting-toggle-fallback-checked").hide();
        $(".yay-smtp-card .setting-toggle-fallback-unchecked").show();
        $(".yay-smtp-card .yaysmtp_fallback_auth_det").hide();
      }
    });

    // Show/hide mail report (weekly/monthly)
    $("#yaysmtp_addition_setts_report_cb").click(function() {
      if ($(this).is(":checked")) {
        $(".yaysmtp-addition-setts-report-detail").show();
      } else {
        $(".yaysmtp-addition-setts-report-detail").hide();
      }
    });

    if ($("#yaysmtp_addition_setts_report_cb").is(":checked")) {
      $(".yaysmtp-addition-setts-report-detail").show();
    } else {
      $(".yaysmtp-addition-setts-report-detail").hide();
    }

    // Show/hide fallback detail settings
    $("#yaysmtp_setting_mail_fallback").click(function() {
      if ($(this).is(":checked")) {
        $(".yaysmtp-fallback-setting-detail-wrap").removeAttr("style");
        if ($(window).innerWidth() <= 768) {
          $(".yaysmtp-fallback-setting-detail-wrap").css("display", "block");
        } else {
          $(".yaysmtp-fallback-setting-detail-wrap").css("display", "flex");
        }
      } else {
        $(".yaysmtp-fallback-setting-detail-wrap").hide();
      }
    });

    if ($("#yaysmtp_setting_mail_fallback").is(":checked")) {
      $(".yaysmtp-fallback-setting-detail-wrap").removeAttr("style");
      if ($(window).innerWidth() <= 768) {
        $(".yaysmtp-fallback-setting-detail-wrap").css("display", "block");
      } else {
        $(".yaysmtp-fallback-setting-detail-wrap").css("display", "flex");
      }
    } else {
      $(".yaysmtp-fallback-setting-detail-wrap").hide();
    }

    //Send Test email
    $(".yay-smtp-send-mail-action").click(function() {
      let elMessage = $(".yay-smtp-test-mail-drawer .error-message-email");
      let emailAddress = $("#yay_smtp_test_mail_address").val();
      if (!emailAddress) {
        elMessage.html("Email Address is not empty!").show();
      } else {
        elMessage.html("").hide();
        if (validateEmail(emailAddress, elMessage)) {
          $.ajax({
            url: yaySmtpWpData.YAY_ADMIN_AJAX,
            type: "POST",
            data: {
              action: "yaysmtp_send_mail",
              nonce: yaySmtpWpData.ajaxNonce,
              emailAddress: emailAddress
            },
            beforeSend: function() {
              yaySMTPspinner("yay-smtp-test-mail-drawer", true);
            },
            success: function(result) {
              if (!result.success) {
                let errorMes = result.data.mess;
                elMessage.html(errorMes).show();

                let debugText = result.data.debugText;
                let html = "<strong>Debug:</strong><br>";
                html += debugText;

                // Show error in Send test mail drawer.
                $(".yay-smtp-debug-text").html(html);
                $(".yay-smtp-debug").show();

                // Show error in main page.
                $(".yay-smtp-card-debug-text").html(debugText);
                $(".yay-smtp-card.yay-smtp-card-debug").show();

                if (
                  $("a.toplevel_page_yaysmtp").length > 0 &&
                  $(".icon-yaysmtp-sent-mail-error").length == 0
                ) {
                  $("a.toplevel_page_yaysmtp .wp-menu-name").append(
                    '<span class="icon-yaysmtp-sent-mail-error">!</span>'
                  );
                }

                yaySMTPNotification("Can not send test email", "yay-smtp-wrap");
              } else {
                elMessage.html("").hide();
                $(".yay-smtp-debug").hide();
                $(".yay-smtp-card.yay-smtp-card-debug").hide();
                $(".icon-yaysmtp-sent-mail-error").remove();

                yaySMTPNotification(result.data.mess, "yay-smtp-wrap");
              }

              yaySMTPspinner("yay-smtp-test-mail-drawer", false);
            },
            error: function(xhr, status, error) {
              console.log(xhr, status, error);
              if (yaySmtpWpData.succ_sent_mail_last == "yes") {
                $(".icon-yaysmtp-sent-mail-error").remove();
              }
            }
          });
        }
      }
    });

    //Save YaySMTP settings
    $(".yay-smtp-save-settings-action").click(function() {
      let fromEmail = $("#yay_smtp_setting_mail_from_email").val();
      let fromName = $("#yay_smtp_setting_mail_from_name").val();
      let mailerProvider = $("select.smtper-choose option:checked").val();
      let mailerSettings = {};
      let mailerSettingsEls = $(
        ".yay-smtp-mailer-settings .yay-settings:visible"
      );

      let forceFromEmail =
        $("#yay_smtp_setting_mail_force_from_email").prop("checked") == true
          ? 1
          : 0;
      let forceFromName =
        $("#yay_smtp_setting_mail_force_from_name").prop("checked") == true
          ? 1
          : 0;

      if (mailerSettingsEls.length > 0) {
        $.each(mailerSettingsEls, function() {
          let setting = $(this).attr("data-setting");
          let elType = $(this).attr("type");

          if (typeof setting !== typeof undefined && setting !== false) {
            let settingVal = $(this).val();
            if (typeof elType !== typeof undefined && elType === "radio") {
              if ($(this).is(":checked")) {
                mailerSettings[setting] = settingVal;
              }
            } else if (
              typeof elType !== typeof undefined &&
              elType === "checkbox"
            ) {
              if ($(this).is(":checked")) {
                mailerSettings[setting] = "yes";
              } else {
                mailerSettings[setting] = "no";
              }
            } else {
              mailerSettings[setting] = settingVal;
            }
          }
        });
      }

      // console.log(mailerSettings);

      if (!mailerProvider) {
        alert("Mailer Provider is not empty!");
      } else {
        $.ajax({
          url: yaySmtpWpData.YAY_ADMIN_AJAX,
          type: "POST",
          data: {
            action: "yaysmtp_save_settings",
            nonce: yaySmtpWpData.ajaxNonce,
            settings: {
              fromEmail: fromEmail,
              fromName: fromName,
              forceFromEmail,
              forceFromName,
              mailerProvider: mailerProvider,
              mailerSettings: mailerSettings
            }
          },
          beforeSend: function() {
            yaySMTPspinner("yay-smtp-wrap", true);
          },
          success: function(result) {
            yaySMTPspinner("yay-smtp-wrap", false);
            yaySMTPNotification(result.data.mess, "yay-smtp-wrap");
            setTimeout(function() {
              location.reload();
            }, 1500);
          }
        });
      }
    });

    //Save YaySMTP Addition Settings
    $(".yaysmtp-additional-settings-btn").click(function() {
      let mailReportChoose =
        $("#yaysmtp_addition_setts_report_cb").prop("checked") == true
          ? "yes"
          : "no";

      let mailReportType = $(
        "input[name='yaysmtp_addition_setts_mail_report']:checked"
      ).val();

      if ("undefined" == mailReportType || !mailReportType) {
        mailReportType = "weekly";
      }

      let hasSettingMailFallback =
        $("#yaysmtp_setting_mail_fallback").prop("checked") == true
          ? "yes"
          : "no";

      let fallbackForceFromEmail =
        $("#yaysmtp_fallback_force_from_email").prop("checked") == true
          ? "yes"
          : "no";

      let fallbackForceFromName =
        $("#yaysmtp_fallback_force_from_name").prop("checked") == true
          ? "yes"
          : "no";

      let fallbackAuth =
        $("#yaysmtp_fallback_auth").prop("checked") == true ? "yes" : "no";

      let fallbackAuthType =
        $("#yaysmtp_fallback_encryption_ssl").prop("checked") == true
          ? "ssl"
          : "tls";

      let fallbackFromEmail = $("#yaysmtp_fallback_from_email").val();
      let fallbackFromName = $("#yaysmtp_fallback_from_name").val();
      let fallbackHost = $("#yaysmtp_fallback_host").val();
      let fallbackPort = $("#yaysmtp_fallback_port").val();
      let fallbackSmtpUser = $("#yaysmtp_fallback_smtp_user").val();
      let fallbackSmtpPass = $("#yaysmtp_fallback_smtp_pass").val();

      let paramData = {
        mail_report_choose: mailReportChoose,
        mail_report_type: mailReportType,
        fallback_has_setting_mail: hasSettingMailFallback,
        fallback_force_from_email: fallbackForceFromEmail,
        fallback_force_from_name: fallbackForceFromName,
        fallback_auth: fallbackAuth,
        fallback_auth_type: fallbackAuthType,
        fallback_from_email: fallbackFromEmail,
        fallback_from_name: fallbackFromName,
        fallback_host: fallbackHost,
        fallback_port: fallbackPort,
        fallback_smtp_user: fallbackSmtpUser,
        fallback_smtp_pass: fallbackSmtpPass
      };

      $.ajax({
        url: yaySmtpWpData.YAY_ADMIN_AJAX,
        type: "POST",
        data: {
          action: "yaysmtp_save_addition_settings",
          nonce: yaySmtpWpData.ajaxNonce,
          params: paramData
        },
        beforeSend: function() {
          yaySMTPspinner("yay-smtp-wrap", true);
        },
        success: function(result) {
          yaySMTPspinner("yay-smtp-wrap", false);
          yaySMTPNotification(result.data.mess, "yay-smtp-wrap");
          setTimeout(function() {
            location.reload();
          }, 1500);
        }
      });
    });

    //Send Fallback Test email
    $(".yaysmtp-fallback-send-mail-action").click(function() {
      let elMessage = $(
        ".yaysmtp-fallback-send-test-mail-wrap .error-message-email"
      );
      let emailAddress = $("#yaysmtp_fallback_test_mail_address").val();
      if (!emailAddress) {
        elMessage.html("Email Address is not empty!").show();
      } else {
        elMessage.html("").hide();
        if (validateEmail(emailAddress, elMessage)) {
          $.ajax({
            url: yaySmtpWpData.YAY_ADMIN_AJAX,
            type: "POST",
            data: {
              action: "yaysmtp_fallback_send_mail",
              nonce: yaySmtpWpData.ajaxNonce,
              emailAddress: emailAddress
            },
            beforeSend: function() {
              yaySMTPspinner("yaysmtp-fallback-send-test-mail-wrap", true);
            },
            success: function(result) {
              if (!result.success) {
                let errorMes = result.data.mess;
                elMessage.html(errorMes).show();
                yaySMTPNotification("Can not send test email", "yay-smtp-wrap");
              } else {
                elMessage.html("").hide();
                yaySMTPNotification(result.data.mess, "yay-smtp-wrap");
              }

              yaySMTPspinner("yaysmtp-fallback-send-test-mail-wrap", false);
            },
            error: function(xhr, status, error) {
              console.log(xhr, status, error);
            }
          });
        }
      }
    });

    //Remove Gmail Auth
    $(".yaysmtp-gmail-remove-auth").click(function(e) {
      e.preventDefault();
      $.ajax({
        url: yaySmtpWpData.YAY_ADMIN_AJAX,
        type: "POST",
        data: {
          action: "yaysmtp_gmail_remove_auth",
          nonce: yaySmtpWpData.ajaxNonce
        },
        beforeSend: function() {
          yaySMTPspinner("yay-smtp-wrap", true);
        },
        success: function(result) {
          yaySMTPspinner("yay-smtp-wrap", false);
          location.reload();
        }
      });
    });

    //Remove Yoho Auth
    $(".yaysmtp-yoho-remove-auth").click(function(e) {
      e.preventDefault();
      $.ajax({
        url: yaySmtpWpData.YAY_ADMIN_AJAX,
        type: "POST",
        data: {
          action: "yaysmtp_yoho_remove_auth",
          nonce: yaySmtpWpData.ajaxNonce
        },
        beforeSend: function() {
          yaySMTPspinner("yay-smtp-wrap", true);
        },
        success: function(result) {
          yaySMTPspinner("yay-smtp-wrap", false);
          location.reload();
        }
      });
    });

    //Remove Outlook Microsoft Auth
    $(".yaysmtp-outlookms-remove-auth").click(function(e) {
      e.preventDefault();
      $.ajax({
        url: yaySmtpWpData.YAY_ADMIN_AJAX,
        type: "POST",
        data: {
          action: "yaysmtp_outlookms_remove_auth",
          nonce: yaySmtpWpData.ajaxNonce
        },
        beforeSend: function() {
          yaySMTPspinner("yay-smtp-wrap", true);
        },
        success: function(result) {
          yaySMTPspinner("yay-smtp-wrap", false);
          location.reload();
        }
      });
    });

    // Show/hide Mail settings page or Mail logs page
    var yaySmtpCurrentPage = getYaySmtpCookie("yay_smtp_current_page");
    if (yaySmtpCurrentPage == 1 || yaySmtpCurrentPage == "") {
      // SMTP Settings page
      $(".yay-smtp-wrap.send-mail-settings-wrap").show();
      $(".yay-smtp-wrap.mail-logs").hide();
      $(".yay-smtp-wrap.yaysmtp-additional-settings-wrap").hide();
    } else if (yaySmtpCurrentPage == 2) {
      // Logs page
      $(".yay-smtp-wrap.mail-logs").show();
      $(".yay-smtp-wrap.send-mail-settings-wrap").hide();
      $(".yay-smtp-wrap.yaysmtp-additional-settings-wrap").hide();
      loadFirstYaySMTPLogsList();
    } else if (yaySmtpCurrentPage == 3) {
      // Addition settings page
      $(".yay-smtp-wrap.yaysmtp-additional-settings-wrap").show();
      $(".yay-smtp-wrap.mail-logs").hide();
      $(".yay-smtp-wrap.send-mail-settings-wrap").hide();
    }

    $(".yay-smtp-button.panel-tab-btn.mail-logs-button").click(function(e) {
      e.preventDefault();
      $(".yay-smtp-wrap.mail-logs").show();
      $(".yay-smtp-wrap.yaysmtp-additional-settings-wrap").hide();
      $(".yay-smtp-wrap.send-mail-settings-wrap").hide();
      setYaySmtpCookie("yay_smtp_current_page", 2, 1); // Mail logs page
      loadFirstYaySMTPLogsList();
    });

    $(".yay-smtp-button.panel-tab-btn.mail-additional-setts-button").click(
      function(e) {
        e.preventDefault();
        $(".yay-smtp-wrap.yaysmtp-additional-settings-wrap").show();
        $(".yay-smtp-wrap.send-mail-settings-wrap").hide();
        $(".yay-smtp-wrap.mail-logs").hide();
        setYaySmtpCookie("yay_smtp_current_page", 3, 1); // Addition Settings page
      }
    );

    $(".mail-setting-redirect").click(function(e) {
      e.preventDefault();
      $(".yay-smtp-wrap.mail-logs").hide();
      $(".yay-smtp-wrap.yaysmtp-additional-settings-wrap").hide();
      $(".yay-smtp-wrap.send-mail-settings-wrap").show();
      setYaySmtpCookie("yay_smtp_current_page", 1, 1); // Mail settings page

      // Apply select2 for Smtper choose
      yaySMTPApplySelect2();
    });

    // Apply select2 for Smtper choose
    yaySMTPApplySelect2();
  });
})(window.jQuery);

function validateEmail(mail, elMessage) {
  if (
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(
      mail
    )
  ) {
    elMessage.html("").hide();
    return true;
  }
  elMessage.html("You have entered an invalid email address!").show();
  return false;
}

function yaySMTPspinner(containerClass, isShow) {
  let spinnerHtml = '<div class="yay-smtp-spinner">';
  spinnerHtml +=
    '<svg class="woocommerce-spinner" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">';
  spinnerHtml +=
    '<circle class="woocommerce-spinner__circle" fill="none" stroke-width="5" stroke-linecap="round" cx="50" cy="50" r="30"></circle>';
  spinnerHtml += "/<svg>";
  spinnerHtml += "</div>";
  if (isShow) {
    jQuery("." + containerClass).append(spinnerHtml);
  } else {
    jQuery(".yay-smtp-spinner").remove();
  }
}

function yaySMTPNotification(messages, containerClass) {
  let notifyHtml =
    '<div class="yay-smtp-notification"><div class="yay-smtp-notification-content">' +
    messages +
    "</div></div>";

  jQuery("." + containerClass).after(notifyHtml);
  setTimeout(function() {
    jQuery(".yay-smtp-notification").addClass("NslideDown");
    jQuery(".yay-smtp-notification").remove();
  }, 1500);
}

function yaySMTPEmailLogsList(param) {
  jQuery.ajax({
    url: yaySmtpWpData.YAY_ADMIN_AJAX,
    type: "POST",
    data: {
      action: "yaysmtp_email_logs",
      nonce: yaySmtpWpData.ajaxNonce,
      params: param
    },
    beforeSend: function() {
      yaySMTPspinner("yaysmtp-body", true);
    },
    success: function(results) {
      let data = results.data.data;
      let showColSettings = results.data.showColSettings;
      let showSubjectColClass = showColSettings.showSubjectCol ? "" : "hiden";
      let showToColClass = showColSettings.showToCol ? "" : "hiden";
      let showStatusColClass = showColSettings.showStatusCol ? "" : "hiden";
      let showDatetimeColClass = showColSettings.showDatetimeCol ? "" : "hiden";
      let showActionColClass = showColSettings.showActionCol ? "" : "hiden";

      let html = "";
      data.forEach(function(item) {
        let emailTo = item.email_to;
        let status = "Success";
        let status_cl = "email-success";
        if (parseInt(item.status) == 0) {
          status = "Fail";
          status_cl = "email-fail";
        } else if (parseInt(item.status) == 2) {
          status = "Waiting";
          status_cl = "email-waiting";
        }

        html += "<tr>";
        html += '<th scope="row" class="table-item is-checkbox-column">';
        html += "<div>";
        html += '<span class="checkbox-control-input-container">';
        html +=
          '<input class="checkbox-control-input checkbox-control-input-el" type="checkbox" value="' +
          item.id +
          '">';
        html += "</span>";
        html += "</div>";
        html += "</th>";
        html +=
          '<td class="table-item is-left-aligned subject-col ' +
          showSubjectColClass +
          '">';
        html +=
          '<a class="" data-id="' + item.id + '">' + item.subject + "</a>";
        html += "</td>";

        html +=
          '<td class="table-item is-left-aligned is-sorted to-col ' +
          showToColClass +
          '">';
        emailTo.forEach(function(email_val) {
          html += "<span>" + email_val + "</span><br>";
        });
        html += "</td>";

        html +=
          '<td class="table-item is-left-aligned status-col ' +
          showStatusColClass +
          '">';
        html +=
          '<mark class="email-status ' +
          status_cl +
          '"><span>' +
          status +
          "</span></mark>";
        html += "</td>";

        html +=
          '<td class="table-item datetime-col ' + showDatetimeColClass + '">';
        html += "<span>" + item.date_time + "</span>";
        html += "</td>";

        html += '<td class="table-item action-col ' + showActionColClass + '">';
        html +=
          '<div class="yay-tooltip view-action"><button type="button" class="yaysmtp-btn yaysmtp-view-btn" data-id="' +
          item.id +
          '"><span class="dashicons dashicons-visibility icon-action"></span><span class="yay-tooltiptext yay-tooltip-top">View this email</span></button></div>';
        html +=
          '<div class="yay-tooltip delete-action"><button type="button" class="yaysmtp-btn yaysmtp-delete-btn" data-id="' +
          item.id +
          '"><span class="dashicons dashicons-trash icon-action"></span><span class="yay-tooltiptext yay-tooltip-top">Delete this email</span></button></div>';
        html += "</td>";

        html += "</tr>";
      });

      if (html == "") {
        html +=
          '<tr><td class="table-empty-item" colspan="6">No data to display</td></tr>';
      }

      jQuery(".yaysmtp-body").html(html);

      /** current page - start */
      jQuery(".yay-smtp-content .pag-page-current").val(
        results.data.currentPage
      );
      jQuery(".yay-smtp-content .pag-page-current").attr(
        "max",
        results.data.totalPage
      );
      /** previous, next button - end */

      /** previous, next button - start */
      let htmlPageRowLabel =
        "Page " + results.data.currentPage + " of " + results.data.totalPage;
      jQuery(".yay-smtp-content .pagination-page-arrows-label").html(
        htmlPageRowLabel
      );

      if (parseInt(results.data.currentPage) == 1) {
        jQuery(
          ".yay-smtp-content .pagination-page-arrows-buttons .previous-btn"
        ).prop("disabled", true);
      } else {
        jQuery(
          ".yay-smtp-content .pagination-page-arrows-buttons .previous-btn"
        ).prop("disabled", false);
      }

      if (
        parseInt(results.data.currentPage) ==
          parseInt(results.data.totalPage) ||
        parseInt(results.data.currentPage) > parseInt(results.data.totalPage)
      ) {
        jQuery(
          ".yay-smtp-content .pagination-page-arrows-buttons .next-btn"
        ).prop("disabled", true);
      } else {
        jQuery(
          ".yay-smtp-content .pagination-page-arrows-buttons .next-btn"
        ).prop("disabled", false);
      }
      /** previous, next button - end */
      yaySMTPspinner("yaysmtp-body", false);
    }
  });
}

function yaySMTPGetParam(param) {
  var query = window.location.search.substring(1);
  var vars = query.split("&");
  for (var i = 0; i < vars.length; i++) {
    var pair = vars[i].split("=");
    if (pair[0] == param) {
      return pair[1];
    }
  }
}

function searchConditionBasicCurrent() {
  let limit = jQuery(".yay-smtp-wrap.mail-logs .pag-per-page-sel").val();
  let page = jQuery(".yay-smtp-wrap.mail-logs .pag-page-current").val();
  let valSearch = jQuery(
    ".yay-smtp-wrap.mail-logs .yay-button-wrap .search .search-imput"
  ).val();
  let status;
  if (jQuery("#yaysmtp_logs_status_not_send").is(":checked")) {
    if (jQuery("#yaysmtp_logs_status_sent").is(":checked")) {
      status = "all";
    } else {
      status = "not_send";
    }
  } else {
    if (jQuery("#yaysmtp_logs_status_sent").is(":checked")) {
      status = "sent";
    } else {
      status = "empty";
    }
  }
  let param = {
    page: parseInt(page),
    limit: parseInt(limit),
    valSearch: valSearch,
    status: status
  };
  return param;
}

function setYaySmtpCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
  var expires = "expires=" + d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getYaySmtpCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(";");
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == " ") {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function loadFirstYaySMTPLogsList() {
  let page = jQuery(".yay-smtp-wrap.mail-logs .pag-page-current").val();
  let limit = jQuery(".yay-smtp-wrap.mail-logs .pag-per-page-sel").val();
  let param = {
    page: parseInt(page),
    limit: parseInt(limit)
  };
  yaySMTPEmailLogsList(param);
}

function yaySMTPApplySelect2() {
  jQuery("#yaysmtp_smtper_choose").select2({
    minimumResultsForSearch: -1,
    templateResult: function(smtper) {
      if (typeof smtper.id !== "undefined") {
        var span = jQuery(
          '<span><span class="yaysmtp-smtpicon yaysmtp-' +
            smtper.id +
            '-icon"' +
            "></span>" +
            smtper.text +
            "</span>"
        );
        return span;
      }
    },
    templateSelection: function(smtper) {
      if (typeof smtper.id !== "undefined") {
        var span = jQuery(
          '<span><span class="yaysmtp-smtpicon yaysmtp-' +
            smtper.id +
            '-icon"' +
            "></span>" +
            smtper.text +
            "</span>"
        );
        return span;
      }
    }
  });
}
