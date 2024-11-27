<?php
/**
 * Elementor Page buider base
 *
 * This file will include all global settings which will be used in all over the plugin,
 * It have gatter and setter methods
 *
 * @link              https://themeforest.net/user/dreamstechnologies/portfolio
 * @since             1.0.0
 * @package           doccure
 *
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die('No kiddies please!');
}

/**
 * @prepare Custom taxonomies array
 * @return array
 */
function elementor_get_taxonomies($post_type = 'post', $taxonomy = 'category', $hide_empty = 0, $dataType = 'input') {
	$args = array(
		'type' 			=> $post_type,
		'child_of'  	=> 0,
		'parent' 		=> '',
		'orderby' 		=> 'name',
		'order' 		=> 'ASC',
		'hide_empty' 	=> $hide_empty,
		'hierarchical' 	=> 1,
		'exclude' 		=> '',
		'include' 		=> '',
		'number' 		=> '',
		'taxonomy' 		=> $taxonomy,
		'pad_counts' 	=> false
	);

	$categories = get_categories($args);

	if ($dataType == 'array') {
		return $categories;
	}

	$custom_Cats = array();

	if (isset($categories) && !empty($categories)) {
		foreach ($categories as $key => $value) {
			$custom_Cats[$value->term_id] = $value->name;
		}
	}

	return $custom_Cats;
} 
 
 