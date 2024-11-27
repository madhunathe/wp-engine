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
global $current_user, $wp_roles, $userdata, $post,$doccure_options;
$dir_latitude		= !empty($doccure_options['dir_latitude']) ? $doccure_options['dir_latitude'] : '-34';
$dir_longitude		= !empty($doccure_options['dir_longitude']) ? $doccure_options['dir_longitude'] : '51';
$post_id  		= doccure_get_linked_profile_id($current_user->ID);
$address		= get_post_meta( $post_id , '_address',true );
$address		= !empty( $address ) ? $address : '';
$latitude		= get_post_meta( $post_id , '_latitude',true );
$latitude		= !empty( $latitude ) ? $latitude : $dir_latitude;
$longitude		= get_post_meta( $post_id , '_longitude',true );
$longitude		= !empty( $longitude ) ? $longitude : $dir_longitude;

$location 		= apply_filters('doccure_get_tax_query',array(),$post_id,'locations','');
$location 		= !empty( $location[0]->term_id ) ? $location[0]->term_id : '';
?>
<div class="dc-location dc-tabsinfo">
	 
	<div class="dc-formtheme dc-userform">
		<fieldset>
			<div class="form-group form-group-half">
			<label><?php esc_html_e('Your Location', 'doccure'); ?></label>
				<span class="dc-select">
					<?php do_action('doccure_get_locations_list','location',$location);?>
				</span>
			</div>
			<div class="form-group loc-icon form-group-half">
			<label><?php esc_html_e('Your Address', 'doccure'); ?></label>
				<input type="text" id="location-address-0" name="address" class="form-control" value="<?php echo esc_attr( $address ); ?>" placeholder="<?php esc_attr_e('Your Address', 'doccure'); ?>">
			</div>
		</fieldset>
	</div>
</div>
<?php
	$script = "jQuery(document).ready(function (e) {
				jQuery.doccure_init_profile_map(0,'location-pickr-map', ". esc_js($latitude) . "," . esc_js($longitude) . ");
			});";
	wp_add_inline_script('doccure_maps', $script, 'after');