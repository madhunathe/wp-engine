<?php
/**
 *
 * The template part for displaying the dashboard menu
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post,$doccure_options;
$user_identity 	 = $current_user->ID;
$linked_profile  = doccure_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;
$gallery_option				= !empty($doccure_options['enable_gallery']) ? $doccure_options['enable_gallery'] : '';
$profile_details_url 		= doccure_Profile_Menu::doccure_profile_menu_link('profile', $user_identity, true,'settings');
$educations_url	 			= doccure_Profile_Menu::doccure_profile_menu_link('profile', $user_identity, true,'educations');
$awardss_url				= doccure_Profile_Menu::doccure_profile_menu_link('profile', $user_identity, true,'awards');
$registrations_url			= doccure_Profile_Menu::doccure_profile_menu_link('profile', $user_identity, true,'registrations');
$gallery_url				= doccure_Profile_Menu::doccure_profile_menu_link('profile', $user_identity, true,'gallery');
$booking_url				= doccure_Profile_Menu::doccure_profile_menu_link('profile', $user_identity, true,'booking');
$location_url				= doccure_Profile_Menu::doccure_profile_menu_link('profile', $user_identity, true,'location');
$social_url					= doccure_Profile_Menu::doccure_profile_menu_link('profile', $user_identity, true,'social');
$mode 			 			= !empty($_GET['mode']) ? esc_html( $_GET['mode'] ) : 'settings';
$social_links				= !empty( $doccure_options['social_links'] ) ? $doccure_options['social_links'] : '';

$doctor_booking_option		= '';
if( function_exists( 'doccure_get_booking_oncall_doctors_option' ) ) {
	$doctor_booking_option		= doccure_get_booking_oncall_doctors_option();
}
?>
<div class="dc-dashboardboxtitle">
	<h2><?php esc_html_e('Profile Settings','doccure');?></h2>
</div>
<div class="dc-dashboardtabs">
	<ul class="dc-tabstitle nav navbar-nav">
		<li class="nav-item">
			<a class="<?php echo !empty( $mode ) && $mode === 'settings' ? 'active' : '';?>" href="<?php echo esc_url( $profile_details_url );?>">
				<?php esc_html_e('Personal Details', 'doccure'); ?>
			</a>
		</li>
		<li class="nav-item">
			<a class="<?php echo !empty( $mode ) && $mode === 'educations' ? 'active' : '';?>" href="<?php echo esc_url( $educations_url );?>">
				<?php esc_html_e('Experience &amp; Education', 'doccure'); ?>
			</a>
		</li>
		<li class="nav-item">
			<a class="<?php echo !empty( $mode ) && $mode === 'awards' ? 'active' : '';?>" href="<?php echo esc_url( $awardss_url );?>">
				<?php esc_html_e('Awards', 'doccure'); ?>
			</a>
		</li>
		<?php if(!empty($gallery_option)){?>
			<li class="nav-item">
				<a class="<?php echo !empty( $mode ) && $mode === 'gallery' ? 'active' : '';?>" href="<?php echo esc_url( $gallery_url );?>">
					<?php esc_html_e('Gallery', 'doccure'); ?>
				</a>
			</li>
		<?php } ?>

		<?php if(empty($doctor_booking_option)){?>
			<li class="nav-item">
				<a class="<?php echo !empty( $mode ) && $mode === 'booking' ? 'active' : '';?>" href="<?php echo esc_url( $booking_url );?>">
					<?php esc_html_e('Booking settings', 'doccure'); ?>
				</a>
			</li>
		<?php } ?>
		<li class="nav-item">
			<a class="<?php echo !empty( $mode ) && $mode === 'location' ? 'active' : '';?>" href="<?php echo esc_url( $location_url );?>">
				<?php esc_html_e('Default Location', 'doccure'); ?>
			</a>
		</li>
		<?php if(!empty($social_links) && $social_links === 'yes') {?>
			<li class="nav-item">
				<a class="<?php echo !empty( $mode ) && $mode === 'social' ? 'active' : '';?>" href="<?php echo esc_url( $social_url );?>">
					<?php esc_html_e('Social profiles', 'doccure'); ?>
				</a>
			</li>
		<?php } ?>
	</ul>
</div>