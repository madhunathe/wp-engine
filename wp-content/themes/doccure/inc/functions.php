<?php
/**
 *
 * General Theme Functions
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfoliot
 * @since 1.0
 */

/**
 * @Add Images Sizes
 * @return sizes
 */
add_image_size('doccure_blog_large', 1140, 400, true); 
add_image_size('doccure_blog_sidebar', 825, 360, true); 
add_image_size('doccure_doctors_2x', 547, 428, true);
add_image_size('doccure_blog_medium', 545, 389, true); 
add_image_size('doccure_blog_grid', 350, 250, true);
add_image_size('doccure_blog_list', 308, 220, true);
add_image_size('doccure_doctors_type', 261, 205, true);
add_image_size('doccure_blog_grid_v2', 255, 250, true);
add_image_size('doccure_top_rat_doc', 255, 200, true);
add_image_size('doccure_listing_thumb', 100, 100, true);
add_image_size('doccure_latest_articles_widget', 65, 65, true); 
add_image_size('doccure_artical_auther', 30, 30, true);
add_image_size('doccure_chosen_icone', 20, 20, true);

/**
 * @Init Pagination Code Start
 * @return 
 */

 if ( ! function_exists( 'doccure_string_limit_words' ) ) {
	function doccure_string_limit_words($string, $word_limit) {
	    $words = explode(' ', $string, ($word_limit + 1));
	    if (count($words) > $word_limit) {
	        array_pop($words);
	        //add a ... at last article when more than limit word count
	        return implode(' ', $words) ;
	    } else {
	        //otherwise
	        return implode(' ', $words);
	    }
	}
} 

if (!function_exists('doccure_prepare_pagination')) {
    function doccure_prepare_pagination($pages = '', $range = 4) {
		global $paged;
		$pg_page = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
		$pg_paged = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
		
		//paged works on single pages, page - works on homepage
		$paged = max($pg_page, $pg_paged);
		$current_page = $paged;
		
		if ($pages == '') {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if (!$pages) {
				$pages = 1;
			}
		} else {
			$pages = ceil($pages / $range);
		}

		if (1 != $pages) {
			echo "<div class='dc-paginationvtwo'><nav class=\"dc-pagination\"><ul>";

			if ($current_page > 1) {
				echo "<li class='dc-prevpage'><a href='" . esc_url( get_pagenum_link( $current_page - 1 ) ) . "'><i class=\"fa fa-chevron-left\"></i></i></a></li>";
			}

			for ($i = 1; $i <= $pages; $i++) {
				if (1 != $pages && (!( $i >= $current_page + $range + 1 || $i <= $current_page - $range - 1 ) )) {
					echo esc_html( $paged == $i ) ? "<li class=\"dc-active\"><a href='javascript:;'>" . $i . "</a></li>" : "<li><a href='" . esc_url( get_pagenum_link($i) ) . "'>" . $i . "</a></li>";
				}
			}

			if ($current_page < $pages) {
				echo "<li class='dc-nextpage'><a href=\"" . esc_url( get_pagenum_link($current_page + 1) ) . "\"><i class=\"fa fa-chevron-right\"></i></a></li>";
			}

			echo "</ul></nav></div>";
		}
    }

}

/**
 * @Init Comments Pagination Code Start
 * @return 
 */
if (!function_exists('doccure_prepare_comments_pagination')) {

    function doccure_prepare_comments_pagination($pages = '', $range = 4 ,$current_page=1) {
		$pages = $total_pages = ceil(($pages) / $range);
		global $wp;
		$current_url = home_url(add_query_arg(array(), $wp->request)).'/#comments';
		if (1 != $pages) {
			 echo "<div class='dc-paginationvtwo'><nav class=\"dc-pagination\"><ul>";

			if ($current_page > 1) {
				echo "<li class='dc-prevpage'><a href='" . get_comments_pagenum_link($current_page - 1) . "'><i class=\"fa fa-chevron-left\"></i></i></a></li>";
			}

			for ($i = 1; $i <= $pages; $i++) {
				$pagination_url	= get_comments_pagenum_link($i);
				if( $pagination_url === $current_url ) {
					echo "<li><a href='" . esc_url($pagination_url) . "' class=\"active\">" . $i . "</a></li>";
				} else {
					echo "<li><a href='" . esc_url($pagination_url) . "' class=\"inactive\">" . $i . "</a></li>";
				}
				
			}

			if ($current_page < $pages) {
				echo "<li class='dc-nextpage'><a href=\"" . get_comments_pagenum_link($current_page + 1) . "\"><i class=\"fa fa-chevron-right\"></i></a></li>";
			}

			echo "</ul></nav></div>";
		}
    }

}

