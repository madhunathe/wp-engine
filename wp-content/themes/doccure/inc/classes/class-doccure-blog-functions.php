<?php
/**
 * Core class used to create blog posts.
 *
 * @package WordPress
 * @subpackage doccure
 * @since 2.0.0
 */

class DoccureBase_Blog
{
    /**
     * Outputs a default arguments for the posts
     *
     * @since 2.0.0
     */
    public static function get_default_blog_args()
    {
        $column_size = doccure_get_option('blog-columns', 'col-lg-12');
        $show_author = doccure_get_option('show_author', true);
        $show_date = doccure_get_option('show_date', true);
        $show_comments = doccure_get_option('show_comments', true);
        $show_category = doccure_get_option('show_category', true);
        $show_read_more = doccure_get_option('show_read_more', true);
        $show_share = doccure_get_option('show_share', false);
        $show_post_excerpt = doccure_get_option('show_post_excerpt', true);
        $post_excerpt_length = doccure_get_option('post_excerpt_length', 20);
        $masonry_layout = doccure_get_option('masonry_layout', false);
        return apply_filters('DoccureBase_default_blog_args', [
            'column_size' => $column_size,
            'show_author' => $show_author,
            'show_date' => $show_date,
            'show_comments' => $show_comments,
            'show_category' => $show_category,
            'show_read_more' => $show_read_more,
            'show_share' => $show_share,
            'show_post_excerpt' => $show_post_excerpt,
            'post_excerpt_length' => $post_excerpt_length,
            'masonry_layout' => $masonry_layout,
            'layout' => 'grid'
        ]);
    }

    /**
     * Outputs blog post style.
     *
     * @param string $style to get the post style.
     * @param array $args An array of arguments to add more in default arguments.
     * @since 2.0.0
     *
     */
    public static function get_blog_style($style, $args = [], $classes = '')
    {
        $args = !empty($args) ? $args : self::get_default_blog_args();
        $column_class = ($style == 'style-11') ? 'blog-list-style' : '';
        $col_masonry = $args['masonry_layout'] == true ? 'masonry-item' : '';
        ?>
        <div class="col-lg-4 ">
            <?php get_template_part('template-parts/post/styles/' . $style, null, $args); ?>
        </div>
        <?php
    }

    /**
     * Return attached url from post format link
     *
     * @return string
     *
     * @since 2.0.0
     */

    private static function get_post_attached_url()
    {
        if (!preg_match('/<a\s[^>]*?href=[\'"](.+?)[\'"]/i', get_the_content(), $post_url)) {
            return false;
        }
        return esc_url($post_url[1]);
    }

    /**
     * Return embed media type for post
     *
     * @param array $type - array of embed types(audio/video)
     *
     * @since 2.0.0
     */
    private static function get_embedded_media($type = array())
    {
        $content = do_shortcode(apply_filters('the_content', get_the_content()));
        $embed = get_media_embedded_in_content($content, $type);
        $audio_format_style = doccure_get_option('audio-format-style', 'default');
        if (in_array('audio', $type) && $audio_format_style == 'default') {
            $output = str_replace('?visual=true', '?visual=false', (isset($embed[0]) ? $embed[0] : ''));
        } else {
            $output = isset($embed[0]) ? $embed[0] : '';
        }

        return $output;
    }

    /**
     * return media attachments images
     *
     * @param array $num_posts - number of media attachments
     * @param array $thumnail_size - adjust attachment image size
     * @return array $output - return post featured image/array of media attachments(if post thumbnail is empty)
     *
     * @since 2.0.0
     */
    private static function get_gallery_attachments($num_posts = 1, $thumbnail_size = '')
    {
        $output = '';
        if (has_post_thumbnail() && $num_posts == 1) {
            $output = wp_get_attachment_image_url(get_post_thumbnail_id(get_the_id()), $thumbnail_size);
        } else {
            $attachments = get_posts(
                array(
                    'post_type' => 'attachment',
                    'posts_per_page' => $num_posts,
                    'post_parent' => get_the_ID(),
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                )
            );
            if ($attachments && $num_posts == 1) {
                foreach ($attachments as $attachment) {
                    $output = wp_get_attachment_image_url($attachment->ID, $thumbnail_size);
                }
            } elseif ($attachments && $num_posts > 1) {
                $output = $attachments;
            }
        }
        wp_reset_postdata();
        return $output;
    }

    /**
     * Outputs post format markup.
     *
     * @param string $post_format to get the format of post
     * @since 2.0.0
     *
     */
    public static function get_format_markup($post_format, $thumbnail_size = 'full')
    {
        $format_function = 'get_' . $post_format . '_format_markup';
        echo self::$format_function($thumbnail_size);
    }

