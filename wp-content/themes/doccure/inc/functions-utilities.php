<?php
/**
 * doccure Utility functions
 *
 * @package doccure
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Gets the ID of the current page.
 *
 * @since 1.0.0
 */
function doccure_get_page_id()
{

    global $post;
    return is_home() ? get_option('page_for_posts') : (isset($post->ID) ? $post->ID : '');

}

/**
 * Checks if a value is set and and not empty
 *
 * @since 1.0.0
 */
function doccure_is_not_empty($val, $index = '')
{

    return !empty($index) ? isset($val[$index]) && $val[$index] : isset($val) && $val;

}

/**
 * Get's the currently assigned page layout
 *
 * @since 1.0.0
 */
function doccure_get_layout_id()
{

    // Get's the page layout set in the page/post options
    $page_settings = get_post_meta(doccure_get_page_id(), 'doccure_page_settings', true);
    $layout_id = isset($page_settings['doccure_page_custom_layout']) ? $page_settings['doccure_page_custom_layout'] : '';

    if (!empty($layout_id) && get_post_type($layout_id) == 'layouts') {
        return $layout_id;
    }
    return false;

}

function doccure_get_template_id()
{

    // Get's the page template set in the page/post options
    $page_settings = get_post_meta(doccure_get_page_id(), 'doccure_page_settings', true);
    $template_id = isset($page_settings['popup_type_page_template']) ? $page_settings['popup_type_page_template'] : '';

    if (!empty($template_id) && get_post_type($template_id) == 'doccure_templates') {
        return $template_id;
    }
    return false;

}

/**
 * Returns an option value based on the layout set for that page
 *
 * @since 1.0.0
 */
function doccure_get_layout_option($option)
{

    $layout_id = doccure_get_layout_id();

    // Check if we have a custom layout for this page
    if ($layout_id) {
        $layout_settings = get_post_meta($layout_id, 'doccure_layout_settings', true);
        $value = isset($layout_settings[$option]) ? $layout_settings[$option] : '';
        if (!empty($value)) {
            return $value;
        }
    }

    return false;

}

/**
 * Get theme options configuration
 *
 * @param string $name - the name of the theme option.
 * @param string $default - value to return if the option is not set.
 *
 * @since 1.0.0
 */

 if( !function_exists('doccure_get_option_new') )
{

	function doccure_get_option_new($get_text)
    {
        global $doccure_options;
        if(isset($doccure_options[$get_text]) &&  $doccure_options[$get_text] !=""):
            return $doccure_options[$get_text];
        else:
            return false;
        endif;
    }
}

function doccure_get_option($name, $default = '')
{

    global $doccure_options;

    $layout_id = doccure_get_layout_id();
    $custom_option = doccure_get_layout_option($name);

    if ( $custom_option != 'theme-options' && $custom_option && $layout_id) {
        return $custom_option;
    } else {
        return isset($doccure_options[$name]) ? $doccure_options[$name] : $default;
    }

}

/**
 * Checks if theme options is active
 *
 * @since 1.0.0
 */
function doccure_isset_options()
{

    global $doccure_options;

    return !empty($doccure_options);
}

/**
 * Return image size multiplier
 *
 * @since 1.0.0
 */
function doccure_get_retina_multiplier()
{
    return max(1, doccure_get_option('retina_ready', 1));
}

/**
 * Return an image sizes, alogn with its multiplier
 *
 * @since 1.0.0
 */
function doccure_get_thumb_size($size)
{
    $retina = doccure_get_retina_multiplier() > 1 ? '-@retina' : '';
    return $size . $retina;
}

/**
 * Returns the first term of a post.
 *
 * @since 1.0.0
 */
function doccure_get_first_term($post_id, $tax)
{

    $terms = get_the_terms($post_id, $tax);
    return (isset($terms[0]) && $terms[0]) ? $terms[0] : '';

}

/**
 * Darken a color.
 *
 * @since 1.0.0
 */
function doccure_darken_color($rgb, $darker = 2)
{
    $hash = (strpos($rgb, '#') !== false) ? '#' : '';
    $rgb = (strlen($rgb) == 7) ? str_replace('#', '', $rgb) : ((strlen($rgb) == 6) ? $rgb : false);
    if (strlen($rgb) != 6) return $hash . '000000';
    $darker = ($darker > 1) ? $darker : 1;

    list($R16, $G16, $B16) = str_split($rgb, 2);

    $R = sprintf("%02X", floor(hexdec($R16) / $darker));
    $G = sprintf("%02X", floor(hexdec($G16) / $darker));
    $B = sprintf("%02X", floor(hexdec($B16) / $darker));

    return $hash . $R . $G . $B;
}

/**
 * Convert a color ot rgb.
 *
 * @since 1.0.0
 */
function doccure_hex_to_rgb($hex, $alpha = false)
{
    $hex = sanitize_hex_color($hex);
    $hex = str_replace('#', '', $hex);
    $length = strlen($hex);
    $rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
    $rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
    $rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
    if ($alpha) {
        $rgb['a'] = $alpha;
    }
    return $rgb;
}


/**
 * Check if WooCommerce plugin is active.
 *
 * @since 1.0.0
 */
function doccure_is_woo_active()
{
    return class_exists('WooCommerce');
}

/**
 * Checks if YITH Quick view is active.
 *
 * @since 1.0.0
 */
function doccure_is_yith_wcqv_active()
{
    return class_exists('YITH_WCQV');
}

/**
 * Checks if YITH Wish List is active.
 *
 * @since 1.0.0
 */
function doccure_is_yith_wcwl_active()
{
    return class_exists('YITH_WCWL');
}

/**
 * Checks if YITH Compare is active.
 *
 * @since 1.0.0
 */
function doccure_is_yith_woocompare_active()
{
    return class_exists('YITH_Woocompare');
}

/**
 * Check if Easy Digital Downloads plugin is active.
 *
 * @since 2.1.1
 */
function doccure_is_edd_active(){
  return class_exists('Easy_Digital_Downloads');
}

/**
 * Checks if YITH Compare is active.
 *
 * @since 1.0.0
 */
function doccure_is_rev_slider_active()
{
    return class_exists('RevSlider');
}

/**
 * Returns all the aliases of the slider revolution sliders.
 *
 * @since 1.0.0
 */
function doccure_get_rev_sliders()
{

    if (!doccure_is_rev_slider_active()) {
        return false;
    }

    $rev_sliders = [];
    $slider = new RevSlider();
    $objSliders = $slider->get_sliders();

    foreach ($objSliders as $slider) {
        $rev_sliders[$slider->alias] = $slider->title;
    }

    return $rev_sliders;

}

/**
 * Returns all fontawesome icons.
 *
 * @since 1.0.0
 */
