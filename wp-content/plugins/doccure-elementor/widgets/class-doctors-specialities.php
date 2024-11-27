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
class DoctorsSpecialities extends Widget_Base {

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
		return 'DoctorsSpecialities';
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
		return __( 'Doctors Specialities', 'doccure_elementor' );
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
					'style_main' =>  __( 'Style Main', 'doccure_elementor' ),
					'style1' =>  __( 'Style 1', 'doccure_elementor' ),
					'style2' =>  __(  'Style 2 ', 'doccure_elementor' ),
					'style3'=>  __(  'Style 3', 'doccure_elementor' ),
					'style4'=>  __(  'Style 4', 'doccure_elementor' ),
					'style5'=>  __(  'Style 5', 'doccure_elementor' ),
  				],
			]
		);

 		$this->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'doccure_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Title', 'doccure_elementor' ),
				'condition' => [
					'style' => [ 'style_main','style5'],
				],
			)
		);	

		$this->add_control(
			'stitle',
			array(
				'label'   => __( 'Sub-Title', 'doccure_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
incididunt ut labore et dolore magna aliqua.', 'doccure_elementor' ),
				'condition' => [
					'style' => [ 'style5'],
				],
			)
		);	
	
  
		$this->add_control(
			'limit',
			[
				'label' => __( 'Doctors to display', 'doccure_elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 21,
				'step' => 1,
				'default' => 6,
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
			'button',
			array(
				'label'   => __( 'Button Label', 'doccure_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'See All Specialities', 'doccure_elementor' ),
				'condition' => [
					'style' => [ 'style_main'], 
				],
			)
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
		 if($settings['style']=='style_main') {  		 
			// Get the taxonomy's terms
		  $terms = get_terms(
			array(
				'taxonomy'   => 'specialities',
				'hide_empty' => false,
				'number'        => $limit,
			)
		  );
		  
			?>


<section class="specialities-section-one">
				<div class="container">
					<div class="row">
						<div class="col-md-6 aos" data-aos="fade-up">
							<div class="section-header-one section-header-slider">
								<h2 class="section-title"><?php echo $settings['title']; ?></h2>
							</div>
						</div>
						<div class="col-md-6 aos" data-aos="fade-up">
							<div class="owl-nav slide-nav-1 text-end nav-control"></div>
						</div>
					</div>
					<div class="owl-carousel specialities-slider-one owl-theme aos" data-aos="fade-up">
						

						<?php
		   // Check if any term exists
			if ( ! empty( $terms ) && is_array( $terms ) ) {
		  
			  foreach ( $terms as $term ) {
			   //echo "<pre>";
			  //print_r($term);
		  
				$logo 			= get_term_meta( $term->term_id, 'logo', true );
			   // echo "<pre>";
			   // print_r($logo);
				   $current_logo	= !empty( $logo['url'] ) ? $logo['url'] : '';
				   $attachment_id	= !empty( $logo['attachment_id'] ) ? $logo['attachment_id'] : '';
			   
					ob_start();
		  
					?>

<div class="item">
							<div class="specialities-item">
								<div class="specialities-img">
									<?php if( $logo['url']) { ?> 
									<span><img src="<?php echo $logo['url'];?>" alt=""></span>
									<?php } ?>
								</div>
								<a href="<?php echo home_url();?>/specialities/<?php echo $term->slug;?>"><p><?php echo $term->name; ?></p></a>
							</div>
						</div>

 					<?php 
						echo ob_get_clean();
			  }
		  
			  }
			?>

						   
						  
						 
					</div>
					<div class="specialities-btn aos" data-aos="fade-up">
						<a href="<?php echo $settings['link']; ?>" class="btn">
						<?php echo $settings['button']; ?>
						</a>
					</div>
				</div>
			</section>

		 
		
		<?php } else if($settings['style']=='style5') {  		 
			// Get the taxonomy's terms
		  $terms = get_terms(
			array(
				'taxonomy'   => 'specialities',
				'hide_empty' => false,
				'number'        => $limit,
			)
		  );
		  
			?>


<section class="specialities-section-one">
				<div class="container">
					<div class="row">

					
  						<div class="col-md-12 aos aos-init aos-animate" data-aos="fade-up">

						  <div class="section-header text-center aos aos-init aos-animate" data-aos="fade-up">
<h2><?php echo $settings['title']; ?></h2>
<p class="sub-title"><?php echo $settings['stitle']; ?></p>
</div>
 
					</div>

					</div>

					<div class=" specialities-slider slider">


					<?php
		   // Check if any term exists
			if ( ! empty( $terms ) && is_array( $terms ) ) {
		  
			  foreach ( $terms as $term ) {
			   //echo "<pre>";
			  //print_r($term);
		  
				$logo 			= get_term_meta( $term->term_id, 'logo', true );
			   // echo "<pre>";
			   // print_r($logo);
				   $current_logo	= !empty( $logo['url'] ) ? $logo['url'] : '';
				   $attachment_id	= !empty( $logo['attachment_id'] ) ? $logo['attachment_id'] : '';
			   
					ob_start();
		  
					?>


<div class="speicality-item text-center">
<?php if( $logo['url']) { ?> 
<div class="speicality-img">
<img src="<?php echo $logo['url'];?>" class="img-fluid" alt="Speciality">
<span><i class="fa fa-circle" aria-hidden="true"></i></span>
</div>
<?php } ?>
<p><?php echo $term->name; ?></p>
</div>

 

 					<?php 
						echo ob_get_clean();
			  }
		  
			  }
			?>

  
					</div>
   				</div>
			</section>

		 
		
		<?php } else if($settings['style']=='style1') {  		 
    // Get the taxonomy's terms
  $terms = get_terms(
    array(
        'taxonomy'   => 'specialities',
        'hide_empty' => false,
        'number'        => $limit,
    )
  );
  
    ?>
    <div class="row justify-content-center">
    <?php
   // Check if any term exists
    if ( ! empty( $terms ) && is_array( $terms ) ) {
  
      foreach ( $terms as $term ) {
       //echo "<pre>";
      //print_r($term);
  
        $logo 			= get_term_meta( $term->term_id, 'logo', true );
       // echo "<pre>";
       // print_r($logo);
              $current_logo	= !empty( $logo['url'] ) ? $logo['url'] : '';
            $attachment_id	= !empty( $logo['attachment_id'] ) ? $logo['attachment_id'] : '';
       
            ob_start();
  
            ?>
              <div class="col-lg-3 col-md-6 aos aos-init aos-animate">
              <div class="service-grid">
                              <div class="effect-oscar">
                <img src="<?php echo get_taxonomy_image($term->term_id);?>" class="services-img" alt="" />
                                  <div class="services-caption">
                                      <div class="services-inner">
                                          <div class="service-grid-icon">
										  <?php if( $logo['url']) { ?> 
                         <img src="<?php echo $logo['url'];?>" class="specalitist_logo" alt="" />
						 <?php } ?>
                                          </div>
                                          <p><?php echo $term->name; ?></p>
                                          <a href="<?php echo home_url();?>/specialities/<?php echo $term->slug;?>" class="service-link">
                                              <i class="fas fa-arrow-right"></i>
                                          </a>
                                      </div>
                                  </div>			
                              </div>
                          </div>
                
              </div>
               
            <?php 
                echo ob_get_clean();
      }
  
      }
    ?>
    </div>

<?php } else if($settings['style']=='style2') { ?>

	<?php 
	
    // Get the taxonomy's terms
	$terms = get_terms(
		array(
			'taxonomy'   => 'specialities',
			'hide_empty' => false,
			'number'        => $limit
		)
	  );
	  
		?>
		<div class="row">
		<?php
	   // Check if any term exists
		if ( ! empty( $terms ) && is_array( $terms ) ) {
	  
		  foreach ( $terms as $term ) {
		   //echo "<pre>";
		  //print_r($term);
	  
			$logo 			= get_term_meta( $term->term_id, 'logo', true );
		   // echo "<pre>";
		   // print_r($logo);
				  $current_logo	= !empty( $logo['url'] ) ? $logo['url'] : '';
				$attachment_id	= !empty( $logo['attachment_id'] ) ? $logo['attachment_id'] : '';
		   
				ob_start();
	  
				?>
				   <div class="col-lg-4 col-md-4">
					  <div class="high-service-box">
						  <div class="service-box-inner">
						  <?php if( $logo['url']) { ?> 
							<div class="high-service-img">
								
								<span>
									
								  <img src="<?php echo $logo['url'];?>" class="specalitist_logo" alt="" />
								</span>
							 </div>
							 <?php } ?>
							  <div class="overlay">
							  <?php if( $logo['url']) { ?> 
								<div class="pro-icon">
								  <img src="<?php echo $logo['url'];?>" class="specalitist_logo" alt="" />
								</div>
								<?php  } ?>
								<div class="pro-text">
								  <h4><?php echo $term->name; ?></h4>
								  <p><?php echo $term->count;?> <?php esc_html_e('Doctors','doccure_elementor'); ?></p>
								</div>
                  </div> <!-- overlay -->
							  <h4 class="high-service-head"><?php echo $term->name; ?></h4>
							  <p class="high-service-text"><?php echo $term->count;?> <?php esc_html_e('Doctors','doccure_elementor'); ?> </p>
						  </div>
					  </div>
					</div>
			  
				<?php 
					echo ob_get_clean();
		  }
	  
		  }
		?>
		</div>

		<?php if ( !empty($settings['link']) ) { ?>
				  <div class=" text-center viewall_btn d-flex justify-content-center ">
							<a href="<?php echo $settings['link']; ?>" class="viewall vc_btn3 vc_btn3-style-custom"><?php esc_html_e ('View More','doccure_elementor'); ?> </a>
						  </div>
   <?php } ?>

		
<?php }  else if($settings['style']=='style3') { ?>

<?php 
// Get the taxonomy's terms
  $terms = get_terms(
    array(
        'taxonomy'   => 'specialities',
        'hide_empty' => false,
    )
  );
  
    ?>
    <div class="row justify-content-center">
    <?php
   // Check if any term exists
    if ( ! empty( $terms ) && is_array( $terms ) ) {
  
      foreach ( $terms as $term ) {
       //echo "<pre>";
      //print_r($term);
  
        $logo 			= get_term_meta( $term->term_id, 'logo', true );
       // echo "<pre>";
       // print_r($logo);
              $current_logo	= !empty( $logo['url'] ) ? $logo['url'] : '';
            $attachment_id	= !empty( $logo['attachment_id'] ) ? $logo['attachment_id'] : '';
       
            ob_start();
  
            ?>
              <div class="col-lg-3 col-md-6 d-flex aos aos-init aos-animate">
                  <div class="clinic-grid-four w-100">
                      <div class="clinic-img">
                      
                          <img src="<?php echo get_taxonomy_image($term->term_id);?>" class="specalitist_logo" alt="" />
                          <div class="clinic-content">
                                                <h4><?php echo $term->name; ?></h4>
                                          </div>
                          <div class="clinic-icon-inner">
						  <?php if( $logo['url']) { ?> 
                            <div class="clinic-box-img">
                              <img src="<?php echo $logo['url'];?>" class="specalitist_logo" alt="" />
                            </div>
							<?php } ?>
                          </div>
                      </div>
                      <div class="overlay">
                        <div class="clinic-cricle">
						<?php if( $logo['url']) { ?> 
                            <div class="clinic-round">
                            <img src="<?php echo $logo['url'];?>" class="specalitist_logo" alt="" />
                            </div>
							<?php } ?>
                        </div>
                        <h4><?php echo $term->name; ?></h4>
                      </div>
                   </div>
              </div>
               
            <?php 
                echo ob_get_clean();
      }
  
      }
    ?>
    </div>
    
<?php } else if($settings['style']=='style4') {?>
<?php 
    $args = array(
        'post_type' => 'services',
        'post_status' => 'publish',
        'posts_per_page' => $limit
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
    
          <div class="text-end">
                  <a href="#" class="btn btn-one"><?php esc_html_e ('View More','doccure_elementor'); ?></a>
              </div>
	<?php } else { ?>
	<?php } ?>

 		  <?php 
 
 		 
	}
 
 
}