<?php
/**
 * @Set Post Views
 * @return {}
 */
if (!function_exists('doccure_add_dynamic_styles')) {

    function doccure_add_dynamic_styles() {
		global $doccure_options;
        
		$enable_typo 	= !empty( $doccure_options['typography_option'] ) ? $doccure_options['typography_option'] : '';
		$body_font		= !empty( $doccure_options['regular_typography'] ) ? $doccure_options['regular_typography'] : '';
		$body_p			= !empty( $doccure_options['body_paragraph_typography'] ) ? $doccure_options['body_paragraph_typography'] : '';
		$h1_font		= !empty( $doccure_options['h1_heading_typography'] ) ? $doccure_options['h1_heading_typography'] : '';
		$h2_font		= !empty( $doccure_options['h2_heading_typography'] ) ? $doccure_options['h2_heading_typography'] : '';
		$h3_font		= !empty( $doccure_options['h3_heading_typography'] ) ? $doccure_options['h3_heading_typography'] : '';
		$h4_font		= !empty( $doccure_options['h4_heading_typography'] ) ? $doccure_options['h4_heading_typography'] : '';
		$h5_font		= !empty( $doccure_options['h5_heading_typography'] ) ? $doccure_options['h5_heading_typography'] : '';
		$h6_font		= !empty( $doccure_options['h6_heading_typography'] ) ? $doccure_options['h6_heading_typography'] : '';
		$color_base 	= !empty( $doccure_options['site_colors'] ) ? $doccure_options['site_colors'] : '';
		$custom_css 	= !empty( $doccure_options['custom_css'] ) ? $doccure_options['custom_css'] : '';
		$logo_wide 		= !empty( $doccure_options['logo_wide'] ) ? $doccure_options['logo_wide'] : '';
		
		$loader_wide 		= !empty( $doccure_options['loader_wide'] ) ? $doccure_options['loader_wide'] : '';
		$loader_height 		= !empty( $doccure_options['loader_height'] ) ? $doccure_options['loader_height'] : '';
		
		$pull_loader		= !empty($loader_wide) ? $loader_wide / 2 : ''; 
		
        ob_start();

        if (!empty($enable_typo)) { ?>
        
			body{<?php echo doccure_extract_typography($body_font); ?>}
			body p{<?php echo doccure_extract_typography($body_p); ?>}
			body ul {<?php echo doccure_extract_typography($body_font); ?>}
			body li {<?php echo doccure_extract_typography($body_font); ?>}
			body h1{<?php echo doccure_extract_typography($h1_font); ?>}
			body h2{<?php echo doccure_extract_typography($h2_font); ?>}
			body h3{<?php echo doccure_extract_typography($h3_font); ?>}
			body h4{<?php echo doccure_extract_typography($h4_font); ?>}
			body h5{<?php echo doccure_extract_typography($h5_font); ?>}
			body h6{<?php echo doccure_extract_typography($h6_font); ?>}
       
        <?php } ?>
		
        <?php
		
        if (!empty($color_base) ) {

 			$primary_color = !empty( $doccure_options['theme_primary_color'] ) ? $doccure_options['theme_primary_color'] : '';
			$header_color = !empty( $doccure_options['theme_header_color'] ) ? $doccure_options['theme_header_color'] : '';
			$mheader_color = !empty( $doccure_options['theme_mheader_color'] ) ? $doccure_options['theme_mheader_color'] : '';
			$breadcrumb_color = !empty( $doccure_options['theme_breadcrumb_color'] ) ? $doccure_options['theme_breadcrumb_color'] : '';
			$tertiary_color = !empty( $doccure_options['theme_tertiary_color'] ) ? $doccure_options['theme_tertiary_color'] : '';
			$theme_secondary_color 	= !empty( $doccure_options['theme_secondary_color'] ) ? $doccure_options['theme_secondary_color'] : '';
			$thm_base 	= !empty( $doccure_options['thm_base'] ) ? $doccure_options['thm_base'] : '';
			$thm_base_hover 	= !empty( $doccure_options['thm_base_hover'] ) ? $doccure_options['thm_base_hover'] : '';
			$theme_footer_color 	= !empty( $doccure_options['theme_footer_color'] ) ? $doccure_options['theme_footer_color'] : '';

			$theme_footer_tcolor 	= !empty( $doccure_options['theme_footer_tcolor'] ) ? $doccure_options['theme_footer_tcolor'] : '';

            if (!empty($primary_color)) {
                $theme_color = $primary_color;
                ?>
                :root {--doccure_themecolor:<?php echo esc_html($theme_color);?>;}
				:root {--doccure_themecoloropacity:<?php echo esc_html($theme_color);?>99;}
				
            <?php } 
			
			if (!empty($header_color)) {
                $header_color = $header_color;
                ?>
                :root {--theme_header_color:<?php echo esc_html($header_color);?>;}
            <?php } 

			if (!empty($mheader_color)) {
				$mheader_color = $mheader_color;
				?>
				:root {--theme_mheader_color:<?php echo esc_html($mheader_color);?>;}
			<?php } 

			if (!empty($breadcrumb_color)) {
				$breadcrumb_color = $breadcrumb_color;
				?>
				:root {--theme_breadcrumb_color:<?php echo esc_html($breadcrumb_color);?>;}
			<?php } 

 
			if( !empty( $theme_secondary_color ) ){ ?>
				:root {--doccure_secthemecolor:<?php echo esc_html($theme_secondary_color);?>;}
			<?php
			}
			if( !empty( $thm_base ) ){ ?>
				:root {--thm-base:<?php echo esc_html($thm_base);?>;}
			<?php
			}
			if( !empty( $thm_base_hover ) ){ ?>
				:root {--thm-base-hover:<?php echo esc_html($thm_base_hover);?>;}
			<?php
			}
			if( !empty( $theme_footer_color ) ){ ?>
				:root {--doccure_terthemefootercolor:<?php echo esc_html( $theme_footer_color );?>;}
			<?php
			}

			if( !empty( $theme_footer_tcolor ) ){ ?>
				:root {--doccure_textthemefootercolor:<?php echo esc_html( $theme_footer_tcolor );?>;}
			<?php
			}
			if( !empty( $tertiary_color ) ){ ?>
				:root {--doccure_terthemecolor:<?php echo esc_html( $tertiary_color );?>;}
			<?php
		 
		
			
		}
	}
		
		if( !empty( $custom_css ) ){
			echo esc_html($custom_css); 
		}
		
		if(!empty($logo_wide)){
			echo '.dc-logo{flex-basis: '.$logo_wide.'px;}';
		}
		
		//loader dynamic settings
		if(!empty($loader_wide) && !empty($pull_loader)){
			
			echo '.preloader-outer.dc-customloader .dc-loader,
			.preloader-outer.dc-customloader .dc-preloader-holder{
				width: 150px;
				height: 150px;
				margin: -75px 0 0 -75px;
			}';
		}
		
        return ob_get_clean();
    }

}