function doccure_get_fa_icons()
{

    return array(
        'fab fa-500px' => esc_html__('500px', 'doccure'),
        'fab fa-accessible-icon' => esc_html__('accessible-icon', 'doccure'),
        'fab fa-accusoft' => esc_html__('accusoft', 'doccure'),
        'fas fa-address-book' => esc_html__('address-book', 'doccure'),
        'far fa-address-book' => esc_html__('address-book', 'doccure'),
        'fas fa-address-card' => esc_html__('address-card', 'doccure'),
        'far fa-address-card' => esc_html__('address-card', 'doccure'),
        'fas fa-adjust' => esc_html__('adjust', 'doccure'),
        'fab fa-adn' => esc_html__('adn', 'doccure'),
        'fab fa-adversal' => esc_html__('adversal', 'doccure'),
        'fab fa-affiliatetheme' => esc_html__('affiliatetheme', 'doccure'),
        'fab fa-algolia' => esc_html__('algolia', 'doccure'),
        'fas fa-align-center' => esc_html__('align-center', 'doccure'),
        'fas fa-align-justify' => esc_html__('align-justify', 'doccure'),
        'fas fa-align-left' => esc_html__('align-left', 'doccure'),
        'fas fa-align-right' => esc_html__('align-right', 'doccure'),
        'fas fa-allergies' => esc_html__('allergies', 'doccure'),
        'fab fa-amazon' => esc_html__('amazon', 'doccure'),
        'fab fa-amazon-pay' => esc_html__('amazon-pay', 'doccure'),
        'fas fa-ambulance' => esc_html__('ambulance', 'doccure'),
        'fas fa-american-sign-language-interpreting' => esc_html__('american-sign-language-interpreting', 'doccure'),
        'fab fa-amilia' => esc_html__('amilia', 'doccure'),
        'fas fa-anchor' => esc_html__('anchor', 'doccure'),
        'fab fa-android' => esc_html__('android', 'doccure'),
        'fab fa-angellist' => esc_html__('angellist', 'doccure'),
        'fas fa-angle-double-down' => esc_html__('angle-double-down', 'doccure'),
        'fas fa-angle-double-left' => esc_html__('angle-double-left', 'doccure'),
        'fas fa-angle-double-right' => esc_html__('angle-double-right', 'doccure'),
        'fas fa-angle-double-up' => esc_html__('angle-double-up', 'doccure'),
        'fas fa-angle-down' => esc_html__('angle-down', 'doccure'),
        'fas fa-angle-left' => esc_html__('angle-left', 'doccure'),
        'fas fa-angle-right' => esc_html__('angle-right', 'doccure'),
        'fas fa-angle-up' => esc_html__('angle-up', 'doccure'),
        'fab fa-angrycreative' => esc_html__('angrycreative', 'doccure'),
        'fab fa-angular' => esc_html__('angular', 'doccure'),
        'fab fa-app-store' => esc_html__('app-store', 'doccure'),
        'fab fa-app-store-ios' => esc_html__('app-store-ios', 'doccure'),
        'fab fa-apper' => esc_html__('apper', 'doccure'),
        'fab fa-apple' => esc_html__('apple', 'doccure'),
        'fab fa-apple-pay' => esc_html__('apple-pay', 'doccure'),
        'fas fa-archive' => esc_html__('archive', 'doccure'),
        'fas fa-arrow-alt-circle-down' => esc_html__('arrow-alt-circle-down', 'doccure'),
        'far fa-arrow-alt-circle-down' => esc_html__('arrow-alt-circle-down', 'doccure'),
        'fas fa-arrow-alt-circle-left' => esc_html__('arrow-alt-circle-left', 'doccure'),
        'far fa-arrow-alt-circle-left' => esc_html__('arrow-alt-circle-left', 'doccure'),
        'fas fa-arrow-alt-circle-right' => esc_html__('arrow-alt-circle-right', 'doccure'),
        'far fa-arrow-alt-circle-right' => esc_html__('arrow-alt-circle-right', 'doccure'),
        'fas fa-arrow-alt-circle-up' => esc_html__('arrow-alt-circle-up', 'doccure'),
        'far fa-arrow-alt-circle-up' => esc_html__('arrow-alt-circle-up', 'doccure'),
        'fas fa-arrow-circle-down' => esc_html__('arrow-circle-down', 'doccure'),
        'fas fa-arrow-circle-left' => esc_html__('arrow-circle-left', 'doccure'),
        'fas fa-arrow-circle-right' => esc_html__('arrow-circle-right', 'doccure'),
        'fas fa-arrow-circle-up' => esc_html__('arrow-circle-up', 'doccure'),
        'fas fa-arrow-down' => esc_html__('arrow-down', 'doccure'),
        'fas fa-arrow-left' => esc_html__('arrow-left', 'doccure'),
        'fas fa-arrow-right' => esc_html__('arrow-right', 'doccure'),
        'fas fa-arrow-up' => esc_html__('arrow-up', 'doccure'),
        'fas fa-arrows-alt' => esc_html__('arrows-alt', 'doccure'),
        'fas fa-arrows-alt-h' => esc_html__('arrows-alt-h', 'doccure'),
        'fas fa-arrows-alt-v' => esc_html__('arrows-alt-v', 'doccure'),
        'fas fa-assistive-listening-systems' => esc_html__('assistive-listening-systems', 'doccure'),
        'fas fa-asterisk' => esc_html__('asterisk', 'doccure'),
        'fab fa-asymmetrik' => esc_html__('asymmetrik', 'doccure'),
        'fas fa-at' => esc_html__('at', 'doccure'),
        'fab fa-audible' => esc_html__('audible', 'doccure'),
        'fas fa-audio-description' => esc_html__('audio-description', 'doccure'),
        'fab fa-autoprefixer' => esc_html__('autoprefixer', 'doccure'),
        'fab fa-avianex' => esc_html__('avianex', 'doccure'),
        'fab fa-aviato' => esc_html__('aviato', 'doccure'),
        'fab fa-aws' => esc_html__('aws', 'doccure'),
        'fas fa-backward' => esc_html__('backward', 'doccure'),
        'fas fa-balance-scale' => esc_html__('balance-scale', 'doccure'),
        'fas fa-ban' => esc_html__('ban', 'doccure'),
        'fas fa-band-aid' => esc_html__('band-aid', 'doccure'),
        'fab fa-bandcamp' => esc_html__('bandcamp', 'doccure'),
        'fas fa-barcode' => esc_html__('barcode', 'doccure'),
        'fas fa-bars' => esc_html__('bars', 'doccure'),
        'fas fa-baseball-ball' => esc_html__('baseball-ball', 'doccure'),
        'fas fa-basketball-ball' => esc_html__('basketball-ball', 'doccure'),
        'fas fa-bath' => esc_html__('bath', 'doccure'),
        'fas fa-battery-empty' => esc_html__('battery-empty', 'doccure'),
        'fas fa-battery-full' => esc_html__('battery-full', 'doccure'),
        'fas fa-battery-half' => esc_html__('battery-half', 'doccure'),
        'fas fa-battery-quarter' => esc_html__('battery-quarter', 'doccure'),
        'fas fa-battery-three-quarters' => esc_html__('battery-three-quarters', 'doccure'),
        'fas fa-bed' => esc_html__('bed', 'doccure'),
        'fas fa-beer' => esc_html__('beer', 'doccure'),
        'fab fa-behance' => esc_html__('behance', 'doccure'),
        'fab fa-behance-square' => esc_html__('behance-square', 'doccure'),
        'fas fa-bell' => esc_html__('bell', 'doccure'),
        'far fa-bell' => esc_html__('bell', 'doccure'),
        'fas fa-bell-slash' => esc_html__('bell-slash', 'doccure'),
        'far fa-bell-slash' => esc_html__('bell-slash', 'doccure'),
        'fas fa-bicycle' => esc_html__('bicycle', 'doccure'),
        'fab fa-bimobject' => esc_html__('bimobject', 'doccure'),
        'fas fa-binoculars' => esc_html__('binoculars', 'doccure'),
        'fas fa-birthday-cake' => esc_html__('birthday-cake', 'doccure'),
        'fab fa-bitbucket' => esc_html__('bitbucket', 'doccure'),
        'fab fa-bitcoin' => esc_html__('bitcoin', 'doccure'),
        'fab fa-bity' => esc_html__('bity', 'doccure'),
        'fab fa-black-tie' => esc_html__('black-tie', 'doccure'),
        'fab fa-blackberry' => esc_html__('blackberry', 'doccure'),
        'fas fa-blind' => esc_html__('blind', 'doccure'),
        'fab fa-blogger' => esc_html__('blogger', 'doccure'),
        'fab fa-blogger-b' => esc_html__('blogger-b', 'doccure'),
        'fab fa-bluetooth' => esc_html__('bluetooth', 'doccure'),
        'fab fa-bluetooth-b' => esc_html__('bluetooth-b', 'doccure'),
        'fas fa-bold' => esc_html__('bold', 'doccure'),
        'fas fa-bolt' => esc_html__('bolt', 'doccure'),
        'fas fa-bomb' => esc_html__('bomb', 'doccure'),
        'fas fa-book' => esc_html__('book', 'doccure'),
        'fas fa-bookmark' => esc_html__('bookmark', 'doccure'),
        'far fa-bookmark' => esc_html__('bookmark', 'doccure'),
        'fas fa-bowling-ball' => esc_html__('bowling-ball', 'doccure'),
        'fas fa-box' => esc_html__('box', 'doccure'),
        'fas fa-box-open' => esc_html__('box-open', 'doccure'),
        'fas fa-boxes' => esc_html__('boxes', 'doccure'),
        'fas fa-braille' => esc_html__('braille', 'doccure'),
        'fas fa-briefcase' => esc_html__('briefcase', 'doccure'),
        'fas fa-briefcase-medical' => esc_html__('briefcase-medical', 'doccure'),
        'fab fa-btc' => esc_html__('btc', 'doccure'),
        'fas fa-bug' => esc_html__('bug', 'doccure'),
        'fas fa-building' => esc_html__('building', 'doccure'),
        'far fa-building' => esc_html__('building', 'doccure'),
        'fas fa-bullhorn' => esc_html__('bullhorn', 'doccure'),
        'fas fa-bullseye' => esc_html__('bullseye', 'doccure'),
        'fas fa-burn' => esc_html__('burn', 'doccure'),
        'fab fa-buromobelexperte' => esc_html__('buromobelexperte', 'doccure'),
        'fas fa-bus' => esc_html__('bus', 'doccure'),
        'fab fa-buysellads' => esc_html__('buysellads', 'doccure'),
        'fas fa-calculator' => esc_html__('calculator', 'doccure'),
        'fas fa-calendar' => esc_html__('calendar', 'doccure'),
        'far fa-calendar' => esc_html__('calendar', 'doccure'),
        'fas fa-calendar-alt' => esc_html__('calendar-alt', 'doccure'),
        'far fa-calendar-alt' => esc_html__('calendar-alt', 'doccure'),
        'fas fa-calendar-check' => esc_html__('calendar-check', 'doccure'),
        'far fa-calendar-check' => esc_html__('calendar-check', 'doccure'),
        'fas fa-calendar-minus' => esc_html__('calendar-minus', 'doccure'),
        'far fa-calendar-minus' => esc_html__('calendar-minus', 'doccure'),
        'fas fa-calendar-plus' => esc_html__('calendar-plus', 'doccure'),
        'far fa-calendar-plus' => esc_html__('calendar-plus', 'doccure'),
        'fas fa-calendar-times' => esc_html__('calendar-times', 'doccure'),
        'far fa-calendar-times' => esc_html__('calendar-times', 'doccure'),
        'fas fa-camera' => esc_html__('camera', 'doccure'),
        'fas fa-camera-retro' => esc_html__('camera-retro', 'doccure'),
        'fas fa-capsules' => esc_html__('capsules', 'doccure'),
        'fas fa-car' => esc_html__('car', 'doccure'),
        'fas fa-caret-down' => esc_html__('caret-down', 'doccure'),
        'fas fa-caret-left' => esc_html__('caret-left', 'doccure'),
        'fas fa-caret-right' => esc_html__('caret-right', 'doccure'),
        'fas fa-caret-square-down' => esc_html__('caret-square-down', 'doccure'),
        'far fa-caret-square-down' => esc_html__('caret-square-down', 'doccure'),
        'fas fa-caret-square-left' => esc_html__('caret-square-left', 'doccure'),
        'far fa-caret-square-left' => esc_html__('caret-square-left', 'doccure'),
        'fas fa-caret-square-right' => esc_html__('caret-square-right', 'doccure'),
        'far fa-caret-square-right' => esc_html__('caret-square-right', 'doccure'),
        'fas fa-caret-square-up' => esc_html__('caret-square-up', 'doccure'),
        'far fa-caret-square-up' => esc_html__('caret-square-up', 'doccure'),
        'fas fa-caret-up' => esc_html__('caret-up', 'doccure'),
        'fas fa-cart-arrow-down' => esc_html__('cart-arrow-down', 'doccure'),
        'fas fa-cart-plus' => esc_html__('cart-plus', 'doccure'),
        'fab fa-cc-amazon-pay' => esc_html__('cc-amazon-pay', 'doccure'),
        'fab fa-cc-amex' => esc_html__('cc-amex', 'doccure'),
        'fab fa-cc-apple-pay' => esc_html__('cc-apple-pay', 'doccure'),
        'fab fa-cc-diners-club' => esc_html__('cc-diners-club', 'doccure'),
        'fab fa-cc-discover' => esc_html__('cc-discover', 'doccure'),
        'fab fa-cc-jcb' => esc_html__('cc-jcb', 'doccure'),
        'fab fa-cc-mastercard' => esc_html__('cc-mastercard', 'doccure'),
        'fab fa-cc-paypal' => esc_html__('cc-paypal', 'doccure'),
        'fab fa-cc-stripe' => esc_html__('cc-stripe', 'doccure'),
        'fab fa-cc-visa' => esc_html__('cc-visa', 'doccure'),
        'fab fa-centercode' => esc_html__('centercode', 'doccure'),
        'fas fa-certificate' => esc_html__('certificate', 'doccure'),
        'fas fa-chart-area' => esc_html__('chart-area', 'doccure'),
        'fas fa-chart-bar' => esc_html__('chart-bar', 'doccure'),
        'far fa-chart-bar' => esc_html__('chart-bar', 'doccure'),
        'fas fa-chart-line' => esc_html__('chart-line', 'doccure'),
        'fas fa-chart-pie' => esc_html__('chart-pie', 'doccure'),
        'fas fa-check' => esc_html__('check', 'doccure'),
        'fas fa-check-circle' => esc_html__('check-circle', 'doccure'),
        'far fa-check-circle' => esc_html__('check-circle', 'doccure'),
        'fas fa-check-square' => esc_html__('check-square', 'doccure'),
        'far fa-check-square' => esc_html__('check-square', 'doccure'),
        'fas fa-chess' => esc_html__('chess', 'doccure'),
        'fas fa-chess-bishop' => esc_html__('chess-bishop', 'doccure'),
        'fas fa-chess-board' => esc_html__('chess-board', 'doccure'),
        'fas fa-chess-king' => esc_html__('chess-king', 'doccure'),
        'fas fa-chess-knight' => esc_html__('chess-knight', 'doccure'),
        'fas fa-chess-pawn' => esc_html__('chess-pawn', 'doccure'),
        'fas fa-chess-queen' => esc_html__('chess-queen', 'doccure'),
        'fas fa-chess-rook' => esc_html__('chess-rook', 'doccure'),
        'fas fa-chevron-circle-down' => esc_html__('chevron-circle-down', 'doccure'),
        'fas fa-chevron-circle-left' => esc_html__('chevron-circle-left', 'doccure'),
        'fas fa-chevron-circle-right' => esc_html__('chevron-circle-right', 'doccure'),
        'fas fa-chevron-circle-up' => esc_html__('chevron-circle-up', 'doccure'),
        'fas fa-chevron-down' => esc_html__('chevron-down', 'doccure'),
        'fas fa-chevron-left' => esc_html__('chevron-left', 'doccure'),
        'fas fa-chevron-right' => esc_html__('chevron-right', 'doccure'),
        'fas fa-chevron-up' => esc_html__('chevron-up', 'doccure'),
        'fas fa-child' => esc_html__('child', 'doccure'),
        'fab fa-chrome' => esc_html__('chrome', 'doccure'),
        'fas fa-circle' => esc_html__('circle', 'doccure'),
        'far fa-circle' => esc_html__('circle', 'doccure'),
        'fas fa-circle-notch' => esc_html__('circle-notch', 'doccure'),
        'far fa-clipboard' => esc_html__('clipboard', 'doccure'),
        'far fa-clipboard' => esc_html__('clipboard', 'doccure'),
        'far fa-clipboard-check' => esc_html__('clipboard-check', 'doccure'),
        'far fa-clipboard-list' => esc_html__('clipboard-list', 'doccure'),
        'fas fa-clock' => esc_html__('clock', 'doccure'),
        'far fa-clock' => esc_html__('clock', 'doccure'),
        'fas fa-clone' => esc_html__('clone', 'doccure'),
        'far fa-clone' => esc_html__('clone', 'doccure'),
        'fas fa-closed-captioning' => esc_html__('closed-captioning', 'doccure'),
        'far fa-closed-captioning' => esc_html__('closed-captioning', 'doccure'),
        'fas fa-cloud' => esc_html__('cloud', 'doccure'),
        'fas fa-cloud-download-alt' => esc_html__('cloud-download-alt', 'doccure'),
        'fas fa-cloud-upload-alt' => esc_html__('cloud-upload-alt', 'doccure'),
        'fab fa-cloudscale' => esc_html__('cloudscale', 'doccure'),
        'fab fa-cloudsmith' => esc_html__('cloudsmith', 'doccure'),
        'fab fa-cloudversify' => esc_html__('cloudversify', 'doccure'),
        'fas fa-code' => esc_html__('code', 'doccure'),
        'fas fa-code-branch' => esc_html__('code-branch', 'doccure'),
        'fab fa-codepen' => esc_html__('codepen', 'doccure'),
        'fab fa-codiepie' => esc_html__('codiepie', 'doccure'),
        'fas fa-coffee' => esc_html__('coffee', 'doccure'),
        'fas fa-cog' => esc_html__('cog', 'doccure'),
        'fas fa-cogs' => esc_html__('cogs', 'doccure'),
        'fas fa-columns' => esc_html__('columns', 'doccure'),
        'fas fa-comment' => esc_html__('comment', 'doccure'),
        'far fa-comment' => esc_html__('comment', 'doccure'),
        'fas fa-comment-alt' => esc_html__('comment-alt', 'doccure'),
        'far fa-comment-alt' => esc_html__('comment-alt', 'doccure'),
        'fas fa-comment-dots' => esc_html__('comment-dots', 'doccure'),
        'fas fa-comment-slash' => esc_html__('comment-slash', 'doccure'),
        'fas fa-comments' => esc_html__('comments', 'doccure'),
        'far fa-comments' => esc_html__('comments', 'doccure'),
        'fas fa-compass' => esc_html__('compass', 'doccure'),
        'far fa-compass' => esc_html__('compass', 'doccure'),
        'fas fa-compress' => esc_html__('compress', 'doccure'),
        'fab fa-connectdevelop' => esc_html__('connectdevelop', 'doccure'),
        'fab fa-contao' => esc_html__('contao', 'doccure'),
        'fas fa-copy' => esc_html__('copy', 'doccure'),
        'far fa-copy' => esc_html__('copy', 'doccure'),
        'fas fa-copyright' => esc_html__('copyright', 'doccure'),
        'far fa-copyright' => esc_html__('copyright', 'doccure'),
        'fas fa-couch' => esc_html__('couch', 'doccure'),
        'fab fa-cpanel' => esc_html__('cpanel', 'doccure'),
        'fab fa-creative-commons' => esc_html__('creative-commons', 'doccure'),
        'fas fa-credit-card' => esc_html__('credit-card', 'doccure'),
        'far fa-credit-card' => esc_html__('credit-card', 'doccure'),
        'fas fa-crop' => esc_html__('crop', 'doccure'),
        'fas fa-crosshairs' => esc_html__('crosshairs', 'doccure'),
        'fab fa-css3' => esc_html__('css3', 'doccure'),
        'fab fa-css3-alt' => esc_html__('css3-alt', 'doccure'),
        'fas fa-cube' => esc_html__('cube', 'doccure'),
        'fas fa-cubes' => esc_html__('cubes', 'doccure'),
        'fas fa-cut' => esc_html__('cut', 'doccure'),
        'fab fa-cuttlefish' => esc_html__('cuttlefish', 'doccure'),
        'fab fa-d-and-d' => esc_html__('d-and-d', 'doccure'),
        'fab fa-dashcube' => esc_html__('dashcube', 'doccure'),
        'fas fa-database' => esc_html__('database', 'doccure'),
        'fas fa-deaf' => esc_html__('deaf', 'doccure'),
        'fab fa-delicious' => esc_html__('delicious', 'doccure'),
        'fab fa-deploydog' => esc_html__('deploydog', 'doccure'),
        'fab fa-deskpro' => esc_html__('deskpro', 'doccure'),
        'fas fa-desktop' => esc_html__('desktop', 'doccure'),
        'fab fa-deviantart' => esc_html__('deviantart', 'doccure'),
        'fas fa-diagnoses' => esc_html__('diagnoses', 'doccure'),
        'fab fa-digg' => esc_html__('digg', 'doccure'),
        'fab fa-digital-ocean' => esc_html__('digital-ocean', 'doccure'),
        'fab fa-discord' => esc_html__('discord', 'doccure'),
        'fab fa-discourse' => esc_html__('discourse', 'doccure'),
        'fas fa-dna' => esc_html__('dna', 'doccure'),
        'fab fa-dochub' => esc_html__('dochub', 'doccure'),
        'fab fa-docker' => esc_html__('docker', 'doccure'),
        'fas fa-dollar-sign' => esc_html__('dollar-sign', 'doccure'),
        'fas fa-dolly' => esc_html__('dolly', 'doccure'),
        'fas fa-dolly-flatbed' => esc_html__('dolly-flatbed', 'doccure'),
        'fas fa-donate' => esc_html__('donate', 'doccure'),
        'fas fa-dot-circle' => esc_html__('dot-circle', 'doccure'),
        'far fa-dot-circle' => esc_html__('dot-circle', 'doccure'),
        'fas fa-dove' => esc_html__('dove', 'doccure'),
        'fas fa-download' => esc_html__('download', 'doccure'),
        'fab fa-draft2digital' => esc_html__('draft2digital', 'doccure'),
        'fab fa-dribbble' => esc_html__('dribbble', 'doccure'),
        'fab fa-dribbble-square' => esc_html__('dribbble-square', 'doccure'),
        'fab fa-dropbox' => esc_html__('dropbox', 'doccure'),
        'fab fa-drupal' => esc_html__('drupal', 'doccure'),
        'fab fa-dyalog' => esc_html__('dyalog', 'doccure'),
        'fab fa-earlybirds' => esc_html__('earlybirds', 'doccure'),
        'fab fa-edge' => esc_html__('edge', 'doccure'),
        'fas fa-edit' => esc_html__('edit', 'doccure'),
        'far fa-edit' => esc_html__('edit', 'doccure'),
        'fas fa-eject' => esc_html__('eject', 'doccure'),
        'fab fa-elementor' => esc_html__('elementor', 'doccure'),
        'fas fa-ellipsis-h' => esc_html__('ellipsis-h', 'doccure'),
        'fas fa-ellipsis-v' => esc_html__('ellipsis-v', 'doccure'),
        'fab fa-ember' => esc_html__('ember', 'doccure'),
        'fab fa-empire' => esc_html__('empire', 'doccure'),
        'far fa-envelope' => esc_html__('envelope', 'doccure'),
        'far fa-envelope' => esc_html__('envelope', 'doccure'),
        'far fa-envelope-open' => esc_html__('envelope-open', 'doccure'),
        'far fa-envelope-open' => esc_html__('envelope-open', 'doccure'),
        'far fa-envelope-square' => esc_html__('envelope-square', 'doccure'),
        'fab fa-envira' => esc_html__('envira', 'doccure'),
        'fas fa-eraser' => esc_html__('eraser', 'doccure'),
        'fab fa-erlang' => esc_html__('erlang', 'doccure'),
        'fab fa-ethereum' => esc_html__('ethereum', 'doccure'),
        'fab fa-etsy' => esc_html__('etsy', 'doccure'),
        'fas fa-euro-sign' => esc_html__('euro-sign', 'doccure'),
        'fas fa-exchange-alt' => esc_html__('exchange-alt', 'doccure'),
        'fas fa-exclamation' => esc_html__('exclamation', 'doccure'),
        'fas fa-exclamation-circle' => esc_html__('exclamation-circle', 'doccure'),
        'fas fa-exclamation-triangle' => esc_html__('exclamation-triangle', 'doccure'),
        'fas fa-expand' => esc_html__('expand', 'doccure'),
        'fas fa-expand-arrows-alt' => esc_html__('expand-arrows-alt', 'doccure'),
        'fab fa-expeditedssl' => esc_html__('expeditedssl', 'doccure'),
        'fas fa-external-link-alt' => esc_html__('external-link-alt', 'doccure'),
        'fas fa-external-link-square-alt' => esc_html__('external-link-square-alt', 'doccure'),
        'fas fa-eye' => esc_html__('eye', 'doccure'),
        'fas fa-eye-dropper' => esc_html__('eye-dropper', 'doccure'),
        'fas fa-eye-slash' => esc_html__('eye-slash', 'doccure'),
        'far fa-eye-slash' => esc_html__('eye-slash', 'doccure'),
        'fab fa-facebook' => esc_html__('facebook', 'doccure'),
        'fab fa-facebook-f' => esc_html__('facebook-f', 'doccure'),
        'fab fa-facebook-messenger' => esc_html__('facebook-messenger', 'doccure'),
        'fab fa-facebook-square' => esc_html__('facebook-square', 'doccure'),
        'fas fa-fast-backward' => esc_html__('fast-backward', 'doccure'),
        'fas fa-fast-forward' => esc_html__('fast-forward', 'doccure'),
        'fas fa-fax' => esc_html__('fax', 'doccure'),
        'fas fa-female' => esc_html__('female', 'doccure'),
        'fas fa-fighter-jet' => esc_html__('fighter-jet', 'doccure'),
        'fas fa-file' => esc_html__('file', 'doccure'),
        'far fa-file' => esc_html__('file', 'doccure'),
        'fas fa-file-alt' => esc_html__('file-alt', 'doccure'),
        'far fa-file-alt' => esc_html__('file-alt', 'doccure'),
        'fas fa-file-archive' => esc_html__('file-archive', 'doccure'),
        'far fa-file-archive' => esc_html__('file-archive', 'doccure'),
        'fas fa-file-audio' => esc_html__('file-audio', 'doccure'),
        'far fa-file-audio' => esc_html__('file-audio', 'doccure'),
        'fas fa-file-code' => esc_html__('file-code', 'doccure'),
        'far fa-file-code' => esc_html__('file-code', 'doccure'),
        'fas fa-file-excel' => esc_html__('file-excel', 'doccure'),
        'far fa-file-excel' => esc_html__('file-excel', 'doccure'),
        'fas fa-file-image' => esc_html__('file-image', 'doccure'),
        'far fa-file-image' => esc_html__('file-image', 'doccure'),
        'fas fa-file-medical' => esc_html__('file-medical', 'doccure'),
        'fas fa-file-medical-alt' => esc_html__('file-medical-alt', 'doccure'),
        'fas fa-file-pdf' => esc_html__('file-pdf', 'doccure'),
        'far fa-file-pdf' => esc_html__('file-pdf', 'doccure'),
        'fas fa-file-powerpoint' => esc_html__('file-powerpoint', 'doccure'),
        'far fa-file-powerpoint' => esc_html__('file-powerpoint', 'doccure'),
        'fas fa-file-video' => esc_html__('file-video', 'doccure'),
        'far fa-file-video' => esc_html__('file-video', 'doccure'),
        'fas fa-file-word' => esc_html__('file-word', 'doccure'),
        'far fa-file-word' => esc_html__('file-word', 'doccure'),
        'fas fa-film' => esc_html__('film', 'doccure'),
        'fas fa-filter' => esc_html__('filter', 'doccure'),
        'fas fa-fire' => esc_html__('fire', 'doccure'),
        'fas fa-fire-extinguisher' => esc_html__('fire-extinguisher', 'doccure'),
        'fab fa-firefox' => esc_html__('firefox', 'doccure'),
        'fas fa-first-aid' => esc_html__('first-aid', 'doccure'),
        'fab fa-first-order' => esc_html__('first-order', 'doccure'),
        'fab fa-firstdraft' => esc_html__('firstdraft', 'doccure'),
        'fas fa-flag' => esc_html__('flag', 'doccure'),
        'far fa-flag' => esc_html__('flag', 'doccure'),
        'fas fa-flag-checkered' => esc_html__('flag-checkered', 'doccure'),
        'fas fa-flask' => esc_html__('flask', 'doccure'),
        'fab fa-flickr' => esc_html__('flickr', 'doccure'),
        'fab fa-flipboard' => esc_html__('flipboard', 'doccure'),
        'fab fa-fly' => esc_html__('fly', 'doccure'),
        'fas fa-folder' => esc_html__('folder', 'doccure'),
        'far fa-folder' => esc_html__('folder', 'doccure'),
        'fas fa-folder-open' => esc_html__('folder-open', 'doccure'),
        'far fa-folder-open' => esc_html__('folder-open', 'doccure'),
        'fas fa-font' => esc_html__('font', 'doccure'),
        'fab fa-font-awesome' => esc_html__('font-awesome', 'doccure'),
        'fab fa-font-awesome-alt' => esc_html__('font-awesome-alt', 'doccure'),
        'fab fa-font-awesome-flag' => esc_html__('font-awesome-flag', 'doccure'),
        'fab fa-fonticons' => esc_html__('fonticons', 'doccure'),
        'fab fa-fonticons-fi' => esc_html__('fonticons-fi', 'doccure'),
        'fas fa-football-ball' => esc_html__('football-ball', 'doccure'),
        'fab fa-fort-awesome' => esc_html__('fort-awesome', 'doccure'),
        'fab fa-fort-awesome-alt' => esc_html__('fort-awesome-alt', 'doccure'),
        'fab fa-forumbee' => esc_html__('forumbee', 'doccure'),
        'fas fa-forward' => esc_html__('forward', 'doccure'),
        'fab fa-foursquare' => esc_html__('foursquare', 'doccure'),
        'fab fa-free-code-camp' => esc_html__('free-code-camp', 'doccure'),
        'fab fa-freebsd' => esc_html__('freebsd', 'doccure'),
        'fas fa-frown' => esc_html__('frown', 'doccure'),
        'far fa-frown' => esc_html__('frown', 'doccure'),
        'fas fa-futbol' => esc_html__('futbol', 'doccure'),
        'far fa-futbol' => esc_html__('futbol', 'doccure'),
        'fas fa-gamepad' => esc_html__('gamepad', 'doccure'),
        'fas fa-gavel' => esc_html__('gavel', 'doccure'),
        'fas fa-gem' => esc_html__('gem', 'doccure'),
        'far fa-gem' => esc_html__('gem', 'doccure'),
        'fas fa-genderless' => esc_html__('genderless', 'doccure'),
        'fab fa-get-pocket' => esc_html__('get-pocket', 'doccure'),
        'fab fa-gg' => esc_html__('gg', 'doccure'),
        'fab fa-gg-circle' => esc_html__('gg-circle', 'doccure'),
        'fas fa-gift' => esc_html__('gift', 'doccure'),
        'fab fa-git' => esc_html__('git', 'doccure'),
        'fab fa-git-square' => esc_html__('git-square', 'doccure'),
        'fab fa-github' => esc_html__('github', 'doccure'),
        'fab fa-github-alt' => esc_html__('github-alt', 'doccure'),
        'fab fa-github-square' => esc_html__('github-square', 'doccure'),
        'fab fa-gitkraken' => esc_html__('gitkraken', 'doccure'),
        'fab fa-gitlab' => esc_html__('gitlab', 'doccure'),
        'fab fa-gitter' => esc_html__('gitter', 'doccure'),
        'fas fa-glass-martini' => esc_html__('glass-martini', 'doccure'),
        'fab fa-glide' => esc_html__('glide', 'doccure'),
        'fab fa-glide-g' => esc_html__('glide-g', 'doccure'),
        'fas fa-globe' => esc_html__('globe', 'doccure'),
        'fab fa-gofore' => esc_html__('gofore', 'doccure'),
        'fas fa-golf-ball' => esc_html__('golf-ball', 'doccure'),
        'fab fa-goodreads' => esc_html__('goodreads', 'doccure'),
        'fab fa-goodreads-g' => esc_html__('goodreads-g', 'doccure'),
        'fab fa-google' => esc_html__('google', 'doccure'),
        'fab fa-google-drive' => esc_html__('google-drive', 'doccure'),
        'fab fa-google-play' => esc_html__('google-play', 'doccure'),
        'fab fa-google-plus' => esc_html__('google-plus', 'doccure'),
        'fab fa-google-plus-g' => esc_html__('google-plus-g', 'doccure'),
        'fab fa-google-plus-square' => esc_html__('google-plus-square', 'doccure'),
        'fab fa-google-wallet' => esc_html__('google-wallet', 'doccure'),
        'fas fa-graduation-cap' => esc_html__('graduation-cap', 'doccure'),
        'fab fa-gratipay' => esc_html__('gratipay', 'doccure'),
        'fab fa-grav' => esc_html__('grav', 'doccure'),
        'fab fa-gripfire' => esc_html__('gripfire', 'doccure'),
        'fab fa-grunt' => esc_html__('grunt', 'doccure'),
        'fab fa-gulp' => esc_html__('gulp', 'doccure'),
        'fas fa-h-square' => esc_html__('h-square', 'doccure'),
        'fab fa-hacker-news' => esc_html__('hacker-news', 'doccure'),
        'fab fa-hacker-news-square' => esc_html__('hacker-news-square', 'doccure'),
        'fas fa-hand-holding' => esc_html__('hand-holding', 'doccure'),
        'fas fa-hand-holding-heart' => esc_html__('hand-holding-heart', 'doccure'),
        'fas fa-hand-holding-usd' => esc_html__('hand-holding-usd', 'doccure'),
        'fas fa-hand-lizard' => esc_html__('hand-lizard', 'doccure'),
        'far fa-hand-lizard' => esc_html__('hand-lizard', 'doccure'),
        'fas fa-hand-paper' => esc_html__('hand-paper', 'doccure'),
        'far fa-hand-paper' => esc_html__('hand-paper', 'doccure'),
        'fas fa-hand-peace' => esc_html__('hand-peace', 'doccure'),
        'far fa-hand-peace' => esc_html__('hand-peace', 'doccure'),
        'fas fa-hand-point-down' => esc_html__('hand-point-down', 'doccure'),
        'far fa-hand-point-down' => esc_html__('hand-point-down', 'doccure'),
        'fas fa-hand-point-left' => esc_html__('hand-point-left', 'doccure'),
        'far fa-hand-point-left' => esc_html__('hand-point-left', 'doccure'),
        'fas fa-hand-point-right' => esc_html__('hand-point-right', 'doccure'),
        'far fa-hand-point-right' => esc_html__('hand-point-right', 'doccure'),
        'fas fa-hand-point-up' => esc_html__('hand-point-up', 'doccure'),
        'far fa-hand-point-up' => esc_html__('hand-point-up', 'doccure'),
        'fas fa-hand-pointer' => esc_html__('hand-pointer', 'doccure'),
        'far fa-hand-pointer' => esc_html__('hand-pointer', 'doccure'),
        'fas fa-hand-rock' => esc_html__('hand-rock', 'doccure'),
        'far fa-hand-rock' => esc_html__('hand-rock', 'doccure'),
        'fas fa-hand-scissors' => esc_html__('hand-scissors', 'doccure'),
        'far fa-hand-scissors' => esc_html__('hand-scissors', 'doccure'),
        'fas fa-hand-spock' => esc_html__('hand-spock', 'doccure'),
        'far fa-hand-spock' => esc_html__('hand-spock', 'doccure'),
        'fas fa-hands' => esc_html__('hands', 'doccure'),
        'fas fa-hands-helping' => esc_html__('hands-helping', 'doccure'),
        'fas fa-handshake' => esc_html__('handshake', 'doccure'),
        'far fa-handshake' => esc_html__('handshake', 'doccure'),
        'fas fa-hashtag' => esc_html__('hashtag', 'doccure'),
        'fas fa-hdd' => esc_html__('hdd', 'doccure'),
        'far fa-hdd' => esc_html__('hdd', 'doccure'),
        'fas fa-heading' => esc_html__('heading', 'doccure'),
        'fas fa-headphones' => esc_html__('headphones', 'doccure'),
        'fas fa-heart' => esc_html__('heart', 'doccure'),
        'far fa-heart' => esc_html__('heart', 'doccure'),
        'fas fa-heartbeat' => esc_html__('heartbeat', 'doccure'),
        'fab fa-hips' => esc_html__('hips', 'doccure'),
        'fab fa-hire-a-helper' => esc_html__('hire-a-helper', 'doccure'),
        'fas fa-history' => esc_html__('history', 'doccure'),
        'fas fa-hockey-puck' => esc_html__('hockey-puck', 'doccure'),
        'fas fa-home' => esc_html__('home', 'doccure'),
        'fab fa-hooli' => esc_html__('hooli', 'doccure'),
        'fas fa-hospital' => esc_html__('hospital', 'doccure'),
        'far fa-hospital' => esc_html__('hospital', 'doccure'),
        'fas fa-hospital-alt' => esc_html__('hospital-alt', 'doccure'),
        'fas fa-hospital-symbol' => esc_html__('hospital-symbol', 'doccure'),
        'fab fa-hotjar' => esc_html__('hotjar', 'doccure'),
        'fas fa-hourglass' => esc_html__('hourglass', 'doccure'),
        'far fa-hourglass' => esc_html__('hourglass', 'doccure'),
        'fas fa-hourglass-end' => esc_html__('hourglass-end', 'doccure'),
        'fas fa-hourglass-half' => esc_html__('hourglass-half', 'doccure'),
        'fas fa-hourglass-start' => esc_html__('hourglass-start', 'doccure'),
        'fab fa-houzz' => esc_html__('houzz', 'doccure'),
        'fab fa-html5' => esc_html__('html5', 'doccure'),
        'fab fa-hubspot' => esc_html__('hubspot', 'doccure'),
        'fas fa-i-cursor' => esc_html__('i-cursor', 'doccure'),
        'fas fa-id-badge' => esc_html__('id-badge', 'doccure'),
        'far fa-id-badge' => esc_html__('id-badge', 'doccure'),
        'fas fa-id-card' => esc_html__('id-card', 'doccure'),
        'far fa-id-card' => esc_html__('id-card', 'doccure'),
        'fas fa-id-card-alt' => esc_html__('id-card-alt', 'doccure'),
        'fas fa-image' => esc_html__('image', 'doccure'),
        'far fa-image' => esc_html__('image', 'doccure'),
        'fas fa-images' => esc_html__('images', 'doccure'),
        'far fa-images' => esc_html__('images', 'doccure'),
        'fab fa-imdb' => esc_html__('imdb', 'doccure'),
        'fas fa-inbox' => esc_html__('inbox', 'doccure'),
        'fas fa-indent' => esc_html__('indent', 'doccure'),
        'fas fa-industry' => esc_html__('industry', 'doccure'),
        'fas fa-info' => esc_html__('info', 'doccure'),
        'fas fa-info-circle' => esc_html__('info-circle', 'doccure'),
        'fab fa-instagram' => esc_html__('instagram', 'doccure'),
        'fab fa-internet-explorer' => esc_html__('internet-explorer', 'doccure'),
        'fab fa-ioxhost' => esc_html__('ioxhost', 'doccure'),
        'fas fa-italic' => esc_html__('italic', 'doccure'),
        'fab fa-itunes' => esc_html__('itunes', 'doccure'),
        'fab fa-itunes-note' => esc_html__('itunes-note', 'doccure'),
        'fab fa-java' => esc_html__('java', 'doccure'),
        'fab fa-jenkins' => esc_html__('jenkins', 'doccure'),
        'fab fa-joget' => esc_html__('joget', 'doccure'),
        'fab fa-joomla' => esc_html__('joomla', 'doccure'),
        'fab fa-js' => esc_html__('js', 'doccure'),
        'fab fa-js-square' => esc_html__('js-square', 'doccure'),
        'fab fa-jsfiddle' => esc_html__('jsfiddle', 'doccure'),
        'fas fa-key' => esc_html__('key', 'doccure'),
        'fas fa-keyboard' => esc_html__('keyboard', 'doccure'),
        'far fa-keyboard' => esc_html__('keyboard', 'doccure'),
        'fab fa-keycdn' => esc_html__('keycdn', 'doccure'),
        'fab fa-kickstarter' => esc_html__('kickstarter', 'doccure'),
        'fab fa-kickstarter-k' => esc_html__('kickstarter-k', 'doccure'),
        'fab fa-korvue' => esc_html__('korvue', 'doccure'),
        'fas fa-language' => esc_html__('language', 'doccure'),
        'fas fa-laptop' => esc_html__('laptop', 'doccure'),
        'fab fa-laravel' => esc_html__('laravel', 'doccure'),
        'fab fa-lastfm' => esc_html__('lastfm', 'doccure'),
        'fab fa-lastfm-square' => esc_html__('lastfm-square', 'doccure'),
        'fas fa-leaf' => esc_html__('leaf', 'doccure'),
        'fab fa-leanpub' => esc_html__('leanpub', 'doccure'),
        'fas fa-lemon' => esc_html__('lemon', 'doccure'),
        'far fa-lemon' => esc_html__('lemon', 'doccure'),
        'fab fa-less' => esc_html__('less', 'doccure'),
        'fas fa-level-down-alt' => esc_html__('level-down-alt', 'doccure'),
        'fas fa-level-up-alt' => esc_html__('level-up-alt', 'doccure'),
        'fas fa-life-ring' => esc_html__('life-ring', 'doccure'),
        'far fa-life-ring' => esc_html__('life-ring', 'doccure'),
        'fas fa-lightbulb' => esc_html__('lightbulb', 'doccure'),
        'far fa-lightbulb' => esc_html__('lightbulb', 'doccure'),
        'fab fa-line' => esc_html__('line', 'doccure'),
        'fas fa-link' => esc_html__('link', 'doccure'),
        'fab fa-linkedin' => esc_html__('linkedin', 'doccure'),
        'fab fa-linkedin-in' => esc_html__('linkedin-in', 'doccure'),
        'fab fa-linode' => esc_html__('linode', 'doccure'),
        'fab fa-linux' => esc_html__('linux', 'doccure'),
        'fas fa-lira-sign' => esc_html__('lira-sign', 'doccure'),
        'fas fa-list' => esc_html__('list', 'doccure'),
        'fas fa-list-alt' => esc_html__('list-alt', 'doccure'),
        'far fa-list-alt' => esc_html__('list-alt', 'doccure'),
        'fas fa-list-ol' => esc_html__('list-ol', 'doccure'),
        'fas fa-list-ul' => esc_html__('list-ul', 'doccure'),
        'fas fa-location-arrow' => esc_html__('location-arrow', 'doccure'),
        'fas fa-lock' => esc_html__('lock', 'doccure'),
        'fas fa-lock-open' => esc_html__('lock-open', 'doccure'),
        'fas fa-long-arrow-alt-down' => esc_html__('long-arrow-alt-down', 'doccure'),
        'fas fa-long-arrow-alt-left' => esc_html__('long-arrow-alt-left', 'doccure'),
        'fas fa-long-arrow-alt-right' => esc_html__('long-arrow-alt-right', 'doccure'),
        'fas fa-long-arrow-alt-up' => esc_html__('long-arrow-alt-up', 'doccure'),
        'fas fa-low-vision' => esc_html__('low-vision', 'doccure'),
        'fab fa-lyft' => esc_html__('lyft', 'doccure'),
        'fab fa-magento' => esc_html__('magento', 'doccure'),
        'fas fa-magic' => esc_html__('magic', 'doccure'),
        'fas fa-magnet' => esc_html__('magnet', 'doccure'),
        'fas fa-male' => esc_html__('male', 'doccure'),
        'fas fa-map' => esc_html__('map', 'doccure'),
        'far fa-map' => esc_html__('map', 'doccure'),
        'fas fa-map-marker' => esc_html__('map-marker', 'doccure'),
        'far fa-map-marker-alt' => esc_html__('map-marker-alt', 'doccure'),
        'fas fa-map-pin' => esc_html__('map-pin', 'doccure'),
        'fas fa-map-signs' => esc_html__('map-signs', 'doccure'),
        'fas fa-mars' => esc_html__('mars', 'doccure'),
        'fas fa-mars-double' => esc_html__('mars-double', 'doccure'),
        'fas fa-mars-stroke' => esc_html__('mars-stroke', 'doccure'),
        'fas fa-mars-stroke-h' => esc_html__('mars-stroke-h', 'doccure'),
        'fas fa-mars-stroke-v' => esc_html__('mars-stroke-v', 'doccure'),
        'fab fa-maxcdn' => esc_html__('maxcdn', 'doccure'),
        'fab fa-medapps' => esc_html__('medapps', 'doccure'),
        'fab fa-medium' => esc_html__('medium', 'doccure'),
        'fab fa-medium-m' => esc_html__('medium-m', 'doccure'),
        'fas fa-medkit' => esc_html__('medkit', 'doccure'),
        'fab fa-medrt' => esc_html__('medrt', 'doccure'),
        'fab fa-meetup' => esc_html__('meetup', 'doccure'),
        'fas fa-meh' => esc_html__('meh', 'doccure'),
        'far fa-meh' => esc_html__('meh', 'doccure'),
        'fas fa-mercury' => esc_html__('mercury', 'doccure'),
        'fas fa-microchip' => esc_html__('microchip', 'doccure'),
        'fas fa-microphone' => esc_html__('microphone', 'doccure'),
        'fas fa-microphone-slash' => esc_html__('microphone-slash', 'doccure'),
        'fab fa-microsoft' => esc_html__('microsoft', 'doccure'),
        'fas fa-minus' => esc_html__('minus', 'doccure'),
        'fas fa-minus-circle' => esc_html__('minus-circle', 'doccure'),
        'fas fa-minus-square' => esc_html__('minus-square', 'doccure'),
        'far fa-minus-square' => esc_html__('minus-square', 'doccure'),
        'fab fa-mix' => esc_html__('mix', 'doccure'),
        'fab fa-mixcloud' => esc_html__('mixcloud', 'doccure'),
        'fab fa-mizuni' => esc_html__('mizuni', 'doccure'),
        'fas fa-mobile' => esc_html__('mobile', 'doccure'),
        'fas fa-mobile-alt' => esc_html__('mobile-alt', 'doccure'),
        'fab fa-modx' => esc_html__('modx', 'doccure'),
        'fab fa-monero' => esc_html__('monero', 'doccure'),
        'fas fa-money-bill-alt' => esc_html__('money-bill-alt', 'doccure'),
        'far fa-money-bill-alt' => esc_html__('money-bill-alt', 'doccure'),
        'fas fa-moon' => esc_html__('moon', 'doccure'),
        'far fa-moon' => esc_html__('moon', 'doccure'),
        'fas fa-motorcycle' => esc_html__('motorcycle', 'doccure'),
        'fas fa-mouse-pointer' => esc_html__('mouse-pointer', 'doccure'),
        'fas fa-music' => esc_html__('music', 'doccure'),
        'fab fa-napster' => esc_html__('napster', 'doccure'),
        'fas fa-neuter' => esc_html__('neuter', 'doccure'),
        'fas fa-newspaper' => esc_html__('newspaper', 'doccure'),
        'far fa-newspaper' => esc_html__('newspaper', 'doccure'),
        'fab fa-nintendo-switch' => esc_html__('nintendo-switch', 'doccure'),
        'fab fa-node' => esc_html__('node', 'doccure'),
        'fab fa-node-js' => esc_html__('node-js', 'doccure'),
        'fas fa-notes-medical' => esc_html__('notes-medical', 'doccure'),
        'fab fa-npm' => esc_html__('npm', 'doccure'),
        'fab fa-ns8' => esc_html__('ns8', 'doccure'),
        'fab fa-nutritionix' => esc_html__('nutritionix', 'doccure'),
        'fas fa-object-group' => esc_html__('object-group', 'doccure'),
        'far fa-object-group' => esc_html__('object-group', 'doccure'),
        'fas fa-object-ungroup' => esc_html__('object-ungroup', 'doccure'),
        'far fa-object-ungroup' => esc_html__('object-ungroup', 'doccure'),
        'fab fa-odnoklassniki' => esc_html__('odnoklassniki', 'doccure'),
        'fab fa-odnoklassniki-square' => esc_html__('odnoklassniki-square', 'doccure'),
        'fab fa-opencart' => esc_html__('opencart', 'doccure'),
        'fab fa-openid' => esc_html__('openid', 'doccure'),
        'fab fa-opera' => esc_html__('opera', 'doccure'),
        'fab fa-optin-monster' => esc_html__('optin-monster', 'doccure'),
        'fab fa-osi' => esc_html__('osi', 'doccure'),
        'fas fa-outdent' => esc_html__('outdent', 'doccure'),
        'fab fa-page4' => esc_html__('page4', 'doccure'),
        'fab fa-pagelines' => esc_html__('pagelines', 'doccure'),
        'fas fa-paint-brush' => esc_html__('paint-brush', 'doccure'),
        'fab fa-palfed' => esc_html__('palfed', 'doccure'),
        'fas fa-pallet' => esc_html__('pallet', 'doccure'),
        'fas fa-paper-plane' => esc_html__('paper-plane', 'doccure'),
        'far fa-paper-plane' => esc_html__('paper-plane', 'doccure'),
        'fas fa-paperclip' => esc_html__('paperclip', 'doccure'),
        'fas fa-parachute-box' => esc_html__('parachute-box', 'doccure'),
        'fas fa-paragraph' => esc_html__('paragraph', 'doccure'),
        'fas fa-paste' => esc_html__('paste', 'doccure'),
        'fab fa-patreon' => esc_html__('patreon', 'doccure'),
        'fas fa-pause' => esc_html__('pause', 'doccure'),
        'fas fa-pause-circle' => esc_html__('pause-circle', 'doccure'),
        'far fa-pause-circle' => esc_html__('pause-circle', 'doccure'),
        'fas fa-paw' => esc_html__('paw', 'doccure'),
        'fab fa-paypal' => esc_html__('paypal', 'doccure'),
        'fas fa-pen-square' => esc_html__('pen-square', 'doccure'),
        'fas fa-pencil-alt' => esc_html__('pencil-alt', 'doccure'),
        'fas fa-people-carry' => esc_html__('people-carry', 'doccure'),
        'fas fa-percent' => esc_html__('percent', 'doccure'),
        'fab fa-periscope' => esc_html__('periscope', 'doccure'),
        'fab fa-phabricator' => esc_html__('phabricator', 'doccure'),
        'fab fa-phoenix-framework' => esc_html__('phoenix-framework', 'doccure'),
        'far fa-phone' => esc_html__('phone', 'doccure'),
        'far fa-phone-slash' => esc_html__('phone-slash', 'doccure'),
        'far fa-phone-square' => esc_html__('phone-square', 'doccure'),
        'far fa-phone-volume' => esc_html__('phone-volume', 'doccure'),
        'fab fa-php' => esc_html__('php', 'doccure'),
        'fab fa-pied-piper' => esc_html__('pied-piper', 'doccure'),
        'fab fa-pied-piper-alt' => esc_html__('pied-piper-alt', 'doccure'),
        'fab fa-pied-piper-hat' => esc_html__('pied-piper-hat', 'doccure'),
        'fab fa-pied-piper-pp' => esc_html__('pied-piper-pp', 'doccure'),
        'fas fa-piggy-bank' => esc_html__('piggy-bank', 'doccure'),
        'fas fa-pills' => esc_html__('pills', 'doccure'),
        'fab fa-pinterest' => esc_html__('pinterest', 'doccure'),
        'fab fa-pinterest-p' => esc_html__('pinterest-p', 'doccure'),
        'fab fa-pinterest-square' => esc_html__('pinterest-square', 'doccure'),
        'fas fa-plane' => esc_html__('plane', 'doccure'),
        'fas fa-play' => esc_html__('play', 'doccure'),
        'fas fa-play-circle' => esc_html__('play-circle', 'doccure'),
        'far fa-play-circle' => esc_html__('play-circle', 'doccure'),
        'fab fa-playstation' => esc_html__('playstation', 'doccure'),
        'fas fa-plug' => esc_html__('plug', 'doccure'),
        'fas fa-plus' => esc_html__('plus', 'doccure'),
        'fas fa-plus-circle' => esc_html__('plus-circle', 'doccure'),
        'fas fa-plus-square' => esc_html__('plus-square', 'doccure'),
        'far fa-plus-square' => esc_html__('plus-square', 'doccure'),
        'fas fa-podcast' => esc_html__('podcast', 'doccure'),
        'fas fa-poo' => esc_html__('poo', 'doccure'),
        'fas fa-pound-sign' => esc_html__('pound-sign', 'doccure'),
        'fas fa-power-off' => esc_html__('power-off', 'doccure'),
        'fas fa-prescription-bottle' => esc_html__('prescription-bottle', 'doccure'),
        'fas fa-prescription-bottle-alt' => esc_html__('prescription-bottle-alt', 'doccure'),
        'fas fa-print' => esc_html__('print', 'doccure'),
        'fas fa-procedures' => esc_html__('procedures', 'doccure'),
        'fab fa-product-hunt' => esc_html__('product-hunt', 'doccure'),
        'fab fa-pushed' => esc_html__('pushed', 'doccure'),
        'fas fa-puzzle-piece' => esc_html__('puzzle-piece', 'doccure'),
        'fab fa-python' => esc_html__('python', 'doccure'),
        'fab fa-qq' => esc_html__('qq', 'doccure'),
        'fas fa-qrcode' => esc_html__('qrcode', 'doccure'),
        'fas fa-question' => esc_html__('question', 'doccure'),
        'fas fa-question-circle' => esc_html__('question-circle', 'doccure'),
        'far fa-question-circle' => esc_html__('question-circle', 'doccure'),
        'fas fa-quidditch' => esc_html__('quidditch', 'doccure'),
        'fab fa-quinscape' => esc_html__('quinscape', 'doccure'),
        'fab fa-quora' => esc_html__('quora', 'doccure'),
        'fas fa-quote-left' => esc_html__('quote-left', 'doccure'),
        'fas fa-quote-right' => esc_html__('quote-right', 'doccure'),
        'fas fa-random' => esc_html__('random', 'doccure'),
        'fab fa-ravelry' => esc_html__('ravelry', 'doccure'),
        'fab fa-react' => esc_html__('react', 'doccure'),
        'fab fa-readme' => esc_html__('readme', 'doccure'),
        'fab fa-rebel' => esc_html__('rebel', 'doccure'),
        'fas fa-recycle' => esc_html__('recycle', 'doccure'),
        'fab fa-red-river' => esc_html__('red-river', 'doccure'),
        'fab fa-reddit' => esc_html__('reddit', 'doccure'),
        'fab fa-reddit-alien' => esc_html__('reddit-alien', 'doccure'),
        'fab fa-reddit-square' => esc_html__('reddit-square', 'doccure'),
        'fas fa-redo' => esc_html__('redo', 'doccure'),
        'fas fa-redo-alt' => esc_html__('redo-alt', 'doccure'),
        'fas fa-registered' => esc_html__('registered', 'doccure'),
        'far fa-registered' => esc_html__('registered', 'doccure'),
        'fab fa-rendact' => esc_html__('rendact', 'doccure'),
        'fab fa-renren' => esc_html__('renren', 'doccure'),
        'fas fa-reply' => esc_html__('reply', 'doccure'),
        'fas fa-reply-all' => esc_html__('reply-all', 'doccure'),
        'fab fa-replyd' => esc_html__('replyd', 'doccure'),
        'fab fa-resolving' => esc_html__('resolving', 'doccure'),
        'fas fa-retweet' => esc_html__('retweet', 'doccure'),
        'fas fa-ribbon' => esc_html__('ribbon', 'doccure'),
        'fas fa-road' => esc_html__('road', 'doccure'),
        'fas fa-rocket' => esc_html__('rocket', 'doccure'),
        'fab fa-rocketchat' => esc_html__('rocketchat', 'doccure'),
        'fab fa-rockrms' => esc_html__('rockrms', 'doccure'),
        'fas fa-rss' => esc_html__('rss', 'doccure'),
        'fas fa-rss-square' => esc_html__('rss-square', 'doccure'),
        'fas fa-ruble-sign' => esc_html__('ruble-sign', 'doccure'),
        'fas fa-rupee-sign' => esc_html__('rupee-sign', 'doccure'),
        'fab fa-safari' => esc_html__('safari', 'doccure'),
        'fab fa-sass' => esc_html__('sass', 'doccure'),
        'fas fa-save' => esc_html__('save', 'doccure'),
        'far fa-save' => esc_html__('save', 'doccure'),
        'fab fa-schlix' => esc_html__('schlix', 'doccure'),
        'fab fa-scribd' => esc_html__('scribd', 'doccure'),
        'fas fa-search' => esc_html__('search', 'doccure'),
        'fas fa-search-minus' => esc_html__('search-minus', 'doccure'),
        'fas fa-search-plus' => esc_html__('search-plus', 'doccure'),
        'fab fa-searchengin' => esc_html__('searchengin', 'doccure'),
        'fas fa-seedling' => esc_html__('seedling', 'doccure'),
        'fab fa-sellcast' => esc_html__('sellcast', 'doccure'),
        'fab fa-sellsy' => esc_html__('sellsy', 'doccure'),
        'fas fa-server' => esc_html__('server', 'doccure'),
        'fab fa-servicestack' => esc_html__('servicestack', 'doccure'),
        'fas fa-share' => esc_html__('share', 'doccure'),
        'fas fa-share-alt' => esc_html__('share-alt', 'doccure'),
        'fas fa-share-alt-square' => esc_html__('share-alt-square', 'doccure'),
        'fas fa-share-square' => esc_html__('share-square', 'doccure'),
        'far fa-share-square' => esc_html__('share-square', 'doccure'),
        'fas fa-shekel-sign' => esc_html__('shekel-sign', 'doccure'),
        'fas fa-shield-alt' => esc_html__('shield-alt', 'doccure'),
        'fas fa-ship' => esc_html__('ship', 'doccure'),
        'fas fa-shipping-fast' => esc_html__('shipping-fast', 'doccure'),
        'fab fa-shirtsinbulk' => esc_html__('shirtsinbulk', 'doccure'),
        'fas fa-shopping-bag' => esc_html__('shopping-bag', 'doccure'),
        'fas fa-shopping-basket' => esc_html__('shopping-basket', 'doccure'),
        'fas fa-shopping-cart' => esc_html__('shopping-cart', 'doccure'),
        'fas fa-shower' => esc_html__('shower', 'doccure'),
        'fas fa-sign' => esc_html__('sign', 'doccure'),
        'fas fa-sign-in-alt' => esc_html__('sign-in-alt', 'doccure'),
        'fas fa-sign-language' => esc_html__('sign-language', 'doccure'),
        'fas fa-sign-out-alt' => esc_html__('sign-out-alt', 'doccure'),
        'fas fa-signal' => esc_html__('signal', 'doccure'),
        'fab fa-simplybuilt' => esc_html__('simplybuilt', 'doccure'),
        'fab fa-sistrix' => esc_html__('sistrix', 'doccure'),
        'fas fa-sitemap' => esc_html__('sitemap', 'doccure'),
        'fab fa-skyatlas' => esc_html__('skyatlas', 'doccure'),
        'fab fa-skype' => esc_html__('skype', 'doccure'),
        'fab fa-slack' => esc_html__('slack', 'doccure'),
        'fab fa-slack-hash' => esc_html__('slack-hash', 'doccure'),
        'fas fa-sliders-h' => esc_html__('sliders-h', 'doccure'),
        'fab fa-slideshare' => esc_html__('slideshare', 'doccure'),
        'fas fa-smile' => esc_html__('smile', 'doccure'),
        'far fa-smile' => esc_html__('smile', 'doccure'),
        'fas fa-smoking' => esc_html__('smoking', 'doccure'),
        'fab fa-snapchat' => esc_html__('snapchat', 'doccure'),
        'fab fa-snapchat-ghost' => esc_html__('snapchat-ghost', 'doccure'),
        'fab fa-snapchat-square' => esc_html__('snapchat-square', 'doccure'),
        'fas fa-snowflake' => esc_html__('snowflake', 'doccure'),
        'far fa-snowflake' => esc_html__('snowflake', 'doccure'),
        'fas fa-sort' => esc_html__('sort', 'doccure'),
        'fas fa-sort-alpha-down' => esc_html__('sort-alpha-down', 'doccure'),
        'fas fa-sort-alpha-up' => esc_html__('sort-alpha-up', 'doccure'),
        'fas fa-sort-amount-down' => esc_html__('sort-amount-down', 'doccure'),
        'fas fa-sort-amount-up' => esc_html__('sort-amount-up', 'doccure'),
        'fas fa-sort-down' => esc_html__('sort-down', 'doccure'),
        'fas fa-sort-numeric-down' => esc_html__('sort-numeric-down', 'doccure'),
        'fas fa-sort-numeric-up' => esc_html__('sort-numeric-up', 'doccure'),
        'fas fa-sort-up' => esc_html__('sort-up', 'doccure'),
        'fab fa-soundcloud' => esc_html__('soundcloud', 'doccure'),
        'fas fa-space-shuttle' => esc_html__('space-shuttle', 'doccure'),
        'fab fa-speakap' => esc_html__('speakap', 'doccure'),
        'fas fa-spinner' => esc_html__('spinner', 'doccure'),
        'fab fa-spotify' => esc_html__('spotify', 'doccure'),
        'fas fa-square' => esc_html__('square', 'doccure'),
        'far fa-square' => esc_html__('square', 'doccure'),
        'fas fa-square-full' => esc_html__('square-full', 'doccure'),
        'fab fa-stack-exchange' => esc_html__('stack-exchange', 'doccure'),
        'fab fa-stack-overflow' => esc_html__('stack-overflow', 'doccure'),
        'fas fa-star' => esc_html__('star', 'doccure'),
        'far fa-star' => esc_html__('star', 'doccure'),
        'fas fa-star-half' => esc_html__('star-half', 'doccure'),
        'far fa-star-half' => esc_html__('star-half', 'doccure'),
        'fab fa-staylinked' => esc_html__('staylinked', 'doccure'),
        'fab fa-steam' => esc_html__('steam', 'doccure'),
        'fab fa-steam-square' => esc_html__('steam-square', 'doccure'),
        'fab fa-steam-symbol' => esc_html__('steam-symbol', 'doccure'),
        'fas fa-step-backward' => esc_html__('step-backward', 'doccure'),
        'fas fa-step-forward' => esc_html__('step-forward', 'doccure'),
        'fas fa-stethoscope' => esc_html__('stethoscope', 'doccure'),
        'fab fa-sticker-mule' => esc_html__('sticker-mule', 'doccure'),
        'fas fa-sticky-note' => esc_html__('sticky-note', 'doccure'),
        'far fa-sticky-note' => esc_html__('sticky-note', 'doccure'),
        'fas fa-stop' => esc_html__('stop', 'doccure'),
        'fas fa-stop-circle' => esc_html__('stop-circle', 'doccure'),
        'far fa-stop-circle' => esc_html__('stop-circle', 'doccure'),
        'fas fa-stopwatch' => esc_html__('stopwatch', 'doccure'),
        'fab fa-strava' => esc_html__('strava', 'doccure'),
        'fas fa-street-view' => esc_html__('street-view', 'doccure'),
        'fas fa-strikethrough' => esc_html__('strikethrough', 'doccure'),
        'fab fa-stripe' => esc_html__('stripe', 'doccure'),
        'fab fa-stripe-s' => esc_html__('stripe-s', 'doccure'),
        'fab fa-studiovinari' => esc_html__('studiovinari', 'doccure'),
        'fab fa-stumbleupon' => esc_html__('stumbleupon', 'doccure'),
        'fab fa-stumbleupon-circle' => esc_html__('stumbleupon-circle', 'doccure'),
        'fas fa-subscript' => esc_html__('subscript', 'doccure'),
        'fas fa-subway' => esc_html__('subway', 'doccure'),
        'fas fa-suitcase' => esc_html__('suitcase', 'doccure'),
        'fas fa-sun' => esc_html__('sun', 'doccure'),
        'far fa-sun' => esc_html__('sun', 'doccure'),
        'fab fa-superpowers' => esc_html__('superpowers', 'doccure'),
        'fas fa-superscript' => esc_html__('superscript', 'doccure'),
        'fab fa-supple' => esc_html__('supple', 'doccure'),
        'fas fa-sync' => esc_html__('sync', 'doccure'),
        'fas fa-sync-alt' => esc_html__('sync-alt', 'doccure'),
        'fas fa-syringe' => esc_html__('syringe', 'doccure'),
        'fas fa-table' => esc_html__('table', 'doccure'),
        'fas fa-table-tennis' => esc_html__('table-tennis', 'doccure'),
        'fas fa-tablet' => esc_html__('tablet', 'doccure'),
        'fas fa-tablet-alt' => esc_html__('tablet-alt', 'doccure'),
        'fas fa-tablets' => esc_html__('tablets', 'doccure'),
        'fas fa-tachometer-alt' => esc_html__('tachometer-alt', 'doccure'),
        'fas fa-tag' => esc_html__('tag', 'doccure'),
        'fas fa-tags' => esc_html__('tags', 'doccure'),
        'fas fa-tape' => esc_html__('tape', 'doccure'),
        'fas fa-tasks' => esc_html__('tasks', 'doccure'),
        'fas fa-taxi' => esc_html__('taxi', 'doccure'),
        'fab fa-telegram' => esc_html__('telegram', 'doccure'),
        'fab fa-telegram-plane' => esc_html__('telegram-plane', 'doccure'),
        'fab fa-tencent-weibo' => esc_html__('tencent-weibo', 'doccure'),
        'fas fa-terminal' => esc_html__('terminal', 'doccure'),
        'fas fa-text-height' => esc_html__('text-height', 'doccure'),
        'fas fa-text-width' => esc_html__('text-width', 'doccure'),
        'fas fa-th' => esc_html__('th', 'doccure'),
        'fas fa-th-large' => esc_html__('th-large', 'doccure'),
        'fas fa-th-list' => esc_html__('th-list', 'doccure'),
        'fab fa-themeisle' => esc_html__('themeisle', 'doccure'),
        'fas fa-thermometer' => esc_html__('thermometer', 'doccure'),
        'fas fa-thermometer-empty' => esc_html__('thermometer-empty', 'doccure'),
        'fas fa-thermometer-full' => esc_html__('thermometer-full', 'doccure'),
        'fas fa-thermometer-half' => esc_html__('thermometer-half', 'doccure'),
        'fas fa-thermometer-quarter' => esc_html__('thermometer-quarter', 'doccure'),
        'fas fa-thermometer-three-quarters' => esc_html__('thermometer-three-quarters', 'doccure'),
        'fas fa-thumbs-down' => esc_html__('thumbs-down', 'doccure'),
        'far fa-thumbs-down' => esc_html__('thumbs-down', 'doccure'),
        'fas fa-thumbs-up' => esc_html__('thumbs-up', 'doccure'),
        'far fa-thumbs-up' => esc_html__('thumbs-up', 'doccure'),
        'fas fa-thumbtack' => esc_html__('thumbtack', 'doccure'),
        'fas fa-ticket-alt' => esc_html__('ticket-alt', 'doccure'),
        'fas fa-times' => esc_html__('times', 'doccure'),
        'fas fa-times-circle' => esc_html__('times-circle', 'doccure'),
        'far fa-times-circle' => esc_html__('times-circle', 'doccure'),
        'fas fa-tint' => esc_html__('tint', 'doccure'),
        'fas fa-toggle-off' => esc_html__('toggle-off', 'doccure'),
        'fas fa-toggle-on' => esc_html__('toggle-on', 'doccure'),
        'fas fa-trademark' => esc_html__('trademark', 'doccure'),
        'fas fa-train' => esc_html__('train', 'doccure'),
        'fas fa-transgender' => esc_html__('transgender', 'doccure'),
        'fas fa-transgender-alt' => esc_html__('transgender-alt', 'doccure'),
        'fas fa-trash' => esc_html__('trash', 'doccure'),
        'fas fa-trash-alt' => esc_html__('trash-alt', 'doccure'),
        'far fa-trash-alt' => esc_html__('trash-alt', 'doccure'),
        'fas fa-tree' => esc_html__('tree', 'doccure'),
        'fab fa-trello' => esc_html__('trello', 'doccure'),
        'fab fa-tripadvisor' => esc_html__('tripadvisor', 'doccure'),
        'fas fa-trophy' => esc_html__('trophy', 'doccure'),
        'fas fa-truck' => esc_html__('truck', 'doccure'),
        'fas fa-truck-loading' => esc_html__('truck-loading', 'doccure'),
        'fas fa-truck-moving' => esc_html__('truck-moving', 'doccure'),
        'fas fa-tty' => esc_html__('tty', 'doccure'),
        'fab fa-tumblr' => esc_html__('tumblr', 'doccure'),
        'fab fa-tumblr-square' => esc_html__('tumblr-square', 'doccure'),
        'fas fa-tv' => esc_html__('tv', 'doccure'),
        'fab fa-twitch' => esc_html__('twitch', 'doccure'),
        'fab fa-twitter' => esc_html__('twitter', 'doccure'),
        'fab fa-twitter-square' => esc_html__('twitter-square', 'doccure'),
        'fab fa-typo3' => esc_html__('typo3', 'doccure'),
        'fab fa-uber' => esc_html__('uber', 'doccure'),
        'fab fa-uikit' => esc_html__('uikit', 'doccure'),
        'fas fa-umbrella' => esc_html__('umbrella', 'doccure'),
        'fas fa-underline' => esc_html__('underline', 'doccure'),
        'fas fa-undo' => esc_html__('undo', 'doccure'),
        'fas fa-undo-alt' => esc_html__('undo-alt', 'doccure'),
        'fab fa-uniregistry' => esc_html__('uniregistry', 'doccure'),
        'fas fa-universal-access' => esc_html__('universal-access', 'doccure'),
        'fas fa-university' => esc_html__('university', 'doccure'),
        'fas fa-unlink' => esc_html__('unlink', 'doccure'),
        'fas fa-unlock' => esc_html__('unlock', 'doccure'),
        'fas fa-unlock-alt' => esc_html__('unlock-alt', 'doccure'),
        'fab fa-untappd' => esc_html__('untappd', 'doccure'),
        'fas fa-upload' => esc_html__('upload', 'doccure'),
        'fab fa-usb' => esc_html__('usb', 'doccure'),
        'fas fa-user' => esc_html__('user', 'doccure'),
        'far fa-user' => esc_html__('user', 'doccure'),
        'fas fa-user-circle' => esc_html__('user-circle', 'doccure'),
        'far fa-user-circle' => esc_html__('user-circle', 'doccure'),
        'fas fa-user-md' => esc_html__('user-md', 'doccure'),
        'fas fa-user-plus' => esc_html__('user-plus', 'doccure'),
        'fas fa-user-secret' => esc_html__('user-secret', 'doccure'),
        'fas fa-user-times' => esc_html__('user-times', 'doccure'),
        'fas fa-users' => esc_html__('users', 'doccure'),
        'fab fa-ussunnah' => esc_html__('ussunnah', 'doccure'),
        'fas fa-utensil-spoon' => esc_html__('utensil-spoon', 'doccure'),
        'fas fa-utensils' => esc_html__('utensils', 'doccure'),
        'fab fa-vaadin' => esc_html__('vaadin', 'doccure'),
        'fas fa-venus' => esc_html__('venus', 'doccure'),
        'fas fa-venus-double' => esc_html__('venus-double', 'doccure'),
        'fas fa-venus-mars' => esc_html__('venus-mars', 'doccure'),
        'fab fa-viacoin' => esc_html__('viacoin', 'doccure'),
        'fab fa-viadeo' => esc_html__('viadeo', 'doccure'),
        'fab fa-viadeo-square' => esc_html__('viadeo-square', 'doccure'),
        'fas fa-vial' => esc_html__('vial', 'doccure'),
        'fas fa-vials' => esc_html__('vials', 'doccure'),
        'fab fa-viber' => esc_html__('viber', 'doccure'),
        'fas fa-video' => esc_html__('video', 'doccure'),
        'fas fa-video-slash' => esc_html__('video-slash', 'doccure'),
        'fab fa-vimeo' => esc_html__('vimeo', 'doccure'),
        'fab fa-vimeo-square' => esc_html__('vimeo-square', 'doccure'),
        'fab fa-vimeo-v' => esc_html__('vimeo-v', 'doccure'),
        'fab fa-vine' => esc_html__('vine', 'doccure'),
        'fab fa-vk' => esc_html__('vk', 'doccure'),
        'fab fa-vnv' => esc_html__('vnv', 'doccure'),
        'fas fa-volleyball-ball' => esc_html__('volleyball-ball', 'doccure'),
        'fas fa-volume-down' => esc_html__('volume-down', 'doccure'),
        'fas fa-volume-off' => esc_html__('volume-off', 'doccure'),
        'fas fa-volume-up' => esc_html__('volume-up', 'doccure'),
        'fab fa-vuejs' => esc_html__('vuejs', 'doccure'),
        'fas fa-warehouse' => esc_html__('warehouse', 'doccure'),
        'fab fa-weibo' => esc_html__('weibo', 'doccure'),
        'fas fa-weight' => esc_html__('weight', 'doccure'),
        'fab fa-weixin' => esc_html__('weixin', 'doccure'),
        'fab fa-whatsapp' => esc_html__('whatsapp', 'doccure'),
        'fab fa-whatsapp-square' => esc_html__('whatsapp-square', 'doccure'),
        'fas fa-wheelchair' => esc_html__('wheelchair', 'doccure'),
        'fab fa-whmcs' => esc_html__('whmcs', 'doccure'),
        'fas fa-wifi' => esc_html__('wifi', 'doccure'),
        'fab fa-wikipedia-w' => esc_html__('wikipedia-w', 'doccure'),
        'fas fa-window-close' => esc_html__('window-close', 'doccure'),
        'far fa-window-close' => esc_html__('window-close', 'doccure'),
        'fas fa-window-maximize' => esc_html__('window-maximize', 'doccure'),
        'far fa-window-maximize' => esc_html__('window-maximize', 'doccure'),
        'fas fa-window-minimize' => esc_html__('window-minimize', 'doccure'),
        'far fa-window-minimize' => esc_html__('window-minimize', 'doccure'),
        'fas fa-window-restore' => esc_html__('window-restore', 'doccure'),
        'far fa-window-restore' => esc_html__('window-restore', 'doccure'),
        'fab fa-windows' => esc_html__('windows', 'doccure'),
        'fas fa-wine-glass' => esc_html__('wine-glass', 'doccure'),
        'fas fa-won-sign' => esc_html__('won-sign', 'doccure'),
        'fab fa-wordpress' => esc_html__('wordpress', 'doccure'),
        'fab fa-wordpress-simple' => esc_html__('wordpress-simple', 'doccure'),
        'fab fa-wpbeginner' => esc_html__('wpbeginner', 'doccure'),
        'fab fa-wpexplorer' => esc_html__('wpexplorer', 'doccure'),
        'fab fa-wpforms' => esc_html__('wpforms', 'doccure'),
        'fas fa-wrench' => esc_html__('wrench', 'doccure'),
        'fas fa-x-ray' => esc_html__('x-ray', 'doccure'),
        'fab fa-xbox' => esc_html__('xbox', 'doccure'),
        'fab fa-xing' => esc_html__('xing', 'doccure'),
        'fab fa-xing-square' => esc_html__('xing-square', 'doccure'),
        'fab fa-y-combinator' => esc_html__('y-combinator', 'doccure'),
        'fab fa-yahoo' => esc_html__('yahoo', 'doccure'),
        'fab fa-yandex' => esc_html__('yandex', 'doccure'),
        'fab fa-yandex-international' => esc_html__('yandex-international', 'doccure'),
        'fab fa-yelp' => esc_html__('yelp', 'doccure'),
        'fas fa-yen-sign' => esc_html__('yen-sign', 'doccure'),
        'fab fa-yoast' => esc_html__('yoast', 'doccure'),
        'fab fa-youtube' => esc_html__('youtube', 'doccure'),
        'fab fa-youtube-square' => esc_html__('youtube-square', 'doccure'),
    );
}

