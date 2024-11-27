<?php
/**
 *
 * The template part for displaying social profile 
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */

global $current_user,$doccure_options;

$user_identity 	 	= $current_user->ID;
$linked_profile  	= doccure_get_linked_profile_id($user_identity);
$user_type			= apply_filters('doccure_get_user_type', $user_identity );
$social_links	= !empty( $doccure_options['social_links'] ) ? $doccure_options['social_links'] : '';
$am_socials		= doccure_get_post_meta( $linked_profile, 'am_socials');

if(!empty($social_links) && $social_links === 'yes'){	
	$social_settings    = function_exists('doccure_get_social_media_icons_list') ? doccure_get_social_media_icons_list('no') : array();
?>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-9">		
	<form class="dc-social-profile" method="post">	
	<?php get_template_part('directory/front-end/templates/doctors/dashboard', 'profile-settings-tabs'); ?>
		<div class="dc-dashboardbox dc-dashboardtabsholder">
			
			<div class="dc-tabscontent tab-content">
				<div class="dc-yourdetails dc-tabsinfo">
					<div class="dc-tabscontenttitle">
						<h3><?php esc_html_e('Social profiles', 'doccure'); ?></h3>
					</div>
					<div class="dc-formtheme dc-userform social-profiles-wrap">
						<fieldset>
							<?php
							if(!empty($social_settings)) {
								foreach($social_settings as $key => $val ) {
									$icon		= !empty( $val['icon'] ) ? $val['icon'] : '';
									$classes	= !empty( $val['classses'] ) ? $val['classses'] : '';
									$placeholder	= !empty( $val['placeholder'] ) ? $val['placeholder'] : '';
									$color			= !empty( $val['color'] ) ? $val['color'] : '#484848';
									$social_url		= '';
									$social_url		= !empty($am_socials[$key]) ? $am_socials[$key] : '';
									
									$is_enabled		= !empty($doccure_options[$key]) ? $doccure_options[$key] : '';
									if(!empty($is_enabled)){
									?>
										<div class="form-group form-group-half  dc-inputwithicon <?php echo esc_attr( $classes );?>">
											<i class="dc-icon <?php echo esc_attr( $icon );?>" style="color:<?php echo esc_attr( $color );?> !important"></i>
											<input type="text" name="basics[<?php echo esc_attr($key);?>]" class="form-control" value="<?php echo esc_attr($social_url); ?>" placeholder="<?php echo esc_attr($placeholder); ?>">
										</div>
								<?php }} ?>
							<?php } ?>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
		<div class="dc-updatall">
			<i class="ti-announcement"></i>
			<a class="dc-btn dc-update-social-link" data-id="<?php echo esc_attr( $user_identity ); ?>" data-post="<?php echo esc_attr( $linked_profile ); ?>" href="javascript:;"><?php esc_html_e('Save Changes', 'doccure'); ?></a>
		</div>	
	</form>		
</div>
<?php }