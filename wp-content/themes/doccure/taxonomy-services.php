<?php
/**
 *
 * The template used for displaying default services result
 *
 * @package   doccure
 * @since 1.0
 */
global $wp_query;
get_header();
get_template_part("directory/doctor", "search");
get_footer();