<?php
/**
 *
 * Custom Hooks
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @since 1.0
 */
/**
 * @get next post
 * @return link
 */
if (!function_exists('doccure_next_post')) {

    function doccure_next_post($format) {
        $format = str_replace('href=', 'class="btn-prevpost fa fa-arrow-left" href=', $format);
        return $format;
    }

    add_filter('next_post_link', 'doccure_next_post');
}

/**
 * @get next post
 * @return link
 */
if (!function_exists('doccure_previous_post')) {

    function doccure_previous_post($format) {
        $format = str_replace('href=', 'class="btn-nextpost fa fa-arrow-right" href=', $format);
        return $format;
    }

    add_filter('previous_post_link', 'doccure_previous_post');
}


/**
 * @Naigation Filter
 * @return {sMenu Item class}
 */
if (!function_exists('doccure_add_menu_parent_class')) {
    add_filter('wp_nav_menu_objects', 'doccure_add_menu_parent_class');

    function doccure_add_menu_parent_class($items) {
        $parents = array();
        foreach ($items as $item) {
            if ($item->menu_item_parent && $item->menu_item_parent > 0) {
                $parents[] = $item->menu_item_parent;
            }
        }
        foreach ($items as $item) {
            if (in_array($item->ID, $parents)) {
                $item->classes[] = 'dropdown';
            }
        }
        return $items;
    }

}

/**
 * @get custom Excerpt
 * @return link
 */
if (!function_exists('doccure_prepare_custom_excerpt')) {

    function doccure_prepare_custom_excerpt($more = '...') {
        return '....';
    }

    add_filter('excerpt_more', 'doccure_prepare_custom_excerpt');
}

/**
 * @Change Reply link Class
 * @return sizes
 */
if (!function_exists('doccure_replace_reply_link_class')) {
    add_filter('comment_reply_link', 'doccure_replace_reply_link_class');

    function doccure_replace_reply_link_class($class) {
        $class = str_replace("class='comment-reply-link'", 'class="comment-reply-link dc-btnreply"', $class);
        return $class;
    }

}

/**
 * @Section wraper before
 * @return 
 */
if (!function_exists('doccure_prepare_section_wrapper_before')) {

    function doccure_prepare_section_wrapper_before($post_id='') {
       ob_start();
		?>
		<div class="main-page-wrapper">
		<?php if( !empty( $post_id ) ){
			$post_meta	= doccure_get_post_meta( $post_id );
			if( isset( $post_meta['am_page_title'] ) && $post_meta['am_page_title'] === 'hide' ){
				$show	= false;
			} else{
				$show	= true;
			}
			
			if( $show === true ){
			?>
			<div class="dc-runner">
				<div class="dc-runner-content">
					<div class="dc-runner-heading">
						<h3><?php echo get_the_title($post_id);?></h3>
					</div>
				</div>
			</div>
  		<?php }}?>
   		<?php
		echo ob_get_clean();
    }

    add_action('doccure_prepare_section_wrapper_before', 'doccure_prepare_section_wrapper_before',10,1);
}

/**
 * @Section wraper after
 * @return 
 */
if (!function_exists('doccure_prepare_section_wrapper_after')) {

    function doccure_prepare_section_wrapper_after() {
        echo '</div>';
    }

    add_action('doccure_prepare_section_wrapper_after', 'doccure_prepare_section_wrapper_after');
}


/**
 * @Post Classes
 * @return 
 */
if (!function_exists('doccure_post_classes')) {

    function doccure_post_classes($classes, $class, $post_id) {
        //Add Your custom classes
        return $classes;
    }

    add_filter('post_class', 'doccure_post_classes', 10, 3);
}
/**
 * @Add Body Class
 * @return 
 */
if (!function_exists('doccure_content_classes')) {

    function doccure_content_classes($classes) {
		global $doccure_options,$titlebar_enabled;
        if (is_singular()) {
            $_post = get_post();
            if ($_post != null) {
                if ($_post && preg_match('/vc_row/', $_post->post_content)) {
                    $classes[] = 'vc_being_used';
                }
            }
        }

        //check if maintenance is enable
        $maintenance 	= !empty( $doccure_options['maintenance'] ) ? $doccure_options['maintenance'] : false;

        $post_name = doccure_get_post_name();
        if (( isset($maintenance) && $maintenance === true && !is_user_logged_in() ) || $post_name == "coming-soon"
        ) {
            $classes[] = 'dc-comingsoon-page';
        }

        if (class_exists('Woocommerce') && is_woocommerce() && is_shop()) {
            $classes[] = 'dc-shop-page';
        }
		
		//add dashboard class
		if (is_page_template('directory/dashboard.php')) {
			$classes[] = 'dc-dashboard';
		}

		if( !empty( $titlebar_enabled ) ){
			$classes[] = $titlebar_enabled;
		}
		
        return $classes;
    }

    add_filter('body_class', 'doccure_content_classes', 1);
}

/**
 * @Remove VC Classes
 * @return 
 */
