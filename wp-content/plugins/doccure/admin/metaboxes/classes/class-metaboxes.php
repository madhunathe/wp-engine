<?php

/**
 * @Class		Meta Box
 * @package	 	WordPress
 * @link 		https://themeforest.net/user/dreamstechnologies
 */
// die if accessed directly
if (!defined('ABSPATH')) {
    die('no kiddies please!');
}
if (!class_exists('AM_Metaboxes')) {

    class AM_Metaboxes {

        public function __construct() {
            add_action('add_meta_boxes', array(&$this, 'am_meta_boxes'));
            add_action('save_post', array(&$this, 'am_save_options'));
        }

        /* ------------------------------------------------
         * Function add meta boxes
         * ----------------------------------------------- */

        public function am_meta_boxes() {
            $this->add_meta_box('page_options', esc_html__('Page Options','doccure_core'), 'page');
            $this->add_meta_box('post_options', esc_html__('Post Options','doccure_core'), 'post');
			$this->add_meta_box('booking_options', esc_html__('Booking Details','doccure_core'), 'booking');
			$this->add_meta_box('doctors_options', esc_html__('Doctors Options','doccure_core'), 'doctors');
			$this->add_meta_box('hospitals_options', esc_html__('Hospital Options','doccure_core'), 'hospitals');
			$this->add_meta_box('forum_options', esc_html__('Health Forum Options','doccure_core'), 'healthforum');
        }

        /* ------------------------------------------------
         * Function add meta boxes
         * ----------------------------------------------- */

        public function add_meta_box($id, $label, $post_type) {
            add_meta_box('metabox' . $id, $label, array($this, $id), $post_type);
        }

        /* ------------------------------------------------
         * Function Page Options
         * ----------------------------------------------- */

        public function page_options() {
            include doccureGlobalSettings::get_plugin_path() . 'admin/metaboxes/page-options.php';
        }

        /* ------------------------------------------------
         * Function Post Options
         * ----------------------------------------------- */

        public function post_options() {
            include doccureGlobalSettings::get_plugin_path() . 'admin/metaboxes/post-options.php';
        }
		
		/* ------------------------------------------------
         * Function Doctors Options
         * ----------------------------------------------- */

        public function doctors_options() {
            include doccureGlobalSettings::get_plugin_path() . 'admin/metaboxes/doctors-options.php';
        }
		
		/* ------------------------------------------------
         * Function Health Forum Options
         * ----------------------------------------------- */

        public function forum_options() {
            include doccureGlobalSettings::get_plugin_path() . 'admin/metaboxes/forum-options.php';
        }
		
		/* ------------------------------------------------
         * Function Hospital Options
         * ----------------------------------------------- */

        public function hospitals_options() {
            include doccureGlobalSettings::get_plugin_path() . 'admin/metaboxes/hospitals-options.php';
        }
		
		/* ------------------------------------------------
         * Function Booking Options
         * ----------------------------------------------- */

        public function booking_options() {
            include doccureGlobalSettings::get_plugin_path() . 'admin/metaboxes/booking-options.php';
        }



        /* ------------------------------------------------
         * Function Save Options
         * ----------------------------------------------- */

        public function am_save_options($post_id) {
            global $post_type;
			if (!is_admin() || empty( $post_type ) || $post_type === 'hospitals_team') {
				return;
			}
			
			if(  $post_type === 'hospitals' || $post_type === 'doctors' || $post_type === 'healthforum' || $post_type === 'post' || $post_type === 'page') {
				$am_element_data = array();
				if ( defined('DOING_AUTOSAVE') ) {
					return;
				}
				
				$specialities_array	= array();
				$service			= array();
				$meta_data 			= array();
				
				// $user_id	= doccure_get_linked_profile_id($post_id ,'post');
				$included	= !empty( $_POST['am_included'] ) ? $_POST['am_included'] : '';

				foreach ($_POST as $key => $value) {
					if (strstr($key, 'am_') && $key != 'am_included') {
						
						
						if( $key === 'am_first_name' || $key === 'am_last_name' || $key === 'am_mobile_number') {
							$user_key	= str_replace('am_','',$key);
							update_user_meta( $user_id, $user_key, $value );
						} elseif( $key === 'am_gender' ) {
							update_post_meta( $post_id, $key, $value );
						}

						if( $key === 'am_specialities' &&  ( $post_type === 'hospitals'  || $post_type === 'doctors' ) ) {
							
							if( !empty( $value ) ){
								foreach( $value as $keys => $vals ){
									if( !empty( $vals['speciality_id'] ) ){
										$meta_data[$vals['speciality_id']] = array();
										if( !empty( $vals['services'] ) ) {
											foreach( $vals['services'] as $key => $val ) {
												if( !empty( $val['service'] ) ){
													$service[] = $val['service'];
													$meta_data[$vals['speciality_id']][$val['service']] = $val;
												}
											}
										}
									}
								}
							}
							
							$specialities_array					= $meta_data;
							$am_element_data['am_specialities']	= $meta_data;
							
						}else {
							$am_element_data[$key] = $value;
						}

					} elseif (strstr($key, 'am_') === false ){
						update_post_meta($post_id, $key, $value);
					}
				}

				update_post_meta($post_id, 'am_' . $post_type . '_data', $am_element_data);

				$specialities	= array();
				$service		= array();
				if( ( $post_type === 'doctors' || $post_type === 'hospitals' ) && (!empty($specialities_array)) ){
					if( !empty( $specialities_array ) ){
						foreach( $specialities_array as $keys => $vals ){
							$specialities[] = $keys;
							if( !empty( $vals ) ) {
								foreach( $vals as $key => $val ) {
									$service[] = $val['service'];
								}
							}

						}
					}

				} 
				wp_set_post_terms( $post_id, $specialities, 'specialities' );
				wp_set_post_terms( $post_id, $service, 'services' );
				
				if( !empty($post_type) && $post_type === 'doctors') {
					if( !empty($_POST['_featured_date']) ){
						$featured_date	= !empty($_POST['_featured_date']) ? strtotime( $_POST['_featured_date'] ) : '';
						$current_time	= current_time('timestamp');

						if( $featured_date > $current_time){
							update_post_meta($post_id, '_featured_timestamp', $featured_date);
							update_post_meta($post_id, 'is_featured', 1);
						}else{
							update_post_meta($post_id, '_featured_timestamp', $featured_date);
							update_post_meta($post_id, 'is_featured', 0);
						}
					}else{
						update_post_meta($post_id, '_featured_timestamp', '');
						update_post_meta($post_id, 'is_featured', 0);
					}
				}
			}
			
			if( $post_type === 'healthforum' ){
				$specialities	= !empty($_POST['tax_input']['specialities']) ? $_POST['tax_input']['specialities'] : array();
				
				wp_set_post_terms( $post_id, $specialities, 'specialities' );
			}
		}
    }

}

$metaboxes = new AM_Metaboxes;
