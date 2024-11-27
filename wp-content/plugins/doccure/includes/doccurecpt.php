<?php
 
function doccure_posttype() {
  
    register_post_type( 'services',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Services' ),
                'singular_name' => __( 'Service' )
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title','editor','thumbnail'),
            'rewrite' => array('slug' => 'services'),
            'show_in_rest' => true,
  
        )
    );
    register_post_type( 'testimonials',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Testimonial' ),
                'singular_name' => __( 'testimonials' )
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title','editor','thumbnail','custom-fields','excerpt'),
            'rewrite' => array('slug' => 'testimonial'),
            'show_in_rest' => true,
  
        )
    );
    register_post_type( 'portfolio',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Portfolio' ),
                'singular_name' => __( 'Portfolio' )
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title','editor','thumbnail'),
            'rewrite' => array('slug' => 'portfolio'),
            'show_in_rest' => true,
  
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'doccure_posttype' );
 