jQuery(document).ready(function($) {
    // Perform AJAX login on form submit
    $('form#header-login-form').on('submit', function(e){
        $('form#header-login-form p.status').show().text(ajax_woocommerce_object.loadingmessage);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_woocommerce_object.ajaxurl,
            data: {
                'action': 'doccure_ajax_login',
                'username': $('form#header-login-form #username').val(),
                'password': $('form#header-login-form #password').val(),
                'security': $('form#header-login-form #security').val() },
            success: function(data){
                $('form#header-login-form p.status').text(data.message);
                if (data.loggedin == true){
                    document.location.href = ajax_woocommerce_object.redirecturl;
                }
            }
        });
        e.preventDefault();
    });

    /* Ajax Registration */
     $('#doccure_user-register').on('click',function(e){
       $("#header-login-register-form-wrapper .registration-form-wrapper #doccure_user-register").html('Please Wait... <i class="fa fa-spin fa-spinner"></i>');
       var action = 'doccure_register_user';
       var username = $("#sb-username").val();
       var mail_id = $("#sb-email").val();
       var firstname = $("#sb-fname").val();
       var lastname = $("#sb-lname").val();
       var passwrd = $("#sb-psw").val();
       var confirmPasswrd = $("#sb-confirm-psw").val();
       $.ajax({
           type: 'POST',
           url: ajax_woocommerce_object.ajaxurl,
           data: {
               'action': action,
               'username': username,
               'mail_id': mail_id,
               'firstname': firstname,
               'lastname': lastname,
               'passwrd': passwrd,
               'confirmPasswrd': confirmPasswrd,
           },
           success: function(data){
             $("#header-login-register-form-wrapper .registration-form-wrapper #doccure_user-register").html('Register');
             $("#error-message").html(data);
           }
       });
       e.preventDefault();
    });


});
