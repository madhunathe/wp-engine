<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!doccure_is_woo_active()) {
    return;
}
//======= Actions & Filters =========//
add_filter('woocommerce_add_to_cart_fragments', 'doccure_refresh_cart_fragment');

// Change Filter
remove_action('woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10);
add_action('woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 5);
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);

add_action('woocommerce_before_shop_loop', 'doccure_shop_controls_start', 15);
add_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
add_action('woocommerce_before_shop_loop', 'doccure_shop_controls_end', 40);

// Remove breadcrumbs
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

// Change thumbnail
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_before_shop_loop_item_title', 'doccure_product_thumbnail_start', 10);
add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 15);
add_action('woocommerce_before_shop_loop_item_title', 'doccure_product_thumbnail', 20);
add_action('woocommerce_before_shop_loop_item_title', 'doccure_product_thumbnail_end', 30);

// Change Title
add_action('woocommerce_before_shop_loop_item_title', 'doccure_product_content_wrapper_start', 30);
add_action('woocommerce_after_shop_loop_item_title', 'doccure_product_content_wrapper_end', 35);

remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
add_action('woocommerce_shop_loop_item_title', 'doccure_product_countdown', 5);
add_action('woocommerce_shop_loop_item_title', 'doccure_product_title', 15);

// Product Excerpt
add_action('woocommerce_after_shop_loop_item_title', 'doccure_product_excerpt', 25);

// Remove link tags
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);

// Change Price,  Rating and Sale flash
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);

remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);

add_action('woocommerce_after_shop_loop_item_title', 'doccure_product_meta_wrapper_start', 5);
add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
add_action('woocommerce_after_shop_loop_item_title', 'doccure_product_meta_wrapper_end', 20);

add_action('doccure/product/controls', 'woocommerce_template_loop_add_to_cart', 15);

// Remove the default add to cart button
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

// Badges
add_action('woocommerce_before_shop_loop_item_title', 'doccure_product_badges', 15);
add_filter('woocommerce_sale_flash', 'doccure_get_sale_flash_discount', 90, 3);

add_action('woocommerce_after_shop_loop', 'doccure_products_infinite_scroll', 20);

// Product Single
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

add_action('woocommerce_single_product_summary', 'doccure_product_countdown', 20);

add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 15);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 20);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 25);
add_action('woocommerce_single_product_summary', 'doccure_product_stock_status', 30);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 35);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 36);

add_action('woocommerce_before_main_content', 'doccure_category_strip', 5);

add_action('woocommerce_before_lost_password_form', 'doccure_before_lost_password_form', 10);

add_action('wp_enqueue_scripts', 'doccure_woocommerce_scripts');

add_action('woocommerce_checkout_before_order_review_heading', 'doccure_checkout_order_review_start', 10);
add_action('woocommerce_checkout_after_order_review', 'doccure_checkout_order_review_end', 10);

/**
 * Open tag for WooCommerce checkout order review.
 *
 * @since 1.0.0
 */
function doccure_checkout_order_review_start()
{
    echo '<div class="order_review">';
}

/**
 * Close tag for WooCommerce checkout order review.
 *
 * @since 1.0.0
 */
function doccure_checkout_order_review_end()
{
    echo '</div>';
}

/**
 * Adds woocommerce options to redux sections.
 *
 * @since 1.0.0
 */
function doccure_woocommerce_redux_options($options_files)
{

    $options_files[] = get_template_directory() . '/inc/redux-options/options/woocommerce-settings.php';
    $options_files[] = get_template_directory() . '/inc/redux-options/options/product-settings.php';
    $options_files[] = get_template_directory() . '/inc/redux-options/options/product-details-settings.php';
    $options_files[] = get_template_directory() . '/inc/redux-options/options/my-account-settings.php';

    return $options_files;
}

add_filter('doccure_redux_option_files', 'doccure_woocommerce_redux_options', 10, 1);

/**
 * Enqueue woocommerce scripts and styles.
 *
 * @since 1.0.0
 */
function doccure_woocommerce_scripts()
{

    wp_enqueue_style('doccure-woocommerce', get_template_directory_uri() . '/assets/css/theme-woocommerce.css', array(), '1.0.0');
    wp_enqueue_script('doccure-woocommerce', get_template_directory_uri() . '/assets/js/theme-woocommerce.js', array('jquery'), '1.0.0', true);

}

/**
 * Add the widget areas for woocommerce
 *
 * @since 1.0.0
 */
