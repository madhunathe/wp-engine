<?php
 
if( !function_exists('doccure_cron_activation') ) {
	register_activation_hook (__FILE__, 'doccure_cron_activation');
	add_action('wp', 'doccure_cron_activation');
	function doccure_cron_activation() {
		
		if( !wp_next_scheduled( 'doccure_payout_listing' ) ) { 
			global $theme_settings;
			$interval		= !empty( $theme_settings['cron_interval'] )  ? $theme_settings['cron_interval'] : 'daily';
			
			if( !empty ( $interval ) ) {
				wp_schedule_event( time(), $interval, 'doccure_payout_listing' );  
			}
		}
		
		if( ! wp_next_scheduled( 'doccure_update_featured_expiry_listing' ) ) { 
			
			wp_schedule_event( time(), 'hourly' , 'doccure_update_featured_expiry_listing');
		}

	}
	add_action('wp', 'doccure_cron_activation');
}

/**
 * Update expiry
 *
 * @throws error
 * @return 
 */
if( !function_exists('doccure_update_featured_expiry_listing') ) {
	function doccure_update_featured_expiry_listing() {
		$query_args = array(
			'posts_per_page' 	  => -1,
			'post_type' 	 	  => array( 'doctors' ),
			'post_status' 	 	  => array( 'publish' ),
			'ignore_sticky_posts' => 1,
			'meta_query' 			=> array(
											array(
												'key'   => 'is_featured',
												'value' => 1,
											)
										)
		);
		
		$all_posts 		= get_posts( $query_args );

		foreach( $all_posts as $key => $item ){
			$post_type		= get_post_type($item->ID);
			$current_time   = strtotime( current_time( 'mysql' ) );
			
			$get_expiry	= get_post_meta($item->ID,'_featured_date',true);
			$get_expiry	= !empty($get_expiry) ? strtotime($get_expiry) : 0;
			
			if( empty( $get_expiry ) || $get_expiry < $current_time  ){
				update_post_meta( $item->ID, 'is_featured', 0 );
			}
		}
		
	}
	add_action( 'doccure_update_featured_expiry_listing', 'doccure_update_featured_expiry_listing' );
}

/**
 * Cron schedule weekly and monthly
 *
 * @throws error
 * @return 
 */
if( !function_exists('doccure_cron_schedule') ) {
	function doccure_cron_schedule( $schedules = array() ) {
		$schedules['weekly'] = array(
			'interval' => 604800,
			'display' => esc_html__('Once a weekly','doccure_core')
		);
		
		$schedules['monthly'] = array(
			'interval' => 2635200,
			'display' => esc_html__('Once a month','doccure_core')
		);
		
		$schedules['half_month'] = array(
			'interval' => 1296000,
			'display' => esc_html__('Twice a month','doccure_core')
		);
		
		$schedules['daily'] = array(
			'interval' => 86400,
			'display' => esc_html__('Once a day','doccure_core')
		);
		
		$schedules['mints5daily'] = array(
			'interval' => 300,
			'display' => esc_html__('Every 5 mints','doccure_core')
		);
		
		return $schedules; 
	}
	add_filter( 'cron_schedules', 'doccure_cron_schedule' );
}

/**
 * Payouts
 *
 * @throws error
 * @return 
 */
