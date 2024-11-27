<?php

/**
 * @package   Doccure Core
 * @author    Dreams Technologies
 * @link      http://dreamstechnologies.com/
 * @version 1.0
 * @since 1.0
 */
if (!class_exists('doccure_Prescription')) {

    class doccure_Prescription {

        /**
         * @access  public
         * @Init Hooks in Constructor
         */
        public function __construct() {
            add_action('init', array(&$this, 'init_post_type'));
            add_action( 'diseases_add_form_fields', array(&$this, 'doccure_diseases_meta'), 10, 2 );
			add_action( 'diseases_edit_form_fields', array(&$this, 'doccure_diseases_meta_edit'), 10, 2 );
			
			add_action( 'edited_diseases', array(&$this, 'doccure_diseases_meta_update'), 10, 2 );  
			add_action( 'create_diseases', array(&$this, 'doccure_diseases_meta_save'), 10, 2 );
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
                'name' 				=> esc_html__('Prescription', 'doccure_core'),
                'all_items' 		=> esc_html__('Prescription', 'doccure_core'),
                'singular_name' 	=> esc_html__('Prescription', 'doccure_core'),
                'add_new' 			=> esc_html__('Add Prescription', 'doccure_core'),
                'add_new_item' 		=> esc_html__('Add New Prescription', 'doccure_core'),
                'edit' 				=> esc_html__('Edit', 'doccure_core'),
                'edit_item' 		=> esc_html__('Edit Prescription', 'doccure_core'),
                'new_item' 			=> esc_html__('New Prescription', 'doccure_core'),
                'view' 				=> esc_html__('View Prescription', 'doccure_core'),
                'view_item' 		=> esc_html__('View Prescription', 'doccure_core'),
                'search_items' 		=> esc_html__('Search Prescription', 'doccure_core'),
                'not_found' 		=> esc_html__('No Prescription found', 'doccure_core'),
                'not_found_in_trash' => esc_html__('No Prescription found in trash', 'doccure_core'),
                'parent' 			=> esc_html__('Parent Prescription', 'doccure_core'),
            );
			
            $args = array(
                'labels' 				=> $labels,
                'description' 			=> esc_html__('This is where you can add prescription ', 'doccure_core'),
                'public' 				=> true,
                'supports' 				=> array('title','author'),
                'show_ui' 				=> true,
                'capability_type' 		=> 'post',
                'map_meta_cap' 			=> true,
                'publicly_queryable' 	=> true,
                'hierarchical' 			=> false,
                'menu_position' 		=> 10,
                'rewrite' 				=> array('slug' => 'prescription', 'with_front' => true),
                'query_var' 			=> false,
                'has_archive' 			=> false,
            );
			
			//Regirster Vital Signs Taxonomy
            $vital_labels = array(
                'name'              => esc_html__('Vital Signs', 'doccure_core'),
                'singular_name'     => esc_html__('Vital Sign','doccure_core'),
                'search_items'      => esc_html__('Search Vital Sign', 'doccure_core'),
                'all_items'         => esc_html__('All Vital Sign', 'doccure_core'),
                'parent_item'       => esc_html__('Parent Vital Sign', 'doccure_core'),
                'parent_item_colon' => esc_html__('Parent Vital Sign:', 'doccure_core'),
                'edit_item'         => esc_html__('Edit Vital Sign', 'doccure_core'),
                'update_item'       => esc_html__('Update Vital Sign', 'doccure_core'),
                'add_new_item'      => esc_html__('Add New Vital Sign', 'doccure_core'),
                'new_item_name'     => esc_html__('New Vital Sign Name', 'doccure_core'),
                'menu_name'         => esc_html__('Vital Signs', 'doccure_core'),
            );
            
            $vital_args = array(
                'hierarchical'          => true,
                'labels'                => $vital_labels,
                'show_ui'               => true,
                'show_admin_column'     => false,
                'query_var'             => true,
                'rewrite'               => array('slug' => 'vital-signs'),
            );
			
            register_taxonomy('vital_signs', array('prescription'), $vital_args);
            
            //Regirster Childhood illness Taxonomy
            $illness_labels = array(
                'name'              => esc_html__('Childhood illness', 'doccure_core'),
                'singular_name'     => esc_html__('Childhood illness','doccure_core'),
                'search_items'      => esc_html__('Search Childhood illness', 'doccure_core'),
                'all_items'         => esc_html__('All Childhood illness', 'doccure_core'),
                'parent_item'       => esc_html__('Parent Childhood illness', 'doccure_core'),
                'parent_item_colon' => esc_html__('Parent Childhood illness:', 'doccure_core'),
                'edit_item'         => esc_html__('Edit Childhood illness', 'doccure_core'),
                'update_item'       => esc_html__('Update Childhood illness', 'doccure_core'),
                'add_new_item'      => esc_html__('Add New Childhood illness', 'doccure_core'),
                'new_item_name'     => esc_html__('New Childhood illness Name', 'doccure_core'),
                'menu_name'         => esc_html__('Childhood illness', 'doccure_core'),
            );
            
            $illness_args = array(
                'hierarchical'          => true,
                'labels'                => $illness_labels,
                'show_ui'               => true,
                'show_admin_column'     => false,
                'query_var'             => true,
                'rewrite'               => array('slug' => 'childhood-illness'),
            );
			
            register_taxonomy('childhood_illness', array('prescription'), $illness_args);

            //Regirster Medicine type Taxonomy
            $medicine_labels = array(
                'name'              => esc_html__('Medicine types', 'doccure_core'),
                'singular_name'     => esc_html__('Medicine type','doccure_core'),
                'search_items'      => esc_html__('Search Medicine type', 'doccure_core'),
                'all_items'         => esc_html__('All Medicine type', 'doccure_core'),
                'parent_item'       => esc_html__('Parent Medicine type', 'doccure_core'),
                'parent_item_colon' => esc_html__('Parent Medicine type:', 'doccure_core'),
                'edit_item'         => esc_html__('Edit Medicine type', 'doccure_core'),
                'update_item'       => esc_html__('Update Medicine type', 'doccure_core'),
                'add_new_item'      => esc_html__('Add New Medicine type', 'doccure_core'),
                'new_item_name'     => esc_html__('New Medicine type Name', 'doccure_core'),
                'menu_name'         => esc_html__('Medicine types', 'doccure_core'),
            );
            
            $medicine_args = array(
                'hierarchical'          => true,
                'labels'                => $medicine_labels,
                'show_ui'               => true,
                'show_admin_column'     => false,
                'query_var'             => true,
                'rewrite'               => array('slug' => 'medicine-types'),
            );
			
            register_taxonomy('medicine_types', array('prescription'), $medicine_args);

            //Regirster Medicine Usage Taxonomy
            $usage_labels = array(
                'name'              => esc_html__('Medicine Usage', 'doccure_core'),
                'singular_name'     => esc_html__('Medicine Usage','doccure_core'),
                'search_items'      => esc_html__('Search Medicine Usage', 'doccure_core'),
                'all_items'         => esc_html__('All Medicine Usage', 'doccure_core'),
                'parent_item'       => esc_html__('Parent Medicine Usage', 'doccure_core'),
                'parent_item_colon' => esc_html__('Parent Medicine Usage:', 'doccure_core'),
                'edit_item'         => esc_html__('Edit Medicine Usage', 'doccure_core'),
                'update_item'       => esc_html__('Update Medicine Usage', 'doccure_core'),
                'add_new_item'      => esc_html__('Add New Medicine Usage', 'doccure_core'),
                'new_item_name'     => esc_html__('New Medicine Usage Name', 'doccure_core'),
                'menu_name'         => esc_html__('Medicine Usage', 'doccure_core'),
            );
            
            $usage_args = array(
                'hierarchical'          => true,
                'labels'                => $usage_labels,
                'show_ui'               => true,
                'show_admin_column'     => false,
                'query_var'             => true,
                'rewrite'               => array('slug' => 'medicine-usage'),
            );
			
            register_taxonomy('medicine_usage', array('prescription'), $usage_args);

            //Regirster Medicine Duration Taxonomy
            $duration_labels = array(
                'name'              => esc_html__('Medicine Duration', 'doccure_core'),
                'singular_name'     => esc_html__('Medicine Duration','doccure_core'),
                'search_items'      => esc_html__('Search Medicine Duration', 'doccure_core'),
                'all_items'         => esc_html__('All Medicine Duration', 'doccure_core'),
                'parent_item'       => esc_html__('Parent Medicine Duration', 'doccure_core'),
                'parent_item_colon' => esc_html__('Parent Medicine Duration:', 'doccure_core'),
                'edit_item'         => esc_html__('Edit Medicine Duration', 'doccure_core'),
                'update_item'       => esc_html__('Update Medicine Duration', 'doccure_core'),
                'add_new_item'      => esc_html__('Add New Medicine Duration', 'doccure_core'),
                'new_item_name'     => esc_html__('New Medicine Duration Name', 'doccure_core'),
                'menu_name'         => esc_html__('Medicine Duration', 'doccure_core'),
            );
            
            $duration_args = array(
                'hierarchical'          => true,
                'labels'                => $duration_labels,
                'show_ui'               => true,
                'show_admin_column'     => false,
                'query_var'             => true,
                'rewrite'               => array('slug' => 'medicine-duration'),
            );
			
            register_taxonomy('medicine_duration', array('prescription'), $duration_args);

            //Status Taxonomy
            $duration_labels = array(
                'name'              => esc_html__('Marital status', 'doccure_core'),
                'singular_name'     => esc_html__('Marital status','doccure_core'),
                'search_items'      => esc_html__('Search Marital status', 'doccure_core'),
                'all_items'         => esc_html__('All Marital status', 'doccure_core'),
                'parent_item'       => esc_html__('Parent Marital status', 'doccure_core'),
                'parent_item_colon' => esc_html__('Parent Marital status:', 'doccure_core'),
                'edit_item'         => esc_html__('Edit Marital status', 'doccure_core'),
                'update_item'       => esc_html__('Update Marital status', 'doccure_core'),
                'add_new_item'      => esc_html__('Add New Marital status', 'doccure_core'),
                'new_item_name'     => esc_html__('New Marital status Name', 'doccure_core'),
                'menu_name'         => esc_html__('Marital status', 'doccure_core'),
            );
            
            $duration_args = array(
                'hierarchical'          => true,
                'labels'                => $duration_labels,
                'show_ui'               => true,
                'show_admin_column'     => false,
                'query_var'             => true,
                'rewrite'               => array('slug' => 'marital-status'),
            );
			
            register_taxonomy('marital_status', array('prescription'), $duration_args);

             //Diseases Taxonomy
             $diseases_labels = array(
                'name'              => esc_html__('Disease', 'doccure_core'),
                'singular_name'     => esc_html__('Disease','doccure_core'),
                'search_items'      => esc_html__('Search Diseases', 'doccure_core'),
                'all_items'         => esc_html__('All Diseases', 'doccure_core'),
                'parent_item'       => esc_html__('Parent Disease', 'doccure_core'),
                'parent_item_colon' => esc_html__('Parent Disease:', 'doccure_core'),
                'edit_item'         => esc_html__('Edit Disease', 'doccure_core'),
                'update_item'       => esc_html__('Update Disease', 'doccure_core'),
                'add_new_item'      => esc_html__('Add New Disease', 'doccure_core'),
                'new_item_name'     => esc_html__('New Disease Name', 'doccure_core'),
                'menu_name'         => esc_html__('Diseases', 'doccure_core'),
            );
            
            $diseases_args = array(
                'hierarchical'          => true,
                'labels'                => $diseases_labels,
                'show_ui'               => true,
                'show_admin_column'     => false,
                'query_var'             => true,
                'rewrite'               => array('slug' => 'diseases'),
            );
			
            register_taxonomy('diseases', array('prescription'), $diseases_args);
            
             //Regirster Laboratory Tests Taxonomy
             $labe_labels = array(
                'name'              => esc_html__('Laboratory Tests', 'doccure_core'),
                'singular_name'     => esc_html__('Laboratory Tests','doccure_core'),
                'search_items'      => esc_html__('Search Laboratory Tests', 'doccure_core'),
                'all_items'         => esc_html__('All Laboratory Tests', 'doccure_core'),
                'parent_item'       => esc_html__('Parent Laboratory Tests', 'doccure_core'),
                'parent_item_colon' => esc_html__('Parent Laboratory Tests:', 'doccure_core'),
                'edit_item'         => esc_html__('Edit Laboratory Tests', 'doccure_core'),
                'update_item'       => esc_html__('Update Laboratory Tests', 'doccure_core'),
                'add_new_item'      => esc_html__('Add New Laboratory Tests', 'doccure_core'),
                'new_item_name'     => esc_html__('New Laboratory Tests Name', 'doccure_core'),
                'menu_name'         => esc_html__('Laboratory Tests', 'doccure_core'),
            );
            
            $labe_args = array(
                'hierarchical'          => true,
                'labels'                => $labe_labels,
                'show_ui'               => true,
                'show_admin_column'     => false,
                'query_var'             => true,
                'rewrite'               => array('slug' => 'laboratory-tests'),
            );
			
            register_taxonomy('laboratory_tests', array('prescription'), $labe_args);

            register_post_type('prescription', $args);
			
        }

        /**
		 * Add service meta
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
		public function doccure_diseases_meta() { ?>
			<div class="form-field">
				<label for="speciality"><?php esc_html_e( 'Select speciality', 'doccure_core' ); ?></label>
				<?php doccure_get_specialities_list('speciality');?>
			</div>
		<?php
		}

		/**
		 * Edit service meta
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
		public	function doccure_diseases_meta_edit($term) { 
			$specialities 			= get_term_meta( $term->term_id, 'speciality', true );
			$current_specialities	= !empty( $specialities ) ? $specialities : '';
			
			?>
			<tr class="form-field">
				<th scope="row" valign="top"><label for="specialities"><?php esc_html_e( 'Select a speciality', 'doccure_core' ); ?></label></th>
				<td>
					<?php doccure_get_specialities_list('speciality',$current_specialities);?>
				</td>
			</tr>
		<?php
		}

		/**
		 * Save service meta
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
		public	function doccure_diseases_meta_save( $term_id ) {
			if( !empty( $_POST['speciality'] ) && $_POST['speciality'] ) {
				add_term_meta( $term_id, 'speciality', $_POST['speciality'] );
			}
		}  
		
		/**
		 * update service meta
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
		public	function doccure_diseases_meta_update( $term_id ) {
			if( !empty( $_POST['speciality'] ) && $_POST['speciality'] ) {
				update_term_meta( $term_id, 'speciality', $_POST['speciality'] );
			}
		} 
    }

    new doccure_Prescription();
}