/**
 * Returns smooth scroll animation.
 *
 * @since 2.0.0
 */
function doccure_smooth_scroll()
{
    if (doccure_get_option('enable-smooth-scroll') == true) {
        if (doccure_get_option('smooth_scroll_style') == 'ultra_speed' && !empty(doccure_get_option('smooth_scroll_style'))) {
            $animation_speed = 100;
            $step_size = 70;
        } elseif (doccure_get_option('smooth_scroll_style') == 'fast_speed' && !empty(doccure_get_option('smooth_scroll_style'))) {
            $animation_speed = 300;
            $step_size = 70;
        } elseif (doccure_get_option('smooth_scroll_style') == 'moderate_speed' && !empty(doccure_get_option('smooth_scroll_style'))) {
            $animation_speed = 700;
            $step_size = 70;
        } elseif (doccure_get_option('smooth_scroll_style') == 'default_speed' && !empty(doccure_get_option('smooth_scroll_style'))) {
            $animation_speed = 400;
            $step_size = 70;
        } elseif (doccure_get_option('smooth_scroll_style') == 'slow_speed' && !empty(doccure_get_option('smooth_scroll_style'))) {
            $animation_speed = 1500;
            $step_size = 100;
        } elseif (doccure_get_option('smooth_scroll_style') == 'super_slow_speed' && !empty(doccure_get_option('smooth_scroll_style'))) {
            $animation_speed = 3000;
            $step_size = 100;
        } elseif (doccure_get_option('smooth_scroll_style') == 'snail_speed' && !empty(doccure_get_option('smooth_scroll_style'))) {
            $animation_speed = 5000;
            $step_size = 100;
        } else {
            $animation_speed = 400;
            $step_size = 70;
        }
        ?>
        <script>
            jQuery(document).ready(function ($) {
                SmoothScroll({
                    animationTime: <?php echo esc_js($animation_speed); ?>,
                    stepSize: <?php echo esc_js($step_size); ?>,
                });

                var scroll = new SmoothScroll();

            });
        </script>
        <?php
    }
}