if (!function_exists('doccure_woo_widgets_init')) {
    function doccure_woo_widgets_init()
    {
        register_sidebar(array(
            'name' => esc_html__('Shop Sidebar', 'doccure'),
            'id' => 'shop',
            'description' => esc_html__('Add widgets here to appear in your Shop.', 'doccure'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h6 class="widget-title">',
            'after_title' => '</h6>',
        ));
        register_sidebar(array(
            'name' => esc_html__('Product Details Sidebar', 'doccure'),
            'id' => 'product',
            'description' => esc_html__('Add widgets here to appear in your product details page.', 'doccure'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h6 class="widget-title">',
            'after_title' => '</h6>',
        ));
    }

    add_action('widgets_init', 'doccure_woo_widgets_init');
}

/**
 * Add necessary classes to the product loop.
 *
 * @since 1.0.0
 */
function doccure_product_classes($classes, $product)
{

    $classes[] = 'doccure_product';
    $classes[] = is_woocommerce() || is_singular('product') || is_cart() || is_checkout() ? doccure_get_option('product_style') : '';

    return $classes;
}

add_filter('woocommerce_post_class', 'doccure_product_classes', 10, 2);

/**
 * Adjust the returned sidebar value when in the shop page and product details page.
 *
 * @since 1.0.0
 */
function doccure_shop_sidebar($current_sidebar)
{

    if (is_shop() || is_product_category() || is_product_tag()) {
        $current_sidebar = 'shop';
    } elseif (is_singular('product')) {
        $current_sidebar = 'product';
    }

    return $current_sidebar;

}

add_filter('doccure/sidebar/current_sidebar', 'doccure_shop_sidebar');

/**
 * Adjust lost password form title
 *
 * @since 1.0.0
 */
function doccure_before_lost_password_form()
{
    echo '<h4>' . esc_html__("Lost password", 'doccure') . '</h4>';
}

/**
 * Change the main wrapper of the product pages.
 *
 * @since 1.0.0
 */
function woocommerce_output_content_wrapper()
{
    echo '<main id="main" class="site-main">';
}

/**
 * Change the main wrapper of the product pages.
 *
 * @since 1.0.0
 */
function woocommerce_output_content_wrapper_end()
{
    echo '</main>';
}

/**
 * Opening tags for shop filter.
 *
 * @since 1.0.0
 */
function doccure_shop_controls_start()
{
    echo '<div class="doccure_shop-global">';
}

/**
 * Closing tags for shop filter.
 *
 * @since 1.0.0
 */
function doccure_shop_controls_end()
{
    echo '</div>';
}

/**
 * Openning tag for the product thumbnail.
 *
 * @since 1.0.0
 */
function doccure_product_thumbnail_start()
{
  $doccure_product_style = !empty(doccure_get_option('product_style')) ? doccure_get_option('product_style') : 'style-1';
    echo '<div class="doccure_product-thumb">';

    echo '<div class="doccure_product-controls">';
    if($doccure_product_style == 'style-1' || $doccure_product_style == 'style-2' || $doccure_product_style == 'style-3') {
      do_action('doccure/product/controls');
    }
    echo '</div>';
}

/**
 * Closing tag for the product thumbnail.
 *
 * @since 1.0.0
 */
function doccure_product_thumbnail_end()
{
    echo '</div>';
}

/**
 * Product thumbnail.
 *
 * @since 1.0.0
 */
function doccure_product_thumbnail()
{

    global $product;
    $id = $product->get_id();
    if (has_post_thumbnail($id)) {
        echo '<a href="' . esc_url(get_the_permalink($id)) . '" class="doccure-filter-img-wrapper">' . get_the_post_thumbnail($id, doccure_get_thumb_size('doccure-product')) . '</a>';
    }

}

/**
 * Openning tag for the product content.
 *
 * @since 1.0.0
 */
function doccure_product_content_wrapper_start()
{
    echo '<div class="doccure_product-body">';
}

/**
 * Closing tag for the product content.
 *
 * @since 1.0.0
 */
function doccure_product_content_wrapper_end()
{
    echo '</div>';
}

/**
 * Openning tag for the product content.
 *
 * @since 1.0.0
 */
function doccure_product_meta_wrapper_start()
{
    echo '<div class="doccure_product-body-meta">';
}

/**
 * Closing tag for the product content.
 *
 * @since 1.0.0
 */
function doccure_product_meta_wrapper_end()
{
    echo '</div>';
}

/**
 * Product Title.
 *
 * @since 1.0.0
 */
function doccure_product_title()
{
    global $product;
    $id = $product->get_id();

    echo '<h5 class="doccure_product-title"> <a href="' . esc_url(get_the_permalink($id)) . '">' . get_the_title($id) . '</a> </h5>';
}

/**
 * Product Countdown.
 *
 * @since 1.0.0
 */
function doccure_product_countdown()
{

    global $product;

    $sale_from = $product->get_date_on_sale_from();
    $sale_to = $product->get_date_on_sale_to();
    $is_on_sale = $product->is_on_sale();

    $show_countdown = is_product() ? doccure_get_option('product_single_show_countdown') : doccure_get_option('product_show_countdown');

    if ($sale_from && $sale_to && $is_on_sale && $show_countdown) {
        $sale_to = new DateTime($sale_to);
        echo '<div class="doccure_countdown-timer" data-countdown="' . esc_js($sale_to->format('Y-m-d')) . '"></div>';
    }

}

/**
 * Add short excerpt after price.
 *
 * @since 1.0.0
 */
function doccure_product_excerpt()
{
    global $product;

    if (doccure_get_option('product_show_excerpt')) {
        $excerpt_length = doccure_get_option('product_excerpt_length', 10);
        echo '<p>' . esc_html(doccure_custom_excerpt($excerpt_length)) . '</p>';
    }

}

/**
 * Return the stock status of a product.
 *
 * @since 1.0.0
 */
function doccure_product_stock_status()
{
    global $product;
    ?>
    <div class="doccure_product-stock-status">
        <span><?php echo esc_html__('Availability: ', 'doccure') ?></span>
        <div class="<?php echo esc_attr($product->get_stock_status()) ?>">
            <?php echo esc_html($product->is_in_stock()) ? esc_html__(' In stock', 'doccure') : esc_html__('Out of stock', 'doccure'); ?>
        </div>
    </div>
    <?php
}

/**
 * Add Custom badges to the detail product and archive.
 *
 * @since 1.0.0
 */
function doccure_product_badges()
{
    global $product;

    $featured = '';
    if ($product->is_featured()) {
        $featured = 1;
    }

    $sold_out = '';
    if (!$product->is_in_stock()) {
        $sold_out = 1;
    }
    ?>

    <?php if (!empty($sold_out)) { ?>
      <div class="doccure_product-badge doccure_badge-soldout"><?php echo esc_html__('Sold', 'doccure'); ?> </div>
    <?php } ?>
    <?php if (!empty($featured)) { ?>
      <div class="doccure_product-badge doccure_badge-featured"><i class="fas fa-star"></i></div>
    <?php } ?>

    <?php

}

/**
 * Display the discounted amount in %.
 *
 * @return string
 * @since 1.0.0
 *
 */
function doccure_get_sale_flash_discount($text, $post, $product)
{

    $discount = 0;
    if ($product->is_on_sale()) {
        if ($product->get_type() == 'variable') {
            $available_variations = $product->get_available_variations();
            for ($i = 0; $i < count($available_variations); ++$i) {
                $variation_id = $available_variations[$i]['variation_id'];
                $variable_product1 = new WC_Product_Variation($variation_id);
                $regular_price = $variable_product1->get_regular_price();
                if ($regular_price > 0) {
                    $sales_price = $variable_product1->get_sale_price();
                    if ($sales_price) {
                        $percentage = round(((($regular_price - $sales_price) / $regular_price) * 100), 1);
                    }
                    if (!empty($percentage) && $percentage > $discount) {
                        $discount = $percentage;
                    }
                }
            }
        } elseif ($product->get_type() == 'simple') {
            $discount = round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100);
        }
        if ($discount) {
            $discount = "{$discount}%";
        } else {
            $discount = '';
        }
        $text = sprintf(esc_html__('%s Off', 'doccure'), $discount);
    }

    if (!empty($discount)) {
        return '<div class="doccure_product-badge doccure_badge-sale">' . esc_html($text) . '</div>';
    }

}

/**
 * Retruns a list of all product categories.
 *
 * @since 1.0.0
 */
if (!function_exists('doccure_get_product_categories')) {
    function doccure_get_product_categories()
    {

        $args = array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        );

        $terms = '';
        $terms_list = get_terms($args);

        if ($terms_list) {
            foreach ($terms_list as $term) {

                $term_link = get_term_link($term, 'product_cat');

                $terms .= '<a href="' . esc_url($term_link) . '">' . esc_html($term->name) . '</a>';
            }
        }

        return $terms;
    }
}

