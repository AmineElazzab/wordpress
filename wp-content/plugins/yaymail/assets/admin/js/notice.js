jQuery(document).ready(function () {
    jQuery("#yaymail-notice .yaymail-nothank").on("click", function () {
        jQuery( "#yaymail-notice .notice-dismiss" ).trigger( "click" );
    })
    jQuery("#yaymail-notice .yaymail-see-addons").on("click", function () {
        jQuery( "#yaymail-notice .notice-dismiss" ).trigger( "click" );
    })
    jQuery(document).on("click", "#yaymail-notice .notice-dismiss", function(){
        jQuery.ajax({
            dataType: 'json',
            url: yaymail_notice.admin_ajax,
            type: "post",
            data: {
            action: "yaymail_notice",
            nonce: yaymail_notice.nonce,
            },
        })
        .done(function (result) {
            if (result.success) {
                console.log("success hide notice");
            } else {
                console.log("Error", result.message);
            }
          })
          .fail(function (res) {
            console.log(res.responseText);
          });
    })
})