<?php
/**
 * Template Name: Dashboard
 *
 * @package doccure
 * @since doccure 1.0
 * @desc Template used for front end dashboard.
 */
/* Define Global Variables */
global $current_user, $wp_roles, $userdata, $post,$doccure_options;
get_header();
$user_identity 	= $current_user->ID;
$url_identity 	= !empty($_GET['identity']) ? intval($_GET['identity']) : '';
$user_type		= apply_filters('doccure_get_user_type', $user_identity );
$post_id		= doccure_get_linked_profile_id( $user_identity );
$is_verified 	= get_post_meta($post_id, '_is_verified', true);
$ref 			= !empty($_GET['ref']) ? esc_html( $_GET['ref'] ) : '';
$mode 			= !empty($_GET['mode']) ? esc_html( $_GET['mode'] ) : '';
$verify_user	= !empty( $doccure_options['verify_user'] ) ? $doccure_options['verify_user'] : '';
$doctor_location	= !empty($doccure_options['doctor_location']) ? $doccure_options['doctor_location'] : '';
$doctros_pages	= apply_filters( 'doccure_doctor_redirect_after_login','');
$doctor_booking_option			= '';
if( function_exists( 'doccure_get_booking_oncall_doctors_option' ) ) {
	$doctor_booking_option	= doccure_get_booking_oncall_doctors_option();
}

if( have_posts() ) {
	while ( have_posts() ) : the_post();
	the_content();
	wp_link_pages( array(
		'before'      => '<div class="dc-paginationvtwo"><nav class="dc-pagination"><ul>',
		'after'       => '</ul></nav></div>',
		) );
	endwhile;
	wp_reset_postdata();
}

$remove_payouts	= doccure_theme_option('enable_checkout_page');
$payment_type	= doccure_theme_option('payment_type');

$show_payout_settings	= true; 
if( !empty($payment_type) && $payment_type == 'offline' ){
	if( empty($remove_payouts) && $remove_payouts === 'hide'){
		$show_payout_settings	= false; 
	}
}

