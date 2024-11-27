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
global $current_user, $wp_roles, $userdata, $doccure_options;
$user_identity 	 = $current_user->ID;
$linked_profile  = doccure_get_linked_profile_id($user_identity);

$first_name 	= get_user_meta($user_identity, 'first_name', true);
$last_name 		= get_user_meta($user_identity, 'last_name', true);


$post_id 		= $linked_profile;
$user_meta		= doccure_get_post_meta( $post_id );
$name_bases		= doccure_get_name_bases('','doctor');

$display_name	= get_the_title( $post_id );
$display_name	= !empty( $display_name ) ? $display_name : '';
$am_name_base	= !empty( $user_meta['am_name_base'] ) ? $user_meta['am_name_base'] : '';
$am_sub_heading	= !empty( $user_meta['am_sub_heading'] ) ? $user_meta['am_sub_heading'] : '';
$am_first_name	= !empty( $user_meta['am_first_name'] ) ? $user_meta['am_first_name'] : '';
$am_last_name	= !empty( $user_meta['am_last_name'] ) ? $user_meta['am_last_name'] : '';

$enable_options		= !empty($doccure_options['doctors_contactinfo']) ? $doccure_options['doctors_contactinfo'] : '';
$web_url			= !empty( $user_meta['am_web_url'] ) && !empty($enable_options)  && $enable_options === 'yes' ? $user_meta['am_web_url'] : '';
$base_name_disable		= !empty( $doccure_options['base_name_disable'] ) ? $doccure_options['base_name_disable'] : '';

$am_starting_price		= !empty( $user_meta['am_starting_price'] ) ? $user_meta['am_starting_price'] : '';
$am_short_description	= !empty( $user_meta['am_short_description'] ) ? $user_meta['am_short_description'] : '';
$mobile_number			= !empty( $user_meta['am_mobile_number'] ) ? $user_meta['am_mobile_number'] : '';
$am_gender				= !empty( $user_meta['am_gender'] ) ? $user_meta['am_gender'] : '';

