<?php 
/**
 *
 * The template part for displaying the template to display email settings
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user;
$user_identity 	 = $current_user->ID;
?>
<div class="dc-emailnotiholder tab-pane active fade show" id="dc-emailnoti">
	<div class="dc-emailnoti">
		<div class="dc-tabscontenttitle mt-0">
			<h3><?php esc_html_e('Manage Email Notifications', 'doccure'); ?></h3>
		</div>
		<div class="dc-settingscontent dc-sidepadding">
			<div class="dc-description">
				<p><?php esc_html_e('All the emails will be sent to the below email address','doccure');?></p>
			</div>
			<div class="dc-formtheme dc-userform mb-0">
				<fieldset>
					<div class="form-group form-disabeld">
						<input type="text" name="useremail" class="form-control" placeholder="<?php echo esc_attr($current_user->user_email);?>">
					</div>
				</fieldset>
			</div>
		</div>
	</div>
</div>