/**
 * Add New User Roles
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return string
 */
if (!function_exists('doccure_add_user_roles')) {

    function doccure_add_user_roles() {
        global $doccure_options;
        add_role('doctors', esc_html__('Doctor', 'doccure'));
        add_role('regular_users', esc_html__('Patients', 'doccure'));
		add_role('hospitals', esc_html__('Hospital', 'doccure'));
    }

    add_action('admin_init', 'doccure_add_user_roles');
}


/**
 * @get post thumbnail
 * @return thumbnail url
 */
if (!function_exists('doccure_prepare_thumbnail')) {

    function doccure_prepare_thumbnail($post_id, $width = '300', $height = '300') {
        if (has_post_thumbnail($post_id)) {
			get_the_post_thumbnail();
            $thumb_id = get_post_thumbnail_id($post_id);
            $thumb_url = wp_get_attachment_image_src($thumb_id, array(
                200,
                200
                    ), true);
            if ($thumb_url[1] == $width and $thumb_url[2] == $height) {
                return !empty($thumb_url[0]) ? $thumb_url[0] : '';
            } else {
                $thumb_url = wp_get_attachment_image_src($thumb_id, 'full', true);
				if (strpos($thumb_url[0],'media/default.png') !== false) {
					return '';
				} else{
					return !empty( $thumb_url[0] ) ? $thumb_url[0] : '';
				}
            }
        } else {
            return;
        }
    }

}

/**
 * @get post thumbnail
 * @return thumbnail url
 */
if (!function_exists('doccure_prepare_thumbnail_from_id')) {

    function doccure_prepare_thumbnail_from_id($post_id, $width = '300', $height = '300') {
        global $post;
        $thumb_id = get_post_thumbnail_id($post_id);
        if (!empty($thumb_id)) {
            $thumb_url = wp_get_attachment_image_src($thumb_id, array(
                $width,
                $height
                    ), true);
            if ($thumb_url[1] == $width and $thumb_url[2] == $height) {
                return !empty($thumb_url[0]) ? $thumb_url[0] : '';
            } else {
                $thumb_url = wp_get_attachment_image_src($thumb_id, 'full', true);
                return !empty($thumb_url[0]) ? $thumb_url[0] : '';
            }
        } else {
            return 0;
        }
    }

}

/**
 * @get post thumbnail
 * @return thumbnail url
 */
if (!function_exists('doccure_prepare_image_source')) {

    function doccure_prepare_image_source($post_id, $width = '300', $height = '300') {
        global $post;
        $thumb_url = wp_get_attachment_image_src($post_id, array(
            $width,
            $height
                ), true);
        if ($thumb_url[1] == $width and $thumb_url[2] == $height) {
            return !empty($thumb_url[0]) ? $thumb_url[0] : '';
        } else {
            $thumb_url = wp_get_attachment_image_src($post_id, 'full', true);
            return !empty($thumb_url[0]) ? $thumb_url[0] : '';
        }
    }

}


/**
 * @get revolution sliders
 * @return link
 */
if (!function_exists('doccure_prepare_rev_slider')) {

    function doccure_prepare_rev_slider() {
		$revsliders	= array();
        $revsliders[] = esc_html__('Select Slider', 'doccure');
        if (class_exists('RevSlider')) {
            $slider = new RevSlider();
            $arrSliders = $slider->getArrSliders();
            $revsliders = array();
            if ($arrSliders) {
                foreach ($arrSliders as $key => $slider) {
                    $revsliders[$slider->getId()] = $slider->getAlias();
                }
            }
        }
        return $revsliders;
    }

}

/**
 * @get Excerpt
 * @return link
 */
if (!function_exists('doccure_prepare_excerpt')) {

    function doccure_prepare_excerpt($charlength = '255', $more = 'false', $text = 'Read More') {
        global $post;
        $excerpt = trim(preg_replace('/<a[^>]*>(.*)<\/a>/iU', '', get_the_content()));
        if (strlen($excerpt) > $charlength) {
            if ($charlength > 0) {
                $excerpt = substr($excerpt, 0, $charlength);
            } else {
                $excerpt = $excerpt;
            }
			
            if ($more == 'true') {
                $link = ' <a href="' . esc_url(get_permalink()) . '" class="serviceproviders-more">' . esc_html($text) . '</a>';
            } else {
                $link = '...';
            }
			
            echo wp_strip_all_tags($excerpt) . $link;
        } else {
            echo wp_strip_all_tags($excerpt);
        }
    }

}
/**
 * @Esc Data
 * @return categories
 */
