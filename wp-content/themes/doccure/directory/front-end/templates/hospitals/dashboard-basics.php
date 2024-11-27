<?php 
/**
 *
 * The template part for displaying the user profile basics
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post;

$user_identity 	 	= $current_user->ID;
$linked_profile  	= doccure_get_linked_profile_id($user_identity);
$first_name 		= get_user_meta($user_identity, 'first_name', true);
$last_name 			= get_user_meta($user_identity, 'last_name', true);
$post_id 			= $linked_profile;
$user_meta			= doccure_get_post_meta( $post_id );

$display_name		= get_the_title( $post_id );
$display_name		= !empty( $display_name ) ? $display_name : '';
$am_sub_heading		= !empty( $user_meta['am_sub_heading'] ) ? $user_meta['am_sub_heading'] : '';
$am_first_name		= !empty( $user_meta['am_first_name'] ) ? $user_meta['am_first_name'] : '';
$am_last_name		= !empty( $user_meta['am_last_name'] ) ? $user_meta['am_last_name'] : '';
$am_availability	= !empty( $user_meta['am_availability'] ) ? $user_meta['am_availability'] : '';
$am_other_time		= !empty( $user_meta['am_other_time'] )? $user_meta['am_other_time'] : '';
$web_url			= !empty( $user_meta['am_web_url'] )? $user_meta['am_web_url'] : '';
$mobile_number		= !empty( $user_meta['am_mobile_number'] ) ? $user_meta['am_mobile_number'] : '';

$am_week_days			= !empty( $user_meta['am_week_days'] ) ? $user_meta['am_week_days'] : '';
$am_short_description	= !empty( $user_meta['am_short_description'] ) ? $user_meta['am_short_description'] : '';

$post_object 	= get_post( $post_id );
$content 	 	= $post_object->post_content;

$days		= doccure_get_week_array();
$checked	= '';
?>
<div class="dc-yourdetails dc-tabsinfo dc-hospital-basic">
	<div class="dc-tabscontenttitle">
		<h3><?php esc_html_e('Your Details Hospitals', 'doccure'); ?></h3>
	</div>
	<div class="dc-formtheme dc-userform">
		<fieldset>
			<div class="form-group form-group-half toolip-wrapo">
				<input type="text" name="am_first_name" class="form-control" value="<?php echo esc_attr( $first_name ); ?>" placeholder="<?php esc_attr_e('First name', 'doccure'); ?>">
				<?php do_action('doccure_get_tooltip','element','am_first_name');?>
			</div>			
			<div class="form-group form-group-half toolip-wrapo">
				<input type="text" value="<?php echo esc_attr( $last_name ); ?>" name="am_last_name" class="form-control" placeholder="<?php esc_attr_e('Last Name', 'doccure'); ?>">
				<?php do_action('doccure_get_tooltip','element','am_last_name');?>
			</div>
			<div class="form-group form-group-half toolip-wrapo">
				<input type="text" name="display_name" class="form-control" value="<?php echo esc_attr( $display_name ); ?>" placeholder="<?php esc_attr_e('Display name', 'doccure'); ?>">
				<?php do_action('doccure_get_tooltip','element','display_name');?>
			</div>
			<div class="form-group form-group-half toolip-wrapo">
				<input type="text" value="<?php echo esc_attr( $am_sub_heading ); ?>" name="am_sub_heading" class="form-control" placeholder="<?php esc_attr_e('Sub Heading', 'doccure'); ?>">
				<?php do_action('doccure_get_tooltip','element','am_sub_heading');?>
			</div>
			<div class="form-group">
				<input type="text" value="<?php echo esc_attr( $am_short_description ); ?>" name="am_short_description" class="form-control" placeholder="<?php esc_attr_e('Short Description', 'doccure'); ?>">
			</div>
			<div class="form-group">
				<input type="text" name="am_mobile_number" class="form-control" value="<?php echo esc_attr( $mobile_number ); ?>" placeholder="<?php esc_attr_e('Personal mobile number', 'doccure'); ?>">
			</div>
			<div class="form-group">
				<textarea name="content" class="form-control" placeholder="<?php esc_attr_e('Description', 'doccure'); ?>"><?php echo sanitize_textarea_field( $content ); ?></textarea>
			</div>
			<div class="form-group toolip-wrapo">
				<input type="text" name="am_web_url" class="form-control" value="<?php echo esc_attr( $web_url ); ?>" placeholder="<?php esc_attr_e('Web url', 'doccure'); ?>">
				<?php do_action('doccure_get_tooltip','element','am_web_url');?>
			</div>

		</fieldset>
	</div>
</div>
<div class="dc-working-time dc-tabsinfo">
	<div class="dc-tabscontenttitle">
		<h3><?php esc_html_e('Working time', 'doccure'); ?></h3>
	</div>
	<div class="dc-formtheme dc-userform">
		<fieldset>
			<div class="form-group form-group-half dc-radio-holder">
				<span class="dc-radio">
					<input id="dc-spaces1" class="dc-spaces" type="radio" name="am_availability" value="yes" <?php if( !empty( $am_availability ) && $am_availability ==='yes') echo 'checked';?>>
					<label for="dc-spaces1"><?php esc_html_e('24/7 working time','doccure');?></label>
				</span>
				<span class="dc-radio">
					<input id="dc-others" class="dc-spaces" type="radio" name="am_availability" value="<?php echo esc_attr('others');?>" <?php if( !empty( $am_availability ) && $am_availability ==='others') echo 'checked';?>>
					<label for="dc-others"><?php esc_html_e('Others','doccure');?></label>
				</span>
			</div>
			<div class="form-group form-group-half dc-others <?php if( $am_availability != 'others' ) echo 'dc-display-none';?>">
				<input type="text" name="am_other_time" class="form-control" placeholder="<?php esc_attr_e('Availability Text','doccure');?>" value="<?php echo esc_attr( $am_other_time );?>">
			</div>
		</fieldset>
	</div>
</div>
<div class="dc-working-days dc-tabsinfo">
	<div class="dc-tabscontenttitle">
		<h3><?php esc_html_e('Days, I offer my services','doccure'); ?></h3>
	</div>
	<div class="dc-formtheme dc-userform">
		<fieldset class="dc-offer-holder">
			<div>
				<?php 
					foreach( $days as $key => $val ) {
						if( !empty( $am_week_days ) && in_array($key,$am_week_days) ) {
							$checked	= 'checked';
						} else {
							$checked	= '';
						}
						
						$day_name	= doccure_get_week_keys_translation($key);
					?>
					<span class="dc-checkbox">
						<input id="dc-<?php echo esc_attr( $key );?>" type="checkbox" <?php echo esc_attr( $checked );?> name="am_week_days[]" value="<?php echo esc_html( $key );?>">
						<label for="dc-<?php echo esc_attr( $key );?>"><?php echo esc_html( $day_name );?></label>
					</span>
				<?php  }?>
			</div>
		</fieldset>
	</div>
</div>
<?php get_template_part('directory/front-end/templates/dashboard', 'location');?>
<?php get_template_part('directory/front-end/templates/dashboard', 'phone_numbers');?>
<?php get_template_part('directory/front-end/templates/dashboard', 'languages');?>