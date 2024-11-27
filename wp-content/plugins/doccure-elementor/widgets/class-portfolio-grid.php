<?php
/**
 * Awesomesauce class.
 *
 * @category   Class
 * @package    ElementorAwesomesauce
 * @subpackage WordPress
 * @author     Ben Marshall <me@benmarshall.me>
 * @copyright  2020 Ben Marshall
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @link       link(https://www.benmarshall.me/build-custom-elementor-widgets/,
 *             Build Custom Elementor Widgets)
 * @since      1.0.0
 * php version 7.3.9
 */

namespace ElementorDoccure\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Awesomesauce widget class.
 *
 * @since 1.0.0
 */
class PortfolioGrid extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'doccure-Portfolio-grid';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Portfolio Grid', 'doccure_elementor' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-grid';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'doccure' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
  // 'limit'=>'6',
  //           'orderby'=> 'date',
  //           'order'=> 'DESC',
   //           'ignore_sticky_posts' => 1,
   //           'from_vs' => 'no'


		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Query', 'doccure_elementor' ),
			)
		);

		$this->add_control(
			'limit',
			[
				'label' => __( 'Posts to display', 'doccure_elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 21,
				'step' => 1,
				'default' => 3,
			]
		);
 
		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order by', 'doccure_elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'none' =>  __( 'No order', 'doccure_elementor' ),
					'ID' =>  __(  'Order by post id. ', 'doccure_elementor' ),
					'author'=>  __(  'Order by author.', 'doccure_elementor' ),
					'title' =>  __(  'Order by title.', 'doccure_elementor' ),
					'name' =>  __( ' Order by post name (post slug).', 'doccure_elementor' ),
					'type'=>  __( ' Order by post type.', 'doccure_elementor' ),
					'date' =>  __( ' Order by date.', 'doccure_elementor' ),
					'modified' =>  __( ' Order by last modified date.', 'doccure_elementor' ),
					'parent' =>  __( ' Order by post/page parent id.', 'doccure_elementor' ),
					'rand' =>  __( ' Random order.', 'doccure_elementor' ),
					'comment_count' =>  __( ' Order by number of commen', 'doccure_elementor' ),
					
				],
			]
		);
		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'doccure_elementor'  ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'DESC' =>  __( 'Descending', 'doccure_elementor' ),
					'ASC' =>  __(  'Ascending. ', 'doccure_elementor' ),
				
					
				],
			]
		);

  
	

		 


		$this->end_controls_section();
 
 

		/* Add the options you'd like to show in this tab here */

	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'title', 'none' );
		$this->add_inline_editing_attributes( 'subtitle', 'none' );
		$limit = $settings['limit'] ? $settings['limit'] : 3;
		$orderby = $settings['orderby'] ? $settings['orderby'] : 'title';
		$order = $settings['order'] ? $settings['order'] : 'ASC';
  		$args = array(
            'post_type' => 'portfolio',
            'posts_per_page' => $limit,
            'orderby' => $orderby,
            'order' => $order,
            );
 
        $i = 0;

        $wp_query = new \WP_Query( $args ); ?>
		
		 
			<div class="row latest-news">

			<?php if ( $wp_query->have_posts() ) { ?>


				<?php while ( $wp_query->have_posts() ) : $wp_query->the_post();
				$i++;
                $id = $wp_query->post->ID;
                $thumb = get_post_thumbnail_id();
                $img_url = wp_get_attachment_url( $thumb,'doccure-blog-related-post');
                  
				$custom = get_post_custom( get_the_ID() );
				if ( has_post_thumbnail() ) { 
						 $url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()), 'thumbnail' );
					
			 }else{ 
					$url=get_template_directory_uri( ) ."/assets/images/dravatar-100x100.jpg";
			 } 

			 $contentElementor = "";

			 if (class_exists("\\Elementor\\Plugin")) {
 				 $pluginElementor = \Elementor\Plugin::instance();
				 $contentElementor = $pluginElementor->frontend->get_builder_content($id);
			 }
			 
			  if($contentElementor){ 
				 ?>
 
<div class="col-lg-4 col-md-6">
              <article>
                <div class="homeblog_img">
                  <a href="<?php the_permalink();?>"><img src="<?php echo $url;?>"/></a>
                </div>
                <div class="homeblog_content_block">
                  <div class="homeblog_desc">
                      <h5 class="mb-0"><a href="<?php the_permalink();?>"><?php the_title();?></a></h5>
                   </div>
                 </div>
              </article>
            </div>

			<?php } ?>

 <?php 
			 endwhile; // end of the loop. 
		} else {
			//do_action( "woocommerce_shortcode_{$loop_name}_loop_no_results" );
		}
        ?>
  </div>
        
         
        <?php 
    
		wp_reset_postdata();
	
	
		
	}


		protected function get_terms($taxonomy) {
			$taxonomies = get_terms( array( 'taxonomy' =>$taxonomy,'hide_empty' => false) );

			$options = [ '' => '' ];
			
			if ( !empty($taxonomies) ) :
				foreach ( $taxonomies as $taxonomy ) {
					$options[ $taxonomy->term_id ] = $taxonomy->name;
				}
			endif;

			return $options;
		}

		protected function get_posts() {
			$posts = get_posts( array( 'numberposts' => 99,) );

			$options = [ '' => '' ];
			
			if ( !empty($posts) ) :
				foreach ( $posts as $post ) {
					$options[ $post->ID ] = get_the_title($post->ID);
				}
			endif;

			return $options;
		}
	
}