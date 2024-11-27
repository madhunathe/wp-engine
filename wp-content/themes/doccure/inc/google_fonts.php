<?php
/**
 *
 * Google fonts
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @since 1.0
 */

/**
 * Google fonts enqueue
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_prepare_google_fonts')) {
    function doccure_prepare_google_fonts($include_from_google) {
        $fonts_url = '';
		$protocol = is_ssl() ? 'https' : 'http';
		
        /**
         * Get remote fonts
         * @param array $include_from_google
         */
        if (!sizeof($include_from_google)) {
            return '';
        }

        $font_families = array ();
        foreach ($include_from_google as $font => $font_family) {

            /* Translators: If there are characters in your language that are not
             * supported by Font Faamily, translate this to 'off'. Do not translate
             * into your own language.
              $font_on_off	= '';
              $font_on_off = _x( 'on', $font.' font: on or off', 'doccure' );
             */

            $font_families[] = $font . ':' . $font_family['variants'][0];
        }

        $query_args = array (
                                 'family' => urlencode(implode('|' , $font_families)) ,
                                 'subset' => urlencode('latin,latin-ext') ,
        );


        $fonts_url = add_query_arg($query_args , $protocol.'://fonts.googleapis.com/css');

        return esc_url_raw($fonts_url);
    }
}

/**
 * Default google fonts of theme
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_enqueue_google_fonts')) {
    function doccure_enqueue_google_fonts() {
		$protocol = is_ssl() ? 'https' : 'http';
		
		//Default theme font famlies
		$font_families	= array();
		$font_families[] = 'Open+Sans:400,600';
		$font_families[] = 'Poppins:300,400,500,600,700';
		
		 $query_args = array (
			 'family' => implode('%7C' , $font_families) ,
			 'subset' => 'latin,latin-ext' ,
        );

        $theme_fonts = add_query_arg($query_args , $protocol.'://fonts.googleapis.com/css');
		wp_enqueue_style('doccure-google-fonts' , esc_url_raw($theme_fonts), array () , null);
    }
    add_action('wp_enqueue_scripts' , 'doccure_enqueue_google_fonts');
}