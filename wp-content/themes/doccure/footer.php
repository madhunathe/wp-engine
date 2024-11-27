<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package doccure
 */
$footer_layout = doccure_get_option('footer-layout', 'layout-8');
$footer_type = doccure_get_option('footer_type', 'static');
?>
</div><!-- #content -->

<?php echo doccure_get_the_page_template('page_template_after_footer', 'enable_page_template_after_footer'); ?>


 
 
    <footer class="homepage_footer">
<div class="footer-top">
    <div class="container">
 
        <div class="row">
            <div class="col-lg-3 col-md-4">
                <div class="homepage_firstsec">
                    <?php 
                    if ( is_active_sidebar( 'footer-column-1' ) ) : ?>
                    <?php dynamic_sidebar( 'footer-column-1' ); ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-6">
            <div class="row">

            <div class="col-lg-3 col-md-4">
                <?php 
                if ( is_active_sidebar( 'footer-column-2' ) ) : ?>
                <div id="header-widget-area" class="chw-widget-area widget-area specality_menu" role="complementary">
                <?php dynamic_sidebar( 'footer-column-2' ); ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-3 col-md-4">
            <?php 
                if ( is_active_sidebar( 'footer-column-3' ) ) : ?>
                <div id="header-widget-area" class="chw-widget-area widget-area" role="complementary">
                <?php dynamic_sidebar( 'footer-column-3' ); ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-6 col-md-4">
            <?php 
                if ( is_active_sidebar( 'footer-column-4' ) ) : ?>
                <div id="social-widget-area" class="chw-widget-area widget-area" role="complementary">
                <?php dynamic_sidebar( 'footer-column-4' ); ?>
                </div>
                <?php endif; ?>
            </div>

            </div>
            </div>

            <div class="col-lg-3 col-md-7">
 
            <?php 
                if ( is_active_sidebar( 'footer-column-5' ) ) : ?>
                <div id="newsletter-widget-area" class="chw-widget-area widget-area" role="complementary">
                <?php dynamic_sidebar( 'footer-column-5' ); ?>
                </div>
                <?php endif; ?>


 </div>

        </div>
    </div>
</div>
<div class="footer-bottom">
    <div class="container">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 col-lg-6">
                <div class="copyright-text">
                <p class="mb-0">
                        <?php  echo esc_html(doccure_get_option('footer_copyright')); ?></p>
				</div>
               
                </div>
                <div class="col-md-6 col-lg-6">
                    <?php 
                        wp_nav_menu( array( 
                    'theme_location' => 'header-privacy-menu', 
                    'container_class' => 'header-privacy-menu' ) );
                    ?> 
                </div>
            </div>
        </div>
    </div>
</div>
</footer>
 
</div><!-- #page -->


<!--====== GO TO TOP START ======-->
<?php if (doccure_get_option('back_to_top')=='1') { 
     
     ?>
    
    	<!-- ScrollToTop -->
	<div class="progress-wrap active-progress">
		<svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
			<path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"
				style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919px, 307.919px; stroke-dashoffset: 228.265px;">
			</path>
		</svg>
	</div>
	<!-- /ScrollToTop -->

<?php } ?>
<!--====== GO TO TOP ENDS ======-->
<?php
// Before footer hook
do_action('doccure_after_footer');
wp_footer(); ?>
</body>
</html>
