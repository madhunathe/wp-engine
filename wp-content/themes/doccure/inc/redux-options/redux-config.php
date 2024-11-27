<?php
/**
 * ReduxFramework Sample Config File
 * For full documentation, please visit: http://docs.reduxframework.com/
 */
if (!class_exists('Redux')) {
    return;
}

global $opt_name;
$opt_name = "doccure_options";

$opt_name = apply_filters('redux_demo/opt_name', $opt_name); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores

/**
 * ---> SET ARGUMENTS
 * All the possible arguments for Redux.
 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
 * */
$theme = wp_get_theme();
$args = array(
    'opt_name' => $opt_name,
    'display_name' => $theme->get('Name'),
    'display_version' => $theme->get('Version'),
    'menu_type' => 'menu',
    'allow_sub_menu' => false,
    /* Translators: %s: Theme Name. */
    'menu_title' => sprintf(esc_html__('%s - Theme Options', 'doccure'), $theme->get('Name')),
    /* Translators: %s: Theme Name. */
    'page_title' => sprintf(esc_html__('%s - Theme Options', 'doccure'), $theme->get('Name')),
    'google_api_key' => '',
    'google_update_weekly' => false,
    'async_typography' => true,
    'admin_bar' => true,
    'admin_bar_icon' => 'dashicons-portfolio',
    'admin_bar_priority' => 50,
    'global_variable' => 'doccure_options',
    'dev_mode' => false,
    'update_notice' => true,
    'customizer' => true,
    'page_priority' => null,
    'page_parent' => 'themes.php',
    'page_permissions' => 'manage_options',
    'menu_icon' => '',
    'last_tab' => '',
    'page_icon' => 'icon-themes',
    'page_slug' => 'doccure-options',
    'save_defaults' => true,
    'default_show' => false,
    'default_mark' => '',
    'show_import_export' => true,
    'transient_time' => 60 * MINUTE_IN_SECONDS,
    'output' => true,
    'output_tag' => true,
    'database' => '',
    'use_cdn' => true,
    'hints' => array(
        'icon' => 'el el-question-sign',
        'icon_position' => 'right',
        'icon_color' => 'lightgray',
        'icon_size' => 'normal',
        'tip_style' => array(
            'color' => 'red',
            'shadow' => true,
            'rounded' => false,
            'style' => '',
        ),
        'tip_position' => array(
            'my' => 'top left',
            'at' => 'bottom right',
        ),
        'tip_effect' => array(
            'show' => array(
                'effect' => 'slide',
                'duration' => '500',
                'event' => 'mouseover',
            ),
            'hide' => array(
                'effect' => 'slide',
                'duration' => '500',
                'event' => 'click mouseleave',
            ),
        ),
    ),
);

Redux::setArgs($opt_name, $args);

$options_files = array(
   
    get_template_directory() . '/inc/redux-options/options/theme-layout.php',
    get_template_directory() . '/inc/redux-options/options/styling-settings.php',
    get_template_directory() . '/inc/redux-options/options/header-settings.php',
    //get_template_directory() . '/inc/redux-options/options/contact-info.php',
    get_template_directory() . '/inc/redux-options/options/header-logo-settings.php',
    //get_template_directory() . '/inc/redux-options/options/header-top-settings.php',
    
    get_template_directory() . '/inc/redux-options/options/subheader-settings.php',
    get_template_directory() . '/inc/redux-options/options/blog-settings.php',
    get_template_directory() . '/inc/redux-options/options/page-settings.php',
    get_template_directory() . '/inc/redux-options/options/404.php',
    get_template_directory() . '/inc/redux-options/options/article-settings.php',
    get_template_directory() . '/inc/redux-options/options/booking-settings.php',
   // get_template_directory() . '/inc/redux-options/options/email-settings.php',
   get_template_directory() . '/inc/redux-options/options/otp-verification.php',
   get_template_directory() . '/inc/redux-options/options/email-settings-new.php',
    get_template_directory() . '/inc/redux-options/options/booking-settings.php',
   
    get_template_directory() . '/inc/redux-options/options/payment-settings.php',
    get_template_directory() . '/inc/redux-options/options/social-settings.php',
    get_template_directory() . '/inc/redux-options/options/directories-settings.php',
    get_template_directory() . '/inc/redux-options/options/search-doctors.php',
    get_template_directory() . '/inc/redux-options/options/registration-settings.php',
    get_template_directory() . '/inc/redux-options/options/zoom-settings.php',
    get_template_directory() . '/inc/redux-options/options/footer-settings.php',

);

////$options_files = apply_filters('doccure_redux_option_files', $options_files);

foreach ($options_files as $option_file) {
    if (file_exists($option_file)) {
        $option_data = include($option_file); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
        if ($option_data && is_array($option_data)) {
            Redux::setSection('doccure_options', $option_data);
        }
    }
}
