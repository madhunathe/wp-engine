<?php

/**
 *
 * General Theme Functions
 *
 */
if (!function_exists('doccure_newscripts')) {

    function doccure_newscripts() {
		global $doccure_options;
        $theme_version 	= wp_get_theme('doccure');
        $google_key 	= '';
		$google_key		= !empty( $doccure_options['google_map'] ) ? $doccure_options['google_map'] : '';
		$enable_cart 	= !empty( $doccure_options['enable_cart'] ) ? $doccure_options['enable_cart'] : '';
		$script_source	= '/';
        $protocol 		= is_ssl() ? 'https' : 'http';

        wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css', array(), $theme_version->get('Version'));
		wp_register_style('basictable', get_template_directory_uri() . '/assets/css/basictable.css', array(), $theme_version->get('Version'));
		
		wp_register_style('fullcalendar', get_template_directory_uri() . '/assets/dashboardjs/fullcalendar/lib/main.min.css', array(), $theme_version->get('Version'));
		wp_register_style('datetimepicker', get_template_directory_uri() . '/assets/css/datetimepicker.css', array(), $theme_version->get('Version'));
		
		wp_enqueue_style('select2', get_template_directory_uri() . '/assets/css/select2.min.css', array(), $theme_version->get('Version'));
		wp_register_style('prettyPhoto', get_template_directory_uri() . '/assets/css/prettyPhoto.css', array(), $theme_version->get('Version'));
		wp_register_style('scrollbar', get_template_directory_uri() . '/assets/css'.$script_source.'scrollbar.css', array(), $theme_version->get('Version'));

		//dashboard and doctors single style
		if (is_singular('doctors') || is_page_template('directory/dashboard.php') ) {
			wp_enqueue_style('scrollbar');
			wp_enqueue_style('datetimepicker');
			wp_enqueue_style('fullcalendar');
		}

		
		wp_enqueue_style('doccure-style', get_template_directory_uri() . '/style.css', array(), $theme_version->get('Version'));
		
		
		//if ( is_singular('doctors') || is_singular( 'hospitals') ) {
			wp_enqueue_style('prettyPhoto');
	//	}

		//dashboard styles
		if (is_page_template('directory/dashboard.php')) { 
			
			wp_enqueue_style('basictable');
			wp_enqueue_style('doccure-dashboard', get_template_directory_uri() . '/assets/css'.$script_source.'frontenddashboard.css', array(), $theme_version->get('Version'));
 		}
		
		//inline styles
        $custom_css = doccure_add_dynamic_styles();   
        wp_add_inline_style('doccure-style', $custom_css);
	
        //script
		
		wp_register_script('prettyPhoto', get_template_directory_uri() . '/assets/dashboardjs/prettyPhoto.js', array(), $theme_version->get('Version'), true);
		wp_register_script('select2', get_template_directory_uri() . '/assets/dashboardjs/select2.min.js', array(), $theme_version->get('Version'), true);
		wp_register_script('slick', get_template_directory_uri() . '/assets/dashboardjs/slick.min.js', array(), $theme_version->get('Version'), true);
		wp_register_script('datetimepicker', get_template_directory_uri() . '/assets/dashboardjs/datetimepicker.js', array(), $theme_version->get('Version'), true);
		wp_register_script('doccure-callback', get_template_directory_uri() . '/assets/dashboardjs'.$script_source.'doccure_callback.js', array('jquery'), $theme_version->get('Version'), true);
		wp_register_script('fullcalendar', get_template_directory_uri() . '/assets/dashboardjs/fullcalendar/lib/main.min.js', array(), $theme_version->get('Version'), true);
		wp_register_script('fullcalendar-lang', get_template_directory_uri() . '/assets/dashboardjs/fullcalendar/lib/main.js', array(), $theme_version->get('Version'), true);
		wp_register_script('fullcalendar-lang', get_template_directory_uri() . '/assets/dashboardjs/fullcalendar/lib/locales-all.min.js', array(), $theme_version->get('Version'), true);
		wp_register_script('fullcalendar-aflang', get_template_directory_uri() . '/assets/dashboardjs/fullcalendar/lib/locales/af.js', array(), $theme_version->get('Version'), true);
		
		//chat emoji
		if ( ( is_page_template('directory/dashboard.php') && isset($_GET['ref']) && $_GET['ref'] === 'chat') || is_singular('doctors') ) {
			wp_enqueue_style('emojionearea', get_template_directory_uri() . '/assets/css/emoji/emojionearea.min.css', array(), $theme_version->get('Version')); 
		}

		wp_register_script('socket.io', get_template_directory_uri() . '/node_modules/socket.io-client/dist/socket.io.js', array(), $theme_version->get('Version'), true);
		wp_enqueue_script('modernizr');
		wp_enqueue_script('popper');
		wp_enqueue_script('bootstrap');
		wp_enqueue_script('select2');

	
		wp_enqueue_script('doccure-callback');

		
		
		wp_register_script('scrollbar', get_template_directory_uri() . '/assets/dashboardjs/scrollbar.min.js', array('jquery'), $theme_version->get('Version'), true);
		wp_enqueue_script('wp-util');
		
		//Doctor Single Scripts
		if (is_singular('doctors')) {
			wp_enqueue_script('scrollbar');
			wp_enqueue_script('moment');
			wp_enqueue_script('datetimepicker');
			wp_enqueue_script('fullcalendar');
			wp_enqueue_script('fullcalendar-lang');
			wp_enqueue_script('jrate', get_template_directory_uri() . '/assets/dashboardjs/jrate.js', array(), $theme_version->get('Version'), true);
			
			wp_enqueue_script('jquery-ui-slider');
		}
		
		//Dashboard and doctors chat scripts
		if ( ( is_page_template('directory/dashboard.php') && isset($_GET['ref']) && $_GET['ref'] === 'chat') || is_singular('doctors') ) {
			
			wp_enqueue_script('emojionearea', get_template_directory_uri() . '/assets/dashboardjs/emoji/emojionearea.min.js', array(), '', true);
			wp_enqueue_script('linkify', get_template_directory_uri() . '/assets/dashboardjs/linkify/linkify.min.js', array(), '', true);
			wp_enqueue_script('linkify-string', get_template_directory_uri() . '/assets/dashboardjs/linkify/linkify-string.min.js', array(), '', true);
			wp_enqueue_script('linkify-jquery', get_template_directory_uri() . '/assets/dashboardjs/linkify/linkify-jquery.min.js', array(), '', true);
		}
		//Dashboard scripts
		if (is_page_template('directory/dashboard.php')) { 
			
			wp_enqueue_script('moment');
			wp_enqueue_script('datetimepicker');
			wp_enqueue_script('fullcalendar');
			wp_enqueue_script('fullcalendar-lang');
			wp_enqueue_script('scrollbar');
			wp_enqueue_script('plupload');
			wp_enqueue_script('basictable', get_template_directory_uri() . '/assets/dashboardjs/basictable.min.js', array(), $theme_version->get('Version'), true);
			
			wp_enqueue_script('doccure-dashboard', get_template_directory_uri() . '/assets/dashboardjs'.$script_source.'dashboard.js', array('jquery'), $theme_version->get('Ve
			rsion'), true);
			
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-ui-autocomplete' );

		}
				
		//Dashboard and doctors chat scripts
		if ( ( is_page_template('directory/dashboard.php') && isset($_GET['ref']) && $_GET['ref'] === 'chat') || is_singular('doctors') ) {

		}
		
		//if (is_singular('doctors') || is_singular( 'hospitals') ) {
			wp_enqueue_script('prettyPhoto');
		//}

		wp_localize_script('doccure-callback', 'scripts_vars', array(
			'is_admin'		=> 'no',
            'ajaxurl' 		=> admin_url('admin-ajax.php'),
			'ajax_nonce' => wp_create_nonce('ajax_nonce')
        ));
		
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) { 
			wp_enqueue_script( 'comment-reply' );
		}
    }

	add_action('wp_enqueue_scripts', 'doccure_newscripts', 88);
}


/**
 * @Enqueue admin scripts and styles.
 * @return{}
 */
if (!function_exists('doccure_admin_enqueue')) {

    function doccure_admin_enqueue($hook) {
        global $post;
        $protolcol = is_ssl() ? "https" : "http";
        $theme_version = wp_get_theme('doccure');
		
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array(), $theme_version->get('Version'));
        wp_enqueue_style( 'doccure-admin-style', get_template_directory_uri() . '/admin/css/admin-style.css', array(), $theme_version->get('Version'));
        wp_enqueue_script('doccure-admin-functions', get_template_directory_uri() . '/admin/js/admin_functions.js', array('wp-color-picker','jquery'), $theme_version->get('Version'), false);
		
        wp_enqueue_style('datetimepicker', get_template_directory_uri() . '/assets/css/datetimepicker.css', array(), $theme_version->get('Version'));
		wp_enqueue_script('datetimepicker', get_template_directory_uri() . '/assets/dashboardjs/datetimepicker.js', array('jquery'), $theme_version->get('Version'), true);

		


		wp_enqueue_script('jquery-confirm.min', get_template_directory_uri() . '/assets/dashboardjs/jquery-confirm.min.js', array('jquery'), $theme_version->get('Version'), false);
		wp_enqueue_style('jquery-confirm.min', get_template_directory_uri() . '/assets/css/jquery-confirm.min.css', array(), $theme_version->get('Version'));
		
        $is_author_edit = '';
        if (isset($hook) && $hook == 'user-edit.php') {
            $is_author_edit = 'yes';
        }
		
		$dir_spinner = get_template_directory_uri() . '/images/spinner.gif';

        wp_localize_script('doccure-admin-functions', 'scripts_vars', array(
            'yes' 			=> esc_html__('Yes', 'doccure'),
            'no' 			=> esc_html__('No', 'doccure'),
			'import' 		=> esc_html__('Import Users', 'doccure'),
			'spinner'   	=> '<img class="sp-spin" src="'.esc_url($dir_spinner).'">',
            'import_message'	=> esc_html__('Are you sure, you want to import users?', 'doccure'),
			'repeater_message' 	=> esc_html__('Are you sure, you want to remove?', 'doccure'),
			'repeater_title' 	=> esc_html__('Alert', 'doccure'),
            'is_author_edit' 	=> $is_author_edit,
			'ajax_nonce' 		=> wp_create_nonce('ajax_nonce'),
			
			'reject_account' 			=> esc_html__('Reject account', 'doccure'),
			'reject_account_message' 	=> esc_html__('Do you want to reject this account? After reject, this account will no longer visible in the search listing', 'doccure'),
			'approve_account' 			=> esc_html__('Approve Account', 'doccure'),
			'approve_account_message' 	=> esc_html__('Do you want to approve this account? An email will be sent to this user.', 'doccure'),
        ));
		
		wp_enqueue_media();
    }

    add_action('admin_enqueue_scripts', 'doccure_admin_enqueue', 10, 1);
}

