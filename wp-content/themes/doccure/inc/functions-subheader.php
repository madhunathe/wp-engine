<?php
/**
 * doccure Subheader
 *
 * @package doccure
 */
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Check if subheader is active or not.
 *
 * @since 1.0.0
 */
function doccure_subheader_is_active()
{
    if (!doccure_isset_options() && (!is_front_page() || !is_home())) {
        return true;
    }
    $current_id = doccure_get_page_id();
    $page_settings = $current_id ? get_post_meta($current_id, 'doccure_page_settings', true) : '';
    $subheader_disable = isset($page_settings['doccure_subheader_disable']) ? (bool)$page_settings['doccure_subheader_disable'] : '';
    $subheader_conditions = !doccure_get_option('display_subheader') || is_front_page() || is_404();
    // Show subheader if subheader display option is ENABLED from page options or DISABLED from theme options, don't show it.
    if (($current_id && $subheader_disable) || $subheader_conditions) {
        return false;
    }
    return true;
}

/**
 * Get the background image of the subheader.
 *
 * @since 1.0.0
 */
function doccure_subheader_get_background_image()
{
    $bg_id = $bg_url = '';
    $current_id = doccure_get_page_id();
    $page_settings = $current_id ? get_post_meta($current_id, 'doccure_page_settings', true) : '';
    if ($current_id && doccure_is_not_empty($page_settings, 'doccure_custom_subheader')) {
        $bg_id = $page_settings['doccure_custom_subheader'];
    } elseif (doccure_get_option('subheader_banner_image')) {
        $bg_id = isset(doccure_get_option('subheader_banner_image')['id']) ? doccure_get_option('subheader_banner_image')['id'] : '';
    }
    $bg_url = !empty($bg_id) ? wp_get_attachment_image_src($bg_id, 'full')[0] : '';
    return apply_filters('doccure/subheader/background_image', $bg_url);
}

/**
 * Get the title of the subheader.
 *
 * @since 1.0.0
 */
function doccure_subheader_get_title()
{
    $title = get_the_title();
    $page_title = '';
    $current_id = doccure_get_page_id();
    $page_settings = $current_id ? get_post_meta($current_id, 'doccure_page_settings', true) : '';
    $page_title = $current_id && doccure_is_not_empty($page_settings, 'doccure_page_custom_title') ? $page_settings['doccure_page_custom_title'] : wp_title(' ', false);
    return apply_filters('doccure/subheader/title', $title);
}

/**
 * Get the caption of the subheader.
 *
 * @since 1.0.0
 */
// function doccure_subheader_get_caption()
// {
//     $page_caption = '';
//     $current_id = doccure_get_page_id();
//     $page_settings = $current_id ? get_post_meta($current_id, 'doccure_page_settings', true) : '';
//     if ($current_id && doccure_is_not_empty($page_settings, 'doccure_page_custom_subtitle')) {
//         $page_caption = $page_settings['doccure_page_custom_subtitle'];
//     } elseif (doccure_get_option('subheader_caption')) {
//         $page_caption = doccure_get_option('subheader_caption');
//     }
//     return apply_filters('doccure/subheader/caption', $page_caption);
// }

/**
 * Get all subheader related classes from theme options.
 *
 * @since 1.0.0
 */
function doccure_subheader_classes($classes = [])
{
    $classes[] = doccure_get_option('breadcrumb_position', 'after-title');
    $classes[] = doccure_get_option('subheader_alignment', 'text-left');
    $classes[] = doccure_get_option('subheader_style', 'style-1');
    $classes[] = (doccure_get_option('display_subheader_overlay') == '1') ? 'dark-overlay' : 'dark-overlay';
    $classes[] = (doccure_get_option('display_subheader_shapes') == '1') ? doccure_get_option('shape_style') : '';
    if (empty($classes)) return;
    return apply_filters('doccure/subheader/subheader_classes', str_replace(',', '', implode(', ', $classes)));
}

