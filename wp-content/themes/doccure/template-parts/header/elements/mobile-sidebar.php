<?php
/**
 * Template part for header mobile sidebar.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package doccure
 */
?>
<aside class="doccure_aside">
    <div class="doccure_close aside-trigger">
        <i class="fas fa-times"></i>
    </div>
    <?php if (doccure_get_option('mobile-logo')) { ?>
        <div class="doccure_logo-wrapper">
            <?php 
               
                if(is_page_template('homepagethree.php') || is_page_template('homepagefour.php')) { 
            ?>
             <a href="<?php echo home_url(); ?>"><img src="<?php echo get_template_directory_uri();?>/assets/images/logo-one.png"/></a>
            <?php } else{
                // mobile Logo
             doccure_get_site_logo('mobile-logo');
            }
            
            
            ?>
            
        </div>
    <?php }
    // mobile menu
    doccure_nav_menu('mobile-menu');
    wp_nav_menu( array( 
        'theme_location' => 'my-custom-menu', 
        'container_class' => 'custom-menu-class' ) ); 
    
    if ( is_active_sidebar( 'custom-language-widget' ) ) : ?>
          <div id="lang-widget-area" class="lang-chw-widget-area widget-area" role="complementary">
          <?php dynamic_sidebar( 'custom-language-widget' ); ?>
          </div>
           <?php endif; 
           ?>
</aside>
<div class="doccure_aside-overlay aside-trigger"></div>