/**
 * Refresh cart contents / total fragment using Ajax
 *
 * @since 1.0.0
 */
if (!function_exists('doccure_refresh_cart_fragment')) {
    function doccure_refresh_cart_fragment($fragments)
    {
        global $woocommerce;

        ob_start();

        ?>

        <a class="doccure_header-cart-inner" href="<?php echo esc_url(wc_get_cart_url()) ?>"
           title="<?php echo esc_attr__('Your Cart', 'doccure') ?>">
            <i class="fal fa-shopping-bag"></i>
            <div class="doccure_header-cart-content">
        <span class="doccure_header-cart-count">
          <?php echo WC()->cart->get_cart_contents_count(); ?>
        </span>
            </div>
        </a>

        <?php
        $fragments['a.doccure_header-cart-inner'] = ob_get_clean();
        return $fragments;
    }
}

/**
 * Change number of upsells output
 *
 * @since 1.0.0
 */
add_filter('woocommerce_upsell_display_args', 'doccure_change_upsells_count', 20);
function doccure_change_upsells_count($args)
{

    $args['posts_per_page'] = 3;
    $args['columns'] = 3; //change number of upsells here
    return $args;
}

/**
 * Change number of cross sells output
 *
 * @since 1.0.0
 */
add_filter('woocommerce_cross_sells_columns', 'doccure_change_crosssells_count');
function doccure_change_crosssells_count($columns)
{
    return 3;
}

