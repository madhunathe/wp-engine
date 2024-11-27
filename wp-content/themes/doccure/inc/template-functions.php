<?php
/**
 * Get the custom title for page.
 *
 * @since 2.0.0
 */

function doccure_filter_page_title($title){
    $title['title'] = doccure_subheader_get_title();
    return $title;
}
add_filter( 'document_title_parts', 'doccure_filter_page_title', 10, 1 );

/**
 * Get the site logo assigned from theme options.
 *
 * @param string $logo The logo option (Can be header_logo, footer_logo or mobile_logo)
 * @param bool $slogan Whether to show the slogan if no logo is set or not
 *
 * @since 1.0.0
 */
function doccure_get_site_logo($logo = '', $slogan = true)
{

    $logo_obj = doccure_get_option($logo);

    if (!empty($logo_obj) && !is_array($logo_obj)) {
        $logo_id = $logo_obj;
    } elseif (!empty($logo_obj) && is_array($logo_obj)) {
        $logo_id = isset($logo_obj['id']) ? $logo_obj['id'] : '';
        $logo_url = isset($logo_obj['url']) ? $logo_obj['url'] : '';
    }

    $logo_obj = !empty($logo_id) ? wp_get_attachment_image_src($logo_id, 'full')[0] : '';

    if (!empty($logo_url)) {

        ?>

        <div class="logo-wrap <?php echo esc_attr($logo) ?>">
            <a href="<?php echo esc_url(get_home_url()); ?>" rel="home">
                <img class="img-fluid" src="<?php echo esc_url($logo_url); ?>"
                     alt="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>"/>
            </a>
        </div>

        <?php

    } else {

        if ($logo_obj) {
            ?>
            <div class="logo-wrap <?php echo esc_attr($logo) ?>">
                <a href="<?php echo esc_url(get_home_url()); ?>" rel="home">
                    <img class="img-fluid" src="<?php echo esc_url($logo_obj); ?>"
                         alt="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>"/>
                </a>
            </div>

        <?php } elseif (!$logo_obj && $slogan) { ?>
            <a href="<?php echo esc_url(get_home_url()); ?>" class="site-slogan">
            
             <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" class="logoimgs">
                <h5><?php // bloginfo('name'); ?></h5>
                <span><?php //bloginfo('description'); ?></span>
            </a>
            <?php
        }

    }

}

/**
 * Returns a specific menu
 *
 * @since 1.0.0
 */
function doccure_nav_menu($location = 'primary-menu', $menu_class = 'navbar-nav') {
  global $post;
  $current_id = doccure_get_page_id();
  $page_settings = $current_id ? get_post_meta($current_id, 'doccure_page_settings', true) : '';
  $enable_onepage_menu = isset($page_settings['doccure_one_page_menu_enable']) ? (bool)$page_settings['doccure_one_page_menu_enable'] : '';
    if( isset($post->ID) && $enable_onepage_menu){
      $content = $post->post_content;
      $pattern = get_shortcode_regex();
        echo '<ul class="navbar-nav">';
        if( preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches ) && array_key_exists( 2, $matches ) && in_array( 'vc_row', $matches[2] ) ){
            foreach ($matches[3] as $attr) {
              $props = array();
              $sarray = explode('" ', trim($attr));
              foreach ($sarray as $val) {
                      $el =explode("=", $val);
                      $s1 = str_replace('"', '', trim($el[0]));
                      if( isset($el[1]) ){
                          $s2 = str_replace('"', '', trim($el[1]));
                          $props[$s1] = $s2;
                      }
                  }
                  $one_page_section = isset($props['enable_one_page_section']) ? $props['enable_one_page_section'] : '';
                  $one_page_label = isset($props['one_page_section_label']) ? $props['one_page_section_label'] : '';
                  $one_page_slug = isset($props['one_page_section_slug']) ? $props['one_page_section_slug'] : '';

                  if( $one_page_section!= true ){ continue; }
                  if( empty($one_page_label) ){ continue; }

                  if( isset($post->ID) && $enable_onepage_menu){
                    $content = $post->post_content;
                    $pattern = get_shortcode_regex();
                      echo "<li class='menu-item'><a class='scroll-to-link' href='".esc_attr($one_page_slug)."'>".esc_html($one_page_label)."</a></li>";
                  }
            }
        }
        echo '</ul>';
    } else {
      $defaults = array(
          'menu_class' => $menu_class,
          'menu_id' => '',
          'container' => '',
          'fallback_cb' => '',
      );

      if (has_nav_menu($location)) {
          $defaults['theme_location'] = $location;
      }

      return wp_nav_menu($defaults);
    }

}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */

