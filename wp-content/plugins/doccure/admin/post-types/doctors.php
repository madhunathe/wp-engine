<?php

/**
 * @package   Doccure Core
 * @author    Dreams Technologies
 * @link      http://dreamstechnologies.com/
 * @version 1.0
 * @since 1.0
 */
if (!class_exists('doccure_Doctors')) {

    class doccure_Doctors {

        /**
         * @access  public
         * @Init Hooks in Constructor
         */
        public function __construct() {
            add_action('init', array(&$this, 'init_post_type'));
			add_action('add_meta_boxes', array(&$this, 'linked_profile_add_meta_box'), 10, 2);
        }
		
		/**
		 * @Linked Profile metabox
		 * @return {post}
		 */
		public function linked_profile_add_meta_box($post_type,$post) {
			// $linked_profile	= doccure_get_linked_profile_id($post->ID,'post');
			
			// if(empty( $linked_profile )){return;}
			
			if ($post_type === 'doctors') {
                add_meta_box(
                        'linked_profile', esc_html__('Linked Profile', 'doccure_core'), array(&$this, 'linked_profile_meta_box_print'), 'doctors', 'side', 'high'
                );
            }
		}
		
		/**
		 * @Linked Profile metabox
		 * @return {post}
		 */
		public function linked_profile_meta_box_print($post) {
			$linked_profile	= doccure_get_linked_profile_id($post->ID,'post');
			if(empty( $linked_profile )){return;}
			?>
			<ul class="review-info">
                <li>
                    <span class="push-right">
                    	<a target="_blank" href="<?php echo get_edit_user_link( $linked_profile );?>"><?php esc_html_e('View User Profile', 'doccure_core'); ?></a>
                    </span>
                </li>
			</ul>
			<?php
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
            global $doccure_options;
            $labels = array(
                'name' 				=> esc_html__('Doctors', 'doccure_core'),
                'all_items' 		=> esc_html__('Doctors', 'doccure_core'),
                'singular_name' 	=> esc_html__('Doctor', 'doccure_core'),
                'add_new' 			=> esc_html__('Add Doctor', 'doccure_core'),
                'add_new_item' 		=> esc_html__('Add New Doctor', 'doccure_core'),
                'edit' 				=> esc_html__('Edit', 'doccure_core'),
                'edit_item' 		=> esc_html__('Edit Doctor', 'doccure_core'),
                'new_item' 			=> esc_html__('New Doctor', 'doccure_core'),
                'view' 				=> esc_html__('View Doctor', 'doccure_core'),
                'view_item' 		=> esc_html__('View Doctor', 'doccure_core'),
                'search_items' 		=> esc_html__('Search Doctor', 'doccure_core'),
                'not_found' 		=> esc_html__('No Doctor found', 'doccure_core'),
                'not_found_in_trash' => esc_html__('No Doctor found in trash', 'doccure_core'),
                'parent' 			=> esc_html__('Parent Doctors', 'doccure_core'),
            );
			
            $args = array(
                'labels' 				=> $labels,
                'description' 			=> esc_html__('This is where you can add new doctors', 'doccure_core'),
                'public' 				=> true,
                'supports' 				=> array('title','excerpt','editor','thumbnail','author'),
                'show_ui' 				=> true,
                'capability_type' 		=> 'post',
                'map_meta_cap' 			=> true,
                'publicly_queryable' 	=> true,
                'hierarchical' 			=> false,
                'menu_position' 		=> 10,
                'rewrite' 				=> array('slug' => 'doctors', 'with_front' => true),
                'query_var' 			=> false,
                'has_archive' 			=> false,
				'capabilities' 			=> array('create_posts' => false)
            );
			
			//Regirster Languages Taxonomy
            $languages_labels = array(
                'name' => esc_html__('Languages', 'doccure_core'),
                'singular_name' => esc_html__('Language','doccure_core'),
                'search_items' => esc_html__('Search Language', 'doccure_core'),
                'all_items' => esc_html__('All Language', 'doccure_core'),
                'parent_item' => esc_html__('Parent Language', 'doccure_core'),
                'parent_item_colon' => esc_html__('Parent Language:', 'doccure_core'),
                'edit_item' => esc_html__('Edit Language', 'doccure_core'),
                'update_item' => esc_html__('Update Language', 'doccure_core'),
                'add_new_item' => esc_html__('Add New Language', 'doccure_core'),
                'new_item_name' => esc_html__('New Language Name', 'doccure_core'),
                'menu_name' => esc_html__('Languages', 'doccure_core'),
            );
            
            $language_args = array(
                'hierarchical' => true,
                'labels' => $languages_labels,
                'show_ui' => true,
                'show_admin_column' => false,
                'query_var' => true,
                'rewrite' => array('slug' => 'languages'),
            );
			
			register_taxonomy('languages', array('doctors'), $language_args);
            register_post_type('doctors', $args);
            
            $location_labels = array(
				'name' 				=> esc_html__('Doctors Locations', 'doccure_core'),
				'all_items' 		=> esc_html__('Doctors Locations', 'doccure_core'),
				'singular_name' 	=> esc_html__('Doctor Location', 'doccure_core'),
				'add_new' 			=> esc_html__('Add Doctor Location', 'doccure_core'),
				'add_new_item' 		=> esc_html__('Add New Doctor Location', 'doccure_core'),
				'edit' 				=> esc_html__('Edit', 'doccure_core'),
				'edit_item' 		=> esc_html__('Edit Doctor Location', 'doccure_core'),
				'new_item' 			=> esc_html__('New Doctor Location', 'doccure_core'),
				'view' 				=> esc_html__('View Doctor Location', 'doccure_core'),
				'view_item' 		=> esc_html__('View Doctor Location', 'doccure_core'),
				'search_items' 		=> esc_html__('Search Doctor Location', 'doccure_core'),
				'not_found' 		=> esc_html__('No Doctor found Location', 'doccure_core'),
				'not_found_in_trash' => esc_html__('No Doctor Location found in trash', 'doccure_core'),
				'parent' 			=> esc_html__('Parent Doctors Location', 'doccure_core'),
			);

			$location_args = array(
				'labels' 				=> $location_labels,
				'description' 			=> esc_html__('This is where you can add new doctors locations', 'doccure_core'),
				'public' 				=> false,
				'supports' 				=> array('title','thumbnail'),
				'show_ui' 				=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap' 			=> true,
				'hierarchical' 			=> false,
				'menu_position' 		=> 10,
				'show_in_menu' 			=> 'edit.php?post_type=doctors',
				'rewrite' 				=> array('slug' => 'doctors-locations', 'with_front' => true),
				'query_var' 			=> false,
				'has_archive' 			=> false,
				'capabilities' 			=> array('create_posts' => false)
			);
			register_post_type('dc_locations', $location_args);
			
        }
        
    }

    new doccure_Doctors();
}