/**
 * @Theme Editor/guttenberg Style
 * 
 */
if (!function_exists('doccure_add_editor_styles')) {

    function doccure_add_editor_styles() {
		global $doccure_options;
		$protocol = is_ssl() ? 'https' : 'http';
        $theme_version = wp_get_theme('doccure');
		$editor_css  = '';
		
		if (function_exists('fw_get_db_settings_option')) {
            $color_base = fw_get_db_settings_option('color_settings');
        }
		
		$site_colors 	= !empty( $doccure_options['site_colors'] ) ? $doccure_options['site_colors'] : '';
		
		if ( !empty($site_colors) ) {
			$theme_primary_color 	= !empty( $doccure_options['theme_primary_color'] ) ? $doccure_options['theme_primary_color'] : '';
			$theme_secondary_color 	= !empty( $doccure_options['theme_secondary_color'] ) ? $doccure_options['theme_secondary_color'] : '';
			$theme_tertiary_color 	= !empty( $doccure_options['theme_tertiary_color'] ) ? $doccure_options['theme_tertiary_color'] : '';
			
			if (!empty($theme_primary_color)) {
				$editor_css  .= 'body.block-editor-page .editor-styles-wrapper a,
				body.block-editor-page .editor-styles-wrapper p a,
				body.block-editor-page .editor-styles-wrapper p a:hover,
				body.block-editor-page .editor-styles-wrapper a:hover,
				body.block-editor-page .editor-styles-wrapper a:focus,
				body.block-editor-page .editor-styles-wrapper a:active{color: '.$theme_primary_color.';}';
				
				$editor_css  .= 'body.block-editor-page .editor-styles-wrapper blockquote:not(.blockquote-link),
								 body.block-editor-page .editor-styles-wrapper .wp-block-quote.is-style-large,
								 body.block-editor-page .editor-styles-wrapper .wp-block-quote:not(.is-large):not(.is-style-large),
								 body.block-editor-page .editor-styles-wrapper .wp-block-quote.is-style-large,
								 body.block-editor-page .editor-styles-wrapper .wp-block-pullquote, 
								 body.block-editor-page .editor-styles-wrapper .wp-block-quote, 
								 body.block-editor-page .editor-styles-wrapper .wp-block-quote:not(.is-large):not(.is-style-large),
								 body.block-editor-page .wp-block-pullquote, 
								 body.block-editor-page .wp-block-quote, 
								 body.block-editor-page .wp-block-verse, 
								 body.block-editor-page .wp-block-quote:not(.is-large):not(.is-style-large){border-color:'.$theme_primary_color.';}';
			}
		}
		
		$font_families	= array();
		$font_families[] = 'Open+Sans:400,600';
		$font_families[] = 'Poppins:300,400,500,600,700';
		
		 $query_args = array (
			 'family' => implode('%7C' , $font_families) ,
			 'subset' => 'latin,latin-ext' ,
        );

        $theme_fonts = add_query_arg($query_args , $protocol.'://fonts.googleapis.com/css');
		add_editor_style(esc_url_raw($theme_fonts));
		wp_enqueue_style('doccure-admin-google-fonts' , esc_url_raw($theme_fonts), array () , null);
		
		$editor_css .= "
		body.block-editor-page editor-post-title__input,
		body.block-editor-page .editor-post-title__block .editor-post-title__input
		{font: 400 24px/34px'Poppins', sans-serif;color: #3d4461;}";
		
		$editor_css .= "body.block-editor-page .editor-styles-wrapper{font: 400 14px/26px 'Open Sans', Arial, Helvetica, sans-serif;}";
		
		$editor_css .= "body.block-editor-page .editor-styles-wrapper{color: #3d4461;}";
		$editor_css .= "body.block-editor-page editor-post-title__input,
		body.block-editor-page .editor-post-title__block .editor-post-title__input,
		body.block-editor-page .editor-styles-wrapper h1, 
				body.block-editor-page .editor-styles-wrapper h2, 
				body.block-editor-page .editor-styles-wrapper h3, 
				body.block-editor-page .editor-styles-wrapper h4, 
				body.block-editor-page .editor-styles-wrapper h5, 
				body.block-editor-page .editor-styles-wrapper h6 {font-family: 'Poppins', Arial, Helvetica, sans-serif;}";
							   
		wp_enqueue_style( 'doccure-editor-style', get_template_directory_uri() . '/admin/css/doccure-editor-style.css', array(), $theme_version->get('Version'));
		wp_add_inline_style( 'doccure-editor-style', $editor_css );
		
    }

    add_action('enqueue_block_editor_assets', 'doccure_add_editor_styles');
}

/**
 * @Enqueue before render elementor
 * @return{}
 */
if (!function_exists('doccure_before_render_elementor_enqueue')) {
	
add_action( 'elementor/widget/render_content','doccure_before_render_elementor_enqueue',10, 2 ); 
   function doccure_before_render_elementor_enqueue( $content, $widget ) {
	   if( $widget->get_name() === 'dc_element_custom_links' || $widget->get_name() === 'dc_element_slider_v2' || $widget->get_name() === 'dc_element_top_rated'){
	   		wp_enqueue_style('owl-carousel');
			wp_enqueue_script('owl-carousel');
	   }

	   return $content;
   }
}