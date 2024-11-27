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
class Testimonials extends Widget_Base {

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
		return 'doccure-testimonials';
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
		return __( 'Doccure Testimonials', 'doccure_elementor' );
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
		return 'eicon-testimonial-carousel';
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
 // 'title' =>'We collect reviews from our customers so you can get an honest opinion of what an apartment is really like!',


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
			'style',
			[
				'label' => __( 'Style', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => [
					'style_main' =>  __( 'Style Main', 'doccure_elementor' ),
					'style1' =>  __( 'Style 1', 'doccure_elementor' ),
					'style2' =>  __(  'Style 2. ', 'doccure_elementor' ),
				
					
				],
			]
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'doccure_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'What Our Client Says', 'doccure_elementor' ),
				'condition' => [
					'style' => [ 'style_main'], 
				],
			)
		);

		$this->add_control(
			'subtitle',
			array(
				'label'   => __( 'Sub-title', 'doccure_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Testimonials', 'doccure_elementor' ),
				'condition' => [
					'style' => [ 'style_main'], 
				],
			)
		);

		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order by', 'plugin-domain' ),
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
				'hide_avatar',
				[
					'label' => __( 'Hide User photo', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'your-plugin' ),
					'label_off' => __( 'Hide', 'your-plugin' ),
					'return_value' => 'yes',
					'default' => 'no',
				]
			);
			$this->add_control(
				'hide_job',
				[
					'label' => __( 'Hide User Location', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'your-plugin' ),
					'label_off' => __( 'Hide', 'your-plugin' ),
					'return_value' => 'yes',
					'default' => 'no',
				]
			);	
			$this->add_control(
				'hide_username',
				[
					'label' => __( 'Hide User name', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'your-plugin' ),
					'label_off' => __( 'Hide', 'your-plugin' ),
					'return_value' => 'yes',
					'default' => 'no',
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
		
 ?>
<?php
if($settings['style']=='style_main'){
$args = array(
                'post_type' => 'testimonials',
                'post_status' => 'publish',
                'posts_per_page' => $limit,
			'orderby' => $orderby,
			'order' => $order
              );
              
              $my_query = null;
              $my_query = new \WP_query($args);
              ?>
 				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="testimonial-slider slick">
              <?php
              if($my_query->have_posts()):
                $i=0;
                while($my_query->have_posts()) : $my_query->the_post();
                  $custom = get_post_custom( get_the_ID() );
                  if ( has_post_thumbnail() ) { 
                    $url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()), 'thumbnail' );
                 
                }else{ 
                  $url=get_template_directory_uri( ) ."/assets/images/dravatar-100x100.jpg";
                } 
                  ob_start();
                  $i++;
                  ?>
                    <div class="testimonial-grid">
									<div class="testimonial-info">
										<div class="testimonial-img">
 											<img src="<?php echo  $url;?>" class="img-fluid" alt=""/>
										</div>
										<div class="testimonial-content">
											<div class="section-header section-inner-header testimonial-header">
												<h5><?php echo $settings['subtitle']; ?></h5>
												<h2><?php echo $settings['title']; ?></h2>
											</div>
											<div class="testimonial-details">
												<p><?php the_content();?></p>
												<h6><span><?php the_title();?></span> 
												<?php if($settings['hide_job'] != "yes") { ?> 
													<?php the_excerpt(); ?> 
							                     <?php } ?>
												</h6>
											</div>
										</div>
									</div>
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
					</div>
				</div>
 
    	<?php } else if($settings['style']=='style2'){
$args = array(
                'post_type' => 'testimonials',
                'post_status' => 'publish',
                'posts_per_page' => $limit,
			'orderby' => $orderby,
			'order' => $order
              );
              
              $my_query = null;
              $my_query = new \WP_query($args);
              ?>
              <div class="servicespagetestimonial_section">
                  <div class="row">
              <?php
              if($my_query->have_posts()):
                $i=0;
                while($my_query->have_posts()) : $my_query->the_post();
                  $custom = get_post_custom( get_the_ID() );
                  if ( has_post_thumbnail() ) { 
                    $url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()), 'thumbnail' );
                 
                }else{ 
                  $url=get_template_directory_uri( ) ."/assets/images/dravatar-100x100.jpg";
                } 
                  ob_start();
                  $i++;
                  ?>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        <div class="servicepage_testimonial">
                          <div class="servicepage_testimonial_body">
                              <div class="d-flex align-items-center">
                                <div class="servicepage_testimonial_image"> 
                                    <img src="<?php echo  $url;?>"/>
                                </div>
                                <div class="testmoinal_info">
                                  <h5><?php the_title();?></h5>
                                </div>
                              </div>
                              <div class="d-block d-sm-flex mt-4">
                                <span class="fas fa-quote-left"></span>
                                <?php the_content();?>
                              </div>
                              
                          </div>
                         
                        </div>
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

    	<?php } else { ?>
		<?php
		$args = array(
            'post_type' => 'testimonials',
			'post_status' => 'publish',
            'posts_per_page' => $limit,
            'orderby' => $orderby,
            'order' => $order
             );
        $i = 0;

        $wp_query = new \WP_Query( $args ); ?>
		
		<div class="about_services_section">
              <div class="row">
			<?php if ( $wp_query->have_posts() ) { ?>
				 
  				<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); 
	                    $id = $wp_query->post->ID;
	                    $company = get_post_meta($id, 'doccure_pp_company', true); 
						
						$custom = get_post_custom( get_the_ID() );
						if ( has_post_thumbnail() ) { 
						  $url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()), 'thumbnail' );
					   
					  }else{ 
						$url=get_template_directory_uri( ) ."/assets/images/dravatar-100x100.jpg";
					  } 
					  ?>
	                    <!-- Item -->

						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                    <div class="about_testimonial">
                      <div class="about_testimonial_body">
                          <span class="fas fa-quote-left"></span>
                          <?php the_content();?>
                      </div>
                      <div class="d-flex align-items-center">
					  <?php if($settings['hide_avatar'] != "yes") { ?>
                        <div class="testmoinal_img">
                          <img src="<?php echo  $url;?>"/>
                        </div>
						<?php } ?>
                        <div class="testmoinal_info">
						<?php if($settings['hide_username'] != "yes") { ?>
                            <h5><?php the_title();?></h5>
							<?php } ?>
                            <?php if($settings['hide_job'] != "yes") { ?>
							<span class="designation"><?php the_excerpt(); ?></span>
							<?php } ?>
                            <span></span>
                        </div>
                      </div>
                    </div>
                 </div>

 
	            <?php 	endwhile;  // close the Loop   ?>
				<?php } else {
			//do_action( "woocommerce_shortcode_{$loop_name}_loop_no_results" );
		}
        ?>
			  </div>
		</div>
		<?php } ?>

    
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
			$posts = get_posts( array( 'numberposts' => -1, 'post_type' => 'testimonial') );

			$options = [ '' => '' ];
			
			if ( !empty($posts) ) :
				foreach ( $posts as $post ) {
					$options[ $post->ID ] = get_the_title($post->ID);
				}
			endif;

			return $options;
		}
	
}