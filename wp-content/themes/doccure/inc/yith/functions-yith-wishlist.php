<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if( !doccure_is_yith_wcwl_active() ){
  return;
}

add_action('doccure/product/controls', 'doccure_yith_wishlist_print', 10);
add_action('doccure/product_single/controls', 'doccure_yith_wishlist_print', 20);

add_filter( 'yith_wcwl_loop_positions', 'doccure_force_wishlist_position', 10, 1 );
add_filter( 'yith_wcwl_positions', 'doccure_force_wishlist_position', 10, 1 );

/**
 * Force a single position for the wishlist.
 *
 * See: yith-woocommerce-wishlist/includes/class.yith-wcwl-frontend.php
 *
 * @since 1.0.0
 */
function doccure_force_wishlist_position( $values ) {
  $values = null;
  return $values;
}

/**
 * Return the currently selected wishlist page.
 *
 * @since 1.0.0
 */
function doccure_get_yith_wishlist_page_url(){
  $wishlist_page = get_option('yith_wcwl_wishlist_page_id');
  return !empty($wishlist_page) ? get_the_permalink($wishlist_page) : '#';
}

/**
 * YITH wish list link
 *
 * @since 1.0.0
 */
function doccure_get_yith_wishlist_link(){

  global $product;
  return do_shortcode( '[yith_wcwl_add_to_wishlist product_id='.$product->get_id().']' );
}

/**
 * Print the Wishlist button
 *
 * @since 1.0.0
 */
function doccure_yith_wishlist_print(){

  $wcwl_active = doccure_is_yith_wcwl_active();
  $show_wcwl_in_loop = get_option('yith_wcwl_show_on_loop');
  echo (!empty($show_wcwl_in_loop) && $show_wcwl_in_loop == 'yes' && $wcwl_active ) ? doccure_get_yith_wishlist_link() : '';

}
