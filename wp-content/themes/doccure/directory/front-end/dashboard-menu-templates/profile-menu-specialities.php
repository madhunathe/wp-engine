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

global $current_user, $wp_roles, $userdata, $post;

$reference 		 = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : '';
$mode 			 = (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';
$url_identity 	 = $current_user->ID;
$user_type		 = apply_filters('doccure_get_user_type', $url_identity );

if( !empty( $user_type ) && $user_type === 'regular_users' ){
	//do nohting
} else { ?>
	<li class="<?php echo esc_attr( $reference === 'specialities' ? 'dc-active' : ''); ?>">
		<a href="<?php doccure_Profile_Menu::doccure_profile_menu_link('specialities', $url_identity); ?>">
			<i class="fas fa-briefcase-medical"></i>
			<span><?php esc_html_e('Specialities &amp; Services','doccure');?></span>
		</a>
	</li>
<?php }