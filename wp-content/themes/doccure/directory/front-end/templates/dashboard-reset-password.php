<?php 
/**
 *
 * The template part for displaying the template reset password
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
?>
<div class="dc-passwordholder tab-pane active fade show" id="dc-password">
	<div class="dc-changepassword">
		<div class="dc-tabscontenttitle mt-0">
			<h3><?php esc_html_e('Change Your Password', 'doccure'); ?></h3>
		</div>
		<div class="dc-formtheme dc-userform dc-sidepadding">
			<fieldset>
				<div class="form-group form-group-half">
					<input type="password" name="password" class="form-control" placeholder="<?php esc_attr_e('Last Remember Password', 'doccure'); ?>">
				</div>
				<div class="form-group form-group-half">
					<input type="password" name="retype" class="form-control" placeholder="<?php esc_attr_e('New Password', 'doccure'); ?>">
				</div>
			</fieldset>
		</div>
	</div>
</div>

