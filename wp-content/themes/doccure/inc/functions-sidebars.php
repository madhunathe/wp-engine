<?php
/**
 * doccure Sidebars
 *
 * @package doccure
 */
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Register widget area.
 *
 * @since 1.0.0
 */
function doccure_widgets_init()
{
    register_sidebar(
        array(
            'name' => esc_html__('Blog Sidebar', 'doccure'),
            'id' => 'sidebar-1',
            'description' => esc_html__('Blog Sidebar.', 'doccure'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h6 class="widget-title">',
            'after_title' => '</h6>',
        )
    );
    // Footer sidebars.
    register_sidebar(
        array(
            'name' => esc_html__('Footer column 1', 'doccure'),
            'id' => 'footer-column-1',
            'description' => esc_html__('Add widgets here.', 'doccure'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h6 class="widget-title">',
            'after_title' => '</h6>',
        )
    );
    register_sidebar(
        array(
            'name' => esc_html__('Footer column 2', 'doccure'),
            'id' => 'footer-column-2',
            'description' => esc_html__('Add widgets here.', 'doccure'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h6 class="widget-title">',
            'after_title' => '</h6>',
        )
    );
    register_sidebar(
        array(
            'name' => esc_html__('Footer column 3', 'doccure'),
            'id' => 'footer-column-3',
            'description' => esc_html__('Add widgets here.', 'doccure'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h6 class="widget-title">',
            'after_title' => '</h6>',
        )
    );
    register_sidebar(
        array(
            'name' => esc_html__('Footer column 4', 'doccure'),
            'id' => 'footer-column-4',
            'description' => esc_html__('Add widgets here.', 'doccure'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h6 class="widget-title">',
            'after_title' => '</h6>',
        )
    );

    register_sidebar(
        array(
            'name' => esc_html__('Footer column 5', 'doccure'),
            'id' => 'footer-column-5',
            'description' => esc_html__('Add widgets here.', 'doccure'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h6 class="widget-title">',
            'after_title' => '</h6>',
        )
    );
    register_sidebar(
        array(
            'name' => esc_html__('Page Sidebar', 'doccure'),
            'id' => 'page-sidebar',
            'description' => esc_html__('Add widgets here.', 'doccure'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h6 class="widget-title">',
            'after_title' => '</h6>',
        )
    );
    
    register_sidebar(
        array(
            'name' => esc_html__('Homefour Firstarea', 'doccure'),
            'id' => 'homefourpage-area',
            'description' => esc_html__('Add widgets here.', 'doccure'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h6 class="widget-title">',
            'after_title' => '</h6>',
        )
    );

    register_sidebar(
        array(
            'name' => esc_html__('Homefour lastarea', 'doccure'),
            'id' => 'homefourpage-lastarea',
            'description' => esc_html__('Add widgets here.', 'doccure'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h6 class="widget-title">',
            'after_title' => '</h6>',
        )
    );

    register_sidebar(
        array(
            'name' => esc_html__('Homefive secondarea', 'doccure'),
            'id' => 'homefivepage-secondarea',
            'description' => esc_html__('Add widgets here.', 'doccure'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h6 class="widget-title">',
            'after_title' => '</h6>',
        )
    ); 

    register_sidebar(
        array(
            'name' => esc_html__('Homefive thirdarea', 'doccure'),
            'id' => 'homefivepage-thirdarea',
            'description' => esc_html__('Add widgets here.', 'doccure'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h6 class="widget-title">',
            'after_title' => '</h6>',
        )
    ); 

    register_sidebar(
        array(
            'name' => esc_html__('Homefive fourtharea', 'doccure'),
            'id' => 'homefivepage-fourtharea',
            'description' => esc_html__('Add widgets here.', 'doccure'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h6 class="widget-title">',
            'after_title' => '</h6>',
        )
    );
}
add_action('widgets_init', 'doccure_widgets_init');
/**
 * Functiion that generates the template class
 *
 * @since 1.0.0
 */
function doccure_grid_column_classes()
{
    $sidebar_position = 'right-sidebar';
    $current_sidebar = doccure_get_current_sidebar();
    $sidebar_position = doccure_sidebar_position();
    $blog_details_style = doccure_get_option('blog_details_style', 'style-1');
    if (is_active_sidebar($current_sidebar)) {
        if ('left-sidebar' === $sidebar_position) {
            $grid_classes = 'col-md-8 order-md-2';
        } elseif ('right-sidebar' === $sidebar_position) {
            $grid_classes = 'col-md-8';
        } else {
            $grid_classes = 'col-12';
        }
    } else {
        $grid_classes = 'col-12';
    }
    return apply_filters('doccure/sidebar/grid_classes', $grid_classes);
}
/**
 * Get the current sidebar.
 *
 * @since 1.0.0
 */
function doccure_get_current_sidebar()
{
    $sidebar_position = doccure_sidebar_position();
    if ('full-width' === $sidebar_position || '' === $sidebar_position) {
        return false;
    }
    if (is_page()) {
        $current_sidebar = 'page-sidebar';
    } elseif (is_search()) {
        $current_sidebar = 'sidebar-1';
    } elseif (is_post_type_archive('portfolio')) {
        $current_sidebar = 'portfolio-sidebar';
    } elseif(is_singular('portfolio')) {
      $current_sidebar = 'portfolio-details-sidebar';
    } elseif (is_post_type_archive('service')) {
        $current_sidebar = 'service-sidebar';
    } elseif(is_singular('service')) {
      $current_sidebar = 'service-details-sidebar';
    } elseif (function_exists('is_shop') && is_shop()) {
        $current_sidebar = 'shop-sidebar';
    } elseif (is_home() || is_archive() || is_singular('post')) {
        $current_sidebar = 'sidebar-1';
    } else {
        $current_sidebar = 'sidebar-1';
    }
    return apply_filters('doccure/sidebar/current_sidebar', $current_sidebar);
}
/**
 * Get the current sidebar position.
 *
 * @since 1.0.0
 */
function doccure_sidebar_position()
{
    // Current page ID
    $current_id = doccure_get_page_id();
    // Possible sidebar positions
    $avaiable_sidebar_positions = array('full-width', 'left-sidebar', 'right-sidebar');
    // Page meta
    $page_settings = $current_id ? get_post_meta($current_id, 'doccure_page_settings', true) : '';
    $sidebar_custom_position = isset($page_settings['doccure_page_sidebar_position']) ? $page_settings['doccure_page_sidebar_position'] : '';
    // Default sidebar position value
    $sidebar_position = 'right-sidebar';
    if (in_array($sidebar_custom_position, $avaiable_sidebar_positions)) {
        $sidebar_position = $sidebar_custom_position;
    } else {
        if (is_page()) {
            $sidebar_position = doccure_get_option('page_sidebar', $sidebar_position);
        } elseif (is_search()) {
            $sidebar_position = 'right-sidebar';
        } elseif (is_post_type_archive('portfolio')) {
            $sidebar_position = doccure_get_option('portfolio_sidebar', $sidebar_position);
        } elseif(is_singular('portfolio')) {
            $sidebar_position = doccure_get_option('portfolio_details_sidebar', $sidebar_position);
        } elseif (is_post_type_archive('service')) {
            $sidebar_position = doccure_get_option('service_sidebar', $sidebar_position);
        } elseif(is_singular('service')) {
            $sidebar_position = doccure_get_option('service_details_sidebar', $sidebar_position);
        } elseif (function_exists('is_shop') && is_shop()) {
            $sidebar_position = doccure_get_option('shop-sidebar', $sidebar_position);
        } elseif (is_home() || is_archive() || is_singular('post')) {
            $sidebar_position = doccure_get_option('blog_sidebar', $sidebar_position);
        }
    }
    return apply_filters('doccure/sidebar/sidebar_position', $sidebar_position);
}
