<?php
/**
 * page payouts 
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Elevator
 * @subpackage doccure/includes
 * @author     Dreams Technologies<support@dreamstechnologies.com>
 */
class doccure_payouts {

	// class instance
	static $instance;

	// customer WP_List_Table object
	public $payouts_obj;

	// class constructor
	public function __construct() {
		add_action( 'admin_menu', array(&$this, 'Payouts_menu' ) );
		
	}
	
	/**
	 * Payout Menu
	 *
	 * @throws error
	 * @author Dreams Technologies<support@dreamstechnologies.com>
	 * @return 
	 */
	public function Payouts_menu() {
		$hook = add_submenu_page('edit.php?post_type=doctors', 
							 esc_html__('Payouts','doccure_core'), 
							 esc_html__('Payouts','doccure_core'), 
							 'manage_options', 
							 'payouts',
							 array( &$this, 'Payouts_settings_page' ),
							 7
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
	public function Payouts_settings_page() {
	?>
		<div class="wrap">
			<h2><?php esc_html_e('Payouts','doccure_core');?></h2>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post">
								<div class="tablenav top">
									<div class="alignleft actions bulkactions">
										<input autocomplete="off" placeholder="<?php echo esc_html_e('Start date','doccure_core');?>" type="datetime" class="dc-datetimepicker" name="start_date">
										<input autocomplete="off" placeholder="<?php echo esc_html_e('End date','doccure_core');?>" type="datetime" class="dc-datetimepicker" name="end_date">
										<input type="submit" id="doaction" class="button action" value="Download">
									</div>
								<?php
									$this->payouts_obj->prepare_items();
									$this->payouts_obj->display();
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

		$option = 'per_page';
		$args   = array(
			'label'   => esc_html__('Payouts','doccure_core'),
			'default' => 20,
			'option'  => 'payout_per_page'
		);

		add_screen_option( $option, $args );

		$this->payouts_obj = new Payouts_List();
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

$payouts	= new doccure_payouts();
