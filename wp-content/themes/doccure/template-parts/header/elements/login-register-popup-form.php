<?php
/* Login/Register form */
?>
<div class="modal fade" id="header-login-register-form" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered header-user-wrapper" role="document">
    <div class="modal-content">
      <div class="modal-body header-login-register-form" id="header-login-register-form-wrapper">
        <div class="doccure_close" data-bs-dismiss="modal">
          <span></span>
          <span></span>
        </div>
        <div class="login-register-form-toggle">
        </div>
        <div class="login-form-wrapper">
          <div class="section-title">
            <h2><?php esc_html_e('Log', 'doccure'); ?> <span class="primary-color"><?php esc_html_e('In', 'doccure'); ?></span></h2>
          </div>
          <form id="header-login-form" action="login" method="post">
                <p class="status"></p>
                <div class="form-group">
                  <i class="fal fa-envelope"></i>
                  <input type="text" name="username" id="username" placeholder="<?php echo esc_attr('Username or Email', 'doccure'); ?>" required>
                </div>
                <div class="form-group">
                  <i class="fal fa-eye password-toggle show-password"></i>
                  <input type="password" name="password" id="password" placeholder="<?php echo esc_attr('Password', 'doccure'); ?>" required>
                </div>
                <div class="doccure_form-info form-group">
                  <div class="d-flex align-items-center">
                    <input type="checkbox" id="customCheck1" name="customCheck1">
                    <label class="mb-0" for="customCheck1"><?php esc_html_e('Remember Me', 'doccure'); ?></label>
                  </div>
                  <a href="<?php echo wp_lostpassword_url(); ?>"><?php esc_html_e('Forget Password?', 'doccure'); ?></a>
                </div>
                <?php wp_nonce_field('ajax-login-nonce', 'security'); ?>
                  <div class="form-group">
                    <button type="submit" class="btn-block doccure_btn submit_button"><?php esc_html_e('Login', 'doccure'); ?></button>
                  </div>
                  <p class="text-center mb-0"><?php esc_html_e("Don't Have an Account?", 'doccure'); ?>
                    <a href="#" class="register-new-account"><?php esc_html_e('Sign Up', 'doccure'); ?></a>
                  </p>
            </form>
          </div>
          <div class="registration-form-wrapper">
              <div class="section-title">
                <h2><?php esc_html_e('Sign', 'doccure'); ?> <span class="primary-color"><?php esc_html_e('Up', 'doccure'); ?></span></h2>
              </div>
              <div id="error-message"></div>
              <form method="post" name="doccure-register-form" id="doccure-register-form">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <input type="text" autocomplete="off" name="fname" id="sb-fname" placeholder="<?php echo esc_attr('First Name', 'doccure'); ?>" />
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <input type="text" autocomplete="off" name="lname" id="sb-lname" placeholder="<?php echo esc_attr('Last Name', 'doccure'); ?>" />
                    </div>
                  </div>

                  <div class="col-lg-6">
                   <div class="form-group">
                     <input type="text" autocomplete="off" name="username" id="sb-username" placeholder="<?php echo esc_attr('Username', 'doccure'); ?>" required>
                   </div>
                 </div>

                 <div class="col-lg-6">
                   <div class="form-group">
                     <input type="text" autocomplete="off" name="mail" id="sb-email" placeholder="<?php echo esc_attr('Email', 'doccure'); ?>" required>
                   </div>
                 </div>
                 <div class="col-lg-6">
                   <div class="form-group">
                     <i class="fal fa-eye password-toggle show-password"></i>
                     <input type="password" name="password" id="sb-psw" placeholder="<?php echo esc_attr('Password', 'doccure'); ?>" required>
                   </div>
                 </div>
                 <div class="col-lg-6">
                   <div class="form-group">
                     <i class="fal fa-eye password-toggle show-password"></i>
                     <input type="password" name="confirm-password" id="sb-confirm-psw" placeholder="<?php echo esc_attr('Confirm Password', 'doccure'); ?>" required />
                   </div>
                 </div>
              </div>
              <div class="form-group">
                <button type="submit" id="doccure_user-register" class="btn-block doccure_btn"><?php esc_html_e('Register', 'doccure'); ?></button>
              </div>
            </form>
          </div>
      </div>
    </div>
  </div>
</div>
