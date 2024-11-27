<?php
/**
 *
 * The template used for displaying default location result
 *
 * @package   doccure
 */
global $wp_query;
get_header();
get_template_part("directory/doctor", "search");
get_footer();