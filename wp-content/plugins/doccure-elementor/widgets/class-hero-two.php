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
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Awesomesauce widget class.
 *
 * @since 1.0.0
 */
class HomeHeroTwo extends Widget_Base {

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
		return 'HomeHeroTwo';
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
		return __( 'Doccure Hero 2', 'doccure_elementor' );
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
		return 'eicon-gallery-group';
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
	protected function register_controls() {
   
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'doccure_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Search Doctor, Make an Appointment', 'doccure_elementor' ),
			)
		);	

		$this->add_control(
			'buttonone',
			array(
				'label'   => __( 'Button One', 'doccure_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Instant Operation & Appointment', 'doccure_elementor' ),
			)
		);
		$this->add_control(
			'buttontwo',
			array(
				'label'   => __( 'Button Two', 'doccure_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( '100% Expert Doctors', 'doccure_elementor' ),
			)
		);
		$this->add_control(
			'subtitle',
			array(
				'label'   => __( 'Sub-Title', 'doccure_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Discover the best doctors, clinics & hospitals in the city nearest to you.', 'doccure_elementor' ),
			)
		);
		

		$this->add_control(
			'shape_1',
			[
				'label' => __( 'Choose Image', 'dreamslms_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				]
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

		$this->add_inline_editing_attributes( 'title', 'none' );
		$this->add_inline_editing_attributes( 'subtitle', 'none' );
		$this->add_inline_editing_attributes( 'buttontwo', 'none' );
		$this->add_inline_editing_attributes( 'buttonone', 'none' );
  		?>
 
<!-- Banner
================================================== -->
	

<section class="section home-one-banner page-template-homepagetwo elementor_banner2" >


<div class="container">
	<div class="row">
		<div class="col-md-6 banner-details">
           <h2 class="banner-info"><?php echo $settings['title']; ?></h2>
     <div class="pagetwoinstant_text wpb_content_element">
		  <h4><img class="alignnone size-full " src="<?php echo get_site_url();?>/wp-content/uploads/2022/04/banner-check.png" alt="" width="26" height="26" /> <?php echo $settings['buttonone']; ?></h4>
	 </div>
	 <div class="pagetwoinstant_text wpb_content_element">
		  <h4><img class="alignnone size-full wp-image-3734" src="<?php echo get_site_url();?>/wp-content/uploads/2022/04/banner-check.png" alt="" width="26" height="26" /> <?php echo $settings['buttontwo']; ?></h4>
	 </div>
		  <p><?php  echo $settings['subtitle']; ?></p>
		</div>
		<div class="col-md-6">
		<?php if(isset($settings['shape_1']['url']) && !empty(isset($settings['shape_1']['url']))){ ?> 
			<img src="<?php echo esc_url($settings['shape_1']['url']); ?>" class="img-fluid dr-img">
			<?php } ?>
		</div>
	</div>
<div class="  row">
 
 <div class="search-box aos aos-init aos-animate col-md-12" data-aos="fade-up">
 <?php
  
  global $doccure_options;
  $hide_location		= !empty($doccure_options['hide_location']) ? $doccure_options['hide_location'] : 'no';
  $search_settings	= !empty( $doccure_options['search_form'] ) ? $doccure_options['search_form'] : '';
  $search_option		= !empty($doccure_options['search_type']) ? $doccure_options['search_type'] : '';
  $search_type		= !empty($doccure_options['search_type']) ? $doccure_options['search_type'] : '';
  $search_page		= doccure_get_search_page_uri('doctors'); 


   if( !empty($search_settings) ){?>
   	  <div class="appoinment-wrapper elemento_hometwohero">
  <div class="appoinment-box hometwo_space">
				<form action="<?php echo $search_page;?>" method="get" id="search_form">
			<div class="row">
			<?php if( !empty($hide_location) && $hide_location === 'no'){?>
				<div class="col-md-6">
				  <div class="form-group appoinment-location">
						<div class="dc-select">
							  <?php do_action('doccure_get_search_locations');?>
						  </div>
				</div>
			</div>
			<?php } ?>
			<?php if( !empty($hide_location) && $hide_location === 'no'){?>
				<div class="col-md-6 ">
				<?php }  else { ?>
				<div class="col-md-12 ">
					<?php } ?>
											<div class="appoinment-right">
												<div class="form-group appoinment-location">
						<?php do_action('doccure_get_search_text_field');?>
												</div>
												<div class="form-group">
						<input type="submit" class="dc-btn" value="<?php esc_attr_e('Search','doccure');?>">
												</div>
											</div>
										</div>
						</div>

			</div>
					
							
				</form>
			</div>
   </div>
<?php
  }
 ?>
</div>

</div>
</section>
 
 		<?php
		
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
	
}