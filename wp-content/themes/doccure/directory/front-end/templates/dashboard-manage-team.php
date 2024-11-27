<?php
/**
 *
 * The template part for displaying the dashboard manage team
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user,$doccure_options;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doccure_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-users';

$manage_team_img	= !empty( $doccure_options['manage_team_img']['url'] ) ? $doccure_options['manage_team_img']['url'] : '';
?>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="dc-insightsitem dc-dashboardbox">
		<figure class="dc-userlistingimg">
			<?php if( !empty( $manage_team_img ) ) {?>
				<img src="<?php echo esc_url( $manage_team_img );?>" alt="<?php esc_attr_e('Manage Team', 'doccure'); ?>">
			<?php } else {?>
					<span class="<?php echo esc_attr($icon);?>"></span>
			<?php }?>
		</figure>
		<div class="dc-insightdetails">
			<div class="dc-title">
				<h3><?php esc_html_e('Manage Team', 'doccure'); ?></h3>
				<a href="<?php doccure_Profile_Menu::doccure_profile_menu_link('team', $user_identity,'','manage'); ?>">
					<?php esc_html_e('Click To View', 'doccure'); ?>
				</a>
			</div>													
		</div>	
	</div>
</div>