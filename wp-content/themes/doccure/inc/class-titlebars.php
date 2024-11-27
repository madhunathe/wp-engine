<?php

/**
 *
 * Class used as base to create theme Sub Header
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @since 1.0
 */
if (!class_exists('doccure_Prepare_TitleBar')) {

    class doccure_Prepare_TitleBar {

        function __construct() {
            add_action('doccure_do_process_titlebar' , array (&$this , 'doccure_do_process_titlebar'));
        }

        /**
         * @Prepare Sub headers settings
         * @return {}
         * @author Dreams Technologies
         */
        public function doccure_do_process_titlebar() {
            global $post,$doccure_options;
			$page_id = '';
			
			$object_id = get_queried_object_id();
			$current_object_type	= get_post_type();
			
			//hide for dashboard
			if (is_page_template('directory/dashboard.php')) {
				return false;
			}
			
			if((get_option('show_on_front') && get_option('page_for_posts') && is_home()) ||
				(get_option('page_for_posts') && is_archive() && !is_post_type_archive()) && !(is_tax('product_cat') || is_tax('product_tag')) || (get_option('page_for_posts') && is_search())) {
					$page_id = get_option('page_for_posts');		
			} else {
				if(isset($object_id)) {
					$page_id = $object_id;
				}
			}
			
			if( is_404() 
				|| is_archive() 
				|| is_search() 
				|| is_category() 
				|| is_tag() 
			) {
				$titlebar_type 	= !empty( $doccure_options['titlebar_type'] ) ? $doccure_options['titlebar_type'] : '';
				if(   $titlebar_type === 'default' ) {
					$this->doccure_get_titlebars($page_id);
				} else{
					$this->doccure_get_titlebars($page_id);
				} 
			} else {
				$titlebar_type		= doccure_get_post_meta( $page_id );

				$default_titlebar_type 	= !empty( $doccure_options['titlebar_type'] ) ? $doccure_options['titlebar_type'] : '';
				if( isset( $titlebar_type ) && is_array( $titlebar_type ) ){
					if(  isset( $titlebar_type['am_title_bar'] ) 
						&& $titlebar_type['am_title_bar'] === 'rev' 
						&& !empty( $titlebar_type['rev_slider'] )
					){
						echo '<div class="doccure-slider-container dc-haslayout">';
						echo do_shortcode( '[rev_slider '.$titlebar_type['rev_slider'].']' );
						echo '</div>';
					}else if(  isset( $titlebar_type['am_title_bar'] ) 
						&& $titlebar_type['am_title_bar'] === 'shortcode' 
						&& !empty( $titlebar_type['am_shortcode'] )
					){
						echo '<div class="doccure-shortcode-container dc-haslayout">';
						echo do_shortcode( $titlebar_type['am_shortcode'] );
						echo '</div>';
					} else if(  isset( $titlebar_type['am_title_bar'] ) 
						&& $titlebar_type['am_title_bar'] === 'default' 
					){
						if(  isset( $default_titlebar_type ) && $default_titlebar_type === 'none' ) {
							return;
						}else{
							$this->doccure_get_titlebars($page_id);
						} 
					} else if( isset( $titlebar_type['am_title_bar'] ) 
						&& $titlebar_type['am_title_bar'] === 'custom' 
					){
						$this->doccure_get_titlebars($page_id);
					} else if(  isset( $titlebar_type['am_title_bar'] ) 
						&& $titlebar_type['am_title_bar'] === 'none' 
					){
						//do nothing
					} else{
						if(  isset( $default_titlebar_type['gadget'] ) && $default_titlebar_type['gadget'] === 'none') {
							//do nothing
						} else{
							$this->doccure_get_titlebars($page_id);
						}
					}
				}else{
					if(  isset( $default_titlebar_type['gadget'] ) && $default_titlebar_type['gadget'] === 'none') {
						//do nothing
					} else{
						$this->doccure_get_titlebars($page_id);
					}
				}
			}
        }
        
        /**
         * @Prepare Subheaders
         * @return {}
         * @author Dreams Technologies
         */
        public function doccure_get_titlebars($page_id='') {
			global $post,$doccure_options,$titlebar_enabled;
			$title = '';
			$page_title		= false;
			$titlebar_bg 	= 'rgba(54, 59, 77, 0.40)';
			
			if( is_404() 
				|| is_archive() 
				|| is_search() 
				|| is_category() 
				|| is_tag() 
			) {
				
				$titlebar_type 	= !empty( $doccure_options['titlebar_type'] ) ? $doccure_options['titlebar_type'] : '';

				if(  isset( $titlebar_type ) 
					 && $titlebar_type === 'default' 
				) {
					$title_bar_bg 	    = !empty( $doccure_options['title_bar_bg'] ) ? $doccure_options['title_bar_bg'] : '#f7f7f7';
					$title_bar_text 	= !empty( $doccure_options['title_bar_text'] ) ? $doccure_options['title_bar_text'] : '';
				} else{
					$title_bar_bg 		= '#f7f7f7';
					$title_bar_text 	= '';
				}

				
				if (is_404()) {
 					$title = esc_html__('404', 'doccure');
                } else if( class_exists( 'Woocommerce' ) 
					&& is_woocommerce() 
					&& ( is_product() || is_shop() ) 
					&& ! is_search() 
				) {
					if( ! is_product() ) {
						$title = woocommerce_page_title( false );
					} else{
						$title = esc_html__('Archive', 'doccure');
					}
				}else if ( is_category() ) {
                    $title = single_cat_title("", false);
                } else if ( is_tax() ) {
					$title	= single_term_title("",false);
                }else if ( is_archive() ) {
                    $title = esc_html__('Archive', 'doccure');
                } else if (is_search()) {
                    $title = esc_html__('Search', 'doccure');
                }
					
					
			} else{
				
				$object_id = get_queried_object_id();
				if((get_option('show_on_front') && get_option('page_for_posts') && is_home()) ||
					(get_option('page_for_posts') && is_archive() && !is_post_type_archive()) && !(is_tax('product_cat') || is_tax('product_tag')) || (get_option('page_for_posts') && is_search())) {
						$page_id = get_option('page_for_posts');		
				} else {
					if(isset($object_id)) {
						$page_id = $object_id;
					}
				}
				
				$post_meta			= doccure_get_post_meta( $page_id );
				if( empty( $post_meta['am_title_bar'] )){
					$title_bar_bg 	    = !empty( $doccure_options['title_bar_bg'] ) ? $doccure_options['title_bar_bg'] : '#f7f7f7';
					$title_bar_text 	= !empty( $doccure_options['title_bar_text'] ) ? $doccure_options['title_bar_text'] : '';
				} elseif( isset( $post_meta['am_title_bar'] ) && $post_meta['am_title_bar'] === 'default'){
					$title_bar_bg 	    = !empty( $doccure_options['title_bar_bg'] ) ? $doccure_options['title_bar_bg'] : '#f7f7f7';
					$title_bar_text 	= !empty( $doccure_options['title_bar_text'] ) ? $doccure_options['title_bar_text'] : '';
				} else{
					$title_bar_bg 	    = !empty( $post_meta['am_title_bar_bg'] ) ? $post_meta['am_title_bar_bg'] : '#f7f7f7';
					$title_bar_text 	= !empty( $post_meta['am_title_bar_text'] ) ? $post_meta['am_title_bar_text'] : '';
				}
				
				
				//Title
				if( empty( $title ) ) {
					$title	= get_the_title( $page_id );
				}
			}
			
			if( class_exists( 'Woocommerce' ) 
				&& is_woocommerce() 
				&& ( is_product() || is_shop() ) 
				&& ! is_search() 
			) {
				if( ! is_product() ) {
					$title = woocommerce_page_title( false );
				}
			}
			
			if (is_home()) {
				$title = esc_html__('Home', 'doccure');
			}
			
			if ( is_singular('post') || is_singular('page') ) {
				$am_breadcrumbs		= !empty( $post_meta['am_breadcrumbs'] ) ? $post_meta['am_breadcrumbs'] : '';
				$am_title_bar		= !empty( $post_meta['am_title_bar'] ) ? $post_meta['am_title_bar'] : '';
				$titlebar_type 	= !empty( $doccure_options['titlebar_type'] ) ? $doccure_options['titlebar_type'] : '';
				
				if( !empty( $am_title_bar ) && $am_title_bar === 'hide' ){
					//do nothing
				}else if( !empty( $am_title_bar ) && $am_title_bar != 'hide' ){
					if( !empty( $am_breadcrumbs ) && $am_breadcrumbs === 'enable' ){
						do_action('doccure_breadcrumbs',$title_bar_bg,$title_bar_text,$title);
						$titlebar_enabled	= 'titlebar_enabled';
					} else if( empty( $titlebar_type ) || $titlebar_type === 'default' ){
						do_action('doccure_breadcrumbs',$title_bar_bg,$title_bar_text,$title);
						$titlebar_enabled	= 'titlebar_enabled';
					}
				} else if( empty( $titlebar_type ) || $titlebar_type === 'default' ){
					do_action('doccure_breadcrumbs',$title_bar_bg,$title_bar_text,$title);
					$titlebar_enabled	= 'titlebar_enabled';
				}
				
			} else {
				do_action('doccure_breadcrumbs',$title_bar_bg,$title_bar_text,$title);
				$titlebar_enabled	= 'titlebar_enabled';
			}
		}
    }
    new doccure_Prepare_TitleBar();
}