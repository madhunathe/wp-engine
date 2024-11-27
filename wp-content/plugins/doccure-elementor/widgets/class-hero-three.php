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
class HomeHeroThree extends Widget_Base {

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
		return 'HomeHeroThree';
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
		return __( 'Doccure Hero 3', 'doccure_elementor' );
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
				'label'   => __( 'Search Doctor, Make an Appointment', 'doccure_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Search Doctor, Make an Appointment', 'doccure_elementor' ),
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
	

<section class="section hero_section3" <?php if(isset($settings['shape_1']['url']) && !empty(isset($settings['shape_1']['url']))){ ?> style="background: #f9f9f9 url(<?php echo esc_url($settings['shape_1']['url']); ?>) no-repeat bottom center;background-size: cover;" 	<?php } ?> >
<div class="container">
<div class="banner-wrapper ">
 
  <?php
 global $doccure_options;
 $hide_location		= !empty($doccure_options['hide_location']) ? $doccure_options['hide_location'] : 'no';
 $search_settings	= !empty( $doccure_options['search_form'] ) ? $doccure_options['search_form'] : '';
 $search_option		= !empty($doccure_options['search_type']) ? $doccure_options['search_type'] : '';
 $search_type		= !empty($doccure_options['search_type']) ? $doccure_options['search_type'] : '';

 $gender_search		= !empty($doccure_options['gender_search']) ? $doccure_options['gender_search'] : '';
 $search_page		= doccure_get_search_page_uri('doctors'); 
  
  
    ob_start();
    if( !empty($search_settings) ){?>
     <div class="banner-two-form row">
      <div class="app-form col-md-6">
        <div class="search-doctor">
            <div class="search-area">
                <h2><?php echo $settings['title']; ?></h2>
                <form action="<?php echo $search_page;?>" method="get" id="search_form">
                <div class="row">
                                  <div class="dc-innerbanner">
                                      <div class="dc-formtheme dc-form-advancedsearch">
                                          <fieldset>
                      <div class="col-12 col-md-12">
                      <?php if( !empty($hide_location) && $hide_location === 'no'){?>
                                                  <div class="form-group search-location col-md-12">
                            <label>Location</label>
                                                    <div class="dc-select">
                                                          <?php do_action('doccure_get_search_locations');?>
                                                      </div>
                                                  </div>
  
                                              <?php }?>
                      </div>
                      <div class="col-12 col-md-12">
                          <?php if( !empty($gender_search) ){?>
                              <div class="form-group" id="">
                              <label>Gender</label>
                              <div class="dc-select">
                                  <?php do_action('doccure_get_search_gender');?>
                                </div>
                              </div>
                            <?php } ?>
                        </div>
                        <div class="col-12 col-md-12">
                        <label>Speciality</label>
                          <div class="dc-select">
                                                          <?php do_action('doccure_get_search_speciality');?>
                                                      </div>
                        </div>
                                              <div class="dc-btnarea col-md-12">
                                                  <input type="submit" class="dc-btn" value="<?php esc_attr_e('Search','doccure');?>">
                                              </div>
                                          </fieldset>
                                      </div>
                                      </div>
                
              </div>
                  </form>
  
            </div>
        </div>
      </div>
    </div>
    
  <?php
    }
        echo ob_get_clean();
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