if (!function_exists('doccure_esc_specialchars')) {

    function doccure_esc_specialchars($data = '') {
        return $data;
    }

}

/**
 * @Custom post types
 * @return {}
 */
if (!function_exists('doccure_prepare_custom_posts')) {

    function doccure_prepare_custom_posts($post_type = 'post') {
        $posts_array = array();
        $args = array(
            'posts_per_page' => "-1",
            'post_type' 	 => $post_type,
            'order' 		 => 'DESC',
            'orderby' 		 => 'ID',
            'post_status' 	 => 'publish',
            'ignore_sticky_posts' => 1
        );
		
        $posts_query = get_posts($args);
		
        foreach ($posts_query as $post_data):
            $posts_array[$post_data->ID] = $post_data->post_title;
        endforeach;
		
        return $posts_array;
    }

}

/**
 * @Get post name
 * @return {}
 */
if (!function_exists('doccure_get_post_name')) {

    function doccure_get_post_name() {
        global $post;
        if (isset($post)) {
            $post_name = $post->post_name;
        } else {
            $post_name = '';
        }
		
        return $post_name;
    }

}

/**
 * Sanitize a string, removes special charachters
 * @param type $string
 * @author Dreams Technologies
 */
if (!function_exists('doccure_sanitize_string')) {

    function doccure_sanitize_string($string) {
        $filterd_string = array();
        $strings = explode(' ', $string);
        foreach ($strings as $string) {
            $filterd_string[] = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
        }
        return implode(' ', $filterd_string);
    }

}
/**
 * @get sliders
 * @return {}
 */
if (!function_exists('doccure_prepare_sliders')) {

    function doccure_prepare_sliders() {
        global $post, $product;
		$sliders	=  array();
        $args = array(
            'posts_per_page' => '-1',
            'post_type' 	 => 'doccure_sliders',
            'orderby' 		 => 'ID',
            'post_status' 	 => 'publish');
		
        $cust_query = get_posts($args);
        $sliders[0] = esc_html__('Select Slider', 'doccure');
        if (isset($cust_query) && is_array($cust_query) && !empty($cust_query)) {
            foreach ($cust_query as $key => $slider) {
                $sliders[$slider->ID] = get_the_title($slider->ID);
            }
        }
        return $sliders;
    }

}

if (!isset($content_width)) {
    $content_width = 640;
}

/**
 * @Search contents
 * @return 
 */
if (!function_exists('doccure_prepare_search_content')) {

    function doccure_prepare_search_content($limit = 30) {
        global $post;
        $content = '';
        $limit = $limit;
        $post = get_post($post->ID);
        $custom_excerpt = FALSE;
        $read_more = '[...]';
        $raw_content = wp_strip_all_tags(get_the_content($read_more), '<p>');
        $raw_content = preg_replace('/<(\w+)[^>]*>/', '<$1>', $raw_content);

        if ($raw_content && $custom_excerpt == FALSE) {
            $pattern = get_shortcode_regex();
            $content = $raw_content;
            $content = explode(' ', $content, $limit + 1);
            if (doccure_count_items($content) > $limit) {
                ;
                array_pop($content);
                $content = implode(' ', $content);
                if ($limit != 0) {
                    $content .= $read_more;
                }
            } else {
                $content = implode(' ', $content);
            }
        }

        if ($limit != 0) {
            $content = preg_replace('~(?:\[/?)[^/\]]+/?\]~s', '', $content); // strip shortcode and keep the content
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
        }

        return strip_shortcodes(wp_strip_all_tags( $content ) );
    }

}

/* @Image HTML
 * $return {HTML}
 */
if (!function_exists('doccure_get_post_thumbnail')) {

    function doccure_get_post_thumbnail($url = '', $post_id = '', $linked = 'unlinked') {
        global $post;

        if (isset($linked) && $linked === 'linked') {
            echo '<a href="' . esc_url( get_the_permalink($post_id) ) . '"><img src ="' . esc_url($url) . '" alt="' . esc_attr( get_the_title($post_id) ) . '"></a>';
        } else {
            echo '<img src ="' . esc_url($url) . '" alt="' . esc_attr( get_the_title($post_id) ) . '">';
        }
    }

}

/* @Get categories HTML
 * $return {HTML}
 */
