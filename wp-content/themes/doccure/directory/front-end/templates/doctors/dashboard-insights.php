<?php
/**
 *
 * The template part for displaying the dashboard.
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user;
$show_earning	= doccure_theme_option('show_earning');
$listing_type	= doccure_theme_option('listing_type');
?>
<?php	
get_template_part('directory/front-end/templates/doctors/dashboard', 'current-appointment-listing');
get_template_part('directory/front-end/templates/dashboard', 'package-detail');