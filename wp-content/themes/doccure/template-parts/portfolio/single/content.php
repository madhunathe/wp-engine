<?php
/**
 * Template part for displaying portfolio details
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package doccure
 * @since 2.1.0
 */
$portfolio_details_style = doccure_get_option('portfolio-details-style', 'style-1');
get_template_part('template-parts/portfolio/single/styles/' . $portfolio_details_style);