if (!function_exists('doccure_get_post_categories')) {

    function doccure_get_post_categories($post_id = '', $classes = '', $categoty_type = 'category', $display_title = 'Categories', $enable_title = 'yes') {
        global $post;
        ob_start();
        $args = array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all');
        $terms = apply_filters('doccure_get_tax_query',array(),$post_id,$categoty_type,$args);
        if (!empty($terms)) {
            ?>
            <div class="dc-catgories-wrap">
                <?php if ( !empty($enable_title) && $enable_title === 'yes' && !empty($display_title)) { ?>
                    <span><?php echo esc_html($display_title); ?></span>
                <?php } ?>
                <?php  foreach ($terms as $key => $terms) { ?>
                    <a class="<?php echo esc_attr($classes); ?>" href="<?php echo esc_url( get_term_link($terms->term_id, $categoty_type) ); ?>"><span><?php echo esc_html($terms->name);?></span></a>
                <?php } ?>

            </div>
            <?php
        }
        echo ob_get_clean();
    }

}

/* @Get tags HTML
 * $return {HTML}
 */
if ( !function_exists( 'doccure_get_post_tags' ) ) {

    function doccure_get_post_tags( $post_id = '', $categoty_type = 'tag', $display_title = 'yes' ) {
        global $post;
        ob_start();
        $args = array( 'orderby' => 'name', 'order' => 'ASC', 'fields' => 'all' );
        $tags = wp_get_post_tags( $post_id, $categoty_type, $args );
        if ( !empty( $tags ) ) { ?>
            <div class="dc-tag dc-widgettag">
                <?php if (isset($display_title) && $display_title === 'yes') { ?>
                <span>
                    <?php esc_html_e('Tags:', 'doccure'); ?>
                </span>
                <?php } ?>
                <?php foreach ($tags as $key => $tag) { ?>
                    <a href="<?php echo esc_url( get_tag_link($tag->term_id, 'tag') ); ?>">
                        <?php echo esc_html($tag->name); ?>
                    </a>
                <?php } ?>
            </div>
            <?php
        }

        echo ob_get_clean();
    }

}
/* @Post author HTML
 * $return {HTML}
 */
if (!function_exists('doccure_get_post_author')) {

    function doccure_get_post_author($post_author_id = '', $linked = 'linked', $post_id = '') {
        global $post;
        echo '<a href="' . esc_url(get_author_posts_url($post_author_id)) . '"><i class="lnr lnr-user"></i><span>' . get_the_author() . '</span></a>';
    }

}
/* @Post date HTML
 * $return {HTML}
 */
if (!function_exists('doccure_get_post_date')) {

    function doccure_get_post_date($post_id = '') {
        global $post;
        echo '<i class="fa-regular fa-calendar"></i>' . date(get_option('date_format'), strtotime(get_the_date('Y-m-d', $post_id)));
    }

}

/* @Post title HTML
 * $return {HTML}
 */
if (!function_exists('doccure_get_post_title')) {

    function doccure_get_post_title($post_id = '') {
        global $post;
        echo '<a href="' . esc_url( get_the_permalink($post_id) ) . '">' . get_the_title($post_id) . '</a>';
    }

}
/* @Play button HTML
 * $return {HTML}
 */
if (!function_exists('doccure_get_play_link')) {

    function doccure_get_play_link($post_id = '') {
        global $post;
        echo '<a class="dc-btnplay" href="' .esc_url( get_the_permalink($post_id) ) . '"></a>';
    }

}

/**
 * @User Public Profile Save
 * @return {}
 */
if (!function_exists('doccure_comingsoon_background')) {

    function doccure_comingsoon_background() {
		global $doccure_options;
		$background = !empty( $doccure_options['comingsoon_bg']['url'] ) ? $doccure_options['comingsoon_bg']['url'] : get_template_directory_uri() . '/images/commingsoon-bg.jpg';
        return $background;
    }

}

/**
 * Get Social Icon Name
 * $return HTML
 */
if (!function_exists('doccure_get_social_icon_name')) {

    function doccure_get_social_icon_name($icon_class = '') {
        $icons = array(
            'fa-facebook' 				=> 'dc-facebook',
            'fa-facebook-square' 		=> 'dc-facebook',
            'fa-facebook-official' 		=> 'dc-facebook',
            'fa-facebook-f' 			=> 'dc-facebook',
            'fa-twitter' 				=> 'dc-twitter',
            'fa-twitter-square' 		=> 'dc-twitter',
            'fa-linkedin' 				=> 'dc-linkedin',
            'fa-linkedin-square' 		=> 'dc-linkedin',
            'fa-google-plus' 			=> 'dc-googleplus',
            'fa-google-plus-square' 	=> 'dc-googleplus',
            'fa-google' 				=> 'dc-googleplus',
            'fa-rss' 					=> 'dc-rss',
            'fa-rss-square' 			=> 'dc-rss',
            'fa-dribbble' 				=> 'dc-dribbble',
            'fa-youtube' 				=> 'dc-youtube',
            'fa-youtube-play' 			=> 'dc-youtube',
            'fa-youtube-square' 		=> 'dc-youtube',
            'fa-pinterest-square' 		=> 'dc-pinterest',
            'fa-pinterest-p' 			=> 'dc-pinterest',
            'fa-pinterest' 				=> 'dc-pinterest',
            'fa-flickr' 				=> 'dc-flickr',
            'fa-whatsapp' 				=> 'dc-whatsapp',
            'fa-tumblr-square' 			=> 'dc-tumblr',
            'fa-tumblr' 				=> 'dc-tumblr',
			'fa fa-facebook'			=> 'dc-facebook',
        );
        if (!empty($icon_class)) {
            $substr_icon_class = substr($icon_class, 3);
            if (array_key_exists($substr_icon_class, $icons)) {
                return $icons[$substr_icon_class];
            }
        }
    }

}