add_action('wp_footer', 'doccure_smooth_scroll', 90);







add_action('wp_ajax_send_otp', 'send_otp');
add_action('wp_ajax_nopriv_send_otp', 'send_otp');

function send_otp() {
    global $doccure_options;
    check_ajax_referer('send_otp_nonce', 'security');

    $email = sanitize_email($_POST['email']);
    $otp = mt_rand(100000, 999999);

    // Set OTP in transient for 3 minutes
    set_transient('otp_' . md5($email), $otp, 180);

    // Verify options are set correctly
    if (empty($doccure_options)) {
        error_log("doccure_options is empty or missing.");
    }

    $otp_emails_name = $doccure_options['otp_emails_name'] ?? 'Default Name';
    $otp_emails_from_email = $doccure_options['otp_emails_from_email'] ?? 'no-reply@domain.com';
    $otp_subject_from_email = $doccure_options['otp_subject_from_email'] ?? 'Your Confirmation Code';
    $otp_content_from_email = $doccure_options['otp_content_from_email'] ?? 'Your OTP code is {otp}.';
    $otp_email_logo = $doccure_options['otp_email_logo'];
    $otp_email_logo_url = is_array($otp_email_logo) && isset($otp_email_logo['url']) ? $otp_email_logo['url'] : '';


    // $email_logo = $doccure_options['email_logo'];
    // $email_logo_url = $email_logo['url'];
    
    // Replace placeholders in message content
    // $message = str_replace(
    //     array('{otp_email_logo}', '{user_email}', '{otp}'),
    //     array($otp_email_logo_url, $email, $otp),
    //     $otp_content_from_email
    // );

    $subject = $otp_subject_from_email;

    $email_content = str_replace(
        [ '{user_email}', '{otp}'],
        [ $email, $otp],
        $otp_content_from_email
    );

    
    $message = '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; border: 1px solid #ddd; }
            .header { text-align: center; margin-bottom: 30px; }
            .header img { max-width: 150px; }
            .content { background: #fff; padding: 20px; border-radius: 5px; }
            .content h2 { color: #333; }
            .content p { margin-bottom: 20px; }
            .content ul { padding-left:0px; }
            .button { text-align: center; margin-top: 20px; }
            .button a { background-color: #0073e6; color: #fff; padding: 12px 20px; border-radius: 5px; text-decoration: none; font-weight: bold; }
            .footer { margin-top: 30px; text-align: center; color: #999; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">';
    if (!empty($otp_email_logo_url)) {
        $message .= '<img src="' . esc_url($otp_email_logo_url) . '" alt="Logo">';
    }
    $message .= '</div>

            <div class="content">' . $email_content . '</div>

            <div class="footer">
                &copy; ' . date("Y") . ' ' . get_bloginfo('name') . '. All rights reserved.
            </div>
        </div>
    </body>
    </html>';


 
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $otp_emails_name . ' <' . $otp_emails_from_email . '>'
    ];

    if (wp_mail($email, $subject, $message, $headers)) {
        wp_send_json_success();
    } else {
        error_log("Failed to send OTP to $email with subject: $subject.");
        wp_send_json_error(array('message' => 'Failed to send OTP.'));
    }

    wp_die();
}






add_action('wp_ajax_verify_otp', 'verify_otp');
add_action('wp_ajax_nopriv_verify_otp', 'verify_otp');

function verify_otp() {
    check_ajax_referer('send_otp_nonce', 'security');

    $email = sanitize_email($_POST['email']);
    $otp = sanitize_text_field($_POST['otp']);
    
    $stored_otp = get_transient('otp_' . md5($email));

    if ($stored_otp && $stored_otp == $otp) {
        delete_transient('otp_' . md5($email));
        wp_send_json_success();
    } else {
        wp_send_json_error(array('message' => 'Invalid or expired OTP.'));
    }

    wp_die();
}
?>
