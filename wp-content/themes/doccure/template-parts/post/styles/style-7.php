<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package doccure
 */
$post_format = get_post_format();
$format_no_thumb = ['audio', 'quote', 'link', 'gallery', 'image'];
$format_no_content = ['quote', 'link'];
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('doccure_post style-7'); ?> >
    <?php if (has_post_thumbnail() && !in_array($post_format, $format_no_thumb)) { ?>
        <div class="doccure_post-thumb doccure-filter-img-wrapper">
            <a href="<?php the_permalink(); ?>"><?php DoccureBase_Lazy_Load::show_lazy_load_post_image(); ?></a>
            <?php
            if ($post_format == 'video') {
                DoccureBase_Blog::get_format_markup($post_format);
            }
            ?>
        </div>
    <?php }
    if ($post_format == 'video' && !has_post_thumbnail()) {
        DoccureBase_Blog::get_format_markup($post_format);
    }
    if ($post_format == 'audio') {
        DoccureBase_Blog::get_format_markup($post_format);
    }
    if ($post_format == 'quote') {
        DoccureBase_Blog::get_format_markup($post_format);
    }
    if ($post_format == 'link') {
        DoccureBase_Blog::get_format_markup($post_format);
    }
    if ($post_format == 'image') {
        DoccureBase_Blog::get_format_markup($post_format, 'full');
    }
    if ($post_format == 'gallery') {
        DoccureBase_Blog::get_format_markup($post_format, 'full');
    }
    ?>

    <?php if (!in_array($post_format, $format_no_content)) { ?>
        <div class="doccure_post-body">
            <?php if ($args['show_author'] == true || $args['show_date'] == true || $args['show_comments'] == true || $args['show_category'] == true ) { ?>
                <div class="doccure_post-meta">
                    <?php if ($args['show_author'] == true) {
                        DoccureBase_Blog::get_posts_author();
                    }
                    if ($args['show_date'] == true) {
                        DoccureBase_Blog::get_post_date();
                    }
					if ($args['show_comments'] == true) {
						DoccureBase_Blog::show_posts_comments();
                    }
					 if ($args['show_category'] == true && has_post_thumbnail()) {
						DoccureBase_Blog::get_posts_categories();
					}
                    ?>
                </div>
            <?php } ?>
            <div class="doccure_post-content">
                <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                <?php if ($args['show_post_excerpt'] == true) { ?>
                    <p class="m-0"><?php echo doccure_custom_excerpt($args['post_excerpt_length']); ?></p>
                <?php } ?>
                <?php if ($args['show_read_more'] == true) { ?>
                    <a href="<?php echo esc_url(get_the_permalink()); ?>" class="btn-link">
                        <?php esc_html_e('Read More', 'doccure'); ?> <i class="fas fa-angle-right"></i>
                    </a>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</article>
