<?php
/**
 * Core class used to create services.
 *
 * @package WordPress
 * @subpackage doccure
 * @since 2.0.0
 */

class DoccureBase_Service
{
    public static $count = 1;

    /**
     * Outputs a default arguments for the services
     *
     * @since 2.0.0
     */
    public static function get_default_service_args()
    {
        $column_size = doccure_get_option('service-columns', 'col-lg-4 col-md-6');
        $show_service_icon = doccure_get_option('show_service_icon', true);
        $show_service_features = doccure_get_option('show_service_features', true);
        $show_read_more = doccure_get_option('show_read_more', true);
        $show_service_excerpt = doccure_get_option('show_service_excerpt', true);
        $service_excerpt_length = doccure_get_option('service_excerpt_length', 20);
        return apply_filters('DoccureBase_default_blog_args', [
            'column_size' => $column_size,
            'show_service_icon' => $show_service_icon,
            'show_service_features' => $show_service_features,
            'show_read_more' => $show_read_more,
            'show_service_excerpt' => $show_service_excerpt,
            'service_excerpt_length' => $service_excerpt_length,
            'layout' => 'grid'
        ]);
    }

    /**
     * Outputs service style.
     *
     * @param string $style to get the service style.
     * @param array $args An array of arguments to add more in default arguments.
     * @since 2.0.0
     *
     */
    public static function get_service_style($style, $args = [], $classes = '')
    {
        $args = !empty($args) ? $args : self::get_default_service_args();
        ?>
        <div class="<?php echo esc_attr($args['column_size'] . ' ' . $classes); ?>">
            <?php get_template_part('template-parts/service/styles/' . $style, null, $args); ?>
        </div>
        <?php self::$count++;
    }

    /**
     * Outputs service categories.
     *
     * @since 2.1.0
     */
    public static function get_service_categories()
    {
        global $post;
        $service_category = get_the_terms($post->ID, 'service-category');
        if ($service_category) {
            $service_cat = $service_category[0];
            $service_cat_name = $service_cat->name;
            $service_cat_id = $service_cat->slug;
            if (isset($service_cat_name) && !empty($service_cat_name)) {
                ?>
                <div class="doccure_service-categories">
                    <a href="<?php echo esc_url(get_term_link($service_cat->slug, 'service-category')); ?>"
                       class="doccure_service-category"><?php echo esc_html($service_cat_name); ?></a>
                </div>
            <?php }
        }
    }
}
