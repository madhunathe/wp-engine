<?php
if (!function_exists('doccure_color_customize_classes')) {
    /**
     * Get color customizer classe
     *
     * @param array $data contains the css values.
     * @return string
     */
    function doccure_custom_dynamic_style()
    {
        global $doccure_options;
        // Primary Color
        $primary_color = !empty(doccure_get_option('primary_color')) ? doccure_get_option('primary_color') : '#15558d';
        $primary_hover_color = !empty(doccure_get_option('primary_hover_color')) ? doccure_get_option('primary_hover_color') : '';
        if (!empty($primary_hover_color)) {
            $primary_color_dark = $primary_hover_color;
        } elseif (!empty($primary_color) && empty($primary_hover_color)) {
            $primary_color_dark = doccure_darken_color($primary_color, $darker = 1.2);
        }
        if (!empty($primary_color)) {
            $primary_color_rgba = doccure_hex_to_rgb($primary_color, $alpha = 0.2);
        }

        // Secondary Color
        $secondary_color = !empty(doccure_get_option('secondary_color')) ? doccure_get_option('secondary_color') : '#333';
        $secondary_hover_color = !empty(doccure_get_option('secondary_hover_color')) ? doccure_get_option('secondary_hover_color') : '';
        if (!empty($secondary_hover_color)) {
            $secondary_color_dark = $secondary_hover_color;
        } elseif (!empty($secondary_color) && empty($secondary_hover_color)) {
            $secondary_color_dark = doccure_darken_color($secondary_color, $darker = 1.2);
        }
        if (!empty($secondary_color)) {
            $secondary_color_rgba = doccure_hex_to_rgb($secondary_color, $alpha = 0.2);
        }
        // Tertiary Color
        $tertiary_color = !empty(doccure_get_option('tertiary_color')) ? doccure_get_option('tertiary_color') : '#f8f8f8';
        if (!empty($tertiary_color)) {
            $tertiary_color_dark = doccure_darken_color($tertiary_color, $darker = 1.2);
            $tertiary_color_rgba = doccure_hex_to_rgb($tertiary_color, $alpha = 0.2);
        }
        /*------- Header Colors --------*/
        // Header top bar
        $header_top_bg_color = !empty(doccure_get_option('header_top_bg')) ? doccure_get_option('header_top_bg') : '';
        $header_top_color = isset($doccure_options['header_top_color']) ? $doccure_options['header_top_color'] : '';
        $header_top_color_hover = isset($doccure_options['header_top_color_hover']) ? $doccure_options['header_top_color_hover'] : '';
        $header_top_border_color = !empty(doccure_get_option('header_top_border_color')) ? doccure_get_option('header_top_border_color') : '';
        $header_top_size = (!empty($doccure_options['adjust-custom-header-top-width']) && $doccure_options['adjust-custom-header-top-width'] == '1' && $doccure_options['header-top-width-style'] == 'custom-width') ? $doccure_options['header_top_content_size_custom'] : '';
        // Header main
        $header_main_bg = !empty(doccure_get_option('header_main_bg')) ? doccure_get_option('header_main_bg') : '';
        $header_main_color = !empty(doccure_get_option('header_main_color')) ? doccure_get_option('header_main_color') : '';
        $header_main_color_hover = !empty(doccure_get_option('header_main_color_hover')) ? doccure_get_option('header_main_color_hover') : '';
        $header_submenu_bg = !empty(doccure_get_option('header_submenu_bg')) ? doccure_get_option('header_submenu_bg') : '';
        $header_submenu_color = !empty(doccure_get_option('header_submenu_color')) ? doccure_get_option('header_submenu_color') : '';
        $header_submenu_color_hover = !empty(doccure_get_option('header_submenu_color_hover')) ? doccure_get_option('header_submenu_color_hover') : '';
         // Header Bottom
        $header_bottom_bg_color = !empty(doccure_get_option('header_bottom_bg_color')) ? doccure_get_option('header_bottom_bg_color') : '';
        $header_bottom_color = !empty(doccure_get_option('header_bottom_color')) ? doccure_get_option('header_bottom_color') : '';
        $header_bottom_hover_color = !empty(doccure_get_option('header_bottom_hover_color')) ? doccure_get_option('header_bottom_hover_color') : '';
        $header_bottom_border_color = !empty(doccure_get_option('header_bottom_border_color')) ? doccure_get_option('header_bottom_border_color') : '';
        $header_collapse_bg_color = !empty(doccure_get_option('header_collapse_bg_color')) ? doccure_get_option('header_collapse_bg_color') : '';
        $header_collapse_color = !empty(doccure_get_option('header_collapse_color')) ? doccure_get_option('header_collapse_color') : '';
        // header sticky Color
        $header_sticky_bg = !empty(doccure_get_option('header_sticky_bg')) ? doccure_get_option('header_sticky_bg') : '';
        $header_sticky_color = !empty(doccure_get_option('header_sticky_color')) ? doccure_get_option('header_sticky_color') : '';
        $header_sticky_color_hover = !empty(doccure_get_option('header_sticky_color_hover')) ? doccure_get_option('header_sticky_color_hover') : '';
        // Header controls
        $header_controls_bg_color = !empty(doccure_get_option('header_controls_bg_color')) ? doccure_get_option('header_controls_bg_color') : '';
        $header_controls_text_color = !empty(doccure_get_option('header_controls_text_color')) ? doccure_get_option('header_controls_text_color') : '';
        $header_controls_border_color = !empty(doccure_get_option('header_controls_border_color')) ? doccure_get_option('header_controls_border_color') : '';
        $header_controls_hover_bg_color = !empty(doccure_get_option('header_controls_hover_bg_color')) ? doccure_get_option('header_controls_hover_bg_color') : '';
        $header_controls_hover_text_color = !empty(doccure_get_option('header_controls_hover_text_color')) ? doccure_get_option('header_controls_hover_text_color') : '';
        $header_controls_hover_border_color = !empty(doccure_get_option('header_controls_hover_border_color')) ? doccure_get_option('header_controls_hover_border_color') : '';
        // Header Contact Info
         // Header call to Action
        $header_top_btn_bg = !empty(doccure_get_option('header_top_btn_bg_color')) ? doccure_get_option('header_top_btn_bg_color') : '';
        $header_top_btn_color = !empty(doccure_get_option('header_top_btn_color')) ? doccure_get_option('header_top_btn_color') : '';
        // Header logo
        $info_card_bg = isset($doccure_options['info_card_bg']) ? $doccure_options['info_card_bg'] : '';
        $info_card_color = isset($doccure_options['info_card_color']) ? $doccure_options['info_card_color'] : '';
        // Header social links
        $header_top_social_bg_color = !empty(doccure_get_option('header_top_social_bg_color')) ? doccure_get_option('header_top_social_bg_color') : '';
        $header_top_social_color = !empty(doccure_get_option('header_top_social_color')) ? doccure_get_option('header_top_social_color') : '';
        $header_top_social_border_color = !empty(doccure_get_option('header_top_social_border_color')) ? doccure_get_option('header_top_social_border_color') : '';
        $header_top_social_hover_bg_color = !empty(doccure_get_option('header_top_social_hover_bg_color')) ? doccure_get_option('header_top_social_hover_bg_color') : '';
        $header_top_social_hover_color = !empty(doccure_get_option('header_top_social_hover_color')) ? doccure_get_option('header_top_social_hover_color') : '';
        $header_top_social_hover_border_color = !empty(doccure_get_option('header_top_social_hover_border_color')) ? doccure_get_option('header_top_social_hover_border_color') : '';
        // Header cta info
        $cta_info_title_color = !empty(doccure_get_option('cta_info_title_color')) ? doccure_get_option('cta_info_title_color') : '';
        $cta_info_subtitle_color = !empty(doccure_get_option('cta_info_subtitle_color')) ? doccure_get_option('cta_info_subtitle_color') : '';
        $cta_info_icon_color = !empty(doccure_get_option('cta_info_icon_color')) ? doccure_get_option('cta_info_icon_color') : '';
        $cta_info_icon_bg_color = !empty(doccure_get_option('cta_info_icon_bg_color')) ? doccure_get_option('cta_info_icon_bg_color') : '';
        $cta_info_icon_border_color = !empty(doccure_get_option('cta_info_icon_border_color')) ? doccure_get_option('cta_info_icon_border_color') : '';
        $cta_info_hover_color = !empty(doccure_get_option('cta_info_hover_color')) ? doccure_get_option('cta_info_hover_color') : '';
        // Header cta button
        $header_cta_bg_color = !empty(doccure_get_option('header_cta_bg_color')) ? doccure_get_option('header_cta_bg_color') : '';
        $header_cta_color = !empty(doccure_get_option('header_cta_color')) ? doccure_get_option('header_cta_color') : '';
        $header_cta_hover_bg_color = !empty(doccure_get_option('header_cta_hover_bg_color')) ? doccure_get_option('header_cta_hover_bg_color') : '';
        $header_cta_hover_color = !empty(doccure_get_option('header_cta_hover_color')) ? doccure_get_option('header_cta_hover_color') : '';

        /*----- Subheader Colors ---*/
        $subheader_background_color = !empty(doccure_get_option('subheader_background_color')) ? doccure_get_option('subheader_background_color') : '';
        // Breadcrumbs
        $breadcrumb_color = !empty(doccure_get_option('breadcrumb_color')) ? doccure_get_option('breadcrumb_color') : '';
        $breadcrumb_link_color = !empty(doccure_get_option('breadcrumb_link_color')) ? doccure_get_option('breadcrumb_link_color') : '';
        $breadcrumb_link_color_hover = !empty(doccure_get_option('breadcrumb_link_color_hover')) ? doccure_get_option('breadcrumb_link_color_hover') : '';
        $breadcrumb_bg = isset($doccure_options['breadcrumb_bg']) ? $doccure_options['breadcrumb_bg'] : '';
        $breadcrumb_bg_color = !empty(doccure_get_option('breadcrumb_bg_color')) ? doccure_get_option('breadcrumb_bg_color') : '';
        // Title
        $subheader_title_color = !empty(doccure_get_option('subheader_title_color')) ? doccure_get_option('subheader_title_color') : '';
 
        /*------- Footer Colors --------*/
        $footer_background = !empty(doccure_get_option('footer_background')) ? doccure_get_option('footer_background')['background-color'] : '';
        $footer_widget_title_color = !empty(doccure_get_option('footer_widget_title_color')) ? doccure_get_option('footer_widget_title_color') : '';
        $footer_text_color = !empty(doccure_get_option('footer_text_color')) ? doccure_get_option('footer_text_color') : '';
        $footer_text_hover_color = !empty(doccure_get_option('footer_text_hover_color')) ? doccure_get_option('footer_text_hover_color') : '';
        $footer_social_bg_color = !empty(doccure_get_option('footer_social_bg_color')) ? doccure_get_option('footer_social_bg_color') : '';
        $footer_social_color = !empty(doccure_get_option('footer_social_color')) ? doccure_get_option('footer_social_color') : '';
        $footer_social_hover_bg_color = !empty(doccure_get_option('footer_social_hover_bg_color')) ? doccure_get_option('footer_social_hover_bg_color') : '';
        $footer_social_hover_color = !empty(doccure_get_option('footer_social_hover_color')) ? doccure_get_option('footer_social_hover_color') : '';
        $footer_border_color = !empty(doccure_get_option('footer_border_color')) ? doccure_get_option('footer_border_color') : '';
        $footer_form_input_bg_color = !empty(doccure_get_option('footer_form_input_bg_color')) ? doccure_get_option('footer_form_input_bg_color') : '';
        $footer_form_input_border_color = !empty(doccure_get_option('footer_form_input_border_color')) ? doccure_get_option('footer_form_input_border_color') : '';

        /*------- Theme Layout Colors --------*/
        // Body text color
        $body_color = isset($doccure_options['body_color']) ? $doccure_options['body_color'] : '';
        // Back to top
        $back_to_top_bg = isset($doccure_options['back_to_top_bg']) ? $doccure_options['back_to_top_bg'] : '';
        $back_to_top_bg_hover = isset($doccure_options['back_to_top_bg_hover']) ? $doccure_options['back_to_top_bg_hover'] : '';
        $back_to_color = isset($doccure_options['back_to_color']) ? $doccure_options['back_to_color'] : '';
        $back_to_color_hover = isset($doccure_options['back_to_color_hover']) ? $doccure_options['back_to_color_hover'] : '';

        // Pre-loaders color
        $preloader_bg = isset($doccure_options['preloader_bg']) ? $doccure_options['preloader_bg'] : '';
        $preloader_color = isset($doccure_options['preloader_color']) ? $doccure_options['preloader_color'] : '';

        /*---- 404 Page colors ----*/
        $page_404_title_color = isset($doccure_options['404_title_color']) ? $doccure_options['404_title_color'] : '';
        $page_404_desc_color = isset($doccure_options['404_desc_color']) ? $doccure_options['404_desc_color'] : '';

        /*------ Theme Typography ----*/
       // $body_typography		= $doccure_options['body_typography'];
    		//$body_typography_ff = doccure_is_not_empty($body_typography, 'font-family') ? $body_typography['font-family'] : '';
    		//$body_typography_fw = doccure_is_not_empty($body_typography, 'font-weight') ? $body_typography['font-weight'] : '';
    		//$body_typography_ls = doccure_is_not_empty($body_typography, 'letter-spacing') ? $body_typography['letter-spacing'] : '';
    		//$body_typography_lh = doccure_is_not_empty($body_typography, 'line-height') ? $body_typography['line-height'] : '';
    		//$body_typography_fs = doccure_is_not_empty($body_typography, 'font-size') ? $body_typography['font-size'] : '';

        //$heading_typography		= $doccure_options['heading_typography'];
    		//$heading_typography_ff = doccure_is_not_empty($heading_typography, 'font-family') ? $heading_typography['font-family'] : '';

        /*------ Newsletter Popup ----*/
        $newsl_pp_title_color = isset($doccure_options['newsl_pp_title_color']) ? $doccure_options['newsl_pp_title_color'] : '';
        $newsl_pp_desc_color = isset($doccure_options['newsl_pp_desc_color']) ? $doccure_options['newsl_pp_desc_color'] : '';
        $newsl_pp_bg_color = isset($doccure_options['newsl_pp_bg_color']) ? $doccure_options['newsl_pp_bg_color'] : '';
        $newsl_pp_close_trigg_bg_color = isset($doccure_options['newsl_pp_close_trigg_bg_color']) ? $doccure_options['newsl_pp_close_trigg_bg_color'] : '';
        $newsl_pp_close_trigg_color = isset($doccure_options['newsl_pp_close_trigg_color']) ? $doccure_options['newsl_pp_close_trigg_color'] : '';

        ob_start();
        ?>

        :root {
        <?php 
         
        if (!empty($primary_color_rgba)) { ?>
            --thm-base-rgb: <?php echo esc_attr($primary_color_rgba['r']); ?>, <?php echo esc_attr($primary_color_rgba['g']); ?>, <?php echo esc_attr($primary_color_rgba['b']); ?>;
        <?php } if(!empty($primary_color)) { ?>
            --thm-base-hue: <?php echo esc_attr($primary_color); ?>40;
        <?php } if (!empty($secondary_color)) { ?>
            --thm-secondary: <?php echo esc_attr($secondary_color); ?>;
        <?php }
        if (!empty($secondary_color_dark)) { ?>
            --thm-secondary-hover: <?php echo esc_attr($secondary_color_dark); ?>;
        <?php }
        if (!empty($secondary_color_rgba)) { ?>
            --thm-secondary-rgb: <?php echo esc_attr($secondary_color_rgba['r']); ?>, <?php echo esc_attr($secondary_color_rgba['b']); ?>, <?php echo esc_attr($secondary_color_rgba['b']); ?>;
        <?php }
        if (!empty($tertiary_color)) { ?>
          --thm-tertiary: <?php echo esc_attr($tertiary_color); ?>;
        <?php }
        if(!empty($body_color)) { ?>
          --doccure_terthemecolor: <?php echo esc_attr($body_color); ?>;
        <?php }
        if(!empty($body_typography_ff)) { ?>
          --thm-b-font : <?php echo esc_attr($body_typography_ff); ?>;
        <?php }
          if(!empty($heading_typography_ff)) { ?>
            --thm-font : <?php echo esc_attr($heading_typography_ff); ?>;
        <?php } ?>
        }

        <?php if (!empty($header_top_bg_color)) { ?>
        .doccure_header-top,
        .doccure_header.header-layout-7 .doccure_header-top,
        .doccure_header.header-layout-8 .doccure_header-top,
        .doccure_header.header-layout-10 .doccure_header-top {
        background-color: <?php echo esc_attr($header_top_bg_color); ?>;
        }
        <?php }
        if (!empty($header_top_color)) { ?>
            .doccure_header-top{
            color: <?php echo esc_attr($header_top_color); ?>;
            }
        <?php }
        if (!empty($header_top_color_hover)) { ?>
            .doccure_header-top a:hover{
            color: <?php echo esc_attr($header_top_color_hover); ?>;
            }
        <?php }
        if (!empty($header_top_border_color)) { ?>
            .doccure_header-top{
            border-bottom-color: <?php echo esc_attr($header_top_border_color); ?>;
            }
        <?php }
        if (!empty($header_main_bg)) { ?>
            .doccure_header-middle,
            .doccure_header.header-layout-9 .doccure_header-middle,
            .doccure_header.header-layout-10 .doccure_header-middle .container-fluid,
            .doccure_header.header-layout-10 .doccure_header-middle .container .navbar{
            background-color: <?php echo esc_attr($header_main_bg); ?>;
            }
        <?php }
        if (!empty($header_bottom_bg_color)) { ?>
            .doccure_header .doccure_header-bottom,
            .doccure_header.header-layout-5 .doccure_header-bottom,
            .doccure_header.header-layout-8 .doccure_header-bottom,
            .doccure_header.header-layout-9 .doccure_header-bottom{
            background-color: <?php echo esc_attr($header_bottom_bg_color); ?>;
            }
        <?php }
        if (!empty($header_main_color)) { ?>
            .doccure_header .navbar-nav > li > a{
            color: <?php echo esc_attr($header_main_color); ?>;
            }
            .doccure_header.header-layout-7 .aside-toggle span{
              background-color: <?php echo esc_attr($header_main_color); ?>;
            }
        <?php }
        if (!empty($header_main_color_hover)) { ?>
            .doccure_header .navbar-nav > li > a:hover{
            color: <?php echo esc_attr($header_main_color_hover); ?>;
            }
            .doccure_header.header-layout-7 .aside-toggle:hover span{
              background-color: <?php echo esc_attr($header_main_color_hover); ?>;
            }
        <?php } ?>
        <?php if (!empty($header_bottom_color)) { ?>
        .doccure_header .doccure_header-bottom .navbar-nav > li > a,
        .doccure_header.header-layout-5 .doccure_header-bottom .navbar-nav > li > a,
        .doccure_header.header-layout-8 .doccure_header-bottom .navbar-nav > li > a,
        .doccure_header.header-layout-9 .doccure_header-bottom .navbar-nav > li > a{
        color: <?php echo esc_attr($header_bottom_color); ?>;
        }
    <?php } ?>
        <?php if (!empty($header_bottom_hover_color)) { ?>
        .doccure_header .doccure_header-bottom .navbar-nav > li > a:hover,
        .doccure_header.header-layout-5 .doccure_header-bottom .navbar-nav > li > a:hover,
        .doccure_header.header-layout-8 .doccure_header-bottom .navbar-nav > li > a:hover,
        .doccure_header.header-layout-8 .doccure_header-bottom .navbar-nav > li.active > a,
        .doccure_header.header-layout-9 .doccure_header-bottom .navbar-nav > li > a:hover,
        .doccure_header.header-layout-9 .doccure_header-bottom .navbar-nav > li.active > a{
        color: <?php echo esc_attr($header_bottom_hover_color); ?>;
        }
    <?php } ?>
        <?php if (!empty($header_bottom_border_color)) { ?>
        .doccure_header.header-layout-5 .doccure_header-bottom .navbar{
        border-top-color: <?php echo esc_attr($header_bottom_border_color); ?>;
        }
        .doccure_header.header-layout-8 .doccure_header-middle{
          border-bottom-color: <?php echo esc_attr($header_bottom_border_color); ?>;
        }
        .doccure_header.header-layout-8 .doccure_logo-wrapper{
          border-right-color: <?php echo esc_attr($header_bottom_border_color); ?>;
        }
        .doccure_header.header-layout-8 .doccure_header-middle .doccure_header-controls{
          border-left-color: <?php echo esc_attr($header_bottom_border_color); ?>;
        }
    <?php }
        if (!empty($header_collapse_bg_color)) { ?>
            .doccure_header .doccure_header-bottom-inner .aside-toggle.desktop-toggler,
            .doccure_header.header-layout-9 .doccure_header-middle .doccure_header-controls ul li.aside-toggle{
            background-color: <?php echo esc_attr($header_collapse_bg_color); ?>;
            }
        <?php } ?>
        <?php if (!empty($header_collapse_color)) { ?>
        .doccure_header .doccure_header-bottom-inner .aside-toggle.desktop-toggler span,
        .doccure_header.header-layout-9 .doccure_header-middle .aside-toggle span{
        background: <?php echo esc_attr($header_collapse_color); ?>;
        }
    <?php } ?>
        <?php if (!empty($header_submenu_bg)) { ?>
        .doccure_header .navbar-nav li .sub-menu,
        body .doccure_mega-menu-wrapper{
        background: <?php echo esc_attr($header_submenu_bg); ?>;
        }
    <?php } ?>
        <?php if (!empty($header_submenu_color)) { ?>
        .doccure_header .navbar-nav li .sub-menu li a,
        .doccure_header.header-layout-8 .doccure_header-bottom .navbar-nav li .sub-menu li a{
        color: <?php echo esc_attr($header_submenu_color); ?>;
        }
    <?php } ?>
        <?php if (!empty($header_submenu_color_hover)) { ?>
        .doccure_header .navbar-nav li .sub-menu li a:hover,
        .doccure_header.header-layout-8 .doccure_header-bottom .navbar-nav li .sub-menu li a:hover{
        color: <?php echo esc_attr($header_submenu_color_hover); ?>;
        }
    <?php }
        if (!empty($header_controls_bg_color)) { ?>
            .doccure_header .doccure_header-controls ul li.header-controls-item a,
            .doccure_header .doccure_header-controls ul li.aside-toggle.aside-trigger,
            .doccure_header.header-layout-4 .aside-toggle.desktop-toggler,
            .doccure_header.header-layout-5 .aside-toggle.desktop-toggler,
            .doccure_header.header-layout-6 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li a,
            .doccure_header.header-layout-6 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler,
            .doccure_header.header-layout-8 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler,
            .doccure_header.header-layout-9 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler,
            .doccure_header.header-layout-10 .doccure_header-middle .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler,
            .doccure_header.header-layout-1 .aside-toggle.desktop-toggler{
              background-color: <?php echo esc_attr($header_controls_bg_color); ?>;
            }
        <?php }
        if (!empty($header_controls_text_color)) { ?>
            .doccure_header .doccure_header-controls ul li.header-controls-item a,
            .doccure_header .doccure_header-controls ul li.header-controls-item a.doccure_header-control-cart p,
            .doccure_header.header-layout-6 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li a,
            .doccure_header.header-layout-8 .doccure_header-bottom .doccure_header-controls .header-controls-item .search-form button,
            .doccure_header.header-layout-8 .doccure_header-bottom .doccure_header-controls .header-controls-item .search-form input::placeholder,
            .doccure_header.header-layout-8 .doccure_header-bottom .doccure_header-controls .header-controls-item .search-form input,
            .doccure_header.header-layout-9 .doccure_header-bottom .doccure_header-controls .header-controls-item .search-form button,
            .doccure_header.header-layout-9 .doccure_header-bottom .doccure_header-controls .header-controls-item .search-form input::placeholder,
            .doccure_header.header-layout-9 .doccure_header-bottom .doccure_header-controls .header-controls-item .search-form input{
              color: <?php echo esc_attr($header_controls_text_color); ?>;
            }
            .doccure_header .doccure_header-controls .aside-toggle span,
            .doccure_header.header-layout-4 .aside-toggle.desktop-toggler span,
            .doccure_header.header-layout-5 .aside-toggle.desktop-toggler span,
            .doccure_header.header-layout-6 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler span,
            .doccure_header.header-layout-8 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler span,
            .doccure_header.header-layout-9 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler span,
            .doccure_header.header-layout-10 .doccure_header-middle .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler span{
              background-color: <?php echo esc_attr($header_controls_text_color); ?>;
            }
        <?php }
        if (!empty($header_controls_border_color)) { ?>
            .doccure_header .doccure_header-controls ul li.header-controls-item a,
            .doccure_header .doccure_header-controls ul li.aside-toggle.aside-trigger,
            .doccure_header.header-layout-4 .aside-toggle.desktop-toggler,
            .doccure_header.header-layout-5 .aside-toggle.desktop-toggler,
            .doccure_header.header-layout-6 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li,
            .doccure_header.header-layout-6 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler,
            .doccure_header.header-layout-8 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler,
            .doccure_header.header-layout-9 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler,
            .doccure_header.header-layout-10 .doccure_header-middle .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler,
            .doccure_header.header-layout-1 .aside-toggle.desktop-toggler{
              border-color: <?php echo esc_attr($header_controls_border_color); ?>;
            }
        <?php }
        if (!empty($header_controls_hover_bg_color)) { ?>
            .doccure_header .doccure_header-controls ul li.header-controls-item a:hover,
            .doccure_header .doccure_header-controls ul li.aside-toggle.aside-trigger:hover,
            .doccure_header.header-layout-4 .aside-toggle.desktop-toggler:hover,
            .doccure_header.header-layout-5 .aside-toggle.desktop-toggler:hover,
            .doccure_header.header-layout-6 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li a:hover,
            .doccure_header.header-layout-6 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler:hover,
            .doccure_header.header-layout-8 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler:hover,
            .doccure_header.header-layout-9 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler:hover,
            .doccure_header.header-layout-10 .doccure_header-middle .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler:hover,
            .doccure_header.header-layout-1 .aside-toggle.desktop-toggler:hover{
              background-color: <?php echo esc_attr($header_controls_hover_bg_color); ?>;
            }
        <?php }
        if (!empty($header_controls_hover_text_color)) { ?>
            .doccure_header .doccure_header-controls ul li.header-controls-item a:hover,
            .doccure_header .doccure_header-controls ul li.header-controls-item a.doccure_header-control-cart:hover p,
            .doccure_header.header-layout-6 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li a:hover,
            .doccure_header.header-layout-8 .doccure_header-bottom .doccure_header-controls .header-controls-item .search-form button:hover,
            .doccure_header.header-layout-8 .doccure_header-bottom .doccure_header-controls .header-controls-item .search-form input:focus::placeholder,
            .doccure_header.header-layout-9 .doccure_header-bottom .doccure_header-controls .header-controls-item .search-form button:hover,
            .doccure_header.header-layout-9 .doccure_header-bottom .doccure_header-controls .header-controls-item .search-form input:focus::placeholder{
              color: <?php echo esc_attr($header_controls_hover_text_color); ?>;
            }
            .doccure_header .doccure_header-controls ul li.aside-toggle.aside-trigger:hover span,
            .doccure_header.header-layout-4 .aside-toggle.desktop-toggler:hover span,
            .doccure_header.header-layout-5 .aside-toggle.desktop-toggler:hover span,
            .doccure_header.header-layout-6 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler:hover span,
            .doccure_header.header-layout-8 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler:hover span,
            .doccure_header.header-layout-9 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler:hover span,
            .doccure_header.header-layout-10 .doccure_header-middle .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler:hover span,
            .doccure_header.header-layout-1 .aside-toggle.desktop-toggler:hover span{
            background-color: <?php echo esc_attr($header_controls_hover_text_color); ?>;
            }
        <?php }
        if (!empty($header_controls_hover_border_color)) { ?>
            .doccure_header .doccure_header-controls ul li.header-controls-item a:hover,
            .doccure_header .doccure_header-controls ul li.aside-toggle.aside-trigger:hover,
            .doccure_header.header-layout-4 .aside-toggle.desktop-toggler:hover,
            .doccure_header.header-layout-6 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li:hover,
            .doccure_header.header-layout-6 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler:hover,
            .doccure_header.header-layout-8 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler:hover,
            .doccure_header.header-layout-9 .doccure_header-bottom .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler:hover,
            .doccure_header.header-layout-10 .doccure_header-middle .doccure_header-controls .doccure_header-controls-inner li.aside-toggle.desktop-toggler:hover,
            .doccure_header.header-layout-1 .aside-toggle.desktop-toggler:hover{
            border-color: <?php echo esc_attr($header_controls_hover_border_color); ?>;
            }
        <?php } ?>
    
       
        <?php if (!empty($header_top_btn_bg)) { ?>
        .doccure_header-top .doccure_header-top-cta li a,
        .doccure_header.header-layout-7 .doccure_header-top .doccure_header-top-cta li a,
        .doccure_header.header-layout-8 .doccure_header-top .doccure_header-top-cta li a{
          background-color: <?php echo esc_attr($header_top_btn_bg); ?>;
        }
        .doccure_header.header-layout-7 .doccure_header-top .doccure_header-top-cta li a::before,
        .doccure_header.header-layout-8 .doccure_header-top .doccure_header-top-cta li a::before{
          background: #00000040;
        }
    <?php } ?>
        <?php if (!empty($header_top_btn_color)) { ?>
        .doccure_header-top .doccure_header-top-cta li a,
        .doccure_header.header-layout-7 .doccure_header-top .doccure_header-top-cta li a,
        .doccure_header.header-layout-8 .doccure_header-top .doccure_header-top-cta li a{
          color: <?php echo esc_attr($header_top_btn_color); ?>;
        }
    <?php } ?>
        <?php if (!empty($header_sticky_bg)) { ?>
        .doccure_header.sticky:not(.header-layout-10) .doccure_header-middle,
        .doccure_header.header-layout-10.sticky .doccure_header-middle .navbar{
        background-color: <?php echo esc_attr($header_sticky_bg); ?>;
        }
    <?php } ?>
        <?php if (!empty($header_sticky_color)) { ?>
        .doccure_header.can-sticky .doccure_header-middle .navbar-nav > li > a{
        color: <?php echo esc_attr($header_sticky_color); ?>;
        }
        .doccure_header.header-layout-7.sticky .aside-toggle{
          border-color: <?php echo esc_attr($header_sticky_color); ?>;
        }
        .doccure_header.header-layout-7.sticky .aside-toggle span{
          background-color: <?php echo esc_attr($header_sticky_color); ?>;
        }
    <?php } ?>
        <?php if (!empty($header_sticky_color_hover)) { ?>
        .doccure_header.can-sticky .doccure_header-middle .navbar-nav > li > a:hover{
        color: <?php echo esc_attr($header_sticky_color_hover); ?>;
        }
    <?php } ?>
        <?php if (!empty($header_top_social_bg_color)) { ?>
        .doccure_header.header-layout-1 .doccure_header-top-contacts .doccure_header-top-nav.social-info li a,
        .doccure_header.header-layout-3 .doccure_header-top-contacts .doccure_header-top-nav li a,
        .doccure_header.header-layout-4 .doccure_header-top-contacts .doccure_header-top-nav li a,
        .doccure_header.header-layout-6 .doccure_header-top-contacts .doccure_header-top-nav li a{
        background-color: <?php echo esc_attr($header_top_social_bg_color); ?>;
        }
    <?php }
        if (!empty($header_top_social_color)) { ?>
            .doccure_header.header-layout-1 .doccure_header-top-contacts .doccure_header-top-nav.social-info li a,
            .doccure_header.header-layout-3 .doccure_header-top-contacts .doccure_header-top-nav li a,
            .doccure_header.header-layout-4 .doccure_header-top-contacts .doccure_header-top-nav li a,
            .doccure_header.header-layout-5 .doccure_header-top-contacts .doccure_header-top-nav.social-info li a,
            .doccure_header.header-layout-6 .doccure_header-top-contacts .doccure_header-top-nav li a,
            .doccure_header.header-layout-7 .doccure_header-top-contacts .doccure_header-top-nav.social-info li a,
            .doccure_header.header-layout-8 .doccure_header-top-contacts .doccure_header-top-nav.social-info li a,
            .doccure_header.header-layout-10 .doccure_header-top-inner .doccure_header-top-contacts .doccure_header-top-nav.social-info li a
            {
            color: <?php echo esc_attr($header_top_social_color); ?>;
            }
        <?php }
        if (!empty($header_top_social_border_color)) { ?>
            .doccure_header.header-layout-1 .doccure_header-top-contacts .doccure_header-top-nav.social-info li a,
            .doccure_header.header-layout-3 .doccure_header-top-contacts .doccure_header-top-nav li a,
            .doccure_header.header-layout-4 .doccure_header-top-contacts .doccure_header-top-nav li a,
            .doccure_header.header-layout-6 .doccure_header-top-contacts .doccure_header-top-nav li a{
            border-color: <?php echo esc_attr($header_top_social_border_color); ?>;
            }
        <?php }
        if (!empty($header_top_social_hover_bg_color)) { ?>
            .doccure_header.header-layout-1 .doccure_header-top-contacts .doccure_header-top-nav.social-info li a:hover,
            .doccure_header.header-layout-3 .doccure_header-top-contacts .doccure_header-top-nav li a:hover,
            .doccure_header.header-layout-4 .doccure_header-top-contacts .doccure_header-top-nav li a:hover,
            .doccure_header.header-layout-6 .doccure_header-top-contacts .doccure_header-top-nav li a:hover{
            background-color: <?php echo esc_attr($header_top_social_hover_bg_color); ?>;
            }
        <?php }
        if (!empty($header_top_social_hover_color)) { ?>
            .doccure_header.header-layout-1 .doccure_header-top-contacts .doccure_header-top-nav.social-info li a:hover,
            .doccure_header.header-layout-3 .doccure_header-top-contacts .doccure_header-top-nav li a:hover,
            .doccure_header.header-layout-4 .doccure_header-top-contacts .doccure_header-top-nav li a:hover,
            .doccure_header.header-layout-5 .doccure_header-top-contacts .doccure_header-top-nav.social-info li a:hover,
            .doccure_header.header-layout-6 .doccure_header-top-contacts .doccure_header-top-nav li a:hover,
            .doccure_header.header-layout-7 .doccure_header-top-contacts .doccure_header-top-nav.social-info li a:hover,
            .doccure_header.header-layout-8 .doccure_header-top-contacts .doccure_header-top-nav.social-info li a:hover,
            .doccure_header.header-layout-10 .doccure_header-top-inner .doccure_header-top-contacts .doccure_header-top-nav.social-info li a:hover
            {
            color: <?php echo esc_attr($header_top_social_hover_color); ?>;
            }
        <?php }
        if (!empty($header_top_social_hover_border_color)) { ?>
            .doccure_header.header-layout-1 .doccure_header-top-contacts .doccure_header-top-nav.social-info li a:hover,
            .doccure_header.header-layout-3 .doccure_header-top-contacts .doccure_header-top-nav li a:hover,
            .doccure_header.header-layout-4 .doccure_header-top-contacts .doccure_header-top-nav li a:hover,
            .doccure_header.header-layout-6 .doccure_header-top-contacts .doccure_header-top-nav li a:hover{
            border-color: <?php echo esc_attr($header_top_social_hover_border_color); ?>;
            }
        <?php }
          if (!empty($header_size)) { ?>
            @media (min-width: 991px){
            .doccure_header .doccure_header-middle > .container,
            .doccure_header .doccure_header-bottom > .container {
            max-width: <?php echo esc_attr($header_size . 'px'); ?>;
            }
          }
        <?php } ?>
        <?php if (!empty($header_top_size)) { ?>
        @media (min-width: 991px){
        .doccure_header .doccure_header-top > .container {
        max-width: <?php echo esc_attr($header_top_size . 'px'); ?>;
        }
        }
    <?php } ?>
        <?php if (!empty($info_card_bg)) { ?>
        .doccure_logo-wrapper .logo-infocard{
        background-color: <?php echo esc_attr($info_card_bg); ?>;
        }
        header.doccure_header .logo-infocard .doccure_header-top-contacts .doccure_header-top-nav li a,
        .doccure_header.header-layout-5 .logo-infocard .doccure_header-top-contacts .doccure_header-top-nav.social-info li a{
        color: <?php echo esc_attr($info_card_bg); ?>;
        }
    <?php } ?>
        <?php if (!empty($info_card_color)) { ?>
        .doccure_logo-wrapper .logo-infocard p, .doccure_logo-wrapper .logo-infocard strong, .doccure_logo-wrapper .logo-infocard a{
        color: <?php echo esc_attr($info_card_color); ?>;
        }
        .doccure_logo-wrapper .logo-infocard .contact-item svg path{
        fill: <?php echo esc_attr($info_card_color); ?>;
        }
        .doccure_logo-wrapper .logo-infocard::after{
        border-bottom-color: <?php echo esc_attr($info_card_color); ?>;
        }
        .doccure_logo-wrapper .logo-infocard::before{
        border-top-color: <?php echo esc_attr($info_card_color); ?>;
        }
        header.doccure_header .logo-infocard .doccure_header-top-contacts .doccure_header-top-nav li a,
        .doccure_header.header-layout-5 .logo-infocard .doccure_header-top-contacts .doccure_header-top-nav.social-info li a{
        background-color: <?php echo esc_attr($info_card_color); ?>;
        }
    <?php } ?>
        <?php if (!empty($cta_info_title_color)) { ?>
        .doccure_header.header-layout-5 .doccure_header-middle .doccure_header-top-nav.cta-info li a p b,
        .doccure_header.header-layout-6 .doccure_header-middle .doccure_header-top-nav.cta-info li a p b,
        .doccure_header.header-layout-7 .doccure_header-top-nav.cta-info li a div p:first-child,
        .doccure_header.header-layout-8 .doccure_header-middle .doccure_header-controls .cta-info li a div p b,
        .doccure_header.header-layout-9 .doccure_header-middle .doccure_header-top-links li a p b{
          color: <?php echo esc_attr($cta_info_title_color); ?>;
        }
    <?php }
        if (!empty($cta_info_subtitle_color)) { ?>
            .doccure_header.header-layout-5 .doccure_header-middle .doccure_header-top-nav.cta-info li a p,
            .doccure_header.header-layout-6 .doccure_header-middle .doccure_header-top-nav.cta-info li a p,
            .doccure_header.header-layout-8 .doccure_header-middle .doccure_header-controls .cta-info li a div p,
            .doccure_header.header-layout-7 .doccure_header-top-nav.cta-info li a div p b,
            .doccure_header.header-layout-9 .doccure_header-middle .doccure_header-top-links li a p{
              color: <?php echo esc_attr($cta_info_subtitle_color); ?>;
            }
        <?php }
        if (!empty($cta_info_icon_color)) { ?>
            .doccure_header.header-layout-5 .doccure_header-middle .doccure_header-top-nav.cta-info li a i,
            .doccure_header.header-layout-6 .doccure_header-middle .doccure_header-top-nav.cta-info li a i,
            .doccure_header.header-layout-8 .doccure_header-middle .doccure_header-controls .cta-info li a i,
            .doccure_header.header-layout-9 .doccure_header-middle .doccure_header-top-links li a i,
            .doccure_header.header-layout-8 .doccure_header-middle .doccure_header-controls .cta-info li:nth-child(2) a i,
            .doccure_header.header-layout-7 .doccure_header-top-nav.cta-info li a i{
            color: <?php echo esc_attr($cta_info_icon_color); ?>;
            }
        <?php }
        if (!empty($cta_info_icon_border_color)) { ?>
            .doccure_header.header-layout-5 .doccure_header-middle .doccure_header-top-nav.cta-info li a i,
            .doccure_header.header-layout-8 .doccure_header-middle .doccure_header-controls .cta-info li a i,
            .doccure_header.header-layout-8 .doccure_header-middle .doccure_header-controls .cta-info li:nth-child(2) a i,
            .doccure_header.header-layout-6 .doccure_header-middle .doccure_header-top-nav.cta-info li a i,
            .doccure_header.header-layout-9 .doccure_header-middle .doccure_header-top-links li a i,
            .doccure_header.header-layout-6 .doccure_header-middle .doccure_header-top-nav.cta-info li + li a{
            border-color: <?php echo esc_attr($cta_info_icon_border_color); ?>;
            }
        <?php }
        if (!empty($cta_info_icon_bg_color)) { ?>
            .doccure_header.header-layout-5 .doccure_header-middle .doccure_header-top-nav.cta-info li a i,
            .doccure_header.header-layout-6 .doccure_header-middle .doccure_header-top-nav.cta-info li a i,
            .doccure_header.header-layout-8 .doccure_header-middle .doccure_header-controls .cta-info li a i,
            .doccure_header.header-layout-9 .doccure_header-middle .doccure_header-top-links li a i,
            .doccure_header.header-layout-8 .doccure_header-middle .doccure_header-controls .cta-info li:nth-child(2) a i{
            background-color: <?php echo esc_attr($cta_info_icon_bg_color); ?>;
            }
        <?php }
        if (!empty($cta_info_hover_color)) { ?>
            .doccure_header.header-layout-5 .doccure_header-middle .doccure_header-top-nav.cta-info li a:hover p,
            .doccure_header.header-layout-5 .doccure_header-middle .doccure_header-top-nav.cta-info li a:hover p b,
            .doccure_header.header-layout-6 .doccure_header-middle .doccure_header-top-nav.cta-info li a:hover p,
            .doccure_header.header-layout-6 .doccure_header-middle .doccure_header-top-nav.cta-info li a:hover p b,
            .doccure_header.header-layout-8 .doccure_header-middle .doccure_header-controls .cta-info li a:hover div p b,
            .doccure_header.header-layout-8 .doccure_header-middle .doccure_header-controls .cta-info li a:hover div p,
            .doccure_header.header-layout-9 .doccure_header-middle .doccure_header-top-links li a:hover div p b,
            .doccure_header.header-layout-9 .doccure_header-middle .doccure_header-top-links li a:hover div p,
            .doccure_header.header-layout-7 .doccure_header-top-nav.cta-info li a:hover div p:first-child {
            color: <?php echo esc_attr($cta_info_hover_color); ?>;
            }
        <?php } ?>
        <?php if (!empty($header_cta_bg_color)) { ?>
        .doccure_header.header-layout-5 .doccure_header-middle .doccure_btn,
        .doccure_header.header-layout-6 .doccure_header-bottom .doccure_btn,
        .doccure_header.header-layout-10 .doccure_header-middle .doccure_btn{
          background-color: <?php echo esc_attr($header_cta_bg_color); ?>;
        }
    <?php }
        if (!empty($header_cta_color)) { ?>
            .doccure_header.header-layout-5 .doccure_header-middle .doccure_btn,
            .doccure_header.header-layout-6 .doccure_header-bottom .doccure_btn,
            .doccure_header.header-layout-10 .doccure_header-middle .doccure_btn{
              color: <?php echo esc_attr($header_cta_color); ?>;
            }
        <?php }
        if (!empty($header_cta_hover_bg_color)) { ?>
            .doccure_header.header-layout-5 .doccure_header-middle .doccure_btn:hover::before,
            .doccure_header.header-layout-6 .doccure_header-bottom .doccure_btn:hover:before,
            .doccure_header.header-layout-10 .doccure_header-middle .doccure_btn:hover:before{
            background-color: <?php echo esc_attr($header_cta_hover_bg_color); ?>;
            }
        <?php }
        if (!empty($header_cta_hover_color)) { ?>
            .doccure_header.header-layout-5 .doccure_header-middle .doccure_btn:hover,
            .doccure_header.header-layout-6 .doccure_header-bottom .doccure_btn:hover,
            .doccure_header.header-layout-10 .doccure_header-middle .doccure_btn:hover{
              color: <?php echo esc_attr($header_cta_hover_color); ?>;
            }
        <?php } ?>

        <?php if (!empty($breadcrumb_color)) { ?>
        .doccure_subheader .doccure_subheader-inner .breadcrumb-nav .breadcrumb li.breadcrumb-item.active,
        .doccure_subheader .doccure_subheader-inner .breadcrumb-nav .breadcrumb li.breadcrumb-item.active span b,
        .doccure_subheader .doccure_subheader-inner .breadcrumb-nav .breadcrumb li.breadcrumb-item.active + .breadcrumb-item-page,
        .doccure_subheader .breadcrumb-nav .breadcrumb li.breadcrumb-item.active
        .doccure_subheader-inner .breadcrumb-nav .breadcrumb li.breadcrumb-item:before,
        .breadcrumb-nav .breadcrumb li.breadcrumb-item:before,
        .breadcrumb-nav.below-subheader .breadcrumb li,
        .breadcrumb-nav.below-subheader .breadcrumb .breadcrumb-item+.breadcrumb-item::before,
        .doccure_subheader .doccure_subheader-inner .breadcrumb-nav .breadcrumb li.breadcrumb-item:before,
        .doccure_subheader .breadcrumb-nav .breadcrumb li.breadcrumb-item:before,
        .doccure_subheader.style-2 .breadcrumb-nav .breadcrumb .breadcrumb-item.active,
        .doccure_subheader .doccure_subheader-inner .breadcrumb-nav .breadcrumb li i,
        .doccure_subheader.style-2 .breadcrumb-nav .breadcrumb li i{
        color: <?php echo esc_attr($breadcrumb_color); ?>;
        }
    <?php }
        if (!empty($breadcrumb_link_color)) { ?>
            .doccure_subheader .doccure_subheader-inner .breadcrumb-nav .breadcrumb li.breadcrumb-item a,
            .doccure_subheader .breadcrumb-nav .breadcrumb li.breadcrumb-item a,
            .breadcrumb-nav.below-subheader .breadcrumb li a{
            color: <?php echo esc_attr($breadcrumb_link_color); ?>;
            }
        <?php }
        if (!empty($breadcrumb_link_color_hover)) { ?>
            .doccure_subheader .doccure_subheader-inner .breadcrumb-nav .breadcrumb li.breadcrumb-item a:hover,
            .doccure_subheader .breadcrumb-nav .breadcrumb li.breadcrumb-item a:hover,
            .breadcrumb-nav.below-subheader .breadcrumb li a:hover{
            color: <?php echo esc_attr($breadcrumb_link_color_hover); ?>;
            }
        <?php }
        if (!empty($breadcrumb_bg)) { ?>
            .breadcrumb-nav.below-subheader{
            background-color: <?php echo esc_attr($breadcrumb_link_color_hover); ?>;
            }
        <?php }
        if (!empty($breadcrumb_bg_color)) { ?>
            .doccure_subheader.style-2 .breadcrumb-nav{
            background-color: <?php echo esc_attr($breadcrumb_bg_color); ?>;
            }
        <?php }
        if (!empty($subheader_title_color)) { ?>
            .doccure_subheader .doccure_subheader-inner .page-title,
            .doccure_subheader.style-6 .doccure_subheader-inner .page-title{
            color: <?php echo esc_attr($subheader_title_color); ?>;
            }
        <?php }
       
        if ($doccure_options['display_subheader_overlay'] == 1) {
            if (isset($subheader_background_color['rgba']) && !empty($subheader_background_color['rgba'])) { ?>
                .doccure_subheader.dark-overlay::before,
                .doccure_subheader.style-6 .doccure_subheader-inner .page-title:before,
                .doccure_subheader.style-6 .doccure_subheader-inner .page-title:after{
                background-color: <?php echo esc_attr($subheader_background_color['rgba']); ?>;
                }
            <?php }
        } ?>
        <?php if(!empty($footer_background)) { ?>
          .doccure_footer{
            background-color: <?php echo esc_attr($footer_background); ?>;
          }
        <?php } if (!empty($footer_text_color)) { ?>
        .doccure_footer .doccure_footer-links li a,
        .doccure_footer p,
        .doccure_footer .doccure_footer-copyright,
        .doccure_footer, .doccure_footer a,
        .doccure_footer .doccure_footer-copyright a,
        .doccure_footer-middle .widget_doccure_recent_entries .doccure_recent-post .recent-post-descr h6 a,
        .doccure-footer-widgets-wrapper .widget ul li a{
        color: <?php echo esc_attr($footer_text_color); ?>;
        }
    <?php }
        if (!empty($footer_text_hover_color)) { ?>
            .doccure_footer .doccure_footer-links li a:hover,
            .doccure_footer .doccure_footer-copyright a:hover,
            .doccure_footer-middle .widget_doccure_recent_entries .doccure_recent-post .recent-post-descr h6 a:hover,
            .doccure-footer-widgets-wrapper .widget ul li a:hover{
            color: <?php echo esc_attr($footer_text_hover_color); ?>;
            }
        <?php }
        if (!empty($footer_social_bg_color)) { ?>
            .doccure_footer .doccure_social-icons li a{
            background-color: <?php echo esc_attr($footer_social_bg_color); ?>;
            }
        <?php }
        if (!empty($footer_social_color)) { ?>
            .doccure_footer .doccure_social-icons li a{
            color: <?php echo esc_attr($footer_social_color); ?>;
            }
        <?php }
        if (!empty($footer_social_hover_bg_color)) { ?>
            .doccure_footer .doccure_social-icons li a:hover{
            background-color: <?php echo esc_attr($footer_social_hover_bg_color); ?>;
            }
        <?php }
        if (!empty($footer_social_hover_color)) { ?>
            .doccure_footer .doccure_social-icons li a:hover,
            .doccure-footer-widgets-wrapper .widget .doccure_social-icons li a:hover{
            color: <?php echo esc_attr($footer_social_hover_color); ?>;
            }
        <?php }
        if (!empty($footer_border_color)) { ?>
            .doccure_footer .doccure_footer-bottom,
            .doccure_footer-layout-8 .doccure_footer-middle{
            border-top-color: <?php echo esc_attr($footer_border_color); ?>;
            }
        <?php }
        if (!empty($footer_form_input_bg_color)) { ?>
            .doccure_footer .mc4wp-form-fields input{
            background-color: <?php echo esc_attr($footer_form_input_bg_color); ?>;
            }
        <?php }
        if (!empty($footer_form_input_border_color)) { ?>
            .doccure_footer .mc4wp-form-fields input{
            border-color: <?php echo esc_attr($footer_form_input_border_color); ?>;
            }
        <?php }
        if (!empty($footer_widget_title_color)) { ?>
            .doccure-footer-widgets-wrapper .widget h6.widget-title,
            .doccure_footer .doccure_footer-widget .widget-title{
            color: <?php echo esc_attr($footer_widget_title_color); ?>;
            }
        <?php } ?>

        <?php if (!empty($back_to_top_bg)) { ?>
        .doccure_to-top {
        background-color: <?php echo esc_attr($back_to_top_bg); ?>;
        }
    <?php }
        if (!empty($back_to_top_bg_hover)) { ?>
            .doccure_to-top:hover {
            background-color: <?php echo esc_attr($back_to_top_bg_hover); ?>;
            }
        <?php }
        if (!empty($back_to_color)) { ?>
            .doccure_to-top i {
            color: <?php echo esc_attr($back_to_color); ?>;
            }
        <?php }
        if (!empty($back_to_color_hover)) { ?>
            .doccure_to-top:hover i {
            color: <?php echo esc_attr($back_to_color_hover); ?>;
            }
        <?php } ?>

        <?php if (!empty($preloader_bg)) { ?>
        .doccure_preloader,
        .preloader-gear-inner > div div:nth-child(6){
        background-color: <?php echo esc_attr($preloader_bg); ?>;
        }
    <?php }
        if (!empty($preloader_color)) { ?>
            .doccure_preloader-default .doccure_preloader-inner i,
            .preloader-name::before,
            .preloader-spinner-inner div,
            .preloader-gear-inner > div div,
            .preloader-pulse-inner div,
            .preloader-squares-inner div,
            .preloader-dual-inner div{
            background-color: <?php echo esc_attr($preloader_color); ?>;
            }
            .doccure_preloader.hidden .preloader-name span,
            .preloader-name b{
            color: <?php echo esc_attr($preloader_color); ?>;
            }
            .preloader-eclipse-inner div{
            box-shadow: 0 4px 0 0 <?php echo esc_attr($preloader_color); ?>;
            }
            .preloader-ripple-inner div{
            border-color: <?php echo esc_attr($preloader_color); ?>;
            }
            .doccure_preloader-diamond svg path {
            fill: <?php echo esc_attr($preloader_color); ?>;
            }
        <?php } ?>

        <?php if (!empty($page_404_title_color)) { ?>
        section.error-404 h1.page-title {
        color: <?php echo esc_attr($page_404_title_color); ?>;
        }
    <?php }
        if (!empty($page_404_desc_color)) { ?>
            section.error-404 .page-content > p {
            color: <?php echo esc_attr($page_404_desc_color); ?>;
            }
        <?php } ?>

        <?php if (!is_user_logged_in() && doccure_is_woo_active()) {
        if (doccure_get_option('my_account_style') == 'style-2' && is_account_page() && !is_lost_password_page() && doccure_get_option('hide-header-footer-myaccount') == true) { ?>
            .doccure_header, .doccure_subheader, .doccure_footer{
            display: none;
            }
        <?php } ?>
        .woocommerce-account.doccure_my-account-page-style-2.woocommerce-page:not(.woocommerce-lost-password) .section.section-padding {
        padding: 0;
        }
        .woocommerce-account.doccure_my-account-page-style-2.woocommerce-page:not(.woocommerce-lost-password) .section > .container {
        max-width: 100%;
        padding: 0;
        }
    <?php } ?>

        <?php if (is_404() && doccure_get_option('404_hide_header') == true) { ?>
            body.error404 .doccure_header{
              display: none;
            }
        <?php }
        if (is_404() && doccure_get_option('404_hide_footer') == true) { ?>
            body.error404 .doccure_footer, body.error404  .homepage_footer{
              display: none;
            }
        <?php }

        if(doccure_get_option('disable-page-scrolling') == true) { ?>
            body.doccure-disable-page-scrolling.modal-open{
              height: 100vh;
              overflow-y: hidden;
            }
        <?php } ?>

        <?php if (isset($doccure_options['enable-cpt-image-filter']) && $doccure_options['enable-cpt-image-filter'] == true) {
        if (isset($doccure_options['filter-style']) && $doccure_options['filter-style'] == 'blur') {
            ?>
            .doccure-filter-img-wrapper img {
            filter: blur(<?php echo esc_attr($doccure_options['blur_value']); ?>px);
            }
        <?php } elseif (isset($doccure_options['filter-style']) && $doccure_options['filter-style'] == 'brightness') {
            ?>
            .doccure-filter-img-wrapper img {
            filter: brightness(<?php echo esc_attr($doccure_options['brightness_value']); ?>);
            }
        <?php } elseif (isset($doccure_options['filter-style']) && $doccure_options['filter-style'] == 'contrast') {
            ?>
            .doccure-filter-img-wrapper img {
            filter: contrast(<?php echo esc_attr($doccure_options['contrast_value']); ?>);
            }
        <?php } elseif (isset($doccure_options['filter-style']) && $doccure_options['filter-style'] == 'drop-shadow') {
            ?>
            .doccure-filter-img-wrapper img {
            filter: drop-shadow(<?php echo esc_attr($doccure_options['drop_shadow_offset_x_value'] . 'px ' . $doccure_options['drop_shadow_offset_y_value'] . 'px ' . $doccure_options['drop_shadow_blur_value'] . 'px'); ?><?php echo esc_attr($doccure_options['drop_shadow_color']); ?>);
            }
        <?php } elseif (isset($doccure_options['filter-style']) && $doccure_options['filter-style'] == 'grayscale') {
            ?>
            .doccure-filter-img-wrapper img {
            filter: grayscale(<?php echo esc_attr($doccure_options['grayscale_value']); ?>);
            }
        <?php } elseif (isset($doccure_options['filter-style']) && $doccure_options['filter-style'] == 'hue-rotate') {
            ?>
            .doccure-filter-img-wrapper img {
            filter: hue-rotate(<?php echo esc_attr($doccure_options['hue_roate_value']); ?>deg);
            }
        <?php } elseif (isset($doccure_options['filter-style']) && $doccure_options['filter-style'] == 'invert') {
            ?>
            .doccure-filter-img-wrapper img {
            filter: invert(<?php echo esc_attr($doccure_options['invert_value']); ?>);
            }
        <?php } elseif (isset($doccure_options['filter-style']) && $doccure_options['filter-style'] == 'opacity') {
            ?>
            .doccure-filter-img-wrapper img {
            filter: opacity(<?php echo esc_attr($doccure_options['opacity_value']); ?>);
            }
        <?php } elseif (isset($doccure_options['filter-style']) && $doccure_options['filter-style'] == 'saturate') {
            ?>
            .doccure-filter-img-wrapper img {
            filter: saturate(<?php echo esc_attr($doccure_options['saturate_value']); ?>);
            }
        <?php } elseif (isset($doccure_options['filter-style']) && $doccure_options['filter-style'] == 'sepia') {
            ?>
            .doccure-filter-img-wrapper img {
            filter: sepia(<?php echo esc_attr($doccure_options['sepia_value']); ?>);
            }
        <?php }
      } ?>

      /* Body Typography */
  		<?php if(!empty($body_typography)){ ?>
    		body, p{
    			<?php if( $body_typography_fw ){ ?>
    			font-weight: <?php echo esc_attr($body_typography_fw); ?>;
    			<?php } ?>
    			<?php if( $body_typography_ls ){ ?>
    			letter-spacing: <?php echo esc_attr($body_typography_ls); ?>;
    			<?php } ?>
    			<?php if( $body_typography_lh ){ ?>
    				line-height: <?php echo esc_attr( $body_typography_lh ); ?>;
    			<?php } ?>
    			<?php if( $body_typography_fs ){ ?>
    			font-size: <?php echo esc_attr($body_typography_fs); ?>;
    			<?php } ?>
    		}
      <?php } ?>

      <?php if (!empty($newsl_pp_title_color)) { ?>
        .doccure_newsletter-popup-modal .modal-body h3,
        #doccure_newsletter-popup .doccure_popup .doccure_popup-text h3{
          color: <?php echo esc_attr($newsl_pp_title_color); ?>;
        }
      <?php } if (!empty($newsl_pp_desc_color)) { ?>
        .doccure_newsletter-popup-modal .modal-body p,
        .doccure_newsletter-popup-modal .doccure_newsletter-popup-dismiss,
        #doccure_newsletter-popup .doccure_popup .doccure_popup-text p{
          color: <?php echo esc_attr($newsl_pp_desc_color); ?>;
        }
      <?php } if (!empty($newsl_pp_bg_color)) { ?>
        .doccure_newsletter-popup-modal .modal-body,
        #doccure_newsletter-popup .doccure_popup{
          background-color: <?php echo esc_attr($newsl_pp_bg_color); ?>;
        }
      <?php } if (!empty($newsl_pp_close_trigg_bg_color)) { ?>
        .doccure_newsletter-popup-modal .doccure_close,
        #doccure_newsletter-popup .doccure_popup .doccure_close{
          background-color: <?php echo esc_attr($newsl_pp_close_trigg_bg_color); ?>;
        }
      <?php } if (!empty($newsl_pp_close_trigg_color)) { ?>
          .doccure_newsletter-popup-modal .doccure_close span,
          #doccure_newsletter-popup .doccure_popup .doccure_close span{
            background-color: <?php echo esc_attr($newsl_pp_close_trigg_color); ?>;
          }
      <?php } ?>

        <?php
        $content = apply_filters('doccure/custom_css', ob_get_clean());
        $content = str_replace(array("\r\n", "\r"), "\n", $content);
        $lines = explode("\n", $content);
        $new_lines = array();
        foreach ($lines as $i => $line) {
            if (!empty($line)) {
                $new_lines[] = trim($line);
            }
        }
        return implode($new_lines);
    }
}
