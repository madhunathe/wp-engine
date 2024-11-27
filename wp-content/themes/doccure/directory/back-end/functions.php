<?php
/**
 *
 * Functions
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @since 1.0
 */


/**
 * @get settings
 * @return {}
 */
if (!function_exists('doccure_profile_backend_settings')) {
	function  doccure_profile_backend_settings(){
		if(current_user_can('administrator')) {
			$list	= array(
				'payments'	 	=> 'payments',
			);
			return $list;
		}
		
		return array();
	}
}
