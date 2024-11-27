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

global $current_user, $doccure_options;

$reference 		 = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : '';
$mode 			 = (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';
$url_identity 	= $current_user->ID;
$doctor_location	= !empty($doccure_options['doctor_location']) ? $doccure_options['doctor_location'] : 'hospitals';

if( apply_filters('doccure_is_appointment_allowed', 'dc_bookings', $url_identity) === true ){
	if(!empty($doctor_location) && $doctor_location === 'both'){?>
	<li class="menu-item-has-children <?php echo esc_attr( $reference === 'appointment' && ( $mode ==='setting' || $mode === 'location-settings' ) ? 'dc-active' : ''); ?>">
		<span class="dc-dropdowarrow"><i class="fa fa-chevron-right"></i></span>
		<a href="javascript:;">
			<i class="far fa-clipboard"></i>
			<span><?php esc_html_e('Available Timings','doccure');?></span>
		</a>
		<ul class="sub-menu" style="display:<?php echo esc_attr( $reference === 'appointment' && ( $mode ==='setting' || $mode === 'location-settings' ) ? 'block' : ''); ?>">
			<li class="<?php echo esc_attr( $reference === 'appointment' && $mode ==='setting' ? 'dc-active' : ''); ?>">
				<a href="<?php doccure_Profile_Menu::doccure_profile_menu_link('appointment', $url_identity,'','setting'); ?>">
					<span><?php esc_html_e('Hospital Settings','doccure');?></span>
				</a>
			</li>
			<li class="<?php echo esc_attr( $reference === 'appointment' && $mode === 'location-settings' ? 'dc-active' : ''); ?>">
				<a href="<?php doccure_Profile_Menu::doccure_profile_menu_link('appointment', $url_identity,'','location-settings'); ?>">
					<span><?php esc_html_e('Clinic Settings','doccure');?></span>
				</a>
			</li>
		</ul>
	</li>
<?php }else if(!empty($doctor_location) && $doctor_location === 'clinic'){?>
	<li class="<?php echo esc_attr( $reference === 'appointment' && $mode ==='location-settings' ? 'dc-active' : ''); ?>">
		<a href="<?php doccure_Profile_Menu::doccure_profile_menu_link('appointment', $url_identity,'','location-settings'); ?>">
			<i class="fas fa-clock"></i>
			<span><?php esc_html_e('Available Timings','doccure');?></span>
		</a>
	</li>
<?php }else if(!empty($doctor_location) && $doctor_location === 'hospitals'){?>
	<li class="<?php echo esc_attr( $reference === 'appointment' && $mode ==='setting' ? 'dc-active' : ''); ?>">
		<a href="<?php doccure_Profile_Menu::doccure_profile_menu_link('appointment', $url_identity,'','setting'); ?>">
			<i class="far fa-clipboard"></i>
			<span><?php esc_html_e('Available Timings','doccure');?></span>
		</a>
	</li>
<?php }
}