/**
 * Change default sorting name in orderby
 *
 * @since 2.0.0
 */
add_filter('woocommerce_catalog_orderby', 'doccure_rename_sorting_option_woocommerce_shop');

function doccure_rename_sorting_option_woocommerce_shop($options)
{
    $options['menu_order'] = 'Filter';
    return $options;
}


/**
 * Return the markup for the category slip.
 *
 * @since 2.0.0
 */
function doccure_category_strip()
{

    if (doccure_get_option('show_category_strip')) {

        $args = array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
        );

        // List of excluded terms
        $excluded_terms = doccure_get_option('category_strip_exclude');

        if ($excluded_terms) {
            $excluded_terms = explode(',', $excluded_terms);

            if (is_array($excluded_terms)) {

                $args['exclude'] = array();

                foreach ($excluded_terms as $term) {
                    $term = get_term_by('slug', $term, 'product_cat');
                    if ($term) {
                        array_push($args['exclude'], $term->term_id);
                    }
                }

            }
        }

        $terms = '';
        $terms_list = get_terms($args);

        if ($terms_list && is_shop()) {
            echo '<div class="doccure_category-strip"><div class="container"><div class="doccure_category-strip-scroll"><div class="doccure_category-strip-inner">';

            $all_thumb = doccure_get_option('all_cats_thumb');
            $shop_url = get_permalink(wc_get_page_id('shop'));

            $class = is_shop() ? 'active' : '';
            ?>
            <a href="<?php echo esc_url($shop_url) ?>" class="doccure_category-item <?php echo esc_attr($class) ?>">
                <?php if ($all_thumb['url']) { ?>
                    <div class="doccure_category-thumb">
                        <img src="<?php echo esc_url($all_thumb['url']) ?>"
                             alt="<?php echo esc_attr__('All Categories', 'doccure') ?>"/>
                    </div>
                <?php } ?>
                <div class="doccure_category-desc">
                    <h6><?php echo esc_html__('All', 'doccure') ?></h6>
                </div>
            </a>
            <?php
            foreach ($terms_list as $term) {

                $term_link = get_term_link($term, 'product_cat');

                if ($term_link) {

                    $class = is_product_category($term->slug) ? 'active' : '';

                    $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
                    ?>
                    <a title="<?php echo esc_attr($term->name) ?>" href="<?php echo esc_url($term_link) ?>"
                       class="doccure_category-item <?php echo esc_attr($class) ?>">
                        <?php if ($thumbnail_id) { ?>
                            <div class="doccure_category-thumb">
                                <?php echo wp_get_attachment_image($thumbnail_id) ?>
                            </div>
                        <?php } ?>
                        <div class="doccure_category-desc">
                            <h6><?php echo esc_html($term->name) ?></h6>
                        </div>
                    </a>
                    <?php
                }

            }
            echo '</div></div></div></div>';
        }

    }

}

/**
 * Change woocommerce product thumbnails columns
 *
 * @since 2.0.0
 */

add_filter('woocommerce_single_product_image_gallery_classes', 'doccure_columns_product_gallery');

function doccure_columns_product_gallery($wrapper_classes)
{
    $columns = 4;
    $wrapper_classes[2] = 'woocommerce-product-gallery--columns-' . absint($columns);
    return $wrapper_classes;
}

