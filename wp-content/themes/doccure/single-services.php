<?php
/**
 * The template for displaying all single service posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package doccure
 */
get_header();
?>
<?php if (has_shortcode(get_the_content(), 'vc_row')) { ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="container">
            <div class="entry-content clearfix">
                <?php the_content(); ?>
            </div>
        </div>
    </div>
<?php } else if (has_shortcode(get_the_content(), 'elementor')) { ?>

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

   
    <?php } else { ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="container">
            <div class="entry-content clearfix">
                <?php the_content(); ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php
get_footer();
