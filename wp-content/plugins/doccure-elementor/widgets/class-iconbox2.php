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
class IconBoxTwo extends Widget_Base {

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
		return 'doccure-iconboxtwo';
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
		return __( 'Icon Box 2', 'doccure_elementor' );
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
		return 'eicon-icon-box';
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
				'default' => __( 'Title', 'doccure_elementor' ),
			)
		);	

		$this->add_control(
			'content',
			array(
				'label'   => __( 'Content', 'doccure_elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'Content', 'doccure_elementor' ),
			)
		);	

		$this->add_control(
			'style',
			[
				'label' => __( 'Type of iconbox', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'top' =>  __( 'Icon on top', 'doccure_elementor' ),
					'left' =>  __(  'Icon on the left. ', 'doccure_elementor' ),
				
					
				],
			]
		);

		$this->add_control(
			'styletype',
			[
				'label' => __( 'Styles', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => [
					'style1' =>  __( 'Style 1', 'doccure_elementor' ),
					'style2' =>  __(  'Style 2', 'doccure_elementor' ),
					'style3' =>  __(  'Style 3', 'doccure_elementor' ),
  				],
			]
		);


		$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'doccure_elementor'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
			]
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
		$this->add_inline_editing_attributes( 'styletype', 'none' );
		
		$target = $settings['url']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['url']['nofollow'] ? ' rel="nofollow"' : '';

		if($settings['style'] == 'left' && $settings['styletype'] == 'style1')   {
			if(!empty($settings['url']['url'])){
				echo '<a href="' . $settings['url']['url'] . '"' . $target . $nofollow . '>';	
			} ?>
			
				 <div class="icon-box-version1  ">
				 		<?php if(isset($settings['icon']['library']) && $settings['icon']['library'] == 'svg') { ?>
				 			<i class="doccure-svg-icon-box"><?php \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );  ?></i>
				 		<?php } else {
				 			 \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); 
				 		} ?>
						<?php if($settings['title']!='') { ?>
						<h2><?php echo $this->get_settings( 'title' ) ?></h2>
						<?php } ?>
				<?php if($settings['content']!='') { ?>
						<div class="wpb_text_column "><p><?php echo $this->get_settings( 'content' ) ?></p></div>
						<?php } ?>
  <div class="big_icon">
						<?php if(isset($settings['icon']['library']) && $settings['icon']['library'] == 'svg') { ?>
				 			<i class="doccure-svg-icon-box"><?php \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );  ?></i>
				 		<?php } else {
				 			 \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); 
				 		} ?>
  </div>
				</div>

			<?php
			if(!empty($settings['url']['url'])){
				echo "</a>";
			}
		} else if($settings['style'] == 'left' && $settings['styletype'] == 'style2'){ ?>
 
<div class="looking-box-four">
<div class="looking-inner-box">
<div class="looking-info-four"><?php echo '<a href="' . $settings['url']['url'] . '"' . $target . $nofollow . '>';	?>
 
<?php if(isset($settings['icon']['library']) && $settings['icon']['library'] == 'svg') { ?>
				 			<i class="doccure-svg-icon-box "><?php \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );  ?></i>
				 		<?php } else {
				 			 \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); 
				 		} ?>

						<span class="ms-2"><?php echo $this->get_settings( 'title' ) ?></span></a>
<strong><?php echo $this->get_settings( 'content' ) ?></strong></div>
<div class="looking-four-btn"><a href="<?php echo $settings['url']['url']; ?>" <?php echo $target; ?>>Book Now<i class="fas fa-arrow-right ms-2"></i></a></div>
</div>
</div>

 
		<?php } else if($settings['style'] == 'top' && $settings['styletype'] == 'style3'){ ?>

<div class="abouts_whychoose">
	<div class="vc_column-inner">
		<div class="wpb_wrapper">
 <?php if(isset($settings['icon']['library']) && $settings['icon']['library'] == 'svg') { ?>
				 			<i class="doccure-svg-icon-box "><?php \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );  ?></i>
				 		<?php } else {
				 			 \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); 
				 		} ?>

 
 <h5><?php echo $this->get_settings( 'title' ) ?></h5>
<p><?php echo $this->get_settings( 'content' ) ?></p>
	</div>
	</div>
</div> 
 <?php } ?>
<?php 	}

	
}