<form id="login-form" method="post" action="<?php echo admin_url('admin-ajax.php') ?>">

    <div class="form-group">
        <input type="text" class="form-control" name="user_email" id="user_email" placeholder="Username">
    </div>
    <div class="form-group">
        <input type="password" class="form-control" id="user_password" name="user_password" placeholder="******">
    </div>
    <input type="hidden" name="action" value="jptt_user_login">
    <?php wp_nonce_field('jptt_user_login') ?>
    <div class="form-group text-center">
        <div class="button-container mgb">
            <button type="submit" id="btnsend" class="btn btn-default send-button"><span>GO</span></button>
        </div>
    </div>
    <div class="response"></div>
</form>

<script>
    jQuery(function () {
        jQuery('#login-form').ajaxForm({
            url: admin_url,
            dataType: 'json',
            beforeSubmit: function (formData, jqForm, options) {
                // optionally process data before submitting the form via AJAX
                jQuery('#login-form .send-button').attr('disabled', '').text('<?php _e('Checking data...', TEXT_DOMAIN) ?>');
            },
            success: function (response, statusText, xhr, $form) {
                // code that's executed when the request is processed successfully
                jQuery('#login-form .send-button').removeAttr('disabled');
                jQuery('#login-form .response').empty();
                if (response.success) {
                    jQuery('#login-form .response').html('<div class="alert alert-success" role="alert">Wait a second...</div>');
                    jQuery('#login-form .send-button').text('<?php _e('Session started', TEXT_DOMAIN) ?>');
                    var delay = 1000;
                    setTimeout(function () {
                        location.reload();
                    }, delay);
                } else {
                    jQuery('#login-form .response').html('<div class="alert alert-warning" role="alert">' + response.data[0].message + '</div>');
                    jQuery('#login-form .send-button').text('<?php _e('GO', TEXT_DOMAIN) ?>');
                }
            }
        });

        jQuery('#login-form input, #login-form textarea').focus(function () {
            var buttonText = jQuery('#login-form .send-button').text();
            if (buttonText === '<?php _e('Error!', TEXT_DOMAIN) ?>') {
                jQuery('#send').text('<?php _e('GO', TEXT_DOMAIN) ?>');
            }
        });

    });
</script>