<?php
/**
 * doccure Theme scripts and styles.
 *
 * @package doccure
 */

if (!defined('ABSPATH')) {
    exit;
}

function doccure_load_google_fonts()
{
    $fonts_url = '';
    /* Translators: If there are characters in your language that are not
        * supported by Lora, translate this to 'off'. Do not translate
        * into your own language.
        */
    $poppins = _x('on', 'Poppins font: on or off', 'doccure');
    /* Translators: If there are characters in your language that are not
    * supported by Open Sans, translate this to 'off'. Do not translate
    * into your own language.
    */
    $open_sans = _x('on', 'Poppins font: on or off', 'doccure');
    if ('off' !== $poppins || 'off' !== $open_sans) {
        $font_families = array();

        if ('off' !== $poppins) {
            $font_families[] = 'Poppins:100,200,300,400,500,600,700,800,900&display=swap';
        }
        if ('off' !== $open_sans) {
            $font_families[] = 'Open Sans:300,400,600,700,800&display=swap';
        }
       
        $query_args =
            array(
                'family' => urlencode(implode('|', $font_families)),
            );
        $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
    }
    return $fonts_url;
}



/**
 * Enqueue scripts and styles.
 *
 * @since 1.0.0
 */
function doccure_scripts()
{
    $theme_data = wp_get_theme();
    if (is_child_theme() && is_object($theme_data->parent())) {
        $theme_data = wp_get_theme($theme_data->parent()->template);
    }
    $version = $theme_data->get('Version');

    // 3rd Party Styles
     

     // Theme Scripts
   if ( is_rtl() ) {
    wp_enqueue_style('bootstrap-rtl', get_template_directory_uri() . '/assets/css/bootstrap/bootstrap.rtl.min.css', array(), '5.3.0');
  } else {
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap/bootstrap.min.css', array(), '5.3.0');

  }


    wp_enqueue_style('flaticon', get_template_directory_uri() . '/assets/css/flaticon.css', array(), $version);
    wp_enqueue_style('magnific-popup', get_template_directory_uri() . '/assets/css/magnific-popup.css', array(), '1.1.0');
    wp_enqueue_style('slick', get_template_directory_uri() . '/assets/css/slick.css', array(), '1.0.0');
    wp_enqueue_style('owlcarousel', get_template_directory_uri() . '/assets/css/owl.carousel.min.css', array(), '1.0.0');
    wp_enqueue_style('v4-shims', get_template_directory_uri() . '/assets/css/v4-shims.min.css', array(), '5.11.2');
     wp_enqueue_style('fontawesome', get_template_directory_uri() . '/assets/css/fontawesome/all.min.css', array('v4-shims'), '6.4.0');
    wp_enqueue_style('feather', get_template_directory_uri() . '/assets/css/feather.css', array(), '1.0.0');
    wp_enqueue_style('animate', get_template_directory_uri() . '/assets/css/animate.min.css', array(), '4.1.1');

    // Mapbox CSS
    if(!empty(doccure_get_option('mapbox_access_token_value'))) {
      wp_enqueue_style('mapbox-gl', get_template_directory_uri() . '/assets/css/mapbox-gl.min.css', array(), '1.0.0');
    }

    // Google Fonts
    wp_enqueue_style('google-fonts', doccure_load_google_fonts(), array(), null);

    // Theme Styles
    wp_enqueue_style('doccure-style', get_stylesheet_uri(), array('bootstrap'));
    wp_enqueue_style('doccure-theme', get_template_directory_uri() . '/assets/css/theme.css', array(), '1.4.0');
    wp_enqueue_style('doccure-responsive', get_template_directory_uri() . '/assets/css/responsive.css', array(), $version);

    // 3rd Party Scripts
    wp_enqueue_script('masonry');
    wp_enqueue_script('imagesloaded');
    wp_enqueue_script('popper', get_template_directory_uri() . '/assets/js/popper.min.js', array('jquery'), $version, true);
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap/bootstrap.bundle.min.js', array('jquery'), '5.3.0', true);

    wp_enqueue_script('isotope', get_theme_file_uri('/assets/js/isotope.min.js'), array('jquery'), false, true);
    wp_enqueue_script('magnific-popup', get_template_directory_uri() . '/assets/js/magnific-popup.js', array('jquery'), '1.1.0', true);
    wp_enqueue_script('inview', get_template_directory_uri() . '/assets/js/jquery.inview.min.js', array('jquery'), $version, true);
    wp_enqueue_script('countTo', get_template_directory_uri() . '/assets/js/jquery.countTo.js', array('jquery'), $version, true);
    wp_enqueue_script('owlcarousel', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array('jquery'), $version, true);
    wp_enqueue_script('slick', get_template_directory_uri() . '/assets/js/slick.min.js', array('jquery'), $version, true);

    wp_enqueue_script('doccure-backtotop', get_template_directory_uri() . '/assets/js/backToTop.js', array('jquery'), $version, true);

    if(is_page_template('doctors-map-view.php')) {
    wp_enqueue_script('mapjs', get_template_directory_uri() . '/assets/js/map.js', array('jquery'), $version, true);
    }
    // Map box
    if(!empty(doccure_get_option('mapbox_access_token_value'))) {
      wp_enqueue_script('mapbox-gl', get_template_directory_uri() . '/assets/js/mapbox-gl.min.js', array('jquery'), $version, true);
      wp_enqueue_script('map', get_template_directory_uri() . '/assets/js/map.js', array('jquery'), '1.0.0', true);
    }
    // Smooth Scroll
    if (doccure_get_option('enable-smooth-scroll') == true) {
      wp_enqueue_script('smooth-scroll', get_template_directory_uri() . '/assets/js/SmoothScroll.min.js', array('jquery'), $version, true);
    }

    // Lazy Load
    if (doccure_get_option('enable-lazy-loading') == true) {
      wp_enqueue_script('unveil', get_template_directory_uri() . '/assets/js/unveil.js', array('jquery'), '5.2.0', true);
    }

    // Infinite Scroll
    if (doccure_get_option('shop_infinite_scroll') == true) {
      wp_enqueue_script('infinite-scroll', get_template_directory_uri() . '/assets/js/infinite-scroll.min.js', array('jquery'), '3.0.6', true);
    }

    // Ajax login register
    if(doccure_get_option('display-user-icon')) {
      wp_enqueue_script('ajax-login-register', get_template_directory_uri() . '/assets/js/ajax-login-register.js', array('jquery'), $version, true);
    }

    wp_enqueue_script('doccure-theia-sticky-ResizeSensor', get_template_directory_uri() . '/assets/theia-sticky-sidebar/ResizeSensor.js', array('jquery'), $version, true);
    wp_enqueue_script('doccure-theia-sticky-sidebar', get_template_directory_uri() . '/assets/theia-sticky-sidebar/theia-sticky-sidebar.js', array('jquery'), $version, true);

    // Theme Scripts
   if ( is_rtl() ) {
    wp_enqueue_script('doccure-theme-rtl', get_template_directory_uri() . '/assets/js/theme-rtl.js', array('jquery'), $version, true);
    wp_enqueue_style('doccure-theme-rtl', get_template_directory_uri() . '/assets/css/theme-rtl.css', array(), $version);
  } else {
    wp_enqueue_script('doccure-theme', get_template_directory_uri() . '/assets/js/theme.js', array('jquery'), $version, true);

  }

//   function doccure_zoom_enqueue_scripts() {
//     wp_enqueue_script('doccure-zoom', get_template_directory_uri() . '/assets/js/doccure-zoom.js', array('jquery'), null, true);
//     wp_localize_script('doccure-zoom', 'doccureZoom', array(
//         'ajax_url' => admin_url('admin-ajax.php'),
//     ));
// }
// add_action('admin_enqueue_scripts', 'doccure_zoom_enqueue_scripts');


  // wp_enqueue_script('doccure-zoom', get_template_directory_uri() . '/assets/js/doccure-zoom.js', array('jquery'), true);

   wp_localize_script( 'doccure-theme', 'ajax_woocommerce_object', array(
      'ajaxurl' => admin_url( 'admin-ajax.php' ),
      'redirecturl' => home_url('/'),
      'loadingmessage' => esc_html('Sending user info, please wait...')
    ));
   
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'doccure_scripts', 10);

