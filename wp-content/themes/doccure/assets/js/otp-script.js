jQuery(document).ready(function ($) {
    $('#signup_button').prop('disabled', true);

    $('#send_otp').on('click', function () {
        var email = $('#email').val();

        if (!email) {
            alert('Please enter your email address.');
            return;
        }

        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'send_otp',
                email: email,
                security: ajax_object.nonce
            },
            success: function (response) {
                if (response.success) {
                    alert('OTP sent to your email!');

                    // Disable the button and start the countdown
                    $('#send_otp').prop('disabled', true);
                    startCountdown(180);

                    // Show OTP input and verify button
                    $('#otp_block').show();
                } else {
                    alert(response.data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });

    function startCountdown(seconds) {
        var countdown = seconds;
        var button = $('#send_otp');
        var originalText = button.text();

        var interval = setInterval(function () {
            countdown--;
            button.text('Resend OTP (' + countdown + 's)');

            if (countdown <= 0) {
                clearInterval(interval);
                button.prop('disabled', false);
                button.text(originalText);
            }
        }, 1000);
    }

    // Handle OTP verification
    $('#verify_otp').on('click', function () {
        var otp = $('#otp').val();
        var email = $('#email').val();

        if (!otp) {
            alert('Please enter the OTP.');
            return;
        }

        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'verify_otp',
                email: email,
                otp: otp,
                security: ajax_object.nonce
            },
            success: function (response) {
                if (response.success) {
                    alert('OTP verified successfully!');
                    $('#email_hidden').val(email);
                    $('#signup_button').prop('disabled', false);
                    $('#otp_block').hide();
                    $('#verify_otp').hide();
                    $('#send_otp').hide();
                    $('#email').prop('disabled', true);
                } else {
                    alert(response.data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });
});
