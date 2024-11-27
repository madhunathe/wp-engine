<?php
/**
 * Template part for displaying post details
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package doccure
 * @since 2.0.0
 */
$blog_details_style = doccure_get_option('blog_details_style', 'style-1');
get_template_part('template-parts/post/single/styles/' . $blog_details_style);
