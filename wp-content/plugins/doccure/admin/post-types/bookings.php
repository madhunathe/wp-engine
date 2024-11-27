<?php

/**
 * @package   Doccure Core
 * @author    Dreams Technologies
 * @link      http://dreamstechnologies.com/
 * @version 1.0
 * @since 1.0
 */
if (!class_exists('doccure_Booking')) {

    class doccure_Booking {

        /**
         * @access  public
         * @Init Hooks in Constructor
         */
        public function __construct() {
            add_action('init', array(&$this, 'init_post_type'));
			add_filter('manage_booking_posts_columns', array(&$this, 'booking_columns_add'));
			add_action('manage_booking_posts_custom_column', array(&$this, 'booking_columns'),10, 2);
        }

        /**
         * @Init Post Type
         * @return {post}
         */
        public function init_post_type() {
            $this->prepare_post_type();
        }
				
        /**
         * @Prepare Post Type Category
         * @return post type
         */
        public function prepare_post_type() {
			
            $labels = array(
                'name' 				=> esc_html__('Booking', 'doccure_core'),
                'all_items' 		=> esc_html__('Booking', 'doccure_core'),
                'singular_name' 	=> esc_html__('Booking', 'doccure_core'),
                'add_new' 			=> esc_html__('Add Booking', 'doccure_core'),
                'add_new_item' 		=> esc_html__('Add New Booking', 'doccure_core'),
                'edit' 				=> esc_html__('Edit', 'doccure_core'),
                'edit_item' 		=> esc_html__('Edit Booking', 'doccure_core'),
                'new_item' 			=> esc_html__('New Booking', 'doccure_core'),
                'view' 				=> esc_html__('View Booking', 'doccure_core'),
                'view_item' 		=> esc_html__('View Booking', 'doccure_core'),
                'search_items' 		=> esc_html__('Search Booking', 'doccure_core'),
                'not_found' 		=> esc_html__('No Booking found', 'doccure_core'),
                'not_found_in_trash' => esc_html__('No Booking found in trash', 'doccure_core'),
                'parent' 			=> esc_html__('Parent Booking', 'doccure_core'),
            );
            $args = array(
                'labels' 				=> $labels,
                'description' 			=> esc_html__('This is where you can add new Booking', 'doccure_core'),
                'public' 				=> true,
                'supports' 				=> array('title','author'),
                'show_ui' 				=> true,
                'capability_type' 		=> 'post',
                'map_meta_cap' 			=> true,
                'publicly_queryable' 	=> false,
                'exclude_from_search' 	=> true,
                'hierarchical' 			=> false,
                'menu_position' 		=> 10,
				'menu_icon'   			=> 'dashicons-calendar-alt',
                'rewrite' 				=> array('slug' => 'booking', 'with_front' => true),
                'query_var' 			=> false,
                'has_archive' 			=> false,
				'capabilities' 			=> array('create_posts' => false)

            );
			
            register_post_type('booking', $args);
			
        }
		
		/**
		 * @Prepare Columns
		 * @return {post}
		 */
		public function booking_columns_add($columns) {
			
			$columns['doctor'] 		= esc_html__('Doctor','doccure_core');
			$columns['hospital'] 	= esc_html__('Location','doccure_core');
			$columns['title']		= esc_html__('Booking ID#','doccure_core');
  			return $columns;
		}
		
		/**
		 * @Get Columns
		 * @return {}
		 */
		public function booking_columns($name) {
            global $post;
			
			$hospital_id	= get_post_meta( $post->ID,'_hospital_id',true );
			$hospital_id	= !empty( $hospital_id ) ? intval( $hospital_id ) : '';
			$hospital_name	= function_exists('doccure_full_name') && !empty( $hospital_id ) ? doccure_full_name($hospital_id) : '';
				
			
            $doctor_id		= get_post_meta( $post->ID,'_doctor_id',true );
			$doctor_name	= function_exists('doccure_full_name') && !empty( $doctor_id )  ? doccure_full_name($doctor_id) : get_the_title($doctor_id);
			
            $doctor   			= '<a href="'.get_edit_post_link($doctor_id).'">'.$doctor_name.'</a>';
			$hospital			= '<a href="'.get_edit_post_link($hospital_id).'">'.$hospital_name.'</a>';

            switch ($name) {
                case 'doctor':
                   if (!empty( $doctor) ) {
                        echo force_balance_tags( $doctor );
                    }
                    break;
                case 'hospital':
                    if (!empty( $hospital ) ) {
                        echo force_balance_tags( $hospital );
                    }
                    break;
            }
        }
    }

    new doccure_Booking();
}