function doccure_body_classes($classes)
{

  $current_id = doccure_get_page_id();
  $page_settings = $current_id ? get_post_meta($current_id, 'doccure_page_settings', true) : '';
  $enable_onepage_menu = isset($page_settings['doccure_one_page_menu_enable']) ? (bool)$page_settings['doccure_one_page_menu_enable'] : '';

    $classes[] = !empty(doccure_get_option('header-position')) ? 'has-' . doccure_get_option('header-position') : 'has-header-relative';
    $classes[] = !empty(doccure_get_layout_id()) ? 'doccure-layouts-' . doccure_get_layout_id() : 'doccure-layouts-default';
    $classes[] = doccure_is_woo_active() ? 'doccure_woo-active' : '';
    $classes[] = !empty(doccure_get_option('my_account_style')) ? 'doccure_my-account-page-' . doccure_get_option('my_account_style') : 'doccure_my-account-page-style-1';
    $classes[] = !empty(doccure_get_option('blog_sidebar_style')) ? 'doccure_widget-' . doccure_get_option('blog_sidebar_style') : 'doccure_widget-style-1';
    $classes[] = !empty(doccure_get_option('button-shape')) ? 'btn-' . doccure_get_option('button-shape') : 'btn-rounded';
    $classes[] = !empty(doccure_get_option('button-style')) ? 'btn-' . doccure_get_option('button-style') : 'btn-style-1';
    $classes[] = !empty(doccure_get_option('input-style')) ? 'doccure-form-input-' . doccure_get_option('input-style') : 'doccure-form-input-style-1';
    $classes[] = (doccure_get_option('shop_infinite_scroll') == true ) ? esc_attr('doccure_product-infinite-scroll') : '';
    $classes[] = (doccure_get_option('disable-page-scrolling') == true ) ? esc_attr('doccure-disable-page-scrolling') : '';
    $classes[] = (doccure_get_option('enable-cpt-image-filter') == true && !empty(doccure_get_option('filter-style'))) ? 'doccure-image-filter-' . doccure_get_option('filter-style') : '';
    $classes[] = 'archive-ajax-enabled';
    $classes[] = ($enable_onepage_menu == true ) ? 'doccure-one-page-menu' : '';

    return $classes;
}

add_filter('body_class', 'doccure_body_classes');


/**
 * Get all header related classes from theme options.
 *
 * @since 1.0.0
 */
function doccure_header_classes($classes = [])
{

    $classes[] = !empty(doccure_get_option('header-layout')) ? 'header-' . doccure_get_option('header-layout') : 'header-layout-2';
    $classes[] = doccure_get_option('header-scheme', 'header-scheme-light');
    $classes[] = doccure_get_option('header-position', 'header-relative');

    if (empty($classes)) return;

    return apply_filters('doccure/header/header_classes', str_replace(',', '', implode(', ', $classes)));

}

/**
 * Get all sticky headers related classes from theme options.
 *
 * @since 1.0.0
 */
function doccure_header_sticky_classes($classes = [])
{

    $classes[] = !empty(doccure_get_option('header-layout')) ? 'header-' . doccure_get_option('header-layout') : 'header-layout-1';
    $classes[] = doccure_get_option('header-sticky-scheme', 'header-scheme-light');

    if (empty($classes)) return;

    return apply_filters('doccure/header/header_sticky_classes', str_replace(',', '', implode(', ', $classes)));

}

/**
 * Return Customs excerpt
 *
 * @param int $limit - The limit of characters to display
 * @param string $text - The text to cut down, can be excerpt, custom text, or anything else.
 *
 * @since 1.0.0
 */