/**
 * Get Image Src
 * @return 
 */
if (!function_exists('doccure_get_image_metadata')) {

    function doccure_get_image_metadata($attachment_id) {

        if (!empty($attachment_id)) {
            $attachment = get_post($attachment_id);
            if (!empty($attachment)) {
                return array(
                    'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
                    'caption' => $attachment->post_excerpt,
                    'description' => $attachment->post_content,
                    'href' => get_permalink($attachment->ID),
                    'src' => $attachment->guid,
                    'title' => $attachment->post_title
                );
            } else {
                return array();
            }
        }
    }

}

/**
 * A custom sanitization function that will take the incoming input, and sanitize
 * the input before handing it back to WordPress to save to the database.
 *
 */
if (!function_exists('doccure_sanitize_array')) {

    function doccure_sanitize_array($input) {
        if (!empty($input)) {
            // Initialize the new array that will hold the sanitize values
            $new_input = array();

            // Loop through the input and sanitize each of the values
            foreach ($input as $key => $val) {
                $new_input[$key] = isset($input[$key]) ? sanitize_text_field($val) : '';
            }

            return $new_input;
        } else {
            return $input;
        }
    }

}

/**
 * @OWL Carousel RTL
 * @return {}
 */
if (!function_exists('doccure_owl_rtl_check')) {

    function doccure_owl_rtl_check() {
        if (is_rtl()) {
            return 'true';
        } else {
            return 'false';
        }
    }

}

/**
 * @OWL Carousel RTL
 * @return {}
 */
if (!function_exists('doccure_rtl_check')) {

    function doccure_rtl_check() {
        if (is_rtl()) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * @doccure Unique Increment
 * @return {}
 */
if (!function_exists('sp_unique_increment')) {

    function sp_unique_increment($length = 5) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

}

/**
 * @Custom Title Linking
 * @return {}
 */
if (!function_exists('doccure_get_registered_sidebars')) {

    function doccure_get_registered_sidebars() {
        global $wp_registered_sidebars;
        $sidebars = array();
		
        foreach ($wp_registered_sidebars as $key => $sidebar) {
            $sidebars[$key] = $sidebar['name'];
        }
		
        return $sidebars;
    }

}

/**
 * @Add http from URL
 * @return {}
 */
if (!function_exists('doccure_add_http_protcol')) {

    function doccure_add_http_protcol($url) {
        $protolcol = is_ssl() ? "https" : "http";
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = $protolcol . ':' . $url;
        }
        return $url;
    }

}

/**
 * Get Page or Post Slug by id
 * @return slug
 */
if (!function_exists('doccure_get_slug')) {

    function doccure_get_slug($post_id) {
        if (!empty($post_id)) {
            return get_post_field('post_name', $post_id);
        }
    }

}

/**
 * @Get Dob Date Format 
 * @return {Expected Day}
 */
if (!function_exists('doccure_get_dob_format')) {

    function doccure_get_dob_format($date, $return_type = 'echo') {
        ob_start();
        $current_month 	= date("n");
        $current_day 	= date("j");

        $dob 		= strtotime($date);
        $dob_month 	= date("n", $dob);
        $dob_day 	= date("j", $dob);

        if ($current_month == $dob_month) {
            if ($current_day == $dob_day) {
                esc_html_e('Today', 'doccure');
            } elseif ($current_day == $dob_day + 1) {
                esc_html_e('Yesterday', 'doccure');
            } elseif ($current_day == $dob_day - 1) {
                esc_html_e('Tomorrow', 'doccure');
            } else {
                esc_html_e('In this month', 'doccure');
            }
        } elseif ($current_month < $dob_month) {
            esc_html_e('In future', 'doccure');
        } else {
            esc_html_e('Long back', 'doccure');
        }

        if (isset($return_type) && $return_type === 'return') {
            return ob_get_clean();
        } else {
            echo ob_get_clean();
        }
    }

}
/**
 * comment form fields
 * @return slug
 */
if (!function_exists('doccure_modify_comment_form_fields')) {
	function doccure_modify_comment_form_fields(){
		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );

		$fields['author'] = '<div class="form-row"><div class="form-group col-sm-6"><input type="text" name="author" id="author" value="'. esc_attr( $commenter['comment_author'] ) .'" placeholder="'. esc_attr__("Your name (required)", 'doccure').'" size="22" tabindex="1" '. ( $req ? 'aria-required="true"' : '' ).' class="form-control" /></div>';

		$fields['email'] = '<div class="form-group col-sm-6"><input type="text" name="email" id="email" value="'. esc_attr( $commenter['comment_author_email'] ) .'" placeholder="'. esc_attr__("Your email (required)", 'doccure').'" size="22" tabindex="2" '. ( $req ? 'aria-required="true"' : '' ).' class="form-control"  /></div></div>';

		return $fields;
	}
	add_filter('comment_form_default_fields','doccure_modify_comment_form_fields');     
} 

