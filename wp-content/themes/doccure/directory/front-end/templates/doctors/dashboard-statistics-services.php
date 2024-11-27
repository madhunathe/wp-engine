<?php
/**
 *
 * The template part for displaying the dashboard current balance for doctor
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user,$doccure_options;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doccure_get_linked_profile_id($user_identity);
$icon					= 'lnr lnr-list';
$service_spec		= !empty( $doccure_options['service_spec']['url'] ) ? $doccure_options['service_spec']['url'] : '';
$published_articles		= count_user_posts($user_identity);
?>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="dc-insightsitem dc-dashboardbox">
		<figure class="dc-userlistingimg">
			<?php if( !empty($service_spec) ) {?>
				<img src="<?php echo esc_url($service_spec);?>" alt="<?php esc_attr_e('Specialties and Services', 'doccure'); ?>">
			<?php } else {?>
					<span class="<?php echo esc_attr($icon);?>"></span>
			<?php }?>
		</figure>
		<div class="dc-insightdetails">
			<div class="dc-title">
				<h3><?php esc_html_e('Specialties and Services', 'doccure'); ?></h3>
				<a href="<?php doccure_Profile_Menu::doccure_profile_menu_link('specialities', $user_identity); ?>"><?php esc_html_e('Specialties and Services', 'doccure'); ?></a>
			</div>													
		</div>	
	</div>
</div>