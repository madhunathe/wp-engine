<?php
/**
 * Template part for header controls
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package doccure
 */
$display_search = doccure_get_option('display-search-icon');
$display_user_icon = doccure_get_option('display-user-icon');
$display_cart = (doccure_get_option('display-cart')) ? doccure_get_option('display-cart') : '';
$header_controls_style = doccure_get_option('header_controls_style', 'style-1');
if($display_search || $display_cart || doccure_get_option('display-collapse-sidebar')) {
?>
<div class="doccure_header-controls <?php echo esc_attr($header_controls_style); ?>">
    <ul class="doccure_header-controls-inner">
        <?php if ($display_search) {
          if(doccure_get_option('header-layout') == 'layout-8' || doccure_get_option('header-layout') == 'layout-9') { ?>
            <li class="header-controls-item d-none d-sm-block">
              <?php get_search_form(); ?>
            </li>
          <?php } else { ?>
            <li class="search-trigger header-controls-item d-none d-sm-block">
                <a class="doccure_header-control-search" title="Search" href="#">
                    <i class="far fa-search"></i>
                </a>
            </li>
        <?php } }
        if($display_user_icon){
          if(is_user_logged_in()){
        ?>
          <li class="user-trigger header-controls-item d-none d-sm-block">
              <a href="<?php echo esc_url(home_url('/my-account')); ?>" class="my-account-page">
                  <i class="fas fa-user"></i>
              </a>
          </li>
        <?php } else{ ?>
          <li class="user-trigger header-controls-item d-none d-sm-block">
              <a href="#" data-toggle="modal" data-target="#header-login-register-form">
                  <i class="far fa-user"></i>
              </a>
          </li>
        <?php } }
        if ($display_cart && function_exists('wc_get_cart_url')) {
            if ($header_controls_style == 'style-1') {
                ?>
                <li class="cart-trigger header-controls-item d-none d-sm-block">
                    <a class="doccure_header-control-cart" title="Your Cart"
                       href="<?php echo esc_url(wc_get_cart_url()); ?>">
                        <i class="far fa-shopping-basket"></i>
                        <div class="doccure_header-control-cart-inner">
                            <span><?php echo sprintf(_n('%d item', '%d items', WC()->cart->get_cart_contents_count(), 'doccure'), WC()->cart->get_cart_contents_count()); ?></span>
                            <p> <?php echo WC()->cart->get_cart_total(); ?> </p>
                        </div>
                    </a>
                </li>
            <?php } else { ?>
                <li class="cart-trigger header-controls-item d-none d-sm-block">
                    <a class="doccure_header-control-cart" title="<?php echo esc_attr__('Your Cart', 'doccure') ?>"
                       href="<?php echo esc_url(wc_get_cart_url()); ?>">
                        <i class="far fa-shopping-basket"></i>
                        <span><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                    </a>
                </li>
            <?php }
        }
        if ((doccure_get_option('header-layout') == 'layout-1' || doccure_get_option('header-layout') == 'layout-5' || doccure_get_option('header-layout') == 'layout-6' || doccure_get_option('header-layout') == 'layout-8' || doccure_get_option('header-layout') == 'layout-9' || doccure_get_option('header-layout') == 'layout-10')  && doccure_get_option('display-collapse-sidebar')) {
            if (is_active_sidebar('header-collapse-sidebar')) {
                ?>
                <li class="aside-toggle aside-trigger-right desktop-toggler">
                    <span></span>
                    <span></span>
                    <span></span>
                </li>
            <?php }
        } ?>
        <li class="aside-toggle aside-trigger">
            <span></span>
            <span></span>
            <span></span>
        </li>
    </ul>
</div>
<?php } else {  ?>
  <div class="doccure_header-controls d-flex d-lg-none <?php echo esc_attr($header_controls_style); ?>">
      <ul class="doccure_header-controls-inner">
        <li class="aside-toggle aside-trigger">
            <span></span>
            <span></span>
            <span></span>
        </li>
      </ul>
  </div>
<?php }
