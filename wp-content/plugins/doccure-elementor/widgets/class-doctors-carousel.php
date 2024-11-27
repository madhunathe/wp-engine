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
class DoctorsCarousel extends Widget_Base {

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
		return 'DoctorsCarousel';
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
		return __( 'Doctors Carousel', 'doccure_elementor' );
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
		return 'eicon-carousel-loop';
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
					'style' => [ 'style_main'], 
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
				'default' => 4,
			]
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
		$link = $this->add_inline_editing_attributes( 'link', 'none' );
		$settings = $this->get_settings_for_display();
 		$limit = $settings['limit'] ? $settings['limit'] : 4;
		$orderby = $settings['orderby'] ? $settings['orderby'] : 'title';
		$order = $settings['order'] ? $settings['order'] : 'ASC';
		

		?>

<?php if($settings['style']=='style_main') { ?>
				<?php 
				$args = array(
					'post_type' => 'doctors',
					'post_status' => 'publish',
					'posts_per_page' =>$limit
				);
			  
				$my_query = null;
				$my_query = new \WP_query($args);
				?>
				 
				<div class="container">

				<div class="row">
						<div class="col-md-6 aos" data-aos="fade-up">
							<div class="section-header-one section-header-slider">
								<h2 class="section-title"><?php echo $settings['title']; ?></h2>
							</div>
						</div>
						<div class="col-md-6 aos" data-aos="fade-up">
							<div class="owl-nav slide-nav-2 text-end nav-control"></div>
						</div>
					</div>
					<div class="owl-carousel doctor-slider-one owl-theme aos" data-aos="fade-up">
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
						$dc_average_rating		= !empty( $feedback['dc_average_rating'] ) ? $feedback['dc_average_rating'] : 0 ;
						$name     = doccure_full_name(get_the_ID() );
						 $name      = !empty( $name ) ? $name : '';
						 $current_post_id = get_the_ID();

						// Subtract one from the current post ID
						$modified_post_id = $current_post_id - 1;

						// Get the consultant fee for the modified post ID
						//$starting_price = get_post_meta($modified_post_id, '_consultant_fee', true);
					  $starting_price  = doccure_get_post_meta( get_the_ID(), 'am_starting_price');
						//  $starting_price  = get_post_meta( get_the_ID(), '_consultant_fee');
					  	// $id_new = get_the_ID(  );
						//  print_r($id_new);
						 $am_specialities	= doccure_get_post_meta( get_the_ID(),'am_specialities');
						ob_start();
			  
						?>
						 <div class="item">
								<div class="doctor-profile-widget">
									<div class="doc-pro-img">
										<a href="<?php echo get_permalink(get_the_ID())?>">
											<div class="doctor-profile-img">
 												<img src="<?php echo $url;?>" class="img-fluid" alt=""/>
											</div>
										</a>
										<div class="doctor-amount">
											<span><?php doccure_price_format($starting_price);?></span>
										</div>
									</div>
									<div class="doc-content">
									
										<div class="doc-pro-info">
											<div class="doc-pro-name">
												<a href="<?php echo get_permalink(get_the_ID())?>"><?php echo $name;?></a>
 											 <?php //do_action('doccure_specilities_list_new',get_the_ID(),'1');
											 
											 if( !empty(get_the_ID()) ) {
												$specialities	= wp_get_post_terms(get_the_ID(), 'specialities', array( 'fields' => 'all' ) );
												ob_start();
												
												if(!empty($specialities) && !is_wp_error($specialities)){
													
													$sp_count		= 0;
													$show_number ='1';
													$tipsco_html	= '';
													$total_sp_count	= !empty($specialities) ? count($specialities) : 0;
													$remining_count	= $total_sp_count - $show_number;
 													?>
											
														<?php foreach( $specialities as $speciality ){
															if($sp_count=='1') {
																break;       
															}
															 $term_url	= get_term_link($speciality);
															?>
														
														<a href="<?php echo esc_url($term_url);?>"><p><?php echo esc_html($speciality->name);?></p></a>
														<?php	$sp_count++;
														}
														?>
									
																 
													<?php
												}
												
												echo ob_get_clean();
													
											}
											?> 
  											</div>
											<div class="reviews-ratings">
												<p>
											<span><i class="fas fa-star"></i><?php echo $dc_average_rating; ?></span>(<?php echo $total_rating; ?>)</p>
											</div>
										</div>
										<div class="doc-pro-location">
											<p><i class="feather-map-pin"></i> <?php echo $first_category = wp_get_post_terms( get_the_ID(), 'locations' )[0]->name;?></p>
										</div>
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
				</div>
			 

				<?php } else if($settings['style']=='style1') {  
  			$args_new = array(
				'post_type' => 'doctors',
				'post_status' => 'publish',
				'posts_per_page' =>$limit,
				'orderby' => $orderby,
				'order' => $order,
			);
		  
			$my_query = null;
			///$my_query = new WP_query($args_new);
			$my_query 		= get_posts( $args_new );
			$total_doctors		= count($my_query);
			?>
			
			<div class="home_doctor_slider row">
			<?php foreach ( $my_query as $my_querys ){?>
			<?php
   
					$custom = get_post_custom( $my_querys->ID );
					 if ( has_post_thumbnail( $my_querys->ID) ) { 
							  $url = wp_get_attachment_url( get_post_thumbnail_id($my_querys->ID), 'thumbnail' );
						 
				  }else{ 
						 $url=get_template_directory_uri( ) ."/assets/images/dravatar-100x100.jpg";
				  } 
				   
					$feedback			= get_post_meta($my_querys->ID,'review_data',true);
					$feedback			= !empty( $feedback ) ? $feedback : array();
					$total_rating		= !empty( $feedback['dc_total_rating'] ) ? $feedback['dc_total_rating'] : 0 ;
					$total_percentage	= !empty( $feedback['dc_total_percentage'] ) ? $feedback['dc_total_percentage'] : 0 ;
					$name			= doccure_full_name($my_querys->ID);
					 $name			= !empty( $name ) ? $name : '';
					 $starting_price	= doccure_get_post_meta( $my_querys->ID, 'am_starting_price');
 					 $am_specialities	= doccure_get_post_meta( get_the_ID(),'am_specialities');
 		  
					?>
					<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12" >
 		  <div class="col-lg-12">
			  <div class="doccure_doctor-thumb doccure-filter-img-wrapper">
				<img src="<?php echo $url;?>"/>
			  </div>
		  </div>
		  <div class="col-lg-12">
			  <div class="doccure_doctor-body">
				  <h5>
				  <a href="<?php echo get_permalink($my_querys->ID)?>"><?php echo  get_the_title($my_querys->ID); $name;?></a>
				  </h5>
				  <div class="ratings">
					  <div class="empty-stars"></div>
					  <div class="full-stars" style="width: <?php echo intval( $total_percentage );?>%"></div>
				  </div>
				  <span>
        <i class="feather-map-pin"></i>
        <?php echo $first_category = wp_get_post_terms( $my_querys->ID, 'locations' )[0]->name;?>
        </span>
				 
				<?php if( !empty( $starting_price ) ) { ?>
					  <div class="price-section"><i class="far fa-money-bill-alt"></i>&nbsp; <?php doccure_price_format($starting_price);?></div> <?php } ?>
		  
				<div id="button-widget" class="chw-widget-area widget-area" role="complementary">
				  <div class="row row-sm mt-3">
					<div class="col-6">
					<a href="<?php echo get_permalink($my_querys->ID)?>">
					  <button class="view-btn" tabindex="0"><?php esc_html_e('View Profile','doccure_elementor'); ?></button>
						</a>
					</div>
					<div class="col-6">
					<a href="<?php echo get_permalink($my_querys->ID)?>">
					  <button class="book-btn" tabindex="0"><?php esc_html_e('Book Now','doccure_elementor'); ?></button>
					  </a>
					</div>
				  </div>
				</div>
			  </div>
		  </div>
		  
				</div>
				<?php 
			}   ?>


			
			</div>
			<?php } else if($settings['style']=='style2') { ?>
				<?php 
				$args = array(
					'post_type' => 'doctors',
					'post_status' => 'publish',
					'posts_per_page' =>$limit
				);
			  
				$my_query = null;
				$my_query = new \WP_query($args);
				?>
				<div class="doctor-book-slider">
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
			  
						 $am_specialities	= doccure_get_post_meta( get_the_ID(),'am_specialities');
						ob_start();
			  
						?>
						<div>
						<div class="doctor-profile-widget" style="display: inline-block; width:100%">
						<a href="<?php echo get_permalink(get_the_ID())?>"><img class="home2_doctor_img" src="<?php echo $url;?>"/></a>
						<div class="pro-content">
						  <div class="provider-info">
							<div class="pro-icon">
							  <img src="<?php echo get_template_directory_uri();?>/assets/images/icon2.png"/>
							</div>
							<h3 class="mb-1">
					  <a href="<?php echo get_permalink(get_the_ID())?>"><?php echo $name;?>
					  <?php do_action('doccure_get_doctorverification_check',get_the_ID(),'');?>
					</a>
					  </h3>
					  
					  <?php //do_action('doccure_specilities_list_new',get_the_ID(),'1');
											 
											 if( !empty(get_the_ID()) ) {
												$specialities	= wp_get_post_terms(get_the_ID(), 'specialities', array( 'fields' => 'all' ) );
												ob_start();
												
												if(!empty($specialities) && !is_wp_error($specialities)){
													
													$sp_count		= 0;
													$show_number ='1';
													$tipsco_html	= '';
													$total_sp_count	= !empty($specialities) ? count($specialities) : 0;
													$remining_count	= $total_sp_count - $show_number;
 													?>
											
														<?php foreach( $specialities as $speciality ){
															if($sp_count=='1') {
																break;       
															}
															 $term_url	= get_term_link($speciality);
															?>
														
														<a href="<?php echo esc_url($term_url);?>"><p><?php echo esc_html($speciality->name);?></p></a>
														<?php	$sp_count++;
														}
														?>
									
																 
													<?php
												}
												
												echo ob_get_clean();
													
											}
											?> 
					 
					  <div>
					  <div class="ratings">
								<div class="empty-stars"></div>
								<div class="full-stars" style="width: <?php echo intval( $total_percentage );?>%"></div>
							</div>
					  </div>
					  <div class="content-info">
						<div class="social-info">
						
						</div>
						<div class="booking-btn">
						<button class="book-btn" tabindex="0"> <a href="<?php echo get_permalink(get_the_ID())?>">
						<?php esc_html_e('Book Appointment','doccure_elementor'); ?></a></button>
						</div>
					  </div>
						  </div>
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
				  <div class="slick-view-btn me-2">
							<a href="<?php echo $settings['link']; ?>" class="viewall"><?php esc_html_e ('View More','doccure_elementor'); ?> <i class="fas fa-arrow-right ms-2"></i></a>
						  </div>
						  <?php } ?>

				<?php } else if($settings['style']=='style3')  {?>
<?php
					
    $args = array(
        'post_type' => 'doctors',
        'post_status' => 'publish',
        'posts_per_page' =>$limit
    );
  
    $my_query = null;
    $my_query = new \WP_query($args);
    ?>
    <div class="best-doctor-slider">
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
             $am_specialities	= doccure_get_post_meta( get_the_ID(),'am_specialities');
  
            ob_start();
  
            ?>
            <div>
            <div class="best-doctor-widget" style="width: 100%; display: inline-block;">
              <div class="best-doctor-image">
                  <a href="<?php echo get_permalink(get_the_ID())?>"> <img src="<?php echo $url;?>"/></a>
                  <div class="overlay">
                    <div class="pro-icon">
                    <img src="<?php echo get_template_directory_uri();?>/assets/images/icon2.png"/>
                    </div>
                    <div class="social-info">
                    </div>
                  </div>
              </div>
              <div class="item-info">
                  <div class="doctor-verify-overlay d-flex">
                  <a href="javascript:void(0)" class="fav-icon" tabindex="0">
                    <span class="doctor-writter"><?php esc_html_e('Verified','doccure_elementor'); ?></span>
                    <?php do_action('doccure_get_doctorverification_check',get_the_ID(),'');?>
                  </a>
                  </div>
                  <div class="doctor-fav-btn">
                                              <a href="javascript:void(0)" class="fav-icon" tabindex="0">
                                                  <i class="far fa-bookmark"></i>
                                              </a>
                                   </div>
              </div>
              <div class="provider-info text-center">
                <h3> <a href="<?php echo get_permalink(get_the_ID())?>"><?php echo $name;?></a></h3>
				<?php //do_action('doccure_specilities_list_new',get_the_ID(),'1');
											 
											 if( !empty(get_the_ID()) ) {
												$specialities	= wp_get_post_terms(get_the_ID(), 'specialities', array( 'fields' => 'all' ) );
												ob_start();
												
												if(!empty($specialities) && !is_wp_error($specialities)){
													
													$sp_count		= 0;
													$show_number ='1';
													$tipsco_html	= '';
													$total_sp_count	= !empty($specialities) ? count($specialities) : 0;
													$remining_count	= $total_sp_count - $show_number;
 													?>
											
														<?php foreach( $specialities as $speciality ){
															if($sp_count=='1') {
																break;       
															}
															 $term_url	= get_term_link($speciality);
															?>
														
														<a href="<?php echo esc_url($term_url);?>"><p><?php echo esc_html($speciality->name);?></p></a>
														<?php	$sp_count++;
														}
														?>
									
																 
													<?php
												}
												
												echo ob_get_clean();
													
											}
											?> 
                 <div>
                <div class="ratings">
                    <div class="empty-stars"></div>
                    <div class="full-stars" style="width: <?php echo intval( $total_percentage );?>%"></div>
                </div>
                
                </div>
                <div class="content-info">
                    <div class="doctor-appointment-btn"><a class="btn btn-two" href="<?php echo get_permalink(get_the_ID())?>">
                    <?php esc_html_e('Book Appointment','doccure_elementor'); ?></a></div>
              </div>
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
				  <div class="   text-center d-flex justify-content-center viewall_btn ">
							<a  href="<?php echo $settings['link']; ?>" class="viewall vc_btn3 vc_btn3-style-custom"><?php esc_html_e ('View More','doccure_elementor'); ?></a>
						  </div>
						  <?php } ?>

 <?php }  else if($settings['style']=='style4') {?>
<?php 
					$args = array(
        'post_type' => 'doctors',
        'post_status' => 'publish',
        'posts_per_page' =>$limit
    );
  
    $my_query = null;
    $my_query = new \WP_query($args);
    ?>
    <div class="best-doctors-slider">
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
            
             $am_specialities	= doccure_get_post_meta( get_the_ID(),'am_specialities');
  
  
            ob_start();
  
            ?>
            <div>
            <div class="best-doctors-grid" style="width: 100%; display: inline-block;">
              <div class="best-doctors-img">
                <a href="<?php echo get_permalink(get_the_ID())?>"> <img src="<?php echo $url;?>"/></a>
              </div>
              <div class="best-doctors-info">
                <h3> <a href="<?php echo get_permalink(get_the_ID())?>"><?php echo $name;?></a></h3>
  
				<?php //do_action('doccure_specilities_list_new',get_the_ID(),'1');
											 
											 if( !empty(get_the_ID()) ) {
												$specialities	= wp_get_post_terms(get_the_ID(), 'specialities', array( 'fields' => 'all' ) );
												ob_start();
												
												if(!empty($specialities) && !is_wp_error($specialities)){
													
													$sp_count		= 0;
													$show_number ='1';
													$tipsco_html	= '';
													$total_sp_count	= !empty($specialities) ? count($specialities) : 0;
													$remining_count	= $total_sp_count - $show_number;
 													?>
											
														<?php foreach( $specialities as $speciality ){
															if($sp_count=='1') {
																break;       
															}
															 $term_url	= get_term_link($speciality);
															?>
														
														<a href="<?php echo esc_url($term_url);?>"><p><?php echo esc_html($speciality->name);?></p></a>
														<?php	$sp_count++;
														}
														?>
									
																 
													<?php
												}
												
												echo ob_get_clean();
													
											}
											?> 
              <?php if( !empty( $starting_price ) ) { ?>
                <h5 class="doctors-amount">$<?php echo $starting_price; ?></h5>
              <?php } else{ ?>
                <h5 class="doctors-amount">$0</h5>
             <?php  } ?>
             <div class="ratings">
                  <div class="empty-stars"></div>
                  <div class="full-stars" style="width: <?php echo intval( $total_percentage );?>%"></div>
              </div>
                  
                <div class="booking-btn">
                                          <a href="<?php echo get_permalink(get_the_ID())?>" class="btn" tabindex="0">
                                              <span>
											  <?php esc_html_e('Book Appointment','doccure_elementor'); ?> <i class="fas fa-arrow-right ms-2"></i>
                                              </span>
                                          </a>
                                      </div> 
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
					<?php } ?>
 		  <?php 
 
 		 
	}
 
 
}