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
class DoctorsServices extends Widget_Base {

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
		return 'DoctorsServices';
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
		return __( 'Doctors Services', 'doccure_elementor' );
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
			'style',
			[
				'label' => __( 'Style ', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => [
					'style1' =>  __( 'Style 1', 'doccure_elementor' ),
					'style2' =>  __(  'Style 2 ', 'doccure_elementor' ),
					'style3'=>  __(  'Style 3', 'doccure_elementor' ),
					'style4'=>  __(  'Style 4', 'doccure_elementor' ),
   				],
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
		$this->add_control(
			'link',
			array(
				'label'   => __( 'Link', 'doccure_elementor' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => '',
			)
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
 		  if($settings['style']=='style1') {   ?>

		  <?php 
		  
		  $args = array(
			'post_type' => 'services',
			'post_status' => 'publish',
			'posts_per_page' =>$limit 
		);
	  
		$my_query = null;
		$my_query = new \WP_query($args);
		?>
		<div class="features-section-slider">
		<?php
		if($my_query->have_posts()):
			while($my_query->have_posts()) : $my_query->the_post();
				$custom = get_post_custom( get_the_ID() );
				 if ( has_post_thumbnail() ) { 
						  $url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()), 'thumbnail' );
					 
			  }else{ 
					 $url=get_template_directory_uri( ) ."/assets/images/dravatar-100x100.jpg";
			  } 
			   
				$feedback     = get_post_meta(get_the_ID(),'review_data',true);
				$feedback     = !empty( $feedback ) ? $feedback : array();
				$total_rating   = !empty( $feedback['dc_total_rating'] ) ? $feedback['dc_total_rating'] : 0 ;
				$total_percentage = !empty( $feedback['dc_total_percentage'] ) ? $feedback['dc_total_percentage'] : 0 ;
				$name     = doccure_full_name(get_the_ID() );
				 $name      = !empty( $name ) ? $name : '';
				 $starting_price  = doccure_get_post_meta( get_the_ID(), 'am_starting_price');
				
				 if( class_exists('Dynamic_Featured_Image') ) {
				  global $dynamic_featured_image;
				  $featured_images = $dynamic_featured_image->get_featured_images( get_the_ID()) ;
	  
				  $imgsrc = $featured_images[0]['full'];
				  
				  //You can now loop through the image to display them as required
			  }
	  
				ob_start();
	  
				?>
				   <div>
					 <div class="features-grid hvr-bounce-to-bottom" style="width: 100%;display: inline-block;">
						<div class="features-cricle">
						 <div class="features-round">
						   <img src="<?php echo $imgsrc;?>"/>
					</div>
					</div>
				  <p><?php echo $name;?></p>
				</div>
			   </div>
				
		   
				<?php 
					  echo ob_get_clean();
	  
			endwhile;
			wp_reset_postdata();
		else :
		_e( 'Sorry, no posts matched your criteria.' );
		endif;
		?>
		</div>
	  
		<?php if ( !empty($settings['link']) ) { ?>
		  <div class="slick-view-btn me-2">
					<a href="<?php echo $settings['link']; ?>" class="viewall"><?php esc_html_e ('View More','doccure_elementor'); ?> <i class="fas fa-arrow-right ms-2"></i></a>
				  </div>
   				 <?php } ?> 
	<?php } else if($settings['style']=='style2') {?>
		<?php 
		
		$args = array(
        'post_type' => 'services',
        'post_status' => 'publish',
        'posts_per_page' =>$limit 
    );
  
    $my_query = null;
    $my_query = new \WP_query($args);
    ?>
    <div class="row clinic-row">
    <?php
    if($my_query->have_posts()):
        while($my_query->have_posts()) : $my_query->the_post();
            $custom = get_post_custom( get_the_ID() );
             if ( has_post_thumbnail() ) { 
                      $url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()), 'thumbnail' );
                 
          }else{ 
                 $url=get_template_directory_uri( ) ."/assets/images/dravatar-100x100.jpg";
          } 
           
            $feedback			= get_post_meta(get_the_ID(),'review_data',true);
            $feedback			= !empty( $feedback ) ? $feedback : array();
            $total_rating		= !empty( $feedback['dc_total_rating'] ) ? $feedback['dc_total_rating'] : 0 ;
            $total_percentage	= !empty( $feedback['dc_total_percentage'] ) ? $feedback['dc_total_percentage'] : 0 ;
            $name			= doccure_full_name(get_the_ID() );
             $name			= !empty( $name ) ? $name : '';
             $starting_price	= doccure_get_post_meta( get_the_ID(), 'am_starting_price');
            
             if( class_exists('Dynamic_Featured_Image') ) {
              global $dynamic_featured_image;
              $featured_images = $dynamic_featured_image->get_featured_images( get_the_ID()) ;
  
              $imgsrc = $featured_images[0]['full'];
              
              //You can now loop through the image to display them as required
          }
  
            ob_start();
  
            ?>
            
            <div class="col-lg-4 col-md-6 d-flex clinic-main-grid">
                <div class="clinic-grid w-100 hvr-bounce-to-right">
                    <img src="<?php echo $imgsrc;?>"/>
                    <h4><?php echo $name;?></h4>
                </div>
             </div>
            <?php 
                        echo ob_get_clean();
  
        endwhile;
        wp_reset_postdata();
    else :
    _e( 'Sorry, no posts matched your criteria.' );
    endif;
    ?>
    </div>
	
 <?php } else if($settings['style']=='style3') {?>

<?php
	
    $args = array(
        'post_type' => 'services',
        'post_status' => 'publish',
        'posts_per_page' =>$limit 
    );
  
    $my_query = null;
    $my_query = new \WP_query($args);
    ?>
    <div class="features-clinic-slider-four">
    <?php
    if($my_query->have_posts()):
        while($my_query->have_posts()) : $my_query->the_post();
            $custom = get_post_custom( get_the_ID() );
             if ( has_post_thumbnail() ) { 
                      $url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()), 'thumbnail' );
                 
          }else{ 
                 $url=get_template_directory_uri( ) ."/assets/images/dravatar-100x100.jpg";
          } 
           
            $feedback			= get_post_meta(get_the_ID(),'review_data',true);
            $feedback			= !empty( $feedback ) ? $feedback : array();
            $total_rating		= !empty( $feedback['dc_total_rating'] ) ? $feedback['dc_total_rating'] : 0 ;
            $total_percentage	= !empty( $feedback['dc_total_percentage'] ) ? $feedback['dc_total_percentage'] : 0 ;
            $name			= doccure_full_name(get_the_ID() );
             $name			= !empty( $name ) ? $name : '';
             $starting_price	= doccure_get_post_meta( get_the_ID(), 'am_starting_price');
  
            ob_start();
  
            ?>
            <div>
              <div class="features-clinic-grid">
                <div class="features-clinic-img">
                    <a href="<?php echo get_permalink(get_the_ID())?>"> <img src="<?php echo $url;?>"/></a>
                </div>
                <div class="feature-clinic-overlay">
                  <p><?php echo $name;?></p>
                </div>
  
              </div>
        </div>
        
            <?php 
                        echo ob_get_clean();
  
        endwhile;
        wp_reset_postdata();
    else :
    _e( 'Sorry, no posts matched your criteria.' );
    endif;
    ?>
    </div>
    <?php if ( !empty($settings['link']) ) { ?>
          <div class="text-end">
                  <a href="<?php echo $settings['link']; ?>" class="btn btn-one"><?php esc_html_e ('View More','doccure_elementor'); ?></a>
              </div>

			  <?php } ?>

	<?php } else if($settings['style']=='style4'){ ?>

		<?php
		$args = array(
        'post_type' => 'services',
        'post_status' => 'publish',
        'posts_per_page' => $limit ,
        'orderby' => 'meta_value_num',
        'order' => 'DESC'
      );
      
      $my_query = null;
      $my_query = new \WP_query($args);
      ?>
      <div class="about_services_section">
          <div class="row">
      <?php
      if($my_query->have_posts()):
        $i=0;
        while($my_query->have_posts()) : $my_query->the_post();
          $custom = get_post_custom( get_the_ID() );
          ob_start();
          $i++;
          ?>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                <article>
                  <span class="number">0<?php echo $i;?></span>
                  <h5><a href="<?php the_permalink();?>"><?php the_title();?></a></h5>
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

 <?php  } ?>
 <?php  }
 
}