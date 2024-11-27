<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package doccure
 */
$current_sidebar = doccure_get_current_sidebar();
$blog_details_style = doccure_get_option('blog_details_style', 'style-1');
if (!$current_sidebar || !is_active_sidebar($current_sidebar) || ($blog_details_style == 'style-3' && is_singular('post'))) {
    return;
}
?>
<aside id="secondary" class="widget-area sidebar col-sm-12 col-md-4">
    <?php dynamic_sidebar($current_sidebar); ?>
</aside><!-- #secondary -->
