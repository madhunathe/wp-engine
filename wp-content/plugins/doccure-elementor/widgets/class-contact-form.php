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
class ContactForm extends Widget_Base {

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
		return 'doccure-contact-form';
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
		return __( 'Contact Form', 'doccure_elementor' );
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
		return 'eicon-form-horizontal';
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
 // 'title' 		=> 'Service Title',
	// 	    'url' 			=> '',
	// 	    'url_title' 	=> '',

	// 	   	'icon'          => 'im im-icon-Office',
	// 	    'type'			=> 'box-1', // 'box-1, box-1 rounded, box-2, box-3, box-4'
	// 	    'with_line' 	=> 'yes',


		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'doccure_elementor' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'doccure_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Contact Details', 'doccure_elementor' ),
			)
		);	

		$this->add_control(
			'stitle',
			array(
				'label'   => __( 'Sub-title', 'doccure_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'GET IN TOUCH', 'doccure_elementor' ),
			)
		);	

		$this->add_control(
			'content',
			array(
				'label'   => __( 'Shortcode', 'doccure_elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'Content', 'doccure_elementor' ),
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

		$this->add_inline_editing_attributes( 'title', 'none' );
		$this->add_inline_editing_attributes( 'subtitle', 'none' );
		$content = $this->get_settings_for_display( 'content' );
		$content = do_shortcode( shortcode_unautop( $content ) );
 			  ?>
			<div class="contact_form_section">
				<div class="vc_column-inner">
				 <div class="wpb_wrapper">
				 <?php if($settings['stitle']!='') { ?>
						<div class="wpb_text_column "><p><?php echo $this->get_settings( 'stitle' ) ?></h2></p>
						<?php } ?>
						<?php if($settings['title']!='') { ?>
						<div class="section-title"><h2 class="title contact_spacing"><?php echo $this->get_settings( 'title' ) ?></h2></div>
						<?php } ?>
						
						<?php if($settings['content']!='') { ?>
						<div class="elementor-shortcode"><?php echo $content; ?></div>
						<?php } ?>
 				
 
				</div>
				</div>
			</div>

			<?php
			if(!empty($settings['url']['url'])){
				echo "</a>";
			}
		   ?>
 
 
 
	 
<?php 	}

	
}