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
class HomeHeroMain extends Widget_Base {

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
		return 'HomeHeroMain';
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
		return __( 'Doccure Hero Main', 'doccure_elementor' );
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
 // 'title' 		=> 'Service Title',
	// 	    'url' 			=> '',
	// 	    'url_title' 	=> '',

	// 	   	'icon'          => 'im im-icon-Office',
	// 	    'type'			=> 'box-1', // 'box-1, box-1 rounded, box-2, box-3, box-4'
	// 	    'with_line' 	=> 'yes',
	// 	    
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
			'subtitle',
			array(
				'label'   => __( 'Sub-Title', 'doccure_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Discover the best doctors, clinic & hospital the city nearest to you.', 'doccure_elementor' ),
			)
		);

		$this->add_control(
			'button_label',
			array(
				'label'   => __( 'Button Label', 'doccure_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Start a Consult', 'doccure_elementor' ),
			)
		);

	 

		$this->add_control(
			'button_url',
			[
				'label' => __( 'Link','doccure_elementor' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'doccure_elementor' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
				],
 			]
		);

		$this->add_control(
			'shape_1',
			[
				'label' => __( 'Choose Background', 'dreamslms_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				]
			]
		);

		$this->add_control(
			'shape_2',
			[
				'label' => __( 'Image 1', 'dreamslms_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				]
			]
		);

		$this->add_control(
			'shape_3',
			[
				'label' => __( 'Image 2', 'dreamslms_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				]
			]
		);

		$this->add_control(
			'shape_4',
			[
				'label' => __( 'Image 3', 'dreamslms_elementor' ),
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


		if($settings['button_url']!='') {
			$target = $settings['button_url']['is_external'] ? ' target="_blank"' : '';
			$nofollow = $settings['button_url']['nofollow'] ? ' rel="nofollow"' : '';
		}
			 if(!empty($settings['button_url']['url'])){
				$full_url =  $settings['button_url']['url'];	
			} else {
				$full_url = '';
			}

		$this->add_inline_editing_attributes( 'title', 'none' );
		$this->add_inline_editing_attributes( 'subtitle', 'none' );
  		?>
 
<!-- Banner
================================================== -->
	

<section class="banner-section main_manner">
<div class="container">
<div class="row align-items-center">
<div class="col-lg-6">
<div class="banner-content aos aos-init aos-animate" data-aos="fade-up">
<h1><?php echo $settings['title']; ?></h1>
<img src="<?php echo get_template_directory_uri(); ?>/assets/images/header-icon.svg" class="header-icon" alt="header-icon">
<p><?php  echo $settings['subtitle']; ?></p>
<a href="<?php echo  $full_url; ?>" <?php echo  $target; ?> <?php echo  $nofollow;?> class="btn"><?php echo $settings['button_label']; ?></a>
<div class="banner-arrow-img">
<img src="<?php echo get_template_directory_uri(); ?>/assets/images/down-arrow-img.png" class="img-fluid" alt="">
</div>
</div>

<?php
 global $doccure_options;
    $hide_location		= !empty($doccure_options['hide_location']) ? $doccure_options['hide_location'] : 'no';
    $search_settings	= !empty( $doccure_options['search_form'] ) ? $doccure_options['search_form'] : '';
    $search_option		= !empty($doccure_options['search_type']) ? $doccure_options['search_type'] : '';
    $search_type		= !empty($doccure_options['search_type']) ? $doccure_options['search_type'] : '';
	$search_page		= doccure_get_search_page_uri('doctors'); 
  
  
    ob_start();
    if( !empty($search_settings) ){?>
<div class="search-box-one hero_banner_main aos aos-init aos-animate" data-aos="fade-up">
<form action="<?php echo $search_page; ?>" method="get" id="search_form">
<?php if( !empty($hide_location) && $hide_location === 'no'){?>
<div class="search-input search-map-line">
<i class="feather-map-pin"></i>
<div class="form-group mb-0">

 
<div class="dc-select">
   <?php do_action('doccure_get_search_locations');?>
 </div>


 
</div>
</div>
<?php }?>
<div class="search-input search-line1">
 <div class="form-group search-info mb-0">
 <?php do_action('doccure_get_search_text_field');?>

 <?php if( !empty($hide_location) && $hide_location === 'no'){?>

 <i class="feather-search bficon"></i>
 <?php }?>
 </div>

</div>
 
<div class="form-search-btn">
<input type="submit" class=" btn" value="<?php esc_attr_e('Search','doccure');?>">

</div>
</form>
</div>

<?php
    }
        echo ob_get_clean();
		?>

</div>
<div class="col-lg-6">
<div class="banner-img aos aos-init aos-animate" data-aos="fade-up">
<?php if(isset($settings['shape_1']['url']) && !empty(isset($settings['shape_1']['url']))){ ?> 
<img src="<?php echo esc_url($settings['shape_1']['url']); ?>" class="img-fluid" alt="">
<?php } ?>
 
<div class="banner-img1">
<?php if(isset($settings['shape_2']['url']) && !empty(isset($settings['shape_2']['url']))){ ?> 
<img src="<?php echo esc_url($settings['shape_2']['url']); ?>" class="img-fluid" alt="">
<?php } ?>
 </div>
<div class="banner-img2">

<?php if(isset($settings['shape_3']['url']) && !empty(isset($settings['shape_3']['url']))){ ?> 
<img src="<?php echo esc_url($settings['shape_3']['url']); ?>" class="img-fluid" alt="">
<?php } ?>
</div>
<div class="banner-img3">
<?php if(isset($settings['shape_4']['url']) && !empty(isset($settings['shape_4']['url']))){ ?> 
<img src="<?php echo esc_url($settings['shape_4']['url']); ?>" class="img-fluid" alt="">
<?php } ?>
</div>
</div>
</div>
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