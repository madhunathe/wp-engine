<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.2.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$my_account_page_style = doccure_get_option('my_account_style', 'style-1');
$page_wrapper_class = (doccure_get_option('my_account_style') == 'style-2') ? 'no-gutters align-items-center' : '';
$my_acc_page_title = doccure_get_option('my_account_page_title');
$my_acc_page_desc = doccure_get_option('my_account_page_desc');
$my_acc_page_img = doccure_get_option('my_account_page_img');
if(isset($my_acc_page_img) && !empty($my_acc_page_img)) {
	$my_acc_page_img_url = $my_acc_page_img['url'];
}

do_action( 'woocommerce_before_customer_login_form' ); ?>
<?php  if($my_account_page_style == 'style-1') {
		if(!empty($my_acc_page_title) || !empty($my_acc_page_desc)) { ?>
			<div class="section-title centered">
				<?php if(!empty($my_acc_page_title)) { ?>
				  <h3 class="title">
						<?php echo wp_kses( $my_acc_page_title, array(
							'a' =>  array(
								'href'  =>  array(),
								'title' =>  array()
								),
								'span' =>  array(
									'class'  =>  array(),
								),
								'p'
							));
							?>
					</h3>
				<?php }
				if(!empty($my_acc_page_desc)) {
					echo wp_kses( $my_acc_page_desc, array(
						'a' =>  array(
							'href'  =>  array(),
							'title' =>  array()
						),
						'span' =>  array(
							'class'  =>  array(),
						),
						'p'
					));
		 } ?>
	</div>
<?php } } ?>
<div class="login-form-section">
	<div class="row my-account-<?php echo esc_attr($my_account_page_style . ' ' . $page_wrapper_class); ?>" id="customer_login">
		<?php if(!empty($my_acc_page_img_url) && $my_account_page_style == 'style-1') { ?>
			<div class="col-lg-6">
				<div class="w-100 h-100 mb-lg-30">
		      <img src="<?php echo esc_url($my_acc_page_img_url); ?>" class="w-100 h-100 object-cover" alt="img">
		    </div>
			</div>
		<?php } ?>
		<div class="col-md-6 offset-md-3">
			<?php  if($my_account_page_style == 'style-2') {
					if(!empty($my_acc_page_title) || !empty($my_acc_page_desc)) { ?>
						<div class="section-title centered text-left">
							<?php if(!empty($my_acc_page_title)) { ?>
								<h3 class="title">
									<?php echo wp_kses( $my_acc_page_title, array(
										'a' =>  array(
											'href'  =>  array(),
											'title' =>  array()
											),
											'span' =>  array(
												'class'  =>  array(),
											),
											'p'
										));
										?>
								</h3>
							<?php }
							if(!empty($my_acc_page_desc)) {
								echo wp_kses( $my_acc_page_desc, array(
									'a' =>  array(
										'href'  =>  array(),
										'title' =>  array()
									),
									'span' =>  array(
										'class'  =>  array(),
									),
									'p'
								));
					 } ?>
				</div>
			<?php } } ?>
			<form class="woocommerce-form woocommerce-form-login login" method="post">

				<?php do_action( 'woocommerce_login_form_start' ); ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-group">
					<i class="far fa-envelope"></i>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" placeholder="<?php echo esc_attr('Email', 'doccure'); ?>" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
				</p>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-group">
					<i class="far fa-eye password-toggle show-password"></i>
					<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" placeholder="<?php echo esc_attr('Password', 'doccure'); ?>" id="password" autocomplete="current-password" />
				</p>

				<?php do_action( 'woocommerce_login_form' ); ?>
				<p class="doccure_form-info form-group">
					<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
						<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'doccure' ); ?></span>
					</label>
					<span class="woocommerce-LostPassword lost_password">
						<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot password?', 'doccure' ); ?></a>
					</span>
				</p>
				<p class="form-row">
					<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
					<button type="submit" class="woocommerce-button button woocommerce-form-login__submit btn-block" name="login" value="<?php esc_attr_e( 'Log in', 'doccure' ); ?>"><?php esc_html_e( 'Log in', 'doccure' ); ?></button>
				</p>
				<?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>
	          <p class="text-center mb-0"><?php esc_html_e("Don't Have an Account? ", "doccure"); ?><a href="javascript:void(0)" class="account-register-link"><?php esc_html_e('Sign Up', 'doccure'); ?></a></p>
	      <?php endif; ?>


				<?php do_action( 'woocommerce_login_form_end' ); ?>

			</form>
			<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
			<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

				<?php do_action( 'woocommerce_register_form_start' ); ?>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-group">
						<i class="fal fa-user"></i>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" placeholder="<?php echo esc_attr('Username', 'doccure'); ?>" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
					</p>

				<?php endif; ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-group">
					<i class="fal fa-envelope"></i>
					<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" placeholder="<?php echo esc_attr("Email Address", "doccure"); ?>" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
				</p>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-group">
						<i class="fal fa-eye password-toggle show-password"></i>
						<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" placeholder="<?php echo esc_attr("Password", "doccure"); ?>" id="reg_password" autocomplete="new-password" />
					</p>

				<?php else : ?>

					<p><?php esc_html_e( 'A password will be sent to your email address.', 'doccure' ); ?></p>

				<?php endif; ?>

				<?php do_action( 'woocommerce_register_form' ); ?>

				<p class="woocommerce-FormRow form-row mb-0">
					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
					<button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit btn-block" name="register" value="<?php esc_attr_e( 'Register', 'doccure' ); ?>"><?php esc_html_e( 'Register', 'doccure' ); ?></button>
				</p>
				<p class="login-account-block mb-0 text-center"> <?php esc_html_e('Have an account? ', 'doccure'); ?><a href="javascript:void(0)" class="account-login-link"><?php esc_html_e('Sign In', 'doccure'); ?></a>
	      </p>
				<?php do_action( 'woocommerce_register_form_end' ); ?>

			</form>
		<?php endif; ?>

		</div>
		<?php if(!empty($my_acc_page_img_url) && $my_account_page_style == 'style-2') { ?>
			<div class="col-lg-6 d-none d-lg-block">
	      <div class="doccure_form-image">
	        <img src="<?php echo esc_url($my_acc_page_img_url); ?>" alt="img">
	      </div>
	    </div>
		<?php } ?>

	</div>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