if ( is_user_logged_in() 
	&& ( $user_type === 'doctors' || $user_type === 'hospitals' || $user_type === 'regular_users' ) ) {

	if( empty( $is_verified ) || $is_verified === 'no' ){
		$verify   = doccure_get_signup_page_url(); 
		?>
		<div class="container">
		  <div class="dc-haslayout dc-jobalertsdashboard re-sendverification">
					<?php if(!empty($verify_user) && $verify_user === 'no' ){
							doccure_Prepare_Notification::doccure_warning(esc_html__('Verification', 'doccure'), esc_html__('Your account will be approved after the verification', 'doccure'),'','');
						} elseif(!empty($verify_user) && $verify_user === 'yes' ){
							doccure_Prepare_Notification::doccure_warning(esc_html__('Verification', 'doccure'), esc_html__('Your account is not verified. Please check your email for the verification', 'doccure'),'javascript:;',esc_html__('Resend email', 'doccure'));
						}
					?>
			</div>
			</div>			
	<?php }?>
	<div class="content">
	<div class="container">
		<div class="row">
			<div class="col-md-5 col-lg-4 col-xl-3">
			<?php doccure_Profile_Menu::doccure_profile_menu_left(); ?>

			</div>
			<div class="col-md-7 col-lg-8 col-xl-9">
			<div class="dc-haslayout dc-dbsectionspace">
			<?php 
				if( !empty($ref) && $_GET['ref'] === 'profile' && $mode === 'settings' && ($url_identity === $user_identity) ) {
					get_template_part('directory/front-end/templates/'.$user_type.'/dashboard', 'profile-settings');
				}elseif ( !empty($ref) && $_GET['ref'] === 'profile' && $mode === 'social' && ($url_identity === $user_identity) ) {
					get_template_part('directory/front-end/templates/dashboard', 'social-profile');
				} elseif( $mode === 'educations') {
					get_template_part('directory/front-end/templates/'.$user_type.'/dashboard', 'profile-settings-educations');
				} elseif( !empty($ref) && $ref === 'prescription' && $mode === 'view' && ($url_identity === $user_identity) ) {
					get_template_part('directory/front-end/templates/'.$user_type.'/dashboard', 'update-prescription');
				} elseif( $mode === 'awards') {
					get_template_part('directory/front-end/templates/'.$user_type.'/dashboard', 'profile-settings-awards');
				} elseif( $mode === 'registrations') {
					get_template_part('directory/front-end/templates/'.$user_type.'/dashboard', 'profile-settings-registrations');
				} elseif( $mode === 'location') {
					get_template_part('directory/front-end/templates/'.$user_type.'/dashboard', 'profile-location');
				} elseif( $mode === 'gallery') {
					get_template_part('directory/front-end/templates/'.$user_type.'/dashboard', 'profile-gallery');
				} elseif( $mode === 'booking' && $user_type ==='doctors' && empty($doctor_booking_option)) {
					get_template_part('directory/front-end/templates/'.$user_type.'/dashboard', 'profile-booking-options');
				} else if( $user_type === 'doctors' && $ref === 'manage-article' && $mode === 'listings' && ($url_identity === $user_identity) ) {
					get_template_part('directory/front-end/templates/doctors/dashboard', 'manage-article');
				} else if( $user_type === 'doctors' && $ref === 'appointment' && $mode === 'setting' && ($url_identity === $user_identity) ) {
					if( !empty($doctor_location) && $doctor_location !== 'clinic' && apply_filters('doccure_is_appointment_allowed', 'dc_bookings', $url_identity) === true ){
						get_template_part('directory/front-end/templates/doctors/dashboard', 'appointment-setting');
					}
				} else if( $user_type === 'doctors' && $ref === 'appointment' && $mode === 'location-settings' && ($url_identity === $user_identity) ) {
					if( !empty($doctor_location) && $doctor_location !== 'hospitals' ){
						get_template_part('directory/front-end/templates/doctors/dashboard', 'appointment-location-settings');
					}
				} else if( $user_type === 'doctors' && $ref === 'appointment' && $mode === 'details' && ($url_identity === $user_identity) ) {
					if(apply_filters('doccure_is_appointment_allowed', 'dc_bookings', $url_identity) === true ){
						get_template_part('directory/front-end/templates/doctors/dashboard', 'appointment-detials');
					}
				} else if( $user_type === 'doctors' && $ref === 'appointment' && $mode === 'listing' && ($url_identity === $user_identity) ) {
					if(apply_filters('doccure_is_appointment_allowed', 'dc_bookings', $url_identity) === true ){
						get_template_part('directory/front-end/templates/doctors/dashboard', 'appointment-listing');
					}
				} else if ( $user_type === 'doctors' && !empty($ref) && $_GET['ref'] === 'package' && ($url_identity === $user_identity) ) {
					get_template_part('directory/front-end/templates/dashboard', 'packages');
				} else if ( $user_type === 'doctors' && !empty($ref) && $_GET['ref'] === 'invoices' && ($url_identity === $user_identity) ) {
					get_template_part('directory/front-end/templates/dashboard', 'invoices');
				} else if ( $user_type === 'doctors' && !empty($ref) && $_GET['ref'] === 'payouts' && ($url_identity === $user_identity) ) {
					if( !empty($show_payout_settings) ){
						get_template_part('directory/front-end/templates/doctors/dashboard', 'payouts');
					}
				} elseif( $user_type === 'doctors' && !empty( $ref ) && $ref === 'specialities' && ($url_identity === $user_identity) ) {
					get_template_part('directory/front-end/templates/dashboard', 'profile-settings-specialities');
				} else if( $user_type === 'hospitals' && $ref === 'team' && $mode === 'manage' && ($url_identity === $user_identity) ) {
					get_template_part('directory/front-end/templates/hospitals/dashboard', 'team');
				} else if( $user_type === 'hospitals' && $ref === 'location' && $mode === 'details') {
					get_template_part('directory/front-end/templates/hospitals/dashboard', 'location-details');
				} elseif( $user_type === 'hospitals' && !empty( $ref ) && $ref === 'specialities' && ($url_identity === $user_identity) ) {
					get_template_part('directory/front-end/templates/dashboard', 'profile-settings-specialities');
				} else if( $user_type === 'regular_users' && $ref === 'appointment' && $mode === 'listing' && ($url_identity === $user_identity) ) {
					get_template_part('directory/front-end/templates/regular_users/dashboard', 'appointment-listing');
				} else if ( $user_type === 'regular_users' && !empty($ref) && $_GET['ref'] === 'invoices' && ($url_identity === $user_identity) ) {
					get_template_part('directory/front-end/templates/dashboard', 'invoices');
				} else if ( !empty($ref) && $_GET['ref'] === 'account-settings' && ($url_identity === $user_identity) ) {
					get_template_part('directory/front-end/templates/dashboard', 'account-manage');
				}elseif ( !empty($ref) && $_GET['ref'] === 'saved' && ($url_identity === $user_identity) ) {
					get_template_part('directory/front-end/templates/dashboard', 'saved-items');
				} else if( !empty( $ref ) && $ref === 'chat' && ($url_identity === $user_identity) ) {
					if( ( !empty( $user_type ) 
						 && ( $user_type === 'doctors' && apply_filters('doccure_is_feature_allowed', 'dc_chat', $user_identity) === true ) ) 
						|| $user_type === 'hospitals' 
						|| $user_type === 'regular_users'
					) {
						get_template_part('directory/front-end/templates/dashboard', 'messaging');
					}
				} else{
					get_template_part('directory/front-end/templates/'.$user_type.'/dashboard', 'insights');
				}	
			?>
	</div>
			</div>
			</div>
		</div>
	</div>
	<?php } else {?>
		<div class="container">
		 <div class="dc-haslayout page-data">
			<?php doccure_Prepare_Notification::doccure_warning(esc_html__('Restricted Access', 'doccure'), esc_html__('You have not any privilege to view this page. Please Login as Doctor/Patient.', 'doccure'));?>
		  </div>
		</div>
<?php }
get_footer(); 