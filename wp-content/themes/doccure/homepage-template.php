<?php
/*
* Template Name: Home Pages
 * @package doccure
 */
get_header();
the_post();
?>
 
 
                     <main id="main" class="site-main">
                        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                 <?php
                                the_content();
                                wp_link_pages(
                                    array(
                                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'doccure'),
                                        'after' => '</div>',
                                    )
                                );
                                ?>
                         </div>
                        <?php
                        // If comments are open or we have at least one comment, load up the comment template.
                        // if (comments_open() || get_comments_number()) {
                        //     comments_template();
                        // }
                        ?>
                    </main><!-- #main -->
                 <?php get_sidebar(); ?>
 
 
<?php
get_footer();
