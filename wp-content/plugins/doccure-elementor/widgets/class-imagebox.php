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
class ImageBox extends Widget_Base {

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
		return 'doccure-imagebox';
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
		return __( 'Box Image', 'doccure_elementor' );
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
		return 'eicon-image';
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

		//link
		//image
		//featured
		//term
		//style alternative-imagebox
		//
		//


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
				'default' => __( 'Title', 'doccure_elementor' ),
			)
		);	

		$this->add_control(
			
			'url',
			[
				'label' => __( 'Link','doccure_elementor' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'doccure_elementor' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);

		$this->add_control(
			'link_label',
			array(
				'label'   => __( 'Link Title', 'doccure_elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Book Now', 'doccure_elementor' ),
			)
		);	
		$this->add_control(
			'background',
			[
				'label' => __( 'Choose Background Image', 'doccure_elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				]
			]
		);
 
		$this->add_control(
			'style',
			[
				'label' => __( 'Style ', 'doccure_elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'alternative-imagebox',
				'options' => [
					'standard' => __( 'Standard', 'doccure_elementor' ),
					'alternative-imagebox' => __( 'Alternative', 'doccure_elementor' ),
				],
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
		$this->add_inline_editing_attributes( 'link_label', 'none' );
		
		$target = $settings['url']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['url']['nofollow'] ? ' rel="nofollow"' : '';
		$url = $settings['url']['url'];
  		?>
		
		<div class="looking_infoblock elementor_block">
		<?php if(isset($settings['background']['url']) && !empty(isset($settings['background']['url']))){ ?><img src="<?php echo $settings['background']['url']; ?>" alt="" class="img-fluid">
			<?php } ?>
<div class="lookingfor_content">
<div>
<h4 class=""><?php echo $settings['title']; ?></h4>
<p><a <?php echo 'href="' . esc_url($url) . '"' . $target . $nofollow; ?> class="btn book-btn1 px-3 py-2 mt-3 btn-one-light" tabindex="0"><?php echo $settings['link_label']; ?></a></p>
</div>
</div>
</div>
 	
		<?php
		//lik
	}

	protected function get_taxonomies() {
		$taxonomies = get_object_taxonomies( 'listing', 'objects' );

		$options = [ '' => '' ];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	protected function get_terms($taxonomy) {
		$taxonomies = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false) );

		$options = [ '' => '' ];
		
		if ( !empty($taxonomies) ) :
			foreach ( $taxonomies as $taxonomy ) {
				
				$options[ $taxonomy->term_id ] = $taxonomy->name;
			}
		endif;

		return $options;
	}
	
}