/**
 * comment form textarea
 * @return slug
 */
if (!function_exists('doccure_move_comment_field_to_botto')) {
	function doccure_move_comment_field_to_botto( $fields ) {
		$comment_field 	= $fields['comment'];
		unset( $fields['comment'] );
		$fields['comment'] = $comment_field;
		return $fields;
	}

	add_filter( 'comment_form_fields', 'doccure_move_comment_field_to_botto' );
}


/**
 * comments listings
 * @return slug
 */
if (!function_exists('doccure_comments')) {

    function doccure_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;

	$args['reply_text'] = esc_html__('Reply','doccure').' <i class="ti-share-alt"></i>';
	?>
	<li <?php comment_class('comment-entry clearfix'); ?> id="comment-<?php comment_ID(); ?>">
		<div class="d-sm-flex">
           	<figure class="m-0 dc-card-img"><?php echo get_avatar($comment, 55); ?> </figure>
            <div class="card dc-card">
    			<div class="card-body dc-card-body">
					<div class="card-title d-flex dc-cardtitle-user">
						<div class="dc-title-content align-self-center">
                            <div class="dc-auth"><h5><a href="<?php echo esc_url( get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php comment_author(); ?></a></h5></div>
                            <div class="dc-comment-cal"><i class="fa-regular fa-calendar"></i>&nbsp;<?php echo sprintf( _x( '%s ago', '%s = human-readable time difference', 'doccure' ), human_time_diff( get_comment_time( 'U' ), strtotime( current_time( 'mysql' ) ) ) ); ?></div>
						</div>
						<div class="d-flex ml-auto align-self-center dc-reply">
							<?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
						</div>
					</div>
					<div class="card-text dc-author-description">
						<?php if ($comment->comment_approved == '0') : ?>
							<p class="m-0"><?php esc_html_e('Your comment is awaiting moderation.', 'doccure'); ?></p>
						<?php endif; ?>
						<?php comment_text(); ?>
					</div>
    			</div>
            </div>
		</div>
		<?php
	}

}

/**
 * Answer listings
 * @return slug
 */
if (!function_exists('doccure_answers')) {

    function doccure_answers($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;

	$args['reply_text'] = '<i class="fa fa-reply"></i>';
	$profile_id			= doccure_get_linked_profile_id($comment->user_id);
	?>
	<li <?php comment_class('comment-entry clearfix dc-consultation-details'); ?> id="comment-<?php comment_ID(); ?>">
		<?php do_action('doccure_get_user_info_by_ID',$profile_id); ?>
		<div class="dc-description">
			<?php if ($comment->comment_approved == '0') : ?>
			<p class="comment-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'doccure'); ?></p>
			<?php endif; ?>
			<?php if ($comment->comment_approved == '1') :?><p><?php echo do_shortcode(nl2br($comment->comment_content));?></p><?php endif;?>
		</div>
	<?php
	}

}

/**
 * Answer listings
 * @return slug
 */