if( !function_exists('doccure_payouts_function') ) {
	function doccure_payouts_function() {
		global $wpdb, $theme_settings;
		
		$min_amount		= !empty( $theme_settings['min_amount'] )  ? $theme_settings['min_amount'] : 0;
		
		
		$table_name 	= $wpdb->prefix . "dc_earnings";
		$payouts_table 	= $wpdb->prefix . "dc_payouts_history";
		$insert_payouts	= array();

		$current_date 						= current_time('mysql');
		$gmt_time							= current_time( 'mysql', 1 );
		$insert_payouts['processed_date'] 	= $current_date;
		$insert_payouts['date_gmt'] 		= $gmt_time;
		$insert_payouts['year'] 			= date('Y', strtotime($current_date));
		$insert_payouts['month'] 			= date('m', strtotime($current_date));
		$insert_payouts['timestamp'] 		= strtotime($current_date);
		$insert_payouts['status']			= 'completed';
		
		if( function_exists('doccure_get_current_currency') ) {
			$currency			= doccure_get_current_currency();
		} else {
			$currency['symbol']	= '$';
		}
		
		$insert_payouts['currency_symbol']	= $currency['symbol'];
		if (function_exists('doccure_sum_earning_doctor_payouts')) {
			$payouts	= doccure_sum_earning_doctor_payouts('completed','doctor_amount');

			if( !empty( $payouts ) && count( $payouts ) > 0 ) {
				foreach( $payouts as $payout ) {
					$doctor_amount	= intval($payout->total_amount);
					if( $doctor_amount > $min_amount ) {
						$contents	= get_user_meta($payout->user_id,'payrols',true);
						$payrol		= !empty($contents['type']) ? $contents['type'] : "";
						if( !empty( $payrol ) ){
							if( $payrol === 'paypal' ){
								$email								= !empty($contents['paypal_email']) ? $contents['paypal_email'] : "";
								$insert_payouts['paypal_email']		= $email;

								//check if email is valid
								if( empty( $email ) || !is_email( $email ) ){
									continue;
								}

							} else if( $payrol === 'bacs' ){
								$bank_details	= array();
								$bank_details['bank_account_name']		= !empty($contents['bank_account_name']) ? $contents['bank_account_name'] : "";
								$bank_details['bank_account_number']	= !empty($contents['bank_account_number']) ? $contents['bank_account_number'] : "";
								$bank_details['bank_name']				= !empty($contents['bank_name']) ? $contents['bank_name'] : "";
								$bank_details['bank_routing_number']	= !empty($contents['bank_routing_number']) ? $contents['bank_routing_number'] : "";
								$bank_details['bank_iban']				= !empty($contents['bank_iban']) ? $contents['bank_iban'] : "";
								$bank_details['bank_bic_swift']			= !empty($contents['bank_bic_swift']) ? $contents['bank_bic_swift'] : "";
								$insert_payouts['payment_details']		= serialize( $bank_details );

								if( empty( $contents['bank_account_name'] ) || empty( $contents['bank_account_number'] ) || empty( $contents['bank_name'] ) ){
									continue;
								}
							} else{
								$payout_details	= array();
								$fields	= doccure_get_payouts_lists($payrol);
								if( !empty($fields[$payrol]['fields'])) {
									foreach( $fields[$payrol]['fields'] as $key => $field ){
										if(!empty($field['show_this']) && $field['show_this'] == true){
											$payout_details[$key]		= !empty($contents[$key]) ? $contents[$key] : "";
										}
									}
								}
								
								$insert_payouts['payment_details']		= serialize( $payout_details );
							}

							$insert_payouts['user_id']			= intval($payout->user_id);
							$insert_payouts['amount']			= $payout->total_amount;
							$insert_payouts['payment_method']	= $payrol;

							if( function_exists('doccure_update_earning') ) {
								$wpdb->insert($payouts_table,$insert_payouts);
								$where		= array( 
												'user_id' => intval($payout->user_id) ,
												'status'  => 'completed'
											);

								$update		= array('status' => 'processed');
								doccure_update_earning( $where, $update, 'dc_earnings');
							}
						}
					}
				}
			}
		}
	}
	
	add_action ('doccure_payout_listing', 'doccure_payouts_function');
}

/**
 * Deactive plugin
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */


// if( !function_exists('doccure_cron_deactivate') ) {
// 	function doccure_cron_deactivate() {	
// 		$timestamp = wp_next_scheduled ('doccure_payout_listing');
// 		wp_unschedule_event ($timestamp, 'doccure_payout_listing');
// 	} 
// 	register_deactivation_hook (__FILE__, 'doccure_cron_deactivate');
// }




if (!function_exists('doccure_register_menu_page')) {

	function doccure_register_menu_page() {
		add_submenu_page('edit.php?post_type=doctors', 
			__('Payout Cron', 'doccure_core'), // Page title
			__('Payout Cron', 'doccure_core'), // Menu title
			'manage_options',                   // Capability
			'doccure-payout',                   // Menu slug
			'doccure_payout_page',              // Callback function
			 11                                   // Position
		);
	}
	add_action('admin_menu', 'doccure_register_menu_page');
	}
	
	
	
	// Callback function for the menu page
	function doccure_payout_page() {
		?>
		<div class="wrap">
			<h1><?php _e('Doccure Payout', 'doccure_core'); ?></h1>
			<button id="doccure-deactivate-cron" class="button button-primary" style="margin-top: 50px;"><?php _e('Run Cron', 'doccure_core'); ?></button>
		</div>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#doccure-deactivate-cron').on('click', function() {
				$.ajax({
					type: 'POST',
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					data: {
						action: 'doccure_cron_deactivate'
					},
					success: function(response) {
						alert(response.message);
					},
					error: function() {
						alert('An error occurred');
					}
				});
			});
		});
		</script>
		<?php
	}

	
 

// Function to deactivate cron
if( !function_exists('doccure_cron_deactivate') ) {
    function doccure_cron_deactivate() {
        $timestamp = wp_next_scheduled('doccure_payout_listing');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'doccure_payout_listing');
            $response = array('message' => __('Cron activated  successfully', 'doccure_core'));
        } else {
            $response = array('message' => __('No scheduled cron found', 'doccure_core'));
        }
        wp_send_json($response);
    }
    add_action('wp_ajax_doccure_cron_deactivate', 'doccure_cron_deactivate');
}


 