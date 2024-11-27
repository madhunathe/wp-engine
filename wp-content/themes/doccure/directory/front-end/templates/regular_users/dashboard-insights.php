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
$booking_option	= doccure_theme_option();
?>
<div class="dc-haslayout dc-jobpostedholder">
	<?php 
		get_template_part('directory/front-end/templates/dashboard', 'statistics-messages'); 
		get_template_part('directory/front-end/templates/doctors/dashboard', 'statistics-saved-items');
		if(empty($booking_option)){
			get_template_part('directory/front-end/templates/dashboard', 'statistics-appointments');
		}
	?>
</div>