function doccure_custom_excerpt($limit, $text ='') {
	$text = $text == '' ? get_the_content() : $text;
	$content = explode(' ', $text, $limit);
	if (count($content)>=$limit) {
		array_pop($content);
		$content = implode(" ",$content).'...';
	} else {
		$content = implode(" ",$content);
	}
	$content = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $content);
	return $content;
}


/**
 * doccure Home page app view (This function will redirect all users on mobile to your assigned page from
 * theme options instead of the main home page).
 *
 * @since 1.0.0
 */
function doccure_mobile_homepage()
{

    $mobile_view = doccure_get_option('mobile_view', 'responsive_view');

    if (is_front_page() && wp_is_mobile() && $mobile_view == 'app_view') {

        $mobile_home_id = !empty(doccure_get_option('app_view_home')) ? doccure_get_option('app_view_home') : '';

        if (!empty($mobile_home_id) && get_option('page_on_front') != $mobile_home_id) {
            $app_view_home = esc_url(get_the_permalink($mobile_home_id));

            if (empty($app_view_home)) return false;

            ob_start();
            wp_safe_redirect($app_view_home);
            ob_flush();

        }

    }
}

add_action('template_redirect', 'doccure_mobile_homepage');


/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */

function doccure_pingback_header()
{

    if (is_singular() && pings_open()) {

        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));

    }

}

add_action('wp_head', 'doccure_pingback_header');


if (!function_exists('doccure_comment_form_field')) {

    /**
     * Function for comment field order.
     *
     * @param array $fields fields array.
     * @return array
     * @since  1.0.0
     *

     */

    function doccure_comment_form_field($fields)
    {


        $comment_field = isset($fields['comment']) ? $fields['comment'] : '';

        $cookies_field = isset($fields['cookies']) ? $fields['cookies'] : '';


        unset($fields['comment']);

        unset($fields['cookies']);


        $fields['comment'] = $comment_field;

        $fields['cookies'] = $cookies_field;


        return $fields;

    }

}

add_filter('comment_form_fields', 'doccure_comment_form_field');


if (!function_exists('doccure_widget_categories_args')) {

    /**
     * Change the walker for the categories widget.
     *
     * @param array $instance fields array.
     * @param array $cat_args fields array.
     * @return array
     */

    function doccure_widget_categories_args($cat_args, $instance)
    {

        $cat_args['walker'] = new doccure_Walker_Category;

        return $cat_args;

    }

}

add_filter('widget_categories_args', 'doccure_widget_categories_args', 10, 2);


if (!function_exists('doccure_archive_count')) {

    /**
     * Change the sturcture for the archives link.
     *
     * @param string $links string.
     * @return string
     */

    function doccure_archive_count($links)
    {

        $links = str_replace('&nbsp;(', '<span>', $links);

        $links = str_replace(')', '</span>', $links);

        return $links;

    }

}

add_filter('get_archives_link', 'doccure_archive_count');


/**
 * Get the current page layout (Boxed - Full Width)
 *
 * @since  1.0.0
 */
function doccure_get_page_layout()
{

    // Current page ID
    $current_id = doccure_get_page_id();

    // Possible layout values
    $avaiable_layouts = array('container', 'container-fluid');

    // Page meta
    $page_settings = $current_id ? get_post_meta($current_id, 'doccure_page_settings', true) : '';
    $page_custom_layout = isset($page_settings['doccure_page_layout']) ? $page_settings['doccure_page_layout'] : '';

    // Default page layout value
    $page_layout = 'container';

    if (in_array($page_custom_layout, $avaiable_layouts)) {
        $page_layout = $page_custom_layout;
    } elseif (doccure_get_option('page_layout')) {
        $page_layout = doccure_get_option('page_layout');
    }

    return apply_filters('doccure/page/page_layout', $page_layout);

}

/**
 * Get posts for options
 * @param $tax is for the post type name
 * @return $result returns the posts in the added post type
 * @since 2.0.0
 */
