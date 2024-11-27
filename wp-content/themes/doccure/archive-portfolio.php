<?php
/**
 * The template for displaying portfolio archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package doccure
 */
get_header();
$portfolio_style = doccure_get_option('portfolio-style', 'style-1');
?>
    <div class="section section-padding">
        <div class="container">
            <div class="row">
                <div id="primary" class="content-area <?php echo esc_attr(doccure_grid_column_classes()); ?>">
                    <main id="main" class="site-main">
                        <?php if (have_posts()) : ?>
                            <div class="row">
                                <?php
                                /* Start the Loop */
                                while (have_posts()) :
                                    the_post();
                                    DoccureBase_Portfolio::get_portfolio_style($portfolio_style);
                                endwhile;
                                ?>
                            </div>
                            <?php if (get_next_posts_link() != '' || get_previous_posts_link() != '') { ?>
                                <div class="post-pagination">
                                    <?php the_posts_pagination(); ?>
                                </div>
                            <?php } ?>
                        <?php
                        else :
                            get_template_part('template-parts/content', 'none');
                        endif;
                        ?>
                    </main><!-- #main -->
                </div><!-- #primary -->
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
<?php
get_footer();