/**
 * Product details controls after add to cart button
 *
 * @since 2.0.0
 */
add_action('woocommerce_after_add_to_cart_form', 'doccure_product_details_after_cart_button');

function doccure_product_details_after_cart_button()
{
  global $product;
    echo '<div class="product-details-after-cart-btn">';
      do_action('doccure/product_single/controls');
    echo '</div>';
}

/**
* Get Product gallery thumbnails
*
* @since 2.0.0
*/

function doccure_product_gallery_thumbnails() {
  global $product;
  $id = $product->get_id();
  $attachment_ids = $product->get_gallery_image_ids();

  if(!empty($attachment_ids) && $attachment_ids){
  ?>
    <div class="doccure_product-gallery">
      <?php
      $i = 0;
      foreach( $attachment_ids as $attachment_id ) {
        if($i < 3) {
          $gallery_img_url = wp_get_attachment_url( $attachment_id );
          DoccureBase_Lazy_Load::show_resized_image($gallery_img_url, 30, 30, true, '', 'prod-variation');
        }
        $i++;
       } ?>
    </div>
  <?php }
}

/**
* Get product add to cart button in product archive styles accordingly
*
* @since 2.0.0
*/

function doccure_product_atc_button(){

  $doccure_product_style = !empty(doccure_get_option('product_style')) ? doccure_get_option('product_style') : 'style-1';

  if($doccure_product_style == 'style-2' || $doccure_product_style == 'style-3' || $doccure_product_style == 'style-4' || $doccure_product_style == 'style-7') {
    woocommerce_template_loop_add_to_cart();
  }

  if($doccure_product_style == 'style-5') { ?>
    <div class="doccure_product-footer">
      <?php
        doccure_yith_wishlist_print();

        woocommerce_template_loop_add_to_cart();

        do_action('doccurecore/compare_button');
      ?>
    </div>
  <?php }
  if($doccure_product_style == 'style-6') {

    woocommerce_template_loop_add_to_cart();

    doccure_yith_quickview_print();

  }

}
add_action('woocommerce_after_shop_loop_item_title', 'doccure_product_atc_button', 30);

/**
* Get product meta in product archive styles accordingly
*
* @since 2.0.0
*/

function doccure_woo_product_meta_styles(){

  $doccure_product_style = !empty(doccure_get_option('product_style')) ? doccure_get_option('product_style') : 'style-1';

  // ratings
  if($doccure_product_style == 'style-1' || $doccure_product_style == 'style-2') {
    add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_rating', 25);
  } elseif($doccure_product_style == 'style-6'){
    add_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_rating', 20 );
  } elseif($doccure_product_style == 'style-7'){
    add_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_rating', 10 );
  } else{
    add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 22 );
  }

  // quickview
  if($doccure_product_style == 'style-5') {
    add_action('woocommerce_before_shop_loop_item_title', 'doccure_yith_quickview_print', 18);
  }

  // gallery thumbnails
  if($doccure_product_style == 'style-3' && doccure_get_option('show_product_gallery_on_hover') == 'true') {
    add_action('woocommerce_before_shop_loop_item_title', 'doccure_product_gallery_thumbnails', 15 );
  }

  // woo product share
  if(function_exists('doccurecore_posts_share')) {
    add_action('woocommerce_share', 'doccurecore_posts_share', 50);
  }
}

add_action('init', 'doccure_woo_product_meta_styles', 99);

/**
 * Add the Infinite Scroll markup
 *
 * @since 2.0.0
 */
function doccure_products_infinite_scroll(){

  $next_link = get_next_posts_link();
  if ( $next_link && doccure_get_option('shop_infinite_scroll') ) {
  ?>
  <div class="scroller-status">
    <div class="infinite-scroll-request">
      <i class="fa fa-spinner fa-spin"></i>
    </div>
  </div>

  <?php
  }

}


/**
 * Show snackbar on added to cart.
 *
 * @since 2.1.1
 */
function doccure_product_snackbar(){
  if(doccure_get_option('add_to_cart_snackbar')){
    echo '<div class="doccure-snackbar">'.esc_html__('Product added to cart', 'doccure').'</div>';
  }
}

add_action('doccure_after_footer', 'doccure_product_snackbar', 15);


// Functions responsible for YITH Quickview
require get_template_directory() . '/inc/yith/functions-yith-quickview.php';

// Functions responsible for YITH Wishlist
require get_template_directory() . '/inc/yith/functions-yith-wishlist.php';

// Functions responsible for YITH Compare
require get_template_directory() . '/inc/yith/functions-yith-compare.php';

?>
