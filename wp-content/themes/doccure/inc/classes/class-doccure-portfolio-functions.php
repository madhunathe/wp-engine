<?php
/**
 * Core class used to create portfolio's.
 *
 * @package WordPress
 * @subpackage doccure
 * @since 2.0.0
 */

class DoccureBase_Portfolio
{
    /**
     * Outputs a default arguments for the portfolio
     *
     * @since 2.0.0
     */
    public static function get_default_portfolio_args()
    {
        $column_size = doccure_get_option('portfolio-columns', 'col-lg-4 col-md-6');
        $show_portfolio_category = doccure_get_option('show_portfolio_cat', true);
        $show_portfolio_excerpt = doccure_get_option('show_portfolio_excerpt', true);
        $portfolio_excerpt_length = doccure_get_option('portfolio_excerpt_length', 20);
        return apply_filters('DoccureBase_default_blog_args', [
            'column_size' => $column_size,
            'show_portfolio_cat' => $show_portfolio_category,
            'show_portfolio_excerpt' => $show_portfolio_excerpt,
            'portfolio_excerpt_length' => $portfolio_excerpt_length,
            'layout' => 'grid'
        ]);
    }

    /**
     * Outputs portfolio style.
     *
     * @param string $style to get the portfolio style.
     * @param array $args An array of arguments to add more in default arguments.
     * @since 2.0.0
     *
     */
    public static function get_portfolio_style($style, $args = [], $classes = '')
    {
        $args = !empty($args) ? $args : self::get_default_portfolio_args();
        ?>
        <div class="<?php echo esc_attr($args['column_size'] . ' ' . $classes); ?>">
            <?php get_template_part('template-parts/portfolio/styles/' . $style, null, $args); ?>
        </div>
        <?php
    }

    /**
     * Outputs portfolio categories.
     *
     * @since 2.0.0
     */
    public static function get_portfolio_categories()
    {
        global $post;
        $portfolio_category = get_the_terms($post->ID, 'portfolio-category');
        if ($portfolio_category) {
            $portfolio_cat = $portfolio_category[0];
            $portfolio_cat_name = $portfolio_cat->name;
            $portfolio_cat_id = $portfolio_cat->slug;
            if (isset($portfolio_cat_name) && !empty($portfolio_cat_name)) {
                ?>
                <div class="doccure_portfolio-categories">
                    <a href="<?php echo esc_url(get_term_link($portfolio_cat->slug, 'portfolio-category')); ?>"
                       class="doccure_portfolio-category"><?php echo esc_html($portfolio_cat_name); ?></a>
                </div>
            <?php }
        }
    }

    /**
     * Displays related portfolio.
     *
     * @since 2.0.0
     */
    public static function get_related_portfolio()
    {
        $args = !empty($args) ? $args : self::get_default_portfolio_args();
        $related_portfolio_style = doccure_get_option('related-portfolio-style', 'style-1');
        $related_category_ids = wp_get_object_terms(get_the_ID(), 'portfolio-category', array('fields' => 'ids'));
        $related_portfolio_columns = doccure_get_option('related-portfolio-columns', '3');
        if (empty($related_category_ids)) {
            return;
        }
        $query_args = array(
            'post_type' => 'portfolio',
            'posts_per_page' => -1,
            'post__not_in' => array(get_the_ID()),
            'tax_query' => array(
                array(
                    'taxonomy' => 'portfolio-category',
                    'field' => 'id',
                    'terms' => $related_category_ids,
                ),
            ),
        );
        $related_portfolio_query = new WP_Query($query_args);
        if ($related_portfolio_query->have_posts() && doccure_get_option('show_related_portfolio') == true) {
            $portfolio_slick_options = array(
                'autoplay' => false,
                'infinite' => true,
                'arrows' => true,
                'slidesToShow' => $related_portfolio_columns,
                'slidesToScroll' => 1,
                'centerMode' => true,
                'centerPadding' => 0,
                'responsive' => array(
                    array(
                        'breakpoint' => 991,
                        'settings' => array(
                            'slidesToShow' => 2,
                        ),
                    ),
                    array(
                        'breakpoint' => 767,
                        'settings' => array(
                            'slidesToShow' => 1,
                        ),
                    ),
                )
            );
            ?>
            <div class="section section-padding pb-0 doccure_related-portfolio doccure-shortcode-wrapper">
                <h3 class="doccure-related-title"><?php echo esc_html__('Related Posts', 'doccure') ?></h3>
                <div class="row doccure-portfolio-related-slider slick-slider shortcode_slider arrows-style-2 arrows-top-right"
                     data-slick="<?php echo esc_attr(json_encode($portfolio_slick_options)); ?>">
                    <?php
                    while ($related_portfolio_query->have_posts()) {
                        $related_portfolio_query->the_post();
                        echo '<div class="col-lg-6">';
                        get_template_part('template-parts/portfolio/styles/' . $related_portfolio_style, null, $args);
                        echo '</div>';
                    }
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
            <?php
        }
    }
}
