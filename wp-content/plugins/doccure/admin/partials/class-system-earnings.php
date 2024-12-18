<?php
/**
 * page Earnings 
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Elevator
 * @subpackage doccure/includes
 * @author     Dreams Technologies<support@dreamstechnologies.com>
 */
class doccure_earnings {

	// class instance
	static $instance;

	// Earnings WP_List_Table object
	public $earnings_obj;

	// class constructor
	public function __construct() {
		add_action( 'admin_menu', array(&$this, 'earnings_menu' ) );
		
	}
	
	/**
	 * Payout Menu
	 *
	 * @throws error
	 * @author Dreams Technologies<support@dreamstechnologies.com>
	 * @return 
	 */
	public function earnings_menu() {
		$hook = add_submenu_page('edit.php?post_type=doctors', 
							 esc_html__('Earnings','doccure_core'), 
							 esc_html__('Earnings','doccure_core'), 
							 'manage_options', 
							 'earnings',
							 array( &$this, 'earnings_settings_page' ),
							 10
						 );
		
		add_action( "load-$hook", array(&$this, 'screen_option' ) );
	}
	
	/**
	 * Screen
	 *
	 * @throws error
	 * @author Dreams Technologies<support@dreamstechnologies.com>
	 * @return 
	 */
	public function earnings_settings_page() {
	?>
		<div class="wrap">
			<h2><?php esc_html_e('Earnings','doccure_core');?></h2>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post">
								<form id="posts-filter" method="get">
									<p class="search-box">
										<input type="search" id="post-search-input" name="s" value="<?php if(!empty($_REQUEST['s']) ) echo $_REQUEST['s'];?>">
										<input type="submit" id="search-submit" class="button" value="Search By Booking ID">
									</p>
								</form>
								<?php
									$this->earnings_obj->prepare_items();
									$this->earnings_obj->display();
								?>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php
	}

	/**
	 * Screen ption
	 *
	 * @throws error
	 * @author Dreams Technologies<support@dreamstechnologies.com>
	 * @return 
	 */
	public function screen_option() {

		$option = 'earnings_per_page';
		$args   = array(
			'label'   => esc_html__('Earnings','doccure_core'),
			'default' => 20,
			'option'  => 'earnings_per_page'
		);

		add_screen_option( $option, $args );

		$this->earnings_obj = new earnings_List();
	}

	/**
	 * Singleton instance
	 *
	 * @throws error
	 * @author Dreams Technologies<support@dreamstechnologies.com>
	 * @return 
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}

$earnings	= new doccure_earnings();
