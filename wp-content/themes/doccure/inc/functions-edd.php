<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if( !doccure_is_edd_active() ){
  return;
}

/**
 * Outputs a default arguments for the download
 *
 * @since 2.1.1
 */
function doccure_get_default_download_args()
{
    $column_size = doccure_get_option('download-columns', 'col-lg-4 col-md-6');
    $show_download_icon = doccure_get_option('show_download_icon', true);
    $show_download_features = doccure_get_option('show_download_features', true);
    $show_download_excerpt = doccure_get_option('show_download_excerpt', true);
    $download_excerpt_length = doccure_get_option('download_excerpt_length', 20);
    return apply_filters('DoccureBase_default_blog_args', [
        'column_size' => $column_size,
        'show_download_icon' => $show_download_icon,
        'show_download_features' => $show_download_features,
        'show_download_excerpt' => $show_download_excerpt,
        'download_excerpt_length' => $download_excerpt_length,
        'layout' => 'grid'
    ]);
}

/**
 * Outputs download style.
 *
 * @param string $style to get the download style.
 * @param array $args An array of arguments to add more in default arguments.
 * @since 2.1.1
 *
 */
function doccure_get_download_style($style, $count, $args = [], $classes = '')
{
    global $count_no;

    $count_no = $count;

    $args = !empty($args) ? $args : doccure_get_default_download_args();
    ?>
    <div class="<?php echo esc_attr($args['column_size'] . ' ' . $classes); ?>">
        <?php get_template_part('template-parts/download/styles/' . $style, null, $args); ?>
    </div>
    <?php
}

/**
 * Get the base price of a variant download.
 *
 * @since 2.1.1
 */
function doccure_get_download_base_price( $id ){
  $item_props = edd_add_schema_microdata() ? ' itemprop="offers" itemscope itemtype="http://schema.org/Offer"' : '';
  ?>
  <div class="doccure_download-price-wrapper" <?php
    echo wp_kses($item_props, array(
        'div' => array(
            'itemprop' => array(),
            'itemscope' => array(),
            'itemtype' => array(),
        ),
    ));
   ?>>
    <div itemprop="price" class="edd_price">
      <span class="doccure_download-price"><?php echo edd_currency_filter( edd_format_amount( edd_get_lowest_price_option( $id ) ) ); ?></span>
    </div>
  </div>
  <?php
}

/**
* Get the purchase button
*
* @since 2.1.1
*/
function doccure_get_download_purchase_button($id, $class = '', $text = 'Add To Cart', $price = FALSE, $direct = FALSE  ) {
  $item_prop = edd_add_schema_microdata() ? ' itemprop="purchaseButton"' : '';
  ?>
  <div class="doccure_download-purchase-button" <?php
  echo wp_kses($item_prop, array(
      'div' => array(
          'itemprop' => array(),
      ),
  ));
   ?>>
    <?php echo edd_get_purchase_link( array(
      'download_id' => $id,
      'price'  => $price,
      'direct' => $direct,
      'text' => $text,
      'class' => $class
    ) ); ?>
  </div>
  <?php
}

/**
 * Get the excerpt of a download.
 *
 * @since 2.1.1
 */
function doccure_get_download_excerpt( $id, $excerpt_length = 30 ){
  $item_prop = edd_add_schema_microdata() ? ' itemprop="description"' : '';
  if ( has_excerpt() ) :
    ?>
    <p <?php
    echo wp_kses($item_prop, array(
        'div' => array(
            'itemprop' => array(),
        ),
    ));
     ?> class="doccure_download-excerpt">
      <?php echo wp_trim_words( get_post_field( 'post_excerpt', $id ), $excerpt_length ); ?>
    </p>
  <?php elseif ( get_the_content() ) : ?>
    <p <?php echo wp_kses($item_prop, array(
        'div' => array(
            'itemprop' => array(),
        ),
    )); ?> class="doccure_download-excerpt">
      <?php echo wp_trim_words( get_post_field( 'post_content', $id ), $excerpt_length ); ?>
    </p>
  <?php
  endif;
}

/**
 * Add the downloads widget areas.
 *
 * @since 2.1.1
 */
function doccure_get_download_widgets_init() {

  register_sidebar(
    array(
      'name'          => esc_html__( 'Download Sidebar', 'doccure' ),
      'id'            => 'download-sidebar',
      'description'   => esc_html__( 'Add widgets here.', 'doccure' ),
      'before_widget' => '<section id="%1$s" class="widget %2$s">',
      'after_widget'  => '</section>',
      'before_title'  => '<h4 class="widget-title">',
      'after_title'   => '</h4>',
    )
  );

}
add_action( 'widgets_init',  'doccure_get_download_widgets_init' );

/**
 * Set the current sidebar to download-sidebar.
 *
 * @see doccure/sidebar/current_sidebar
 *
 * @since 2.1.1
 */
function doccure_get_download_sidebar( $current_sidebar ) {

  if ( is_singular( 'download' ) || is_post_type_archive('download') ) {
    $current_sidebar = 'download-sidebar';
  }

  return $current_sidebar;

}

add_filter( 'doccure/sidebar/current_sidebar', 'doccure_get_download_sidebar' );

/**
 * Get the sidebar position of download-sidebar from theme options.
 *
 * @see doccure/sidebar/sidebar_position
 *
 * @since 2.1.1
 */
function doccure_get_download_sidebar_position( $sidebar_position ) {

  if (is_post_type_archive('download') ) {
    $sidebar_position = doccure_get_option('download_sidebar', $sidebar_position);
  } elseif( is_singular( 'download' ) ){
    $sidebar_position = doccure_get_option('download_details_sidebar', $sidebar_position);
  }

  return $sidebar_position;

}
add_filter( 'doccure/sidebar/sidebar_position', 'doccure_get_download_sidebar_position');

/**
 * Get the title of a download
 *
 * @since 2.1.1
 */
function doccure_get_download_title( $id ){
  $item_prop = edd_add_schema_microdata() ? ' itemprop="name"' : '';
  ?>
  <h5 <?php echo wp_kses($item_prop, array(
      'div' => array(
          'itemprop' => array(),
      ),
  )); ?> class="doccure_download-title">
    <a itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
  </h5>
  <?php
}

/**
 * Outputs download categories.
 *
 * @since 2.1.1
 */
function doccure_get_download_categories() {
    global $post;
    $download_category = get_the_terms($post->ID, 'download_category');
    if ($download_category) {
        $download_cat = $download_category[0];
        $download_cat_name = $download_cat->name;
        $download_cat_id = $download_cat->slug;
        if (isset($download_cat_name) && !empty($download_cat_name)) {
            ?>
            <div class="doccure_download-categories">
                <a href="<?php echo esc_url(get_term_link($download_cat->slug, 'download_category')); ?>"
                   class="doccure_download-category"><?php echo esc_html($download_cat_name); ?></a>
            </div>
        <?php }
    }
}

/**
 * Enqueue woocommerce scripts and styles.
 *
 * @since 2.1.1
 */
function doccure_get_download_scripts()
{
    wp_enqueue_style('doccure-download', get_template_directory_uri() . '/assets/css/theme-download.css', array(), '2.1.1');
}

add_action('wp_enqueue_scripts', 'doccure_get_download_scripts');

/**
 * Add the redux options to the list of available options.
 *
 * @since 2.1.1
 */
function doccure_edd_redux_options( $options_files ){

  $options_files[] = get_template_directory() . '/inc/redux-options/options/download-settings.php';

  return $options_files;

}
add_filter( 'doccure_redux_option_files', 'doccure_edd_redux_options' );