/**
 * Enqueue the dynamic CSS
 *
 * @since 1.0.0
 */
function doccure_dynamic_css_sheet()
{
     wp_enqueue_style('doccure-dynamic', get_template_directory_uri() . '/assets/css/dynamic.css', array());

    $custom_dynamic_style = doccure_custom_dynamic_style();
    if (!empty($custom_dynamic_style)) {
        wp_add_inline_style('doccure-dynamic', $custom_dynamic_style);
    }
} 

add_action('wp_enqueue_scripts', 'doccure_dynamic_css_sheet', 30); 

/**
 * Enqueue scripts and styles for backend.
 *
 * @since 1.0.0
 */
function doccure_enqueue_scripts_admin($hook)
{
    $theme_data = wp_get_theme();
    if (is_child_theme() && is_object($theme_data->parent())) {
        $theme_data = wp_get_theme($theme_data->parent()->template);
    }
    $version = $theme_data->get('Version');
    wp_enqueue_style('v4-shims', get_template_directory_uri() . '/assets/css/v4-shims.min.css', array(), '5.11.2');
    wp_enqueue_style('fontawesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array('v4-shims'), '5.11.2');
    wp_enqueue_style('flaticon', get_template_directory_uri() . '/assets/css/flaticon.css', array(), $version);
}

add_action('admin_enqueue_scripts', 'doccure_enqueue_scripts_admin');
