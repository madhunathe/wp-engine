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
class Doccure_Clients extends Widget_Base {

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
		return 'doccure-clients';
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
		return __( 'Doccure Clients', 'doccure_elementor' );
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
	// 	'latitude' 	=> '', 
		 	// 'longitude' 	=> '', 
		 	// 'background' => '',
	   //      'from_vs'  	=> '',
	   protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'doccure_elementor' ),
			)
		);
	
		$this->add_control(
			'show_title',
			[
				'label' => esc_html__( 'Show Title', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'textdomain' ),
				'label_off' => esc_html__( 'Hide', 'textdomain' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'doccure_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Our Partners', 'doccure_elementor' ),
				'condition' => [
					'show_title' => [ 'yes'], 
				],
			)
		);

		$this->add_control(
			'subtitle',
			array(
				'label'   => __( 'Sub-title', 'doccure_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'How it Works', 'doccure_elementor' ),
				'condition' => [
					'show_title' => [ 'yes'], 
				],
			)
		);


		$this->add_control(
			'gallery',
			[
				'label' => __( 'Add Images', 'doccure_elementor'  ),
				'type' => \Elementor\Controls_Manager::GALLERY,
				'default' => [],
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
		$settings = $this->get_settings_for_display();  ?>

	
		

	 

	   <!-- Partners Section -->
			<section class="partners-section">
				<div class="container">
				<?php  if($settings['show_title']=='yes')  {  ?>
					<div class="row">
						<div class="col-md-12">
							<div class="section-header-one text-center aos" data-aos="fade-up">
								<h2 class="section-title"><?php echo $settings['title']; ?></h2>
							</div>
						</div>
					</div>
					<?php }?>
					<div class="partners-info aos" data-aos="fade-up">
						<ul class="owl-carousel partners-slider d-flex">
							<?php 	if(!empty($settings['gallery'])){ 
								  foreach ($settings['gallery'] as $image) {?>
						    <li>
						    	<a href="javascript:void(0);">
						    		<img class="img-fluid" src="<?php echo $image['url']; ?>" alt="partners">
						    	</a>
						    </li>
							<?php } } ?>
						     
						 </ul>
					</div>
				</div>
			</section>
			<!-- /Partners Section -->

			<?php
 	}
 	protected function _content_template() {
		?>
		
		<div class="notification closeable {{{ settings.type }}}"><p>{{{ settings.content }}}</p><a class="close" href="#"></a></div>
		
		<?php
	}


}