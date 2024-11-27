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
class DoccureServices extends Widget_Base {

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
		return 'DoccureServices';
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
		return __( 'Doccure Services', 'doccure_elementor' );
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
		return 'eicon-gallery-grid';
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
		$categories	= elementor_get_taxonomies('doctors','specialities');
		$categories	= !empty($categories) ? $categories : array();
		//Content
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'doccure_elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
 

		$this->add_control(
			'limit',
			[
				'label' => __( 'Doctors to display', 'doccure_elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 21,
				'step' => 1,
				'default' => 4,
			]
		);
 
	 
		
	 
		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'DESC' =>  __( 'Descending', 'doccure_elementor' ),
					'ASC' =>  __(  'Ascending. ', 'doccure_elementor' ),
				
					
				],
			]
		);
					
		$this->end_controls_section();
		
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
 		$limit = $settings['limit'] ? $settings['limit'] : 4;
 		$order = $settings['order'] ? $settings['order'] : 'ASC';
 		   ?>
  <?php

			  $args = array(
              'post_type' => 'services',
              'post_status' => 'publish',
              'posts_per_page' => $limit,
              'orderby' => 'meta_value_num',
              'order' => $order
            );
            
            $my_query = null;
            $my_query = new \WP_query($args);
            ?>
            <div class="serviceslist_section">
                <div class="row">
            <?php
            if($my_query->have_posts()):
              while($my_query->have_posts()) : $my_query->the_post();
                $custom = get_post_custom( get_the_ID() );
                if ( has_post_thumbnail() ) { 
                  $url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()), 'thumbnail' );
               
              }else{ 
                $url=get_template_directory_uri( ) ."/assets/images/dravatar-100x100.jpg";
              } 
                ob_start();
                ?>
                  <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                      <article>
                        <div class="servicelist_img">
                        <a href="<?php the_permalink();?>">
                          <img src="<?php echo $url;?>"/>
                        </a>
                       </div>
                        <div class="service_body">
                          <h5><a href="<?php the_permalink();?>"><?php the_title();?></a></h5>
                        </div>
                      </article>
                   </div>
               <?php  
                      echo ob_get_clean();
            
              endwhile;
              wp_reset_postdata();
            else :
              esc_html( 'Sorry, no posts matched your criteria.' );
            endif;
            ?>
            </div>
            </div>
 
		 

 
 <?php  }
 
}