    /**
     * Outputs video post format.
     *
     * @since 2.0.0
     */
    private static function get_video_format_markup()
    {
        $video_embed_type = array('video', 'iframe');
        $video_embed_code = self::get_embedded_media($video_embed_type);
        if (has_post_thumbnail()) {
            preg_match('/src="([^"]+)"/', $video_embed_code, $match);
            $video_url = preg_replace('~/embed/~', '/watch?v=', $match[1]);

            if (!empty($video_url)) { ?>
                <a href="<?php echo esc_url($video_url); ?>" class="doccure_video-btn popup-video">
                    <i class="far fa-play"></i>
                </a>
                <?php
            }
        } else { ?>
            <div class="doccure_post-thumb">
                <div class="embed-responsive embed-responsive-16by9">
                    <?php
                    echo wp_kses($video_embed_code, array(
                         'a' => array(
                             'href' => array(),
                             'title' => array(),
                             'class' => array()

                         ),
                         'iframe' => array(
                             'title' => array(),
                             'width' => array(),
                             'height' => array(),
                             'src' => array(),
                             'frameborder' => array(),
                             'allowfullscreen' => array(),
                             'id' => array(),
                             'class' => array()

                         ),
                         'p'
                     ));
                   ?>
                </div>
            </div>
        <?php }
    }

    /**
     * Outputs audio post format.
     *
     * @since 2.0.0
     */
    private static function get_audio_format_markup()
    {
        $audio_embed_type = array('audio', 'iframe');
        $audio_format_style = doccure_get_option('audio-format-style', 'default');
        ?>
        <div class="doccure_post-thumb <?php echo esc_attr($audio_format_style); ?>">
            <div class="embed-responsive embed-responsive-16by9">
                <?php echo self::get_embedded_media($audio_embed_type); ?>
            </div>
        </div>
        <?php
    }

    /**
     * Outputs quote post format.
     *
     * @since 2.0.0
     */
    private static function get_quote_format_markup()
    {
        ?>
        <a class="doccure_post-thumb" href="<?php the_permalink(); ?>">
            <?php echo get_the_content(); ?>
        </a>
        <?php
    }

    /**
     * Outputs link post format.
     *
     * @since 2.0.0
     */
    private static function get_link_format_markup()
    {
        $post_link_url = self::get_post_attached_url();
        ?>
        <div class="doccure_post-thumb">
            <h5><?php the_title(); ?></h5>
            <?php if (!empty($post_link_url)) { ?>
                <div class="doccure_post-meta"><a href="<?php echo esc_url($post_link_url); ?>"> <i class="fal fa-link"></i> <?php echo esc_html($post_link_url); ?> </a></div>
            <?php } ?>
            <a href="<?php the_permalink(); ?>" class="post-link"></a>
        </div>
        <?php
    }

    /**
     * Outputs image post format.
     *
     * @since 2.0.0
     */
    private static function get_image_format_markup($thumbnail_size)
    {
        $image_attachment = self::get_gallery_attachments(1, $thumbnail_size);
        if($image_attachment) {
        ?>
        <div class="doccure_post-thumb doccure-filter-img-wrapper">
            <a href="<?php the_permalink(); ?>">
              <img src="<?php echo esc_url($image_attachment); ?>"/>
            </a>
        </div>
        <?php
      }
    }

    /**
     * Outputs gallery post format.
     *
     * @since 2.0.0
     */
    private static function get_gallery_format_markup($thumbnail_size)
    {
        $attachments_count = !empty(doccure_get_option('gallery_attachments_count')) ? doccure_get_option('gallery_attachments_count') : 4;
        $slider_wrapper_class = ($attachments_count > 1) ? 'has-slider' : '';
        $gallery_attachments = self::get_gallery_attachments($attachments_count);
        if ($attachments_count == 1) {
            echo '<img src="' . self::get_gallery_attachments(1, $thumbnail_size) . '"/>';
        }
        ?>
        <div class="doccure_post-thumb doccure-filter-img-wrapper <?php echo esc_html($slider_wrapper_class); ?>">
            <?php if (is_array($gallery_attachments)) {
                foreach ($gallery_attachments as $gallery_attachment) {
                    $gallery_img_url = wp_get_attachment_image_url($gallery_attachment->ID, $thumbnail_size);
                    ?>
                    <div class="doccure_post-thumb-img">
                        <img src="<?php echo esc_url($gallery_img_url); ?>" alt="img">
                    </div>
                <?php }
            } ?>
        </div>
        <?php
    }

