<?php
/**
 * Widgets class.
 *
 * @category   Class
 * @package    ElementorDoccure
 * @subpackage WordPress
 * @author     Dreamslms
 * @copyright  Dreamslms
 * @license    https://doccure-wp.dreamstechnologies.com/
 * @since      1.0.0
 * php version 7.3.9
 */

namespace ElementorDoccure;

// Security Note: Blocks direct access to the plugin PHP files.
defined( 'ABSPATH' ) || die();

/**
 * Class Plugin
 *
 * Main Plugin class
 *
 * @since 1.0.0
 */
class Widgets {

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Registers the widget scripts.
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function widget_scripts() {
		//wp_register_script( 'doccure_elementor', plugins_url( '/assets/js/elementordoccure.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
	}

	public function backend_preview_scripts() {
		wp_enqueue_script( 'elementor-preview-doccure', plugins_url( '/assets/js/elementor_preview_doccure.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function include_widgets_files() {

		require_once 'widgets/class-headline.php';
 		require_once 'widgets/class-iconbox.php';
		require_once 'widgets/class-iconbox2.php';
		require_once 'widgets/class-iconboxservice.php';
		require_once 'widgets/class-imagebox.php';
		require_once 'widgets/class-post-grid.php';
		require_once 'widgets/class-portfolio-grid.php';
  		require_once 'widgets/class-aboutbanner.php';
		require_once 'widgets/class-testimonials.php';
 		require_once 'widgets/class-contact-box.php';
		 require_once 'widgets/class-contact-form.php';
 		require_once 'widgets/class-toggle.php';
		 require_once 'widgets/class-togglehome.php';
		require_once 'widgets/class-accordion.php';
		require_once 'widgets/class-hero-main.php';
		require_once 'widgets/class-hero-one.php';
		require_once 'widgets/class-hero-two.php';
		require_once 'widgets/class-hero-three.php';
		require_once 'widgets/class-hero-four.php';
		require_once 'widgets/class-doctors-carousel.php';
		require_once 'widgets/class-doctors-specialities.php';
		require_once 'widgets/class-doctors-services.php';
		require_once 'widgets/class-services.php';
		require_once 'widgets/class-howworks.php';
		require_once 'widgets/class-clients.php';
		
		
		// home search boxes
 	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_widgets() {
		// It's now safe to include Widgets files.
		$this->include_widgets_files();
			 
		// Register the plugin widget classes.
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Headline() );
 		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\IconBox() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\IconBoxTwo() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\IconBoxService() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ImageBox() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\PostGrid() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\PortfolioGrid() );
  		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\FlipBannerAbout() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Testimonials() );
 		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ContactBox() );
		 \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ContactForm() );
   		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Widget_Doccure_Toggle() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Widget_Doccure_Toggle_Home() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Widget_Doccure_Accordion() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\HomeHeroMain() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\HomeHeroOne() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\HomeHeroTwo() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\HomeHeroThree() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\HomeHeroFour() );
		
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\DoctorsCarousel() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\DoctorsSpecialities() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\DoctorsServices() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\DoccureServices() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Widget_Doccure_How_Works() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Doccure_Clients() );

		
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'elementor/elements/categories_registered', array( $this, 'create_custom_categories') );

		// Register the widget scripts.
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'widget_scripts' ) );

		add_action('elementor/preview/enqueue_styles', array($this, 'backend_preview_scripts'), 10);
        
        //add_action('elementor/frontend/after_register_scripts', array($this, 'cocobasic_frontend_enqueue_script'));

		// Register the widgets.
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ) );

		
	}


	function create_custom_categories( $elements_manager ) {

	    $elements_manager->add_category(
	        'doccure',
	        [
	         'title' => __( 'Doccure', 'plugin-name' ),
	         'icon' => 'fa fa-clipboard',
	        ]
	    );
	}
}

// Instantiate the Widgets class.
Widgets::instance();