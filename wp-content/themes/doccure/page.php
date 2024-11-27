<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package doccure
 */
get_header();
the_post();
?>
     <div class="section section-padding">
        <div class="<?php echo esc_attr(doccure_get_page_layout()) ?>">
            <div class="row">
                <div id="primary" class="content-area <?php echo esc_attr(doccure_grid_column_classes()); ?>">
                    <main id="main" class="site-main">
                        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <div class="entry-content">
                                <?php
                                the_content();
                                wp_link_pages(
                                    array(
                                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'doccure'),
                                        'after' => '</div>',
                                    )
                                );
                                ?>
                            </div><!-- .entry-content -->
                        </div>
                        <?php
                        // If comments are open or we have at least one comment, load up the comment template.
                        if (comments_open() || get_comments_number()) {
                            comments_template();
                        }
                        ?>
                    </main><!-- #main -->
                </div><!-- #primary -->
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
 
<?php
get_footer();
