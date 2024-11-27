<?php

/**
 *
 * Sidebar Resgister
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @since 1.0
 */
/**
 * @Register widget area.
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
if (!function_exists('doccure_widgets_init')) {

    function doccure_widgets_init() {
        register_sidebar(array(
            'name' 			=> esc_html__('Sidebar', 'doccure'),
            'id' 			=> 'sidebar-1',
            'description' 	=> esc_html__('Default sidebar for home and archive pages.', 'doccure'),
            'before_widget' => '<div id="%1$s" class="dc-widget %2$s widget_categories">',
            'after_widget' 	=> '</div>',
            'before_title' 	=> '<div class="doc-widgetheading"><h2>',
            'after_title' 	=> '</h2></div>',
        ));
		register_sidebar(array(
            'name' 			=> esc_html__('Forum Single', 'doccure'),
            'id' 			=> 'sidebar-forum',
            'description' 	=> esc_html__('Default sidebar for forum single page.', 'doccure'),
            'before_widget' => '<div id="%1$s" class="dc-widget %2$s widget_categories">',
            'after_widget' 	=> '</div>',
            'before_title' 	=> '<div class="doc-widgetheading"><h2>',
            'after_title' 	=> '</h2></div>',
        ));
		register_sidebar(array(
            'name' 			=> esc_html__('Pages', 'doccure'),
            'id' 			=> 'sidebar-pages-1',
            'description' 	=> esc_html__('Default sidebar for home and archive pages.', 'doccure'),
            'before_widget' => '<div id="%1$s" class="dc-widget %2$s widget_categories">',
            'after_widget' 	=> '</div>',
            'before_title' 	=> '<div class="doc-widgetheading"><h2>',
            'after_title' 	=> '</h2></div>',
        ));

		register_sidebar(array(
			'name' 			=> esc_html__('Doctor single page sidebar', 'doccure'),
			'id' 			=> 'doctor-sidebar-right',
			'description' 	=> esc_html__('It will be shown on doctor single sidebar', 'doccure'),
			'before_widget' => '<div id="%1$s" class="%2$s dc-ads-wgdets">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> '<div class="doc-widgetheading"><h2>',
			'after_title'  	=> '</h2></div>',
		));

		register_sidebar(array(
			'name' 			=> esc_html__('Footer section 1', 'doccure'),
			'id' 			=> 'footer-col-1',
			'description' 	=> esc_html__('It will be shown on footer section', 'doccure'),
			'before_widget' => '<div id="%1$s" class="%2$s dc-fcol dc-widgetcontactus dc-widget">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> '<div class="dc-ftitle"><h3>',
			'after_title'  	=> '</h3></div>',
		));
		
		register_sidebar(array(
			'name' 			=> esc_html__('Footer section 2', 'doccure'),
			'id' 			=> 'footer-col-2',
			'description' 	=> esc_html__('It will be shown on footer section', 'doccure'),
			'before_widget' => '<div id="%1$s" class="%2$s dc-fcol dc-flatestad dc-twitter-live-wgdets dc-widgetcontactus dc-widget">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> '<div class="dc-ftitle"><h3>',
			'after_title'  	=> '</h3></div>',
		));
		
		register_sidebar(array(
			'name' 			=> esc_html__('Footer section 3', 'doccure'),
			'id' 			=> 'footer-col-3',
			'description' 	=> esc_html__('It will be shown on footer section', 'doccure'),
			'before_widget' => '<div id="%1$s" class="%2$s dc-fcol dc-newsletterholder dc-widgetcontactus dc-widget">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> '<div class="dc-ftitle"><h3>',
			'after_title'  	=> '</h3></div>',
		));

    }

    add_action('widgets_init', 'doccure_widgets_init');
}