<?php
/**
 *
 * The template part for displaying the dashboard statistics
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user,$doccure_options;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doccure_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-bubble';

$appointments_img	= !empty( $doccure_options['total_appointments']['url'] ) ? $doccure_options['total_appointments']['url'] : '';

$args = array(
			'posts_per_page' 	=> -1,
			'post_type' 		=> 'booking',
			'author'			=> $user_identity,
			'post_status' 		=> array('publish','pending'),
			'suppress_filters'  => false
		);
$query 		= new WP_Query( $args );
$count_post = $query->found_posts;
?>
