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
class HomeHeroOne extends Widget_Base {

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
		return 'HomeHeroOne';
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
		return __( 'Doccure Hero', 'doccure_elementor' );
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
				'default' => __( 'Professional Services For Your Home & Commercial', 'doccure_elementor' ),
			)
		);	

		$this->add_control(
			'subtitle',
			array(
				'label'   => __( 'Sub-Title', 'doccure_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Search From 100 Awesome Verified Ads', 'doccure_elementor' ),
			)
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
  		?>
 
<!-- Banner
================================================== -->
	

<section class="section section-search elementor_banner1" <?php if(isset($settings['shape_1']['url']) && !empty(isset($settings['shape_1']['url']))){ ?> style="background: #f9f9f9 url(<?php echo esc_url($settings['shape_1']['url']); ?>) no-repeat bottom center;" 	<?php } ?> >
<div class="container-fluid">
<div class="banner-wrapper">
<div class="banner-header text-center aos aos-init aos-animate" data-aos="fade-up">
<h1><?php echo $settings['title']; ?></h1>
<p><?php  echo $settings['subtitle']; ?></p>
</div>
 <div class="search-box aos aos-init aos-animate" data-aos="fade-up">
 <?php
 global $doccure_options;
    $hide_location		= !empty($doccure_options['hide_location']) ? $doccure_options['hide_location'] : 'no';
    $search_settings	= !empty( $doccure_options['search_form'] ) ? $doccure_options['search_form'] : '';
    $search_option		= !empty($doccure_options['search_type']) ? $doccure_options['search_type'] : '';
    $search_type		= !empty($doccure_options['search_type']) ? $doccure_options['search_type'] : '';
	$search_page		= doccure_get_search_page_uri('doctors'); 
  
    ob_start();
    if( !empty($search_settings) ){?>
    <div class="container">
 
    <div class="sidebar_search dc-innerbanner-holder dc-haslayout dc-open dc-opensearchs <?php echo esc_attr($hide_location);?>">
                  <form action="<?php echo $search_page;?>" method="get" id="search_form">
                              <div class="dc-innerbanner">
                                      <div class="dc-formtheme dc-form-advancedsearch">
                                          <div class="row">
                      <?php if( !empty($hide_location) && $hide_location === 'no'){?>
                                                  <div class="form-group search-location col-md-4">
                                                    <div class="dc-select">
                                                          <?php do_action('doccure_get_search_locations');?>
                                                      </div>
   
                                                  </div>
  
                                              <?php }?>
                        
                                              <div class="form-group search-info col-md-6">
                                                  <?php do_action('doccure_get_search_text_field');?>
                                               </div>
  
                                              <div class="dc-btnarea col-md-2">
                                                  <input type="submit" class="dc-btn" value="<?php esc_attr_e('Search','doccure');?>">
                                              </div>
                      </div>
                                      </div>
                                      </div>
                              
                  </form>
              </div>
    
    </div>
  <?php
    }
        echo ob_get_clean();
		?>
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