$post_object 	= get_post( $post_id );
$content 	 	= !empty($post_object->post_content) ?  $post_object->post_content : '';
?>
<div class="dc-yourdetails dc-tabsinfo dashboard_profileinfo">
	<div class="dc-tabscontenttitle mt-0">
		<h3><?php esc_html_e('Your Details', 'doccure'); ?></h3>
	</div>
	<div class="dc-formtheme dc-userform">
		<fieldset>
			<div class="row">
				
 				<?php if( !empty($base_name_disable) ){?>
					<div class="col-xl-2">
				<div class="form-group form-group-half">
				<label><?php echo esc_html('Title', 'doccure'); ?></label>
					<span class="dc-select">
						<select name="am_name_base">
						
							<option value="" disabled=""><?php esc_html_e('Select one', 'doccure'); ?></option>
							<?php if( !empty( $name_bases ) ) {?>
								<?php foreach ( $name_bases as $key => $name_base ) { ?>
									<option value="<?php echo esc_attr( $key );?>" <?php selected( $am_name_base, $key, true); ?>><?php echo esc_html($name_base); ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</span>
				</div>
				</div>
			<?php }?>
			<div class="col-xl-2">
			<div class="form-group form-group-half toolip-wrapo">
			<label><?php echo esc_html('First Name', 'doccure'); ?></label>
				<input type="text" name="am_first_name" class="form-control" value="<?php echo esc_attr( $first_name ); ?>" placeholder="<?php esc_attr_e('First Name', 'doccure'); ?>">
				<?php do_action('doccure_get_tooltip','element','am_first_name');?>
			</div>	
			</div>

			<div class="col-xl-4">

			<div class="form-group form-group-half toolip-wrapo">
			<label><?php echo esc_html('Last Name', 'doccure'); ?></label>
				<input type="text" value="<?php echo esc_attr( $last_name ); ?>" name="am_last_name" class="form-control" placeholder="<?php esc_attr_e('Last Name', 'doccure'); ?>">
				<?php do_action('doccure_get_tooltip','element','am_last_name');?>
			</div>

			</div>

			<div class="col-xl-4">

			<div class="form-group toolip-wrapo">
			<label><?php echo esc_html('Display Name', 'doccure'); ?></label>
				<input type="text" name="display_name" class="form-control" value="<?php echo esc_attr( $display_name ); ?>" placeholder="<?php esc_attr_e('Display Name', 'doccure'); ?>">
				<?php do_action('doccure_get_tooltip','element','display_name');?>
			</div>
			
			</div>

				
			</div>
			<div class="row">
			<div class="col-xl-4">
			<div class="form-group form-group-half">
			<label><?php echo esc_html('Gender', 'doccure'); ?></label>
				<span class="dc-select">
					<select name="am_gender">
						<option value="" disabled=""><?php esc_html_e('Select gender', 'doccure'); ?></option>
						<option value="male" <?php selected( $am_gender, 'male'); ?>><?php esc_html_e('Male','doccure');?></option>
						<option value="female" <?php selected( $am_gender, 'female'); ?>><?php esc_html_e('Female','doccure');?></option>
						<option value="all" <?php selected( $am_gender, 'all'); ?>><?php esc_html_e('Others','doccure');?></option>
					</select>
				</span>
			</div>
			</div>
			<div class="col-xl-4">
			<div class="form-group form-group-half toolip-wrapo">
			<label><?php echo esc_html('Designation', 'doccure'); ?></label>
				<input type="text" value="<?php echo esc_attr( $am_sub_heading ); ?>" name="am_sub_heading" class="form-control" placeholder="<?php esc_attr_e('Designation', 'doccure'); ?>">
				<?php do_action('doccure_get_tooltip','element','am_sub_heading');?>
			</div>
			</div>	
			<div class="col-xl-4">
			<div class="form-group toolip-wrapo">
			<label><?php echo esc_html('Mobile Number', 'doccure'); ?></label>
				<input type="text" name="am_mobile_number" class="form-control" value="<?php echo esc_attr( $mobile_number ); ?>" placeholder="<?php esc_attr_e('Personal Mobile Number', 'doccure'); ?>">
				<?php do_action('doccure_get_tooltip','element','am_phone_numbers');?>
			</div>
			</div>
			</div>
			
			<div class="form-group form-group-half toolip-wrapo">
			    <label><?php echo esc_html('Starting Price', 'doccure'); ?></label>
				<input type="text" name="am_starting_price" class="form-control" value="<?php echo esc_attr( $am_starting_price ); ?>" placeholder="<?php esc_attr_e('Starting Price', 'doccure'); ?>">
				<?php do_action('doccure_get_tooltip','element','am_starting_price');?>
			</div>
			
			<?php if( !empty($enable_options) && $enable_options === 'yes' ){ ?>
				<div class="form-group toolip-wrapo">
				<label><?php echo esc_html('Web url', 'doccure'); ?></label>
					<input type="text" name="am_web_url" class="form-control" value="<?php echo esc_attr( $web_url ); ?>" placeholder="<?php esc_attr_e('Web url', 'doccure'); ?>">
					<?php do_action('doccure_get_tooltip','element','am_web_url');?>
				</div>
			<?php } ?>
			<div class="form-group">
			<label><?php echo esc_html('Description', 'doccure'); ?></label>
				<textarea name="content" class="form-control" placeholder="<?php esc_attr_e('Description', 'doccure'); ?>"><?php echo do_shortcode( $content ); ?></textarea>
			</div>
		</fieldset>
	</div>
</div>
<?php 
	if( !empty($enable_options) && $enable_options === 'yes' ){
		get_template_part('directory/front-end/templates/dashboard', 'phone_numbers');
	}

	get_template_part('directory/front-end/templates/dashboard', 'languages');