/*if (!function_exists('doccure_classes_for_vc_row_and_vc_column')) {

    function doccure_classes_for_vc_row_and_vc_column($class_string, $tag) {
        if ($tag == 'vc_row' || $tag == 'vc_row_inner') {
            $class_string = preg_replace('/vc_row/', 'dc-elm-section vc-doccure-section', $class_string);
            $class_string = $class_string . ' dc-elm-section';
        }
        return $class_string; Important: you should always return modified or original $class_string
    }

    add_filter('vc_shortcodes_css_class', 'doccure_classes_for_vc_row_and_vc_column', 10, 2);
}*/


/**
 * Add theme version to admin footer
 * @return CSS
 */
if (!function_exists('add_doccure_version_to_footer_admin')) {

    function add_doccure_version_to_footer_admin($html) {
		$theme_version 	  = wp_get_theme('doccure');
        $doccure_version = $theme_version->get('Version');
        $doccure_name = '<a href="' . esc_url( $theme_version->get('AuthorURI') ) . '" target="_blank">' . $theme_version->get('Name') . '</a>';

        return ( empty($html) ? '' : $html . ' | ' ) . $doccure_name . ' ' . $doccure_version;
    }

    if (is_admin()) {
        add_filter('update_footer', 'add_doccure_version_to_footer_admin', 13);
    }
}

/**
 * @Product Image 
 * @return {}
 */
if (!function_exists('doccure_prepare_post_thumbnail')) {

    function doccure_prepare_post_thumbnail($object, $atts) {
        extract(shortcode_atts(array(
            "width" => '300',
            "height" => '300',
                        ), $atts));

        if (isset($object) && !empty($object)) {
            return $object;
        } else {
            $object_url = get_template_directory_uri() . '/images/fallback-' . intval( $width ) . 'x' . intval( $height ) . '.jpg';
            return '<img width="' . intval( $width ) . '" height="' . intval( $height ) . '" src="' . esc_url($object_url) . '" alt="' . esc_attr__('Placeholder', 'doccure') . '">';
        }
    }

    add_filter('doccure_prepare_post_thumbnail', 'doccure_prepare_post_thumbnail', 10, 3);
}

/**
 * @ Prevoius Links
 * @return 
 */
if (!function_exists('doccure_do_process_next_previous_link')) {

    function doccure_do_process_next_previous_link($post_type = 'post') {
        global $post;
        $prevous_post_id = $next_post_id = '';
        $post_type 		 = get_post_type($post->ID);
        $count_posts 	 = wp_count_posts($post_type)->publish;
		
        $args = array(
            'posts_per_page' 	=> -1,
            'order' 			=> 'ASC',
            'post_type' 		=> $post_type,
        );

        $all_posts = get_posts($args);

        $ids = array();
        foreach ($all_posts as $current_post) {
            $ids[] = $current_post->ID;
        }
		
        $current_index = array_search($post->ID, $ids);

        if (isset($ids[$current_index - 1])) {
            $prevous_post_id = $ids[$current_index - 1];
        }

        if (isset($ids[$current_index + 1])) {
            $next_post_id = $ids[$current_index + 1];
        }
        ?>
        <ul class="dc-postnav">
            <?php
            if (isset($prevous_post_id) && !empty($prevous_post_id) && $prevous_post_id >= 0) {
                $prev_thumb = doccure_prepare_thumbnail_from_id($prevous_post_id, 71, 71);
                if (empty($prev_thumb)) {
                    $prev_thumb = get_template_directory_uri() . '/images/img-71x71.jpg';
                }
                ?>
                <li class="dc-postprev">
                    <article class="dc-themepost th-thumbpost">
                        <figure class="dc-themepost-img">
                            <a href="<?php echo esc_url(get_permalink($prevous_post_id)); ?>"><img alt="<?php echo esc_attr(get_the_title($next_post_id)); ?>" src="<?php echo esc_url($prev_thumb); ?>"></a>
                        </figure>
                        <div class="dc-contentbox">
                            <a class="dc-btnprevpost" href="<?php echo esc_url(get_permalink($prevous_post_id)); ?>"><?php esc_html_e('previous post', 'doccure'); ?></a>
                            <div class="dc-posttitle">
                                <h2><a href="<?php echo esc_url(get_permalink($prevous_post_id)); ?>"><?php echo esc_html(get_the_title($next_post_id)); ?></a></h2>
                            </div>
                        </div>
                    </article>
                </li>

            <?php } ?>
            <?php
            if (isset($next_post_id) && !empty($next_post_id) && $next_post_id >= 0) {
                $next_thumb = doccure_prepare_thumbnail_from_id($next_post_id, 71, 71);

                if (empty($next_thumb)) {
                    $next_thumb = get_template_directory_uri() . '/images/img-71x71.jpg';
                }
                ?>
                <li class="dc-postnext">
                    <article class="dc-themepost dc-thumbpost">
                        <figure class="dc-themepost-img"> 
                            <a href="<?php echo esc_url(get_permalink($next_post_id)); ?>"><img alt="<?php echo esc_attr(get_the_title($next_post_id)); ?>" src="<?php echo esc_url($next_thumb); ?>"></a> 
                        </figure>
                        <div class="dc-contentbox"> 
                            <a class="dc-btnnextpost" href="<?php echo esc_url(get_permalink($next_post_id)); ?>"><?php esc_html_e('Next post', 'doccure'); ?></a>
                            <div class="dc-posttitle">
                                <h2><a href="<?php echo esc_url(get_permalink($next_post_id)); ?>"><?php echo esc_html(get_the_title($next_post_id)); ?></a></h2>
                            </div>
                        </div>
                    </article>
                </li>
            <?php } ?>
        </ul>
        <?php
        wp_reset_postdata();
    }

    add_action('do_process_next_previous_link', 'doccure_do_process_next_previous_link');
}