if (!function_exists('doccure_answer_by_author')) {

    function doccure_answer_by_author($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;

	$profile_id			= doccure_get_linked_profile_id($comment->user_id);
	$thumbnail			= '';
	$date_formate		= get_option('date_format');
	$db_specialities	= apply_filters('doccure_get_tax_query',array(),$comment->comment_post_ID,'specialities','');
	$speciality_img		= !empty( $db_specialities[0] ) ? get_term_meta( $db_specialities[0]->term_id, 'logo', true ) : '';
	
	if( !empty( $speciality_img['attachment_id'] ) ){

		$thumbnail	= wp_get_attachment_image_src( $speciality_img['attachment_id'],	 'doccure_artical_auther', true );

		$thumbnail	= !empty( $thumbnail[0] ) ? $thumbnail[0] : '';
	}

	$post_title		= get_the_title( $comment->comment_post_ID );
	$post_title		= !empty( $post_title ) ? $post_title : '';

	$post_url		= get_the_permalink( $comment->comment_post_ID );
	$post_url		= !empty( $post_url ) ? $post_url : '';

	$post_date		= get_post_field('post_date',$comment->comment_post_ID);?>
	<li <?php comment_class('comment-entry clearfix dc-consultation-details'); ?> id="comment-<?php comment_ID(); ?>">
		<?php if( !empty( $thumbnail ) ){?>
			<figure class="dc-consultation-img dc-imgcolor1">
				<img src="<?php echo esc_url( $thumbnail );?>" alt="<?php esc_attr_e( 'Answer', 'doccure' );?>">
			</figure>
		<?php } ?>
		<?php if( !empty( $post_title ) || !empty( $post_date )) {?>
			<div class="dc-consultation-title">
				<h5>
					<?php if( !empty( $post_title )) {?>
						<a href="<?php echo esc_url( $post_url );?>"><?php echo esc_html( $post_title ) ;?></a>
					<?php } ?>
					<?php if( !empty( $post_date ) ) {?>
						<em><?php echo date($date_formate,strtotime($post_date));?></em>
					<?php } ?>
				</h5>
			</div>
		<?php } ?>
		<?php if( !empty( $comment->comment_content ) ){?>
			<div class="dc-description"><p><?php echo esc_html( $comment->comment_content );?></p></div>
		<?php } ?>
	<?php
	}

}

/**
 * Dokan plugin compatibility
 * @return slug
 */
if (!function_exists('dokan_dashboard_wrap_start')) {
	add_action('dokan_dashboard_wrap_before', 'dokan_dashboard_wrap_start');
	function dokan_dashboard_wrap_start() {
		$output = '';
		$output .='<div class="container">
					<div id="dc-twocolumns" class="dc-twocolumns">
						<div class="row"><div class="col-12">';

		echo do_shortcode( $output);
	}

}

/**
 * Dokan plugin compatibility
 * @return slug
 */
if (!function_exists('dokan_dashboard_wrap_end')) {
	add_action('dokan_dashboard_wrap_after', 'dokan_dashboard_wrap_end');
	function dokan_dashboard_wrap_end() {
		$output = '';
		$output .='</div></div></div>';

		echo do_shortcode( $output);
	}

}

/**
 * comments wrap start
 * @return slug
 */
if (!function_exists('doccure_comment_form_top')) {
	add_action('comment_form_top', 'doccure_comment_form_top');
	function doccure_comment_form_top() {
		$output = '';
		$output .='<fieldset>';

		echo do_shortcode( $output);
	}

}

/**
 * @count items in array
 * @return {}
 */
if (!function_exists('doccure_count_items')) {
    function doccure_count_items($items) {
        if( is_array($items) ){
			return count($items);
		} else{
			return 0;
		}
    }
}

/**
 * comments wrap start
 * @return slug
 */
if (!function_exists('doccure_comment_form')) {
	add_action('comment_form', 'doccure_comment_form');

	function doccure_comment_form() {
		$output = '';
		$output .= '</fieldset>';

		echo do_shortcode( $output );
	}

}

/**
 * Typo extract
 * @return styles
 */
if (!function_exists('doccure_extract_typography')) {

	function doccure_extract_typography($field) {
		$output = '';
		
		if( !empty( $field['font-family'] ) ){
			$output .= 'font-family: ' . ($field['font-family']) . ';';
			$output .= "\r\n";
		}
		
		if (isset($field['google']) && $field['google'] === true) {
			if (isset($field['variation'])) {
				$pattern = '/(\d+)|(regular|italic)/i';
				preg_match_all($pattern, $field['variation'], $matches);
				foreach ($matches[0] as $value) {
					if ($value == 'italic') {
						$output .= 'font-style: ' . $value . ';';
						$output .= "\r\n";
					} else if ($value == 'regular') {
						$output .= 'font-style: normal;';
						$output .= "\r\n";
					} else {
						if( !empty( $value ) ){
							$output .= 'font-weight: ' . $value . ';';
							$output .= "\r\n";	
						}
					}
				}
			}
		} else {
			
			if( !empty( $field['font-style'] ) ){
				$output .= 'font-style: ' . ($field['font-style']) . ';';
				$output .= "\r\n";
			}
			
			if( !empty( $field['font-weight'] ) ){
				$output .= 'font-weight: ' . ($field['font-weight']) . ';';
				$output .= "\r\n";
			}
		}
		
		if( !empty( $field['text-align'] ) ){
			$output .= 'text-align: ' . ($field['text-align']) . ';';
			$output .= "\r\n";	
		}
		
		if( !empty( $field['font-size'] ) ){
			$output .= 'font-size: ' . ($field['font-size']) . ';';
			$output .= "\r\n";
		}
		
		if( !empty( $field['line-height'] ) ){
			$output .= 'line-height: ' . ($field['line-height']) . ';';
			$output .= "\r\n";
		}
		
		if( !empty( $field['letter-spacing'] ) ){
			$output .= 'letter-spacing: ' . ($field['letter-spacing']) . ';';
		}
		
		if( !empty( $field['color'] ) ){
			$output .= 'color: ' . ($field['color']) . ';';
			$output .= "\r\n";
		}
		
		return $output;
	}

}

