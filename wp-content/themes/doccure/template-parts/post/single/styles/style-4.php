<?php
/**
 * Template part for displaying post details layout 4
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package doccure
 */
if (function_exists('doccurecore_set_post_view')) {
    doccurecore_set_post_view();
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('doccure_post-details'); ?>>
    <?php if (has_post_thumbnail()) { ?>
        <div class="doccure_post-thumbnail">
            <?php DoccureBase_Lazy_Load::show_lazy_load_post_image(); ?>
        </div>
    <?php } ?>
    <div class="doccure_post-details-inner">
        <div class="doccure_post-details-categories">
            <?php DoccureBase_Blog::get_posts_categories(); ?>
        </div>
        <?php
        if (!doccure_subheader_is_active()) {
            the_title('<h1 class="entry-title">', '</h1>');
        }
        ?>
        <div class="doccure_post-details-meta">
            <?php if (function_exists('doccurecore_set_post_view')) { ?>
                <span> <i class="far fa-eye"></i><?php echo doccurecore_get_post_view(); ?></span>
            <?php } ?>
            <?php echo DoccureBase_Blog::show_posts_comments(); ?>
            <?php DoccureBase_Blog::get_post_date(); ?>
        </div>
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
        </div>
        <!-- .entry-content -->
        <div class="doccure_post-details-meta footer-meta tags-share">
            <?php DoccureBase_Blog::get_posts_tags(); ?>
            <?php
            if (function_exists('doccurecore_posts_share')) {
                doccurecore_posts_share();
            }
            ?>
        </div>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->
<?php
  // pagination
  doccure_single_post_pagination();
  // Related Post
  DoccureBase_Blog::get_related_post();
  // Post author box
  DoccureBase_Blog::get_post_authorbox();
?>
