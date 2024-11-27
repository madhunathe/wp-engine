<?php
/**
 *
 * Class used as base to create theme header
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @since 1.0
 */
if (!class_exists('doccure_Prepare_Headers')) {

    class doccure_Prepare_Headers {

        function __construct() {
            add_action('doccure_do_process_headers', array(&$this, 'doccure_do_process_headers'));
			add_action('doccure_prepare_search', array(&$this, 'doccure_prepare_search'));
			add_action('doccure_breadcrumbs_section', array(&$this, 'doccure_breadcrumbs_section'));
			add_action('doccure_systemloader', array(&$this, 'doccure_systemloader'));
			add_action('wp_head', array(&$this, 'doccure_update_metatags'));
		}
		
		/**
         * @Woo
         * @return {}
         */
        public function doccure_shoping_cart($enable_woo = '') {
            ob_start();
            global $woocommerce;
			if (class_exists('woocommerce')) {?>
				<div class="dc-cart dropdown">
					<a href="javascript:;" class="cart-contents" id="dc-cart">
						<i class="fa fa-cart-plus"></i>
						<span class="dc-badge"><?php echo intval($woocommerce->cart->cart_contents_count); ?></span>
					</a>
					<div class="dropdown-menu dc-mini-cart" aria-labelledby="dc-cart">
						<div class="widget_shopping_cart_content"></div>
					</div>
				</div>
				<?php
			}
            echo ob_get_clean();
        }

		
		/**
         * @system Update metadata
         * @return {}
         * @author Dreams Technologies
         */

		public function doccure_update_metatags() {
			global  $doccure_options;
			$post_types	= array('page','post');
			$seo_option	= !empty($doccure_options['enable_seo']) ? $doccure_options['enable_seo'] : '';

			if ( is_singular( $post_types ) && !empty($seo_option) ) {
				$post_id	= get_the_ID();
				$post_meta	= doccure_get_post_meta( $post_id );

				$am_seo_title		= !empty($post_meta['am_seo_title']) ? esc_attr($post_meta['am_seo_title']) : get_the_title($post_id);
				$am_seo_description	= !empty($post_meta['am_seo_description']) ? esc_attr($post_meta['am_seo_description']) : '';
				$am_seo_keywords	= !empty($post_meta['am_seo_keywords']) ? esc_attr($post_meta['am_seo_keywords']) : '';
				ob_start(); ?>
					<?php if(!empty($am_seo_title)) {?>
						<meta name="title" content="<?php echo esc_attr($am_seo_title);?>" />
					<?php } ?>
					<?php if(!empty($am_seo_description)) {?>
						<meta name="description" content="<?php echo esc_attr($am_seo_description);?>" />
					<?php } ?>
					<?php if(!empty($am_seo_keywords)) {?>
						<meta name="keywords" content="<?php echo esc_attr($am_seo_keywords);?>" />
					<?php } ?>
				<?php
					echo ob_get_clean(); 
			}
		}

        /**
         * @system loader
         * @return {}
         * @author Dreams Technologies
         */
        public function doccure_systemloader() {
			global $doccure_options;
			$maintenance 	= !empty( $doccure_options['maintenance'] ) ? $doccure_options['maintenance'] : false;
			$preloader 		= !empty( $doccure_options['site_loader'] ) ? $doccure_options['site_loader'] : false;
			$loader_type	= !empty( $doccure_options['loader_type'] ) ? $doccure_options['loader_type'] : 'default';
			$loader_image	= !empty( $doccure_options['loader_image']['url'] ) ? $doccure_options['loader_image']['url'] : '';
			
            if ( empty( $maintenance ) || $maintenance === false) {
                if ( !empty( $preloader )) {
                    if ( !empty( $preloader ) && $loader_type === 'default' ) { ?>
                        <div class="preloader-outer">
                            <div class="dc-preloader-holder">
                                <div class="dc-loader"></div>
                            </div>
                        </div>
                        <?php
                    } elseif ( !empty( $preloader ) && $loader_type === 'custom' && !empty( $loader_image ) ) { ?>
                        <div class="preloader-outer dc-customloader">
							<div class="dc-preloader-holder">
								<div class="dc-loader">
									<img src="<?php echo esc_url($loader_image); ?>" alt="<?php esc_attr_e('loader', 'doccure'); ?>" />
								</div>
							</div>
                       </div>
                        <?php
                    }
                }
            }
        }
		
		/**
         * @Prepare headers
         * @return {}
         * @author Dreams Technologies
         */
        public function doccure_prepare_search() { 
			
			global $doccure_options,$post;
			
			$search_result_page	= !empty( $doccure_options['search_result_page'] ) ? $doccure_options['search_result_page'] : '';
			$search_settings	= !empty( $doccure_options['search_form'] ) ? $doccure_options['search_form'] : '';
			$search_page		= doccure_get_search_page_uri('doctors');
			$show_home			= !empty( $doccure_options['hide_home_page'] ) ? $doccure_options['hide_home_page'] : '';
			$orderby 			= !empty( $_GET['orderby']) ? $_GET['orderby'] : '';
			$order 				= !empty( $_GET['order']) ? $_GET['order'] : 'ASC';
			$searchby			= !empty($doccure_options['search_type']) ? $doccure_options['search_type'] : '';
			$hide_location		= !empty($doccure_options['hide_location']) ? $doccure_options['hide_location'] : 'no';
			$gender_search		= !empty($doccure_options['gender_search']) ? $doccure_options['gender_search'] : '';
			$show_search_bar		= !empty($doccure_options['show_search_bar']) ? $doccure_options['show_search_bar'] : '';
			$is_search_page		= 'none';
			
			$hide_loc = 'dc-hidelocation'; 
			if( !empty($hide_location) && $hide_location === 'no'){
				$hide_loc = ''; 
			}
			
			$display			= 'none';
			if(!empty($searchby) && ( $searchby === 'both' || $searchby === 'hospitals' ) ){
				$display	='block';
			}
						
			$post_name 			= doccure_get_post_name();
			if ( apply_filters('doccure_get_domain',false) === true && $post_name === 'home-page-2' ) {
				return;
			}
			
			if( is_front_page() || is_home() ) {
				if( !empty($show_search_bar) && $show_search_bar === 'no' ) {
					return;
				}
			}

			if( !empty($search_settings) ){?>
			<div class="dc-innerbanner-holder dc-haslayout dc-open dc-opensearchs <?php echo esc_attr($hide_loc);?>">
				<form action="<?php echo esc_url( $search_page );?>" method="get" id="search_form">
					<div class="container">
						<div class="row">
							<div class="col-12 col-sm-12 col-md-12 col-lg-12">
								<div class="dc-innerbanner">
									<div class="dc-formtheme dc-form-advancedsearch dc-innerbannerform">
										<fieldset>
											<div class="form-group">
												<?php do_action('doccure_get_search_text_field');?>
											</div>
											<?php if( !empty($hide_location) && $hide_location === 'no'){?>
												<div class="form-group">
													<div class="dc-select">
														<?php do_action('doccure_get_search_locations');?>
													</div>
												</div>
											<?php }?>
											<div class="dc-btnarea">
												<input type="submit" class="dc-btn" value="<?php esc_attr_e('Search','doccure');?>">
											</div>
										</fieldset>
									</div>
									<a href="javascript:;" class="dc-docsearch"><span class="dc-advanceicon"><i></i> <i></i> <i></i></span><span><?php echo wp_kses(__('Advanced <br> Search','doccure'),array(
										'br' => array()
									));?></span></a>
								</div>
							</div>
						</div>
					</div>
					<div class="dc-advancedsearch-holder" style="display: <?php echo esc_attr($is_search_page);?>;">
						<div class="container">
							<div class="row">
								<div class="col-12 col-sm-12 col-md-12 col-lg-12">
									<div class="dc-advancedsearchs">
										<div class="dc-formtheme dc-form-advancedsearchs">
											<fieldset>
												<div class="form-group" style="display: <?php echo esc_attr($display);?>;">
													<div class="dc-select">
														<?php do_action('doccure_get_search_type');?>
													</div>
												</div>
												<div class="form-group">
													<div class="dc-select">
														<?php do_action('doccure_get_search_speciality');?>
													</div>
												</div>
												<div class="form-group">
													<div class="dc-select" id="search_services">
														<?php do_action('doccure_get_search_services');?>
													</div>
												</div>
												<?php if( !empty($gender_search) ){?>
													<div class="form-group" id="gender_search">
														<div class="dc-select">
															<?php do_action('doccure_get_search_gender');?>
														</div>
													</div>
												<?php } ?>
												<input type="hidden" name="orderby" class="search_orderby" value="<?php echo esc_attr( $orderby );?>">
												<input type="hidden" name="order" class="search_order" value="<?php echo esc_attr( $order );?>">
												<div class="dc-btnarea">
													<a href="<?php echo esc_url( $search_page );?>" class="dc-btn dc-resetbtn"><?php esc_html_e('Reset Filters','doccure');?></a>
												</div>
											</fieldset>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<?php
			}
         }

        /**
         * @Prepare headers
         * @return {}
         * @author Dreams Technologies
         */
        public function doccure_do_process_headers() {
            global $current_user,$doccure_options;
            $loaderDisbale = '';
			$maintenance 	= !empty( $doccure_options['maintenance'] ) ? $doccure_options['maintenance'] : false;
            $post_name 		= doccure_get_post_name();
			
            if (( isset($maintenance) && $maintenance == true && !is_user_logged_in() ) || $post_name === "coming-soon"
            ) {
                $loaderDisbale = 'elm-display-none';
            }
			
            get_template_part('template-parts/template', 'comingsoon');

            //demo ready
			if ( apply_filters('doccure_get_domain',false) === true ) {
				//do stuff here
			}
            $this->doccure_do_process_header_v1();
			
        }

        /**
         * @Prepare header v1
         * @return {}
         * @author Dreams Technologies
         */
        public function doccure_do_process_header_v1() {
            global $doccure_options;
			$header_type 		= !empty( $doccure_options['header_type'] ) ? $doccure_options['header_type'] : '';
		///	$dashboard_search 	= !empty( $doccure_options['dashboard_search'] ) ? $doccure_options['dashboard_search'] : '';
			$enable_cart 		= !empty( $doccure_options['enable_cart'] ) ? $doccure_options['enable_cart'] : '';


			if( !empty( $header_type )  && $header_type === 'header_1' ) {
				$topbar_h1	= !empty( $doccure_options['topbar_h1'] ) ? $doccure_options['topbar_h1'] : '';
				if( !empty( $topbar_h1 ) ) {
					$em_title	= !empty( $doccure_options['em_text'] ) ? $doccure_options['em_text'] : '';
					$em_phone	= !empty( $doccure_options['em_phone'] ) ? $doccure_options['em_phone'] : '';
					$socials	= !empty( $doccure_options['social_icons'] ) ? $doccure_options['social_icons'] : array();
				} else {
					$em_title	= '';
					$em_phone	= '';
					$socials	= array();
				}
			}
			
			$lists 			= array();
			if( function_exists( 'doccure_list_socila_media') ) {
				$lists 			= doccure_list_socila_media();
			}
			
			$classe 		= is_page_template('directory/dashboard.php') ? 'dc-header-dashboard' : '';
			$classe_header	= is_page_template('directory/dashboard.php') ? 'container-fluid' : 'container';
			$logo	= !empty( $doccure_options['main_logo'] ) ? $doccure_options['main_logo'] : array();
			$logo	= !empty( $logo['url'] ) ? $logo['url'] : get_template_directory_uri() . '/images/logo_header.svg';

			ob_start();
            ?>
            <header id="dc-header" class="dc-header dc-haslayout <?php echo esc_attr( $classe );?>">
            	<?php if( !empty( $topbar_h1 ) && !is_page_template('directory/dashboard.php') ) { ?>
					<div class="dc-topbar">
						<div class="container">
							<div class="row">
								<div class="col-12 col-sm-12 col-md-12 col-lg-12">
									<?php if( !empty( $em_title ) || !empty( $em_phone ) ) { ?>
										<div class="dc-helpnum">
											<?php if( !empty( $em_title ) ) { ?>
												<span><?php echo esc_html( $em_title );?></span>
											<?php } ?>
											<?php if( !empty( $em_phone ) && is_array($em_phone) ) {?>
												<?php foreach($em_phone as $key => $number){?>
												<a href="tel:<?php echo esc_attr($number);?>"><?php echo esc_html( $number );?></a>
											<?php }}elseif( !empty( $em_phone )){?>
												<a href="tel:<?php echo esc_attr($em_phone);?>"><?php echo esc_html( $em_phone );?></a>
											<?php } ?>
										</div>
									<?php } ?>
									<?php if( !empty( $socials ) ) { ?>
										<div class="dc-rightarea">
											<ul class="dc-simplesocialicons dc-socialiconsborder">
												<?php
													foreach ($socials as $key => $value) {
														$social_class		= !empty( $lists[$key]['icon'] ) ? $lists[$key]['icon'] :'';
														$social_name		= !empty( $lists[$key]['lable'] ) ? $lists[$key]['lable'] : '';
														$social_link 		= !empty($value) ? $value : '';
														$social_main_class	= !empty( $lists[$key]['class'] ) ? $lists[$key]['class'] : '';
														
														if (!empty($social_link)) {?>
															<li class="<?php echo esc_attr($social_main_class); ?>"><a href="<?php echo esc_attr($social_link); ?>"><i class="<?php echo esc_attr($social_class); ?>"></i></a></li>
													<?php
														}
													}
												?>
											</ul>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="dc-navigationarea">
					<div class="<?php echo esc_attr( $classe_header );?>">
						<div class="row">
							<div class="col-12 col-sm-12 col-md-12 col-lg-12">
								<div class="hidpi-logowrap">
									<?php $this->doccure_prepare_logo($logo); ?>
									<?php
										/// if( !empty($dashboard_search) && is_page_template('directory/dashboard.php') ){
										/// 	$this->doccure_header_search_form();
										/// }
									?>
									<div class="dc-rightarea">
										<nav id="dc-nav" class="dc-nav navbar-expand-lg">
											<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation','doccure');?>">
												<i class="lnr lnr-menu"></i>
											</button>
											<div class="collapse navbar-collapse dc-navigation" id="navbarNav">
												<?php doccure_Prepare_Headers::doccure_prepare_navigation('primary-menu', '', 'navbar-nav', '0'); ?>
											</div>
										</nav>
										<?php if (function_exists('is_woocommerce') && !empty($enable_cart) && $enable_cart === 'yes') { ?>
											<ul class="add-nav shop-nav">
												<li class="cart-area">
													<?php $this->doccure_shoping_cart(); ?>
												</li>
											</ul>    
										<?php } ?>
										<?php $this->doccure_prepare_registration(); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>
           	
            <?php
			echo ob_get_clean();
		}
		
		 /**
         * @Prepare header search form
         * @return {}
         * @author Dreams Technologies
         */
        public function doccure_header_search_form() {
			global	$doccure_options;
			$search_page		= doccure_get_search_page_uri('doctors');
			$hide_location		= !empty($doccure_options['hide_location']) ? $doccure_options['hide_location'] : 'no';
			
			ob_start();
		?>
		<div class="dc-headerform-holder">
			<div class="dc-search-headerform">
				<div class="closeform-holder">
					<a href="javascript:;" class="dc-removeform"><?php esc_html_e('Cancel','doccure');?></a>
					<a href="javascript:;" class="dc-removeform"> <i class="ti-close"></i></a>
				</div>
				<form class="dc-formtheme dc-form-advancedsearch dc-headerform" action="<?php echo esc_url($search_page);?>" method="GET">
					<fieldset>
						<div class="form-group">
							<?php do_action('doccure_get_search_text_field');?>
						</div>
						<?php if( !empty($hide_location) && $hide_location === 'no'){ ?>
							<div class="form-group">
								<div class="dc-select">
									<?php do_action('doccure_get_search_locations');?>
								</div>
							</div>
						<?php } ?>
						<div class="dc-formbtn">
							<a href="javascript:;" class="dc-header-serach-form"><i class="ti-arrow-right"></i></a>
						</div>
					</fieldset>
				</form>
			</div>
			<a href="javascript:;" class="dc-searchbtn"><i class="fa fa-search"></i></a>
		</div>	
		<?php
		echo ob_get_clean();
		}
		
        /**
         * @Prepare Logo
         * @return {}
         * @author Dreams Technologies
         */
        public function doccure_prepare_logo($logo = '') {
            global $post, $woocommerce;
            $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			
            ob_start();
            ?>
            <strong class="dc-logo"> 
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <?php 
						if (!empty($logo)) {?>
							<img class="amsvglogo" src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($blogname); ?>">
							<?php
						} else {
							echo esc_html($blogname);
						}
                    ?>
                </a> 
            </strong>
            <?php
            echo ob_get_clean();
        }

        /**
         * @Registration and Login
         * @return {}
         */
        public function doccure_prepare_registration() {             
            do_action('doccure_print_login_form');                
        }

        /**
         * @Main Navigation
         * @return {}
         */
        public static function doccure_prepare_navigation($location = '', $id = 'menus', $class = '', $depth = '0') {

            if (has_nav_menu($location)) {
                $defaults = array(
                    'theme_location' 	=> $location,
                    'menu' 				=> '',
                    'container' 		=> 'ul',
                    'container_class' 	=> '',
                    'container_id' 		=> '',
                    'menu_class' 		=> $class,
                    'menu_id' 			=> $id,
                    'echo' 				=> false,
                    'fallback_cb' 		=> 'wp_page_menu',
                    'before' 			=> '',
                    'after' 			=> '',
                    'link_before' 		=> '',
                    'link_after' 		=> '',
                    'items_wrap' 		=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth' 			=> $depth,
                );
                echo do_shortcode(wp_nav_menu($defaults));
            } else {
                $defaults = array(
                    'theme_location' 	=> $location,
                    'menu' 				=> '',
                    'container' 		=> 'ul',
                    'container_class' 	=> '',
                    'container_id' 		=> '',
                    'menu_class' 		=> $class,
                    'menu_id' 			=> $id,
                    'echo' 				=> false,
                    'fallback_cb' 		=> 'wp_page_menu',
                    'before' 			=> '',
                    'after' 			=> '',
                    'link_before' 		=> '',
                    'link_after' 		=> '',
                    'items_wrap' 		=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth' 			=> $depth,
                );
                echo do_shortcode(wp_nav_menu($defaults));
            }
        }

    }

    new doccure_Prepare_Headers();
}