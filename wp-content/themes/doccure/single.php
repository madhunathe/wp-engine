<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package doccure
 */
get_header();
$blog_details_style_class = !empty(doccure_get_option('blog_details_style')) ? doccure_get_option('blog_details_style') : 'style-1';
$blog_details_wrapper_class = doccure_get_option('blog_details_style') == 'style-3' ? 'justify-content-center' : '';
$blog_column_class = doccure_get_option('blog_details_style') == 'style-3' ? 'col-md-8' : doccure_grid_column_classes();
?>
    <div class="section section-padding">
        <div class="container">
            <div class="row <?php echo esc_attr($blog_details_wrapper_class); ?>">
                <div id="primary" class="content-area <?php echo esc_attr($blog_column_class); ?>">
                    <main id="main" class="site-main">
                        <div class="post-details-box <?php echo esc_attr($blog_details_style_class); ?>">
                            <?php
                            while (have_posts()) :
                                the_post();
                                get_template_part('template-parts/post/single/content');
                                // If comments are open or we have at least one comment, load up the comment template.
                                if (comments_open() || get_comments_number()) :
                                    comments_template();
                                endif;
                            endwhile; // End of the loop.
                            ?>
                        </div>
                    </main><!-- #main -->
                </div><!-- #primary -->
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
<?php
get_footer();