/**
 * Get term name
 */
if(!function_exists('doccure_get_slug_name')){
    function doccure_get_slug_name($term_id = '', $taxonomy = ''){
        $term_name = '';
        if(!empty($term_id) && !empty($taxonomy)){
            $term = get_term( $term_id, $taxonomy );
            $term_name = $term->name;
        }
        return $term_name;
    }
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
if(!function_exists('doccure_get_slug_name')){
	add_action( 'wp_head', 'doccure_pingback_header' );
	function doccure_pingback_header() {
		if ( is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
		}
	}
}

/**
 * Comments post type filter
 */
if( !class_exists( 'doccure_Show_Comment_Posts_Filter') ){
    class doccure_Show_Comment_Posts_Filter {
        public function __construct() {
            add_action( 'restrict_manage_comments', array( $this, 'doccure_show_filter_dropdown' ) );
        }
		
        public function get_supported_post_types() {
            $post_types = get_post_types( [], 'objects' );
			$filtered_post_types	= array();
            $filtered_post_types['any'] = esc_html__( 'All post types','doccure' );
            foreach ( $post_types as $slug => $type ) {
                if ( ! post_type_supports( $slug, 'comments' ) )
                    continue;
                $filtered_post_types[ $slug ] = $type->labels->name;
            }
            return apply_filters( 'doccure_comments_post_types', $filtered_post_types );
        }
		
        public function doccure_show_filter_dropdown() {
            $current_post_type = isset( $_REQUEST['post_type'] ) ? sanitize_key( $_REQUEST['post_type'] ) : '';
            $post_types = $this->get_supported_post_types();
            echo '<label class="screen-reader-text" for="filter-by-post-type">' . esc_html__( 'Filter by post type','doccure' ) . '</label>';
            echo '<select id="filter-by-post-type" name="post_type">';
                foreach ( $post_types as $type => $label )
                    echo "\t" . '<option value="' . esc_attr( $type ) . '"' . selected( $current_post_type, $type, false ) . ">$label</option>\n";
            echo '</select>';
        }
    }
    new doccure_Show_Comment_Posts_Filter;
}

/**
 * @override parent theme files
 * @return link
 */
if (!function_exists('doccure_override_templates')) {
	function doccure_override_templates($file) {
		if ( file_exists( get_stylesheet_directory() . $file ) ) {
			$template_load = get_stylesheet_directory() . $file;
		} else {
			$template_load = get_template_directory() . $file;
		}
		
		return $template_load;
	}
}

/**
 * @Remove woocommerce files 
 * @return link
 */
if (!function_exists('doccure_disable_files')) {
	function doccure_disable_files() {
		if( function_exists( 'is_woocommerce' ) ){
			if(! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
				wp_dequeue_style('woocommerce-layout'); 
				wp_dequeue_style('woocommerce-general'); 
				wp_dequeue_style('woocommerce-smallscreen'); 	
				wp_dequeue_script('wc-cart-fragments');
				wp_dequeue_script('woocommerce'); 
				wp_dequeue_script('wc-add-to-cart'); 
			}
		}	
    }
    add_action( 'wp_enqueue_scripts', 'doccure_disable_files' );
}

/**
 * @Remove block library files 
 * @return link
 */

if (!function_exists('doccure_reove_extra_css')) {
    function doccure_reove_extra_css() {
        wp_dequeue_style( 'wp-block-library' ); 
        wp_dequeue_style( 'wp-block-library-theme' ); 
        wp_dequeue_style( 'wc-block-style' ); 
        wp_dequeue_style( 'storefront-gutenberg-blocks' );
    }
    add_action( 'wp_print_styles', 'doccure_reove_extra_css', 100 );
}

