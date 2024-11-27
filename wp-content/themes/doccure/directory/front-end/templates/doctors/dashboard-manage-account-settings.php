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
$user_identity 	 = $current_user->ID;
$linked_profile  = doccure_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;

$post_type		= get_post_type($post_id);
$settings		= doccure_get_account_settings($post_type);
?>
<div class="dc-securityhold tab-pane active fade show" id="dc-security">
	<div class="dc-securitysettings dc-tabsinfo">
		<div class="dc-tabscontenttitle  mt-0">
			<h3><?php esc_html_e('Account Security &amp; Settings','doccure');?></h3>
		</div>
		<div class="dc-settingscontent dc-sidepadding">
			<?php if( !empty( $settings ) ){?>
				<ul class="dc-accountinfo mb-0">
					<?php 
					foreach( $settings as $key => $value ){
						$db_val 	= get_post_meta($linked_profile, $key, true);
						$db_val 	= !empty( $db_val ) ?  $db_val : 'off';
						?>
						<li>
							<div class="dc-on-off">
								<input type="hidden" name="settings[<?php echo esc_attr($key); ?>]" value="off">
								<input type="checkbox" <?php checked( $db_val, 'on' ); ?>  value="on" id="<?php echo esc_attr( $key );?>" name="settings[<?php echo esc_attr( $key );?>]">
								<label for="<?php echo esc_attr( $key );?>"><i></i></label>
							</div>
							<span><?php echo esc_html( $value );?></span>
						</li>
					<?php }?>
				</ul>
			<?php }?>
		</div>
	</div>
</div>