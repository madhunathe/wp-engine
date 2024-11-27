<?php
/**
 * The template for displaying product search form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/product-searchform.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */
?>

<form method="get" class="search-form woocommerce-product-search" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
  <label>
    <input type="text" class="search-field" id="<?php echo esc_attr(uniqid( 'search-form-' )) ?>" placeholder="<?php echo esc_attr__('Search Products...', 'doccure') ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s" required>
    <input type="hidden" name="post_type" value="product" />
  </label>
  <button class="doccure_btn-custom search-submit" type="submit"><i class="fas fa-search"></i></button>
</form>
