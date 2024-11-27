<?php
/**
 * The template for displaying all single portfolio posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package doccure
 */
get_header();
?>
    <div class="section">
        <div class="container">
            <div class="row">
                <div id="primary" class="<?php echo esc_attr(doccure_grid_column_classes()); ?> content-area">
                    <main id="main" class="site-main">
                        <?php
                        while (have_posts()) :
                            the_post();
                            get_template_part('template-parts/portfolio/single/content');
                            // If comments are open or we have at least one comment, load up the comment template.
                            if (comments_open() || get_comments_number()) :
                                comments_template();
                            endif;
                        endwhile; // End of the loop.
                        ?>
                    </main><!-- #main -->
                </div><!-- #primary -->
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
<?php
get_footer();