    /**
     * Print post date
     *
     * @since 2.0.0
     */
    public static function get_post_date()
    {
        ?>
        <span class="posted-on">
			<i class="far fa-clock"></i>
			<a href="<?php echo get_the_permalink(); ?>" rel="bookmark">
				<?php echo get_the_date(); ?>
			</a>
		</span>
        <?php
    }

    /**
     * Prints post categories
     *
     * @since 2.0.0
     */
    public static function get_posts_categories()
    {
        global $post;
        $categories_list = get_the_category_list(esc_html__(', ', 'doccure'));
        if (!empty($categories_list)) {
            ?>
            <span class="categories-list">
            <i class="fas fa-tags"></i>
            <?php print_r($categories_list); ?>
			</span>
            <?php
        }
    }

    /**
     * Displays an post authorbox.
     *
     * @since 1.0.0
     */
    public static function get_post_authorbox()
    {
        if (get_the_author_meta('description')) {
            $retina_mult = doccure_get_retina_multiplier();
            ?>
            <div class="section pb-0 author-box-wrapper">
                <div class="doccure_author-about">
                    <?php echo get_avatar(get_the_author_meta('ID'), 150 * $retina_mult); ?>
                    <div class="doccure_author-about-content">
                        <?php
                        if (!is_author()) {
                            ?>
                            <span> <?php echo esc_html__('Written By ', 'doccure') ?></span>
                            <h4 class="author-title"><?php echo get_the_author(); ?></h4>
                            <?php
                        }
                        ?>
                        <p><?php the_author_meta('description'); ?></p>
                    </div>
                </div>
            </div>
            <?php
        }
    }

    /**
     * Displays related posts.
     *
     * @since 1.0.0
     */
    public static function get_related_post()
    {
        $args = !empty($args) ? $args : self::get_default_blog_args();
        $related_post_style = doccure_get_option('related-blog-style', 'style-1');
        $related_category_ids = wp_get_post_categories(get_the_ID());
        if (empty($related_category_ids)) {
            return;
        }
        $query_args = array(
            'post_type' => 'post',
            'posts_per_page' => 2,
            'post__not_in' => array(get_the_ID()),
            'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'id',
                    'terms' => $related_category_ids,
                ),
            ),
        );
        $related_post_query = new WP_Query($query_args);
        if ($related_post_query->have_posts() && doccure_get_option('show_related_posts') == true) {
            ?>
            <div class="section section-padding pb-0 doccure_related-posts">
                <h3 class="doccure-related-title"><?php echo esc_html__('Related Posts', 'doccure') ?></h3>
                <div class="row">
                    <?php
                    while ($related_post_query->have_posts()) {
                        $related_post_query->the_post();
                        echo '<div class="col-lg-6">';
                        get_template_part('template-parts/post/styles/' . $related_post_style, null, $args);
                        echo '</div>';
                    }
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
            <?php
        }
    }

    /**
     * Posts author meta info
     *
     * @since 1.0.0
     */
    public static function get_posts_author()
    {
        global $post;
        ?>
        <span class="author">
					<?php echo get_avatar(get_the_author_meta('ID'), 50); ?>
					<i class="fal fa-user-circle"></i>
					<a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
						<?php echo esc_html(get_the_author()); ?>
					</a>
				</span>
        <?php
    }

    /**
     * Prints HTML for post tags
     *
     * @since 1.0.0
     */
    public static function get_posts_tags()
    {
        global $post;
        $tags_list = get_the_tag_list('', esc_html_x('', 'list item separator', 'doccure'));
        if (!empty($tags_list)) {
            ?>
            <div class="entry-meta-footer doccure_post_tags">
                <h5><?php esc_html_e('Tags', 'doccure'); ?></h5>
                <div class="tagcloud">
                    <?php print_r($tags_list); ?>
                </div>
            </div>
        <?php }
    }

    /**
     * Posts comments
     *
     * @since 1.0.0
     */
    public static function show_posts_comments()
    {
        global $post;
        ?>
        <?php if (comments_open()) { ?>
        <span class="meta-comment">
					<i class="far fa-comment-dots"></i>
					<?php
            $comment_template = '<span class="comment-count">%s</span>';
            comments_popup_link(
                sprintf($comment_template, '0', esc_html__('comments', 'doccure')),
                sprintf($comment_template, '1', esc_html__('comment', 'doccure')),
                sprintf($comment_template, '%', esc_html__('comments', 'doccure'))
            );
            ?>
				</span>
    <?php } ?>
        <?php
    }
}