if (!function_exists('doccure_get_posts_select')) {
    function doccure_get_posts_select($tax)
    {
        $result = [];
        $args = array(
            'post_type' => $tax
        );
        $posts = get_posts($args);
        if ($posts) {
            foreach ($posts as $post) {
                $result[$post->ID] = $post->post_title;
            }
        }
        return $result;
    }
}

/**
 * Get Custom Page Template
 * @since 2.0.0
 */
function doccure_get_the_page_template($template, $switch)
{
    $page_template = doccure_get_option($template);
    $page_template_location = doccure_get_option($switch);
    if ($page_template && $page_template_location) {
        $post = get_post($page_template);
        return do_shortcode($post->post_content);
    }
    // wp_reset_postdata();
}

/**
* Ajax search for header search
* @since 2.0.0
*/
function doccure_ajax_search_form(){
  if(doccure_is_woo_active() == true){ ?>
    <div class="search-form-wrapper doccure-ajax-search-wrap">
      <div class="ajax-searchform-container">
        <div class="search-trigger doccure_close">
            <span></span>
            <span></span>
        </div>
        <?php get_product_search_form(); ?>
        <div class="doccure-product-search-results"></div>
      </div>
    </div>
  <?php } else { ?>
    <div class="search-form-wrapper">
        <div class="search-trigger doccure_close">
            <span></span>
            <span></span>
        </div>
        <?php get_search_form(); ?>
    </div>
  <?php }
}


/**
 * Get the content of the Ajax Search form.
  *
 * @since 2.0.0
 */
function doccure_ajax_search_content(){

  $keyword    = !empty($_POST['keyword']) ? $_POST['keyword'] : '';

  $args = array(
    'post_type' => 'product',
    'limit'         => -1,
    's' =>  '',
    'tax_query' => ''
  );

  if(!empty($keyword)) $args['s'] = $keyword;

  $query = new WP_Query( $args );

  if($query->have_posts()){

    while($query->have_posts()){
      $query->the_post();
      $product = wc_get_product(get_the_id());
      $categories = $product->get_categories();
      ?>

      <div class="doccure-product-item">
        <?php if(has_post_thumbnail($product->get_id())){ ?>
          <div class="product-thumbnail">
            <?php echo get_the_post_thumbnail($product->get_id(), 'thumbnail'); ?>
          </div>
        <?php } ?>
        <div class="product-desc">
            <a href="<?php echo get_the_permalink($product->get_id()); ?>"><?php echo esc_html($product->get_name()); ?></a>
          <?php woocommerce_template_loop_price(); ?>
        </div>

      </div>

    <?php } ?>

    <p class="text-center">
      <a class="btn-link" href="<?php echo esc_url( home_url( '/' ) . '?s='.$keyword.'&post_type=product' ); ?>"><?php echo esc_html__('View All', 'doccure') ?></a>
    </p>

    <?php
    wp_reset_postdata();

    die();

  } else{

    echo '<p>'.esc_html__('No products were found matching your selection.', 'doccure').'</p>';
    die();

  }

}
add_action( 'wp_ajax_doccure_ajax_search_content', 'doccure_ajax_search_content' );
add_action( 'wp_ajax_nopriv_doccure_ajax_search_content', 'doccure_ajax_search_content' );


/**
 * Get the popup popup style.
  *
 * @since 2.1.1
 */

function doccure_get_popup_layout(){
  get_template_part('template-parts/popup/content');
}

add_action('doccure_after_footer', 'doccure_get_popup_layout');

/**
 * login/register ajax form.
  *
 * @since 2.1.0
 */

function doccure_ajax_login_register_form(){
  get_template_part('template-parts/header/elements/login-register-popup-form');
}
add_action('doccure_after_footer', 'doccure_ajax_login_register_form', 99);

/**
 * Login with Ajax
 */
function doccuree_ajax_login()
{
    // First check the nonce, if it fails the function will break
    check_ajax_referer('ajax-login-nonce', 'security');
    // Nonce is checked, get the POST data and sign user on
    $info = array();
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
    $info['remember'] = true;
    $user_signon = wp_signon($info, false);
    if (is_wp_error($user_signon)) {
        echo json_encode(array('loggedin' => false, 'message' => esc_html('Wrong username or password.')));
    } else {
        echo json_encode(array('loggedin' => true, 'message' => esc_html('Login successful, redirecting...')));
    }
    die();
}