/**
 * Get the breadcrumbs of the subheader.
 *
 * @since 1.0.0
 */
function doccure_subheader_get_breadcrumbs($delimiter = '')
{
    // Set variables for later use
    $home_link = esc_url(home_url('/'));
    $home_text = esc_html__('Home', 'doccure');
    $link_before = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item"><span itemprop="name">';
    $link_after = '</span></li>';
    $link_attr = '';
    $delimiter = !empty($delimiter) ? '<li>' . $delimiter . '</li>' : '';
    $link = $link_before . '<a' . $link_attr . ' href="%1$s" itemprop="item"><span itemprop="name">%2$s</span></a>' . $link_after;
    $before = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item active" aria-current="page"><span itemprop="name">'; // Tag before the current crumb
    $after = '</span></li>';                // Tag after the current crumb
    $page_addon = '';                       // Adds the page number if the query is paged
    $breadcrumb_trail = '';
    $category_links = '';
    /**
     * Set our own $wp_the_query variable. Do not use the global variable version due to
     * reliability
     */
    $wp_the_query = $GLOBALS['wp_the_query'];
    $queried_object = $wp_the_query->get_queried_object();
    // Handle single post requests which includes single pages, posts and attatchments
    if (is_singular()) {
        /**
         * Set our own $post variable. Do not use the global variable version due to
         * reliability. We will set $post_object variable to $GLOBALS['wp_the_query']
         */
        $post_object = sanitize_post($queried_object);
        // Set variables
        $title = apply_filters('the_title', $post_object->post_title, $post_object->ID);
        $parent = $post_object->post_parent;
        $post_type = $post_object->post_type;
        $post_id = $post_object->ID;
        $post_link = $before . $title . $after;
        $parent_string = '';
        $post_type_link = '';
        if ('post' === $post_type) {
            // Get the post categories
            $categories = get_the_category($post_id);
            if ($categories) {
                // Lets grab the first category
                $category = $categories[0];
                $category_links = get_category_parents($category, true, $delimiter);
                $category_links = str_replace('<a', $link_before . '<a' . $link_attr, $category_links);
                $category_links = str_replace('</a>', '</a>' . $link_after, $category_links);
            }
        }
        if (!in_array($post_type, ['post', 'page', 'attachment'])) {
            $post_type_object = get_post_type_object($post_type);
            $archive_link = esc_url(get_post_type_archive_link($post_type));
            $post_type_link = sprintf($link, $archive_link, $post_type_object->labels->singular_name);
        }
        // Get post parents if $parent !== 0
        if (0 !== $parent) {
            $parent_links = [];
            while ($parent) {
                $post_parent = get_post($parent);
                $parent_links[] = sprintf($link, esc_url(get_permalink($post_parent->ID)), get_the_title($post_parent->ID));
                $parent = $post_parent->post_parent;
            }
            $parent_links = array_reverse($parent_links);
            $parent_string = implode($delimiter, $parent_links);
        }
        // Lets build the breadcrumb trail
        if ($parent_string) {
            $breadcrumb_trail = $parent_string . $delimiter . $post_link;
        } else {
            $breadcrumb_trail = $post_link;
        }
        if ($post_type_link)
            $breadcrumb_trail = $post_type_link . $delimiter . $breadcrumb_trail;
        if ($category_links)
            $breadcrumb_trail = $category_links . $breadcrumb_trail;
    }
    // Handle archives which includes category-, tag-, taxonomy-, date-, custom post type archives and author archives
    if (is_archive()) {
        if (is_category() || is_tag() || is_tax()) {
            // Set the variables for this section
            $term_object = get_term($queried_object);
            $taxonomy = $term_object->taxonomy;
            $term_id = $term_object->term_id;
            $term_name = $term_object->name;
            $term_parent = $term_object->parent;
            $taxonomy_object = get_taxonomy($taxonomy);
            $current_term_link = $before . $taxonomy_object->labels->singular_name . ': ' . $term_name . $after;
            $parent_term_string = '';
            if (0 !== $term_parent) {
                // Get all the current term ancestors
                $parent_term_links = [];
                while ($term_parent) {
                    $term = get_term($term_parent, $taxonomy);
                    $parent_term_links[] = sprintf($link, esc_url(get_term_link($term)), $term->name);
                    $term_parent = $term->parent;
                }
                $parent_term_links = array_reverse($parent_term_links);
                $parent_term_string = implode($delimiter, $parent_term_links);
            }
            if ($parent_term_string) {
                $breadcrumb_trail = $parent_term_string . $delimiter . $current_term_link;
            } else {
                $breadcrumb_trail = $current_term_link;
            }
        } elseif (is_author()) {
            $breadcrumb_trail = $before . esc_html__('Author archive for', 'doccure') . $before . $queried_object->data->display_name . $after;
        } elseif (is_date()) {
            // Set default variables
            $year = $wp_the_query->query_vars['year'];
            $monthnum = $wp_the_query->query_vars['monthnum'];
            $day = $wp_the_query->query_vars['day'];
            // Get the month name if $monthnum has a value
            if ($monthnum) {
                $date_time = DateTime::createFromFormat('!m', $monthnum);
                $month_name = $date_time->format('F');
            }
            if (is_year()) {
                $breadcrumb_trail = $before . $year . $after;
            } elseif (is_month()) {
                $year_link = sprintf($link, esc_url(get_year_link($year)), $year);
                $breadcrumb_trail = $year_link . $delimiter . $before . $month_name . $after;
            } elseif (is_day()) {
                $year_link = sprintf($link, esc_url(get_year_link($year)), $year);
                $month_link = sprintf($link, esc_url(get_month_link($year, $monthnum)), $month_name);
                $breadcrumb_trail = $year_link . $delimiter . $month_link . $delimiter . $before . $day . $after;
            }
        } elseif (is_post_type_archive()) {
            $post_type = $wp_the_query->query_vars['post_type'];
            $post_type_object = get_post_type_object($post_type);
            $breadcrumb_trail = $before . $post_type_object->labels->singular_name . $after;
        }
    }
    // Handle the search page
    if (is_search()) {
        $breadcrumb_trail = $before . esc_html__(' Search query for: ', 'doccure') . get_search_query() . $after;
    }
    // Handle 404's
    if (is_404()) {
        $breadcrumb_trail = $before . esc_html__(' Error 404', 'doccure') . $after;
    }
    // Handle paged pages
    if (is_paged()) {
        $current_page = get_query_var('paged') ? get_query_var('paged') : get_query_var('page');
        $page_addon = '<li class="breadcrumb-item-page">' . sprintf(esc_html__(' ( Page %s )', 'doccure'), number_format_i18n($current_page)) . '</li>';
    }
    $breadcrumb_output_link = '';
    $breadcrumb_output_link .= '<nav aria-label="breadcrumb"><ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';
    if (is_home() || is_front_page()) {
        // Do not show breadcrumbs on page one of home and frontpage
        if (is_paged()) {
            $breadcrumb_output_link .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"  class="breadcrumb-item"><a itemprop="item" href="' . esc_url($home_link) . '"><span itemprop="name">' . $home_text . '</span></a></li>';
            $breadcrumb_output_link .= $page_addon;
        }
    } else {
        $breadcrumb_output_link .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"  class="breadcrumb-item"><a itemprop="item" href="' . esc_url($home_link) . '"><span itemprop="name">' . $home_text . '</span></a></li>';
        $breadcrumb_output_link .= $delimiter;
        $breadcrumb_output_link .= $breadcrumb_trail;
        $breadcrumb_output_link .= $page_addon;
    }
    $breadcrumb_output_link .= '</ol></nav><!-- .breadcrumbs -->';
    return apply_filters('doccure/subheader/breadcrumbs', $breadcrumb_output_link);
}
