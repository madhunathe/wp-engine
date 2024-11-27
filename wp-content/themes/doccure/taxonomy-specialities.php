<?php
/**
 *
 * The template used for displaying default specialities result
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @since 1.0
 */
global $wp_query;
get_header();
get_template_part("directory/doctor", "search");
get_footer();