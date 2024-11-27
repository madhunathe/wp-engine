<?php
/**
 * Widget API: WP_Widget_Recent_Posts class
 *
 * @package WordPress
 * @subpackage Widgets
 * @since 4.4.0
 */
/**
 * Core class used to implement a Recent Posts widget.
 *
 * @since 2.8.0
 *
 * @see WP_Widget
 */
class Doccure_Widget_Recent_Posts extends WP_Widget
{
    /**
     * Sets up a new Recent Posts widget instance.
     *
     * @since 2.8.0
     */
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'widget_doccure_recent_entries',
            'description' => esc_html__('Your site&#8217;s most recent Posts.', 'doccure'),
            'customize_selective_refresh' => true,
        );
        parent::__construct('doccure-recent-posts', esc_html__('Doccure Recent Posts', 'doccure'), $widget_ops);
        $this->alt_option_name = 'widget_doccure_recent_entries';
    }
    /**
     * Outputs the content for the current Recent Posts widget instance.
     *
     * @param array $args Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param array $instance Settings for the current Recent Posts widget instance.
     * @since 2.8.0
     *
     */
    public function widget($args, $instance)
    {
        if (!isset($args['widget_id'])) {
            $args['widget_id'] = $this->id;
        }
        $title = (!empty($instance['title'])) ? $instance['title'] : esc_html__('Recent Posts', 'doccure');
        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);
        $number = (!empty($instance['number'])) ? absint($instance['number']) : 5;
        if (!$number) {
            $number = 5;
        }
        $show_date = isset($instance['show_date']) ? $instance['show_date'] : false;
        $show_image = isset($instance['show_image']) ? $instance['show_image'] : false;
        $show_category = isset($instance['show_category']) ? $instance['show_category'] : false;
        $show_author = isset($instance['show_author']) ? $instance['show_author'] : false;
        $style = isset($instance['style']) ? $instance['style'] : 'style-1';
        /**
         * Filters the arguments for the Recent Posts widget.
         *
         * @param array $args An array of arguments used to retrieve the recent posts.
         * @param array $instance Array of settings for the current widget.
         * @see WP_Query::get_posts()
         *
         * @since 3.4.0
         * @since 4.9.0 Added the `$instance` parameter.
         *
         */
        $r = new WP_Query(
            apply_filters(
                'doccure_widget_posts_args',
                array(
                    'posts_per_page' => $number,
                    'no_found_rows' => true,
                    'post_status' => 'publish',
                    'ignore_sticky_posts' => true,
                ),
                $instance
            )
        );
        if (!$r->have_posts()) {
            return;
        }
        ?>
        <?php echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?php
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } ?>
        <div class="latest-posts">
            <?php
        foreach ($r->posts as $recent_post) : ?>
            <?php
            $post_title = get_the_title($recent_post->ID);
            $title = (!empty($post_title)) ? $post_title : esc_html__('(no title)', 'doccure');
            $aria_current = '';
            if (get_queried_object_id() === $recent_post->ID) {
                $aria_current = ' aria-current="page"';
            }
            $meta_wrapper_class = ($show_author == true) ? 'show-author' : '';
            ?>
                <div class="blog_sidebar">
                <?php if ($show_image && has_post_thumbnail($recent_post->ID)) : ?>
                    <a href="<?php the_permalink($recent_post->ID); ?>" class="recent-post-image">
                        <?php echo get_the_post_thumbnail($recent_post->ID, 'thumbnail',array('class' => 'img-responsive')); ?>
                    </a>
                <?php else: ?>
                    <a href="<?php the_permalink($recent_post->ID); ?>" class="recent-post-image">
                    <img width="77" height="60" src="<?php echo get_template_directory_uri(); ?>/assets/images/default-blog.jpg" alt="<?php the_title(); ?>" />
                    </a>
               <?php  endif; ?>
                <div class="recent-post-descr">
                    <?php if ($show_category) :
                        $post_category = get_the_terms($recent_post->ID, 'category');
                        $post_cat = $post_category[0];
                        $post_cat_name = $post_cat->name;
                        ?>
                        <a href="<?php echo get_term_link($post_cat->term_id); ?>"
                           class="post-cat"><?php echo esc_html($post_cat_name); ?></a>
                    <?php endif; ?>
                    <h6>
                        <a href="<?php the_permalink($recent_post->ID); ?>"<?php echo esc_html($aria_current); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html($title); ?></a>
                    </h6>
                    <div class="recent-post-meta <?php echo esc_attr($meta_wrapper_class); ?>">
                        <?php if ($show_author) : ?>
                                <span><?php echo doccure_get_initials(get_the_author()); ?></span>
                        <?php endif; ?>
                        <div>
                            <?php if ($show_author) : ?>
                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"
                                   class="author-name"><?php echo esc_html(get_the_author()); ?></a>
                            <?php endif; ?>
                            <?php if ($show_date) : ?>
                                <a class="posts-date"><i class="far fa-clock me-2"></i> <?php echo get_the_date('', $recent_post->ID); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                </div>

        <?php endforeach; ?>
       </div>
        <?php
        echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
    /**
     * Handles updating the settings for the current Recent Posts widget instance.
     *
     * @param array $new_instance New settings for this instance as input by the user via
     *                            WP_Widget::form().
     * @param array $old_instance Old settings for this instance.
     * @return array Updated settings to save.
     * @since 2.8.0
     *
     */
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['number'] = (int)$new_instance['number'];
        $instance['show_date'] = isset($new_instance['show_date']) ? (bool)$new_instance['show_date'] : false;
        $instance['show_image'] = isset($new_instance['show_image']) ? (bool)$new_instance['show_image'] : false;
        $instance['show_category'] = isset($new_instance['show_category']) ? (bool)$new_instance['show_category'] : false;
        $instance['show_author'] = isset($new_instance['show_author']) ? (bool)$new_instance['show_author'] : false;
        $instance['style'] = isset($new_instance['style']) ? $new_instance['style'] : 'style-1';
        return $instance;
    }
    /**
     * Outputs the settings form for the Recent Posts widget.
     *
     * @param array $instance Current settings.
     * @since 2.8.0
     *
     */
    public function form($instance)
    {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        $show_date = isset($instance['show_date']) ? (bool)$instance['show_date'] : false;
        $show_image = isset($instance['show_image']) ? (bool)$instance['show_image'] : false;
        $show_category = isset($instance['show_category']) ? (bool)$instance['show_category'] : false;
        $show_author = isset($instance['show_author']) ? (bool)$instance['show_author'] : false;
        $style = isset($instance['style']) ? esc_attr($instance['style']) : 'style-1';
        ?>
        <p><label for="<?php echo esc_html($this->get_field_id('text')); ?>"><?php esc_html_e('Style', 'doccure'); ?>
                <select class='widefat' id="<?php echo esc_html($this->get_field_id('style')); ?>"
                        name="<?php echo esc_html($this->get_field_name('style')); ?>" type="text">
                    <option value='style-1'<?php echo esc_html(($style == 'style-1')) ? 'selected' : ''; ?>>
                        <?php esc_html_e('Style 1', 'doccure'); ?>
                    </option>
                  
                    
                </select>
            </label>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'doccure'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/></p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of posts to show:', 'doccure'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1"
                   value="<?php echo esc_attr($number); ?>" size="3"/></p>
        <p><input class="checkbox" type="checkbox"<?php checked($show_date); ?>
                  id="<?php echo esc_attr($this->get_field_id('show_date')); ?>"
                  name="<?php echo esc_attr($this->get_field_name('show_date')); ?>"/>
            <label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>"><?php esc_html_e('Display post date?', 'doccure'); ?></label>
        </p>
        <p><input class="checkbox" type="checkbox"<?php checked($show_image); ?>
                  id="<?php echo esc_attr($this->get_field_id('show_image')); ?>"
                  name="<?php echo esc_attr($this->get_field_name('show_image')); ?>"/>
            <label for="<?php echo esc_attr($this->get_field_id('show_image')); ?>"><?php esc_html_e('Display post image?', 'doccure'); ?></label>
        </p>
        
       
        <?php
    }
}