/**
 * @ Next/Prevoius Products
 * @return 
 */
if (!function_exists('doccure_do_process_next_previous_product')) {

    function doccure_do_process_next_previous_product() {
        global $post;

        $post_type = 'product';
        $prevous_post_id = $next_post_id = '';
        $post_type = get_post_type($post->ID);
        $count_posts = wp_count_posts($post_type)->publish;
        $args = array(
            'posts_per_page' => -1,
            'post_type' => $post_type,
        );

        $all_posts = get_posts($args);

        $ids = array();
        foreach ($all_posts as $current_post) {
            $ids[] = $current_post->ID;
        }
		
        $current_index = array_search($post->ID, $ids);

        if (isset($ids[$current_index - 1])) {
            $prevous_post_id = $ids[$current_index - 1];
        }

        if (isset($ids[$current_index + 1])) {
            $next_post_id = $ids[$current_index + 1];
        }
        ?>
        <div class="dc-nextprevpost">
            <?php if (isset($prevous_post_id) && !empty($prevous_post_id) && $prevous_post_id >= 0) { ?>
                <div class="dc-btnprevpost">
                    <a href="<?php echo esc_url(get_permalink($prevous_post_id)); ?>">
                        <i class="fa fa-chevron-left"></i>
                        <div class="dc-booknameandtitle">
                            <h3><?php echo esc_html(get_the_title($next_post_id)); ?></h3>
                        </div>
                    </a>
                </div>
            <?php } ?>
            <?php if (isset($next_post_id) && !empty($next_post_id) && $next_post_id >= 0) { ?>
                <div class="dc-btnnextpost">
                    <a href="<?php echo esc_url(get_permalink($next_post_id)); ?>">
                        <div class="dc-booknameandtitle">
                            <h3><?php echo esc_html(get_the_title($next_post_id)); ?></h3> 
                        </div>
                        <i class="fa fa-chevron-right"></i>
                    </a>
                </div>
            <?php } ?>
        </div>
        <?php
        wp_reset_postdata();
    }

    add_action('doccure_do_process_next_previous_product', 'doccure_do_process_next_previous_product');
}

/**
 * @IE Compatibility
 * @return 
 */
if (!function_exists('doccure_ie_compatibility')) {

    function doccure_ie_compatibility() {
        ?>
        <!--[if lt IE 9]>
                <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <?php
    }

    add_action('doccure_ie_compatibility', 'doccure_ie_compatibility');
}


/**
 * @Fallback Image 
 * @return {}
 */
if (!function_exists('doccure_get_fallback_image')) {

    function doccure_get_fallback_image($object, $atts = array()) {
        extract(shortcode_atts(array(
            "width" => '300',
            "height" => '300',
                        ), $atts));

        if (isset($object) && !empty($object) && $object != NULL
        ) {
            return $object;
        } else {
            return get_template_directory_uri() . '/images/fallback' . intval( $width ) . 'x' . intval( $height ) . '.jpg';
        }
    }

    add_filter('doccure_get_fallback_image', 'doccure_get_fallback_image', 10, 3);
}

/**
 * @Filter to return Default image if no image found.
 * @return {}
 */
if (!function_exists('doccure_get_media_fallback')) {

    function doccure_get_media_fallback($object, $atts = array()) {
        extract(shortcode_atts(array(
            "width" => '150',
            "height" => '150',
                        ), $atts));

        if (isset($object) && !empty($object) && $object != NULL
        ) {
            return $object;
        } else {
			return get_template_directory_uri() . '/images/img-' . intval( $width ) . 'x' . intval( $height ) . '.jpg';
        }
    }

    add_filter('doccure_get_media_filter', 'doccure_get_media_fallback', 10, 3);
}


/**
 * @non strict characters allow
 * @return allow non strict characters
 */
if( !function_exists( 'doccure_allow_non_strict_login' ) ) {    

    function doccure_allow_non_strict_login( $username, $raw_username, $strict ) {

        if( !$strict )
        return $username;
        return sanitize_user(stripslashes($raw_username), false);
    }

    add_filter('sanitize_user', 'doccure_allow_non_strict_login', 10, 3);
}