add_action('wp_ajax_nopriv_doccure_ajax_login', 'doccuree_ajax_login');

/**
* Register user using ajax
*
* @since 2.1.0
*/

function doccure_ajax_user_registration(){

    if( $_POST['action'] == 'doccure_register_user' ) {

      $error = '';

      $uname = trim( $_POST['username'] );
       $email = trim( $_POST['mail_id'] );
       $fname = trim( $_POST['firstname'] );
       $lname = trim( $_POST['lastname'] );
       $pswrd = $_POST['passwrd'];
       $cf_pswrd = $_POST['confirmPasswrd'];

      if( empty( $_POST['username'] ) ) {
       $error .= '<p class="error">'.esc_html('Enter Username', 'doccure').'</p>';
      }

      if( empty( $_POST['mail_id'] ) ) {
       $error .= '<p class="error">Enter Email Id</p>';
      } elseif( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
       $error .= '<p class="error">'.esc_html('Enter Valid Email', 'doccure').'</p>';
      }


      if( empty( $_POST['passwrd'] ) ) {
       $error .= '<p class="error">'.esc_html('Password should not be blank', 'doccure').'</p>';
      }

      if(empty( $_POST['confirmPasswrd'] )){
        $error .= '<p class="error">'.esc_html('Confirm Password should not be blank', 'doccure').'</p>';
      } elseif( $pswrd !== $cf_pswrd ){
        $error .= '<p class="error">'.esc_html("Password doesn't match", "doccure").'</p>';
      }

      if( empty( $_POST['firstname'] ) ) {
       $error .= '<p class="error">'.esc_html('Enter First Name', 'doccure').'</p>';
      } elseif( !preg_match("/^[a-zA-Z'-]+$/",$fname) ) {
       $error .= '<p class="error">'.esc_html('Enter Valid First Name', 'doccure').'</p>';
      }

      if( empty( $_POST['lastname'] ) ) {
       $error .= '<p class="error">'.esc_html('Enter Last Name', 'doccure').'</p>';
      } elseif( !preg_match("/^[a-zA-Z'-]+$/",$lname) ) {
       $error .= '<p class="error">'.esc_html('Enter Valid Last Name', 'doccure').'</p>';
      }

      if( empty( $error ) ){

        $status = wp_create_user( $uname, $pswrd ,$email );

        if( is_wp_error($status) ){

        $msg = '';

         foreach( $status->errors as $key=>$val ){

           foreach( $val as $k=>$v ){
             $msg = '<p class="error">'.$v.'</p>';
           }
         }

         echo wp_kses($msg, array(
             'p' => array(
                 'class' => array(),
             ),
         ));

      } else { ?>
            <script>
              jQuery("#doccure-register-form")[0].reset();
            </script>
           <?php $msg = '<p class="success">'.esc_html('Registration Successful', 'doccure').'</p>';
           echo wp_kses($msg, array(
               'p' => array(
                   'class' => array(),
               ),
           ));
         }

      } else{
        echo wp_kses($error, array(
            'p' => array(
                'class' => array(),
            ),
        ));
      }
       wp_die();
    }
}
add_action( 'wp_ajax_doccure_register_user', 'doccure_ajax_user_registration' );
add_action( 'wp_ajax_nopriv_doccure_register_user', 'doccure_ajax_user_registration' );

/**
* Update new register user details
*/

function doccure_register_user_metadata( $user_id ){

  if( !empty( $_POST['firstname'] ) && !empty( $_POST['lastname'] ) ){
  update_user_meta( $user_id, 'first_name', trim($_POST['firstname']) );
  update_user_meta( $user_id, 'last_name', trim($_POST['lastname']) );
 }

 //update_user_meta( $user_id, 'show_admin_bar_front', false );
}

add_action( 'user_register', 'doccure_register_user_metadata' );
add_action( 'profile_update', 'doccure_register_user_metadata' );
