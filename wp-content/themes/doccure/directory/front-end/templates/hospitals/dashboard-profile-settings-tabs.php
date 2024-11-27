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
$registrations_url 			= doccure_Profile_Menu::doccure_profile_menu_link('profile', $user_identity, true,'registrations');
$gallery_url				= doccure_Profile_Menu::doccure_profile_menu_link('profile', $user_identity, true,'gallery');
$mode 			 			= !empty($_GET['mode']) ? esc_html( $_GET['mode'] ) : 'settings';
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
			<a class="<?php echo !empty( $mode ) && $mode === 'registrations' ? 'active' : '';?>" href="<?php echo esc_url( $registrations_url );?>">
				<?php esc_html_e('Registrations', 'doccure'); ?>
			</a>
		</li>
		<?php if(!empty($gallery_option)){?>
			<li class="nav-item">
				<a class="<?php echo !empty( $mode ) && $mode === 'gallery' ? 'active' : '';?>" href="<?php echo esc_url( $gallery_url );?>">
					<?php esc_html_e('Gallery', 'doccure'); ?>
				</a>
			</li>
		<?php } ?>	
	</ul>
</div>

