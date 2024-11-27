<?php
/**
 * @Class		Process Form Elements
 * @package	 	WordPress
 * @link 		https://themeforest.net/user/dreamstechnologies
 */

// die if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	die('no kiddies please!'); 
}

if (!class_exists('MetaboxHelper')) {

    class MetaboxHelper {

        public function __construct() {
            // do something
        }

        public static function am_sanitize_class($class) {
            $class = str_replace(array(' ', ',', '.', '"', "'", '/', "\\", '+', '=', ')', '(', '*', '&', '^', '%', '$', '#', '@', '!', '~', '`', '<', '>', '?', '[', ']', '{', '}', '|', ':',), '-', $class);
            $class = sanitize_html_class($class);
            return $class;
        }

        public function am_sanitize_id($id) {
            $id = str_replace(array(' ', ',', '.', '"', "'", '/', "\\", '+', '=', ')', '(', '*', '&', '^', '%', '$', '#', '@', '!', '~', '`', '<', '>', '?', '[', ']', '{', '}', '|', ':',), '_', $id);
            $id = sanitize_html_class($id);
            return $id;
        }

        public static function am_sanitize_name($name) {
            $name = str_replace(array(' ', ',', '.', '"', "'", '/', "\\", '+', '=', ')', '(', '*', '&', '^', '%', '$', '#', '@', '!', '~', '`', '<', '>', '?', '[', ']', '{', '}', '|', ':',), '_', $name);
            $name = sanitize_html_class($name);
            return $name;
        }

        public static function am_dropdown_numbers($start = 1, $end = 500) {

            $am_integer = array();
            for ($i = $start; $i <= $end; $i++) {
                $am_integer[$i] = $i;
            }

            return $am_integer;
        }

    }

    $MetaboxHelper = new MetaboxHelper();
}

if( ! class_exists( 'AMetaboxes' ) ) {
	class AMetaboxes extends MetaboxHelper {
	
		public function __construct(){
			// do something
		}
		
		/*------------------------------------------------
		 * Process Left Menu
		 *-----------------------------------------------*/
		public function do_process_menu( $name = '' , $id = '' , $icon = '' , $open = '' ){
			global $post;
			$am_is_open	=  $open == true ? 'class=open' : '';
			$am_menu_icon  =  ! empty( $icon ) ? 'icon-'.$icon : 'icon-pushpin';
			$am_html	   = '<li>';
			$am_html	  .= '<a href="#am_'.esc_attr( $id ).'_tab" '.$am_is_open.'><i class="'.esc_attr( $am_menu_icon ).'"></i><strong>'.$name.'</strong></a>';
			$am_html	  .= '</li>';
			echo do_shortcode( $am_html );
		}
		
		/*------------------------------------------------
		 * Process Form Input type text
		 *-----------------------------------------------*/
		public function form_process_text( $atts = '' ){
			global $post;
			extract( $atts );
			$post_type	= get_post_type( $post->ID );
			$array_vals = get_post_meta($post->ID, 'am_'.$post_type.'_data',true);
			if( !empty( $array_vals['am_' . $id] ) ) {
				$db_val		= $array_vals['am_' . $id];
				if( !empty( $repeater_id )  ) {
					$db_val = $array_vals['am_' .$repeater_id][$count_id][$field_name];
				}
			}
			
			if( isset( $db_val ) && $db_val !='' ) {
				$val = $db_val;
			} else {
				$val = $std;
			}
			
			if( !empty( $option ) && $option === 'single' ) {
				$name_attr = $id ;
				$val	= get_post_meta($post->ID,$id,true);
			} else {
				$name_attr = 'am_'.$id ;
			}
			
			$am_html = '';
			
			$classes	= !empty( $classes ) ? $classes.'-wrapper' :  '';
			$am_html = '';
			if( !empty( $wrapper_start ) && $wrapper_start === 'yes' ){
				$am_html .= '<div class="am_main_wraper '.$wrapper_class.'">';
			}
			
			$am_html .= '<div class="am_option_wraper '.$classes.'">';
				$am_html .= '<div class="am_desc">';
					$am_html .= '<label for="am_' . $this->am_sanitize_id( $id ) . '">';
					$am_html .= $name;
					$am_html .= '</label>';
					if($desc) {
						$am_html .= '<p>' . $desc . '</p>';
					}
				$am_html .= '</div>';
				$am_html .= '<div class="am_field">';
					$am_html .= '<input type="text" id="am_' . $id . '" name="'.$name_attr.'" value="' . esc_attr( $val ) . '" />';
				$am_html .= '</div>';
			$am_html .= '</div>';
			if( !empty( $wrapper_end ) && $wrapper_end === 'yes' ){
				$am_html .= '</div>';
			}
			echo $am_html;
		}
		
		/*------------------------------------------------
		 * Process Form Input type text
		 *-----------------------------------------------*/
		public function form_process_select( $atts = '' ){
			global $post;
			extract( $atts );
			$post_type		= get_post_type( $post->ID );
			
			$array_vals 	= get_post_meta($post->ID, 'am_'.$post_type.'_data',true);
			if( !empty( $array_vals['am_' . $id] ) ) {
				$db_value		= $array_vals['am_' . $id];
			}
			if (isset($db_value) and $db_value <> '') { 
				$selected_value = $db_value; 
			}else{
				$selected_value = $std;
			}

			$am_html = '';
			
			$classes	= !empty( $classes ) ? $classes.'-wrapper' : '';
			$am_html = '';
			if( !empty( $wrapper_start ) && $wrapper_start === 'yes' ){
				$am_html .= '<div class="am_main_wraper '.$wrapper_class.'">';
			}
			
			$am_html .= '<div class="am_option_wraper '.$classes.'">';
				$am_html .= '<div class="am_desc">';
					$am_html .= '<label for="am_' . $this->am_sanitize_id( $id ) . '">';
					$am_html .= $name;
					$am_html .= '</label>';
					if($desc) {
						$am_html .= '<p>' . $desc . '</p>';
					}
				$am_html .= '</div>';
				$am_html .= '<div class="am_field dropdown-style">';
					$am_html .= '<select id="am_' . $this->am_sanitize_id( $id ) . '" name="am_' . $this->am_sanitize_name( $id ) . '">';
					foreach($options as $key => $option) {
						if( $selected_value == $key ) {
							$selected = 'selected="selected"';
						} else {
							$selected = '';
						}
	
						$am_html .= '<option ' . $selected . 'value="' . $key . '">' . $option . '</option>';
					}
					$am_html .= '</select>';
				$am_html .= '</div>';
			$am_html .= '</div>';
			
			if( !empty( $wrapper_end ) && $wrapper_end === 'yes' ){
				$am_html .= '</div>';
			}
			
			echo $am_html;
		}
		
		/*------------------------------------------------
		 * Process Form Input type textarea
		 *-----------------------------------------------*/
		public function form_process_textarea( $atts = '' ){
			global $post;
			extract( $atts );
			
			$post_type		= get_post_type( $post->ID );
			$array_vals 	= get_post_meta($post->ID, 'am_'.$post_type.'_data',true);
			if( !empty( $array_vals['am_' . $id] ) ) {
				$db_val			= $array_vals['am_' . $id];
			}
			if( !empty( $repeater_id )  ) {
				$db_val = $array_vals['am_' .$repeater_id][$count_id][$field_name];
			}
			
			if( !empty($db_val) ) {
				$val = $db_val;
			} else {
				$val = $std;
			}
			
			$am_html = '';
			$classes	= !empty( $classes ) ? $classes.'-wrapper' : '';
			$am_html = '';
			if( !empty( $wrapper_start ) && $wrapper_start === 'yes' ){
				$am_html .= '<div class="am_main_wraper '.$wrapper_class.'">';
			}
			
			$am_html .= '<div class="am_option_wraper '.$classes.'">';
				$am_html .= '<div class="am_desc">';
					$am_html .= '<label for="am_' . $this->am_sanitize_id( $id ) . '">';
					$am_html .= $name;
					$am_html .= '</label>';
					if($desc) {
						$am_html .= '<p>' . $desc . '</p>';
					}
				$am_html .= '</div>';
				$am_html .= '<div class="am_field">';
					$am_html .= '<textarea cols="125" rows="8" id="am_' . $id . '" name="am_' .$id . '">' . $val . '</textarea>';
				$am_html .= '</div>';
			$am_html .= '</div>';
			if( !empty( $wrapper_end ) && $wrapper_end === 'yes' ){
				$am_html .= '</div>';
			}
			echo $am_html;
		}
		
		/*------------------------------------------------
		 * Process Form Input type hidden
		 *-----------------------------------------------*/
		public function form_process_hidden( $id = '' , $val = '' , $echo = true ){
			global $post;
			if( isset( $echo ) && $echo == true ) {
				echo '<input type="hidden" id="am_' . $this->am_sanitize_id( $id ) . '" name="am_' . $this->am_sanitize_name( $id ) . '" value="' . esc_attr( $val ) . '" />';
			} else{
				return '<input type="hidden" id="am_' . $this->am_sanitize_id( $id ) . '" name="am_' . $this->am_sanitize_name( $id ) . '" value="' . esc_attr( $val ) . '" />';
			}
		}
		
		/*------------------------------------------------
		 * Process Form Input type Submit
		 *-----------------------------------------------*/
		public function form_process_submit( $atts = '' ){
			global $post;
		}
		
		/*------------------------------------------------
		 * Process Form Input type Button
		 *-----------------------------------------------*/
		public function form_process_button( $atts = '' ){
			global $post;
		}
		
		/*------------------------------------------------
		 * Process Form Input type Date
		 *-----------------------------------------------*/
		public function form_process_date( $atts = '' ){
			global $post;
		}
		
		/*------------------------------------------------
		 * Process Form Input type Range Slider
		 *-----------------------------------------------*/
		public function form_process_range( $atts = '' ){
			global $post;
		}
		
		/*------------------------------------------------
		 * Process Form Input type Checkbox
		 *-----------------------------------------------*/
		public function form_process_radio_working( $atts = '' ){
			global $post;
			extract( $atts );
			$post_type		= get_post_type( $post->ID );
			$array_vals 	= get_post_meta($post->ID, 'am_'.$post_type.'_data',true);
			
			if( !empty( $array_vals['am_' . $id] ) ) {
				$db_val			= $array_vals['am_' . $id];
			}
			
			$db_other_time			= !empty( $array_vals['am_other_time'] ) ? $array_vals['am_other_time'] : '';
			$all_time	= '';
			$others		= '';
			$display	= 'none';
			if( isset( $db_val ) && $db_val !='' ) {
				if( $db_val === 'yes' ){
					$all_time	= 'checked';
					$display	= 'none';
					$style		='';
				} else if( $db_val ==='others') {
					$others		= 'checked';
					$display	= 'block';
				}
				
			} else {
				$val = $std;
			}
			
			
			$am_html = '';
			$classes	= !empty( $classes ) ? $classes.'-wrapper' : '';
			$am_html = '';
			if( !empty( $wrapper_start ) && $wrapper_start === 'yes' ){
				$am_html .= '<div class="am_main_wraper '.$wrapper_class.'">';
			}
			
			$am_html .= '<div class="am_option_wraper '.$classes.'">';
				$am_html .=	'<h5>'.esc_html__('Working Time','doccure_core').'</h5>';
				$am_html .= '<div class="am_option_wraper">';
				$am_html .= '<div class="am_desc">';
				$am_html .= '<label for="am_' . $this->am_sanitize_id( $id ) . '">';
				$am_html .= esc_html__('24/7 Working Time','doccure_core');
				$am_html .= '</label>';
				$am_html .= '</div>';
				$am_html .= '<div class="am_field">';
				$am_html .= '<input id="dc-spaces1" class="dc-spaces" type="radio" '.$all_time.' id="am_' . $this->am_sanitize_id( $id ) . '" name="am_' . $this->am_sanitize_name( $id ) . '" value="yes" />';
				$am_html .= '</div>';
				$am_html .= '</div>';
			
				$am_html .= '<div class="am_option_wraper">';
				$am_html .= '<div class="am_desc">';
				$am_html .= '<label for="am_' . $this->am_sanitize_id( $id ) . '">';
				$am_html .= esc_html__('Others','doccure_core');
				$am_html .= '</label>';
				$am_html .= '</div>';
				$am_html .= '<div class="am_field">';
				$am_html .= '<input id="dc-spaces2" class="dc-spaces" type="radio" '.$others.'  name="am_' . $this->am_sanitize_name( $id ) . '" value="others" />';
				$am_html .= '</div>';
				$am_html .= '</div>';
			
			$am_html .= '</div>';
			$am_html .= '<div class="am_option_wraper am_other_time" style="display:' .$display. ';">';
					$am_html .= '<div class="am_desc">';
						$am_html .= '<label for="am_' . $this->am_sanitize_id( $id ) . '">';
						$am_html .= esc_html__('Availability Text','doccure_core');
						$am_html .= '</label>';
					$am_html .= '</div>';
					$am_html .= '<div class="am_field">';
						$am_html .= '<input type="text" name="am_other_time" class="form-control" value="'.$db_other_time.'" >';
					$am_html .= '</div>';
			
			$am_html .= '</div>';
			if( !empty( $wrapper_end ) && $wrapper_end === 'yes' ){
				$am_html .= '</div>';
			}
			echo $am_html;
		}
		
		/*------------------------------------------------
		 * Process Form Input type Checkbox
		 *-----------------------------------------------*/
		public function form_process_checkbox( $atts = '' ){
			global $post;
			extract( $atts );
			$post_type		= get_post_type( $post->ID );
			$array_vals 	= get_post_meta($post->ID, 'am_'.$post_type.'_data',true);
			
			if( !empty( $array_vals['am_' . $id] ) ) {
				$db_val			= $array_vals['am_' . $id];
			}
			
			if( isset( $db_val ) && $db_val !='' ) {
				$val = $db_val;
			} else {
				$val = $std;
			}
			
			$checked	= !empty( $val ) && $val ==='yes' ? 'checked' : '';
			$am_html = '';
			$classes	= !empty( $classes ) ? $classes.'-wrapper' : '';
			$am_html = '';
			if( !empty( $wrapper_start ) && $wrapper_start === 'yes' ){
				$am_html .= '<div class="am_main_wraper '.$wrapper_class.'">';
			}
			
			$am_html .= '<div class="am_option_wraper '.$classes.'">';
				$am_html .= '<div class="am_desc">';
					$am_html .= '<label for="am_' . $this->am_sanitize_id( $id ) . '">';
					$am_html .= $name;
					$am_html .= '</label>';
					if($desc) {
						$am_html .= '<p>' . $desc . '</p>';
					}
				$am_html .= '</div>';
				$am_html .= '<div class="am_field">';
					$am_html .= $this->form_process_hidden( $id , 'no' , false );
					$am_html .= '<input type="checkbox" '.$checked.' id="am_' . $this->am_sanitize_id( $id ) . '" name="am_' . $this->am_sanitize_name( $id ) . '" value="yes" />';
				$am_html .= '</div>';
			$am_html .= '</div>';
			if( !empty( $wrapper_end ) && $wrapper_end === 'yes' ){
				$am_html .= '</div>';
			}
			echo $am_html;
		}
		
		/*------------------------------------------------
		 * Process Form Input type Checkbox for working days
		 *-----------------------------------------------*/
		public function form_process_checkbox_days( $atts = '' ){
			global $post;
			extract( $atts );
			$post_type		= get_post_type( $post->ID );
			$array_vals 	= get_post_meta($post->ID, 'am_'.$post_type.'_data',true);
			$days			= doccure_get_week_array();
			if( !empty( $array_vals['am_' . $id] ) ) {
				$db_val			= $array_vals['am_' . $id];
			}
			
			if( isset( $db_val ) && $db_val !='' ) {
				$val = $db_val;
			} else {
				$val = $std;
			}
			
			$checked	= !empty( $val ) && $val ==='yes' ? 'checked' : '';
			$am_html = '';
			$classes	= !empty( $classes ) ? $classes.'-wrapper' : '';
			$am_html = '';
			
			if( !empty( $wrapper_start ) && $wrapper_start === 'yes' ){
				$am_html .= '<div class="am_main_wraper '.$wrapper_class.'">';
			}
			
			//fw_print($days);
			
			$am_html .= '<div class="am_option_wraper '.$classes.'">';
					$am_html .= '<h5>';
					$am_html .= $name;
				$am_html .= '</h5>';
				foreach( $days as $d_key => $d_val ) {
					if( !empty( $db_val ) && in_array($d_key,$db_val) ) {
						$checked	= 'checked';
					} else {
						$checked	= '';
					}
					
					$am_html .= '<div class="am_option_wraper">';
					$am_html .= '<div class="am_desc">';
					$am_html .= '<label for="am_' . $this->am_sanitize_id( $d_key ) . '">';
					$am_html .= ucfirst( $d_key );
					$am_html .= '</label>';
					$am_html .= '</div>';
					$am_html .= '<div class="am_field">';
					$am_html .= '<input type="checkbox" '.$checked.' id="dc-'.esc_attr( $d_key ).'"  name="am_' . $this->am_sanitize_name( $id ) . '[]" value="'.esc_html( $d_key ).'" />';
					$am_html .= '</div>';
					$am_html .= '</div>';
				}
			
			$am_html .= '</div>';
			if( !empty( $wrapper_end ) && $wrapper_end === 'yes' ){
				$am_html .= '</div>';
			}
			
			echo $am_html;
		}
		
		/*------------------------------------------------
		 * Process Form Input type Radio
		 *-----------------------------------------------*/
		public function form_process_radio( $atts = '' ){
			global $post;
		}
		
		/*------------------------------------------------
		 * Repeater single
		 *-----------------------------------------------*/
		public function form_repeater_single( $atts = '' ){
			global $post;
			extract( $atts );
			$db_vals	= array();
			$array_vals	= doccure_get_post_meta( $post->ID);
			
			if( !empty( $array_vals['am_' . $id] ) ) {
				$db_vals		= $array_vals['am_' . $id];
			}
			
			$am_html 		= '';
			$classes		= !empty( $classes ) ? $classes.'-wrapper' : '';
			$placeholder	= !empty( $placeholder ) ? $placeholder : '';
			
			$am_html = '';
			if( !empty( $wrapper_start ) && $wrapper_start === 'yes' ){
				$am_html .= '<div class="am_main_wraper '.$wrapper_class.'">';
			}
			
			$am_html .= '<div class="am_option_wraper '.$classes.'">';
				$am_html .= '<div class="am_desc">';
					$am_html .= '<label for="am_' . $this->am_sanitize_id( $id ) . '">';
					$am_html .= $name;
					$am_html .= '</label>';
					if($desc) {
						$am_html .= '<p>' . $desc . '</p>';
					}
				$am_html .= '</div>';
				$am_html .= '<div class="am_field">';
				$am_html .= '<div class="repeater-wrap-'.$id.'">';
				foreach ( $db_vals as $i => $val ) {
						$am_html .= '<div class="repeater-wrap-inner">';
							$am_html .= '<div class="remove-repeater"><span class="dashicons dashicons-trash"></span></div>';
							if( $field_type === 'text' ) {
								$am_html .= '<div class="am_field">';
									$am_html .= '<input placeholder="'.$name.'" type="text" id="field-'.$id.'" name="am_'.$id.'['.$i.']" value="'.$val.'">';
								$am_html .= '</div>';
							}  
						
						$am_html .= '</div>';
				}
			
				$am_html .= '</div>';
				$am_html .= '<input class="button repeater-repeather-btn add-repeater-'.$id.'" data-placeholder="' . esc_attr( $placeholder ) . '" type="button" id="am_' . $this->am_sanitize_id( $id ) . '" value="' . esc_attr( $btn_text ) . '" />';
				$am_html .= '</div>';
				$am_html .= '</div>';
				if( !empty( $wrapper_end ) && $wrapper_end === 'yes' ){
					$am_html .= '</div>';
				}
			
				echo $am_html;
				
		}
		
		/*------------------------------------------------
		 * Repeater multiple
		 *-----------------------------------------------*/
		public function form_nested_repeater( $atts = '' ){
			
			global $post;
			extract( $atts );
			$post_type		= get_post_type( $post->ID );
			$array_vals 	= get_post_meta($post->ID, 'am_'.$post_type.'_data',true);
			if( !empty( $array_vals['am_' . $id] ) ){
				$db_vals		= $array_vals['am_' . $id];
			}
			$total_val		= !empty( $db_vals ) ? count( $db_vals ) : 0;
			$am_html = '';
			$classes	= !empty( $classes ) ? $classes.'-wrapper' : '';
			$am_html = '';
			if( !empty( $wrapper_start ) && $wrapper_start === 'yes' ){
				$am_html .= '<div class="am_main_wraper '.$wrapper_class.'">';
			}
			
			$am_html .= '<div class="am_option_wraper '.$classes.'">';
				$am_html .= '<div class="am_desc">';
					$am_html .= '<label for="am_' . $this->am_sanitize_id( $id ) . '">';
					$am_html .= $name;
					$am_html .= '</label>';
					if($desc) {
						$am_html .= '<p>' . $desc . '</p>';
					}
				$am_html .= '</div>';
				$am_html .= '<div class="am_field">';
				$am_html .= '<div class="repeater-wrap-'.$id.'">';
				if( !empty( $total_val ) &&  $total_val > 0 ) {
					foreach( $db_vals as $key => $item ){
						$am_html .= '<div class="repeater-wrap-inner specialities_parents" id="'.$key.'">';
							$am_html .= '<div class="remove-repeater"><span class="dashicons dashicons-trash"></span></div>';
							foreach( $fields as $field ) {
								if( $field['field_type'] === 'specialities' ) {
									$am_html .= '<div class="am_field">';
									$am_html .= '<div class="am_field dropdown-style">';
										$am_html .= apply_filters('doccure_get_specialities_list','am_specialities['.$key.'][speciality_id]',$key,0);
									$am_html .= '</div>';
									if( !empty( $item )) {
										foreach ( $item as $service_key => $service ){
											$am_html .= '<div class="repeater-wrap-inner services-item" id="'.$service_key.'">';
												$am_html .= '<div class="remove-repeater"><span class="dashicons dashicons-trash"></span></div>';
												$am_html .= '<div class="am_field">';
													$am_html .='<div class="am_field dropdown-style related-services">';
														$sp_services	= doccure_list_service_by_specialities($key);
														
														if( !empty( $sp_services ) ) {
															$am_html .='<select name="am_specialities['.$key.'][services]['.$service_key.'][service]" class="sp_services">';
															foreach ( $sp_services as $sp_service ){
																if (isset($service['service']) and $service['service'] <> '') { 
																	$selected_value = $service['service']; 
																}else{
																	$selected_value = '';
																}
																if( $selected_value == $sp_service->term_id ) {
																	$selected = 'selected="selected"';
																} else {
																	$selected = '';
																}
																$am_html .= '<option ' . $selected . 'value="' . $sp_service->term_id . '">' . $sp_service->name . '</option>';
															}
															
															$am_html .='</select>';
															$am_html .= '<div class="am_field">';
																$am_html .= '<input type="text" name="am_specialities['.$key.'][services]['.$service_key.'][price]" class="" placeholder="'. esc_html__('Price','doccure_core').'" value="'.$service['price'].'">';
															$am_html .= '</div>';
															$am_html .= '<div class="am_field">';
																$am_html .= '<textarea placeholder="'. esc_html__('Description','doccure_core').'" name="am_specialities['.$key.'][services]['.$service_key.'][description]" class="">'.$service['description'].'</textarea>';
															$am_html .= '</div>';
														}
														
													$am_html .= '</div>';
												$am_html .= '</div>';
											$am_html .= '</div>';
										}
									}
									$am_html .= '<div class="services-wrap">';
										$am_html .= '<div class="system-buttons">';
											$am_html .= '<a href="javascript:;" id="add-service-'.$key.'" data-id="'.$key.'" class="add-repeater-services">'.esc_html__('Add Services','doccure_core').'</a>';
										$am_html .= '</div>';
									$am_html .= '</div>';
									$am_html .= '</div>';
								} 
							}
						$am_html .= '</div>';
					}
				}
			
				$am_html .= '</div>';
				$am_html .= '<input class="button add-repeater-'.$id.'" type="button" id="am_' . $this->am_sanitize_id( $id ) . '" value="' . esc_attr( $btn_text ) . '" />';
				$am_html .= '</div>';
				$am_html .= '</div>';
			
				echo $am_html;
		}
		/*------------------------------------------------
		 * Repeater multiple
		 *-----------------------------------------------*/
		public function form_repeater_multiple( $atts = '' ){
			global $post;
			extract( $atts );
			$post_type		= get_post_type( $post->ID );
			$array_vals 	= get_post_meta($post->ID, 'am_'.$post_type.'_data',true);
			if( !empty( $array_vals['am_' . $id] ) ){
				$db_vals		= $array_vals['am_' . $id];
			}
			$total_val		= !empty( $db_vals ) ? count( $db_vals ) : 0;
			$am_html = '';
			$am_html .= '<div class="am_option_wraper">';
				$am_html .= '<div class="am_desc">';
					$am_html .= '<label for="am_' . $this->am_sanitize_id( $id ) . '">';
					$am_html .= $name;
					$am_html .= '</label>';
					if($desc) {
						$am_html .= '<p>' . $desc . '</p>';
					}
				$am_html .= '</div>';
				$am_html .= '<div class="am_field">';
				$am_html .= '<div class="repeater-wrap-'.$id.'">';
				if( !empty( $total_val ) &&  $total_val > 0 ) {
					foreach( $db_vals as $key => $item ){
						$am_html .= '<div class="repeater-wrap-inner">';
							$am_html .= '<div class="remove-repeater"><span class="dashicons dashicons-trash"></span></div>';
							foreach( $fields as $field ) {
								$classes	= !empty($field['classes']) ?  $field['classes'] : '';
								if( $field['field_type'] === 'text' ) {
									$am_html .= '<div class="am_field">';
										$am_html .= '<input placeholder="'.$field['name'].'" class="'.$classes.'" type="text" id="field-'.$field['id'].'" name="am_'.$id.'['.$key.']['.$field['id'].']" value="'.$db_vals[$key][$field['id']].'">';
									$am_html .= '</div>';
								} else if( $field['field_type'] === 'textarea' ) {
									$am_html .= '<div class="am_field">';
										$am_html .= '<textarea cols="125" rows="8" id="field-'.$field['id'].'" name="am_'.$id.'['.$key.']['.$field['id'].']">' .$db_vals[$key][$field['id']]. '</textarea>';
									$am_html .= '</div>';
								}else if( $field['field_type'] === 'select' ) {
									$am_html .= '<div class="am_field">';
										$am_html .= '<select id="field-'.$field['id'].'" name="am_'.$id.'['.$key.']['.$field['id'].']">';
											if (isset($db_vals[$key][$field['id']]) and $db_vals[$key][$field['id']] <> '') { 
												$selected_value = $db_vals[$key][$field['id']]; 
											}else{
												$selected_value = $std;
											}
											foreach($field['options'] as $key => $option) {
												if( $selected_value == $key ) {
													$selected = 'selected="selected"';
												} else {
													$selected = '';
												}

												$am_html .= '<option ' . $selected . 'value="' . $key . '">' . $option . '</option>';
											}
											$am_html .= '</select>';
									
									$am_html .= '</div>';
								} else if( $field['field_type'] === 'media' ) {
									$am_html .= '<div class="am_field">';
										$am_html	.= '<div class="section-upload">';
										$am_html	.= '<div class="z-option-uploader">';
										$am_html	.= '<div class="input-sec">';
										$am_html 	.= '<input id="field-'.$field['id'].$key.'" name="am_'.$id.'['.$key.']['.$field['id'].']" value="'.$db_vals[$key][$field['id']].'">';
										$am_html 	.= '<input type="hidden" id="field-'.$field['id'].$key.'" name="am_'.$id.'['.$key.'][id]" value="'.$db_vals[$key]['id'].'">';
										$am_html 	.= '<input type="hidden" name="am_'.$id.'['.$key.'][id]" value="'.$db_vals[$key]['id'].'">';
										$am_html 	.= '</div>';
										$am_html	.= '<div class="system-buttons">';
										$am_html	.= '<span id="upload" class="button system_media_upload_button">'.esc_html__('Upload','doccure_core').'</span>';
										$am_html 	.= '</div>';
										$am_html 	.= '</div>';
										$am_html 	.= '</div>';
									$am_html .= '</div>';
								}
							}
						$am_html .= '</div>';
						
					}
				}
			
				$am_html .= '</div>';
				$am_html .= '<input class="button add-repeater-'.$id.'" type="button" id="am_' . $this->am_sanitize_id( $id ) . '" value="' . esc_attr( $btn_text ) . '" />';
				$am_html .= '</div>';
				$am_html .= '</div>';
			
				echo $am_html;
				
		}
		
		/*------------------------------------------------
		 * Process Form Input type Upload
		 *-----------------------------------------------*/
		public function form_process_upload( $atts = '' ){
			global $post;
			extract( $atts );
			$post_type		= get_post_type( $post->ID );
			$array_vals 	= get_post_meta($post->ID, 'am_'.$post_type.'_data',true);
			if( !empty( $array_vals['am_' . $id] ) ){
				$db_val		= $array_vals['am_' . $id];
			}
	
			if( isset( $db_val ) && $db_val !='' ) {
				$val = $db_val;
			} else {
				$val['url'] = $std;
				$val['id'] = $std;
			}
			
			$display	= ( $val <>'' ? 'inline-block' : 'none' );
			
			$am_html = '';
			$classes	= !empty( $classes ) ? $classes.'-wrapper' : '';
			$am_html = '';
			if( !empty( $wrapper_start ) && $wrapper_start === 'yes' ){
				$am_html .= '<div class="am_main_wraper '.$wrapper_class.'">';
			}
			
			$am_html .= '<div class="am_option_wraper '.$classes.'">';
				$am_html .= '<div class="am_desc">';
					$am_html .= '<label for="am_' . $this->am_sanitize_id( $id ) . '">';
					$am_html .= $name;
					$am_html .= '</label>';
					if($desc) {
						$am_html .= '<p>' . $desc . '</p>';
					}
				$am_html .= '</div>';
				$am_html .= '<div class="am_field">';
					$am_html	.= '<div class="section-upload">';
					$am_html	.= '<div class="z-option-uploader">';
					$am_html	.= '<div class="input-sec">';
					$am_html 	.= '<input id="am_'.$this->am_sanitize_id( $id ).'" name="am_'.$this->am_sanitize_id( $id ).'[url]" type="text" class="upload" value="'.$val['url'].'" />';
					$am_html 	.= '<input type="hidden" name="am_'.$id.'[id]" value="'.$val['id'].'">';
					$am_html 	.= '</div>';
					$am_html	.= '<div class="system-buttons">';
					$am_html	.= '<span id="upload" class="button system_media_upload_button">'.esc_html__('Upload','doccure_core').'</span>';
					$am_html	.= '<span style="display:' .$display. ';" id="reset_upload" class="button remove-item" title="Upload">'.esc_html__('Remove','doccure_core').'</span>';
			
					$am_html 	.= '</div>';
					if( $thumnail ) {
						$am_html 	.= '<div class="screenshot" style="display:' .$display. ';">
									<a href="'.$val.'"><img src="'.$val.'" class="system-upload-image"></a>';
						$am_html 	.= '</div>';
					}
					$am_html 	.= '</div>';
					$am_html 	.= '</div>';
				$am_html .= '</div>';
			$am_html .= '</div>';
			if( !empty( $wrapper_end ) && $wrapper_end === 'yes' ){
				$am_html .= '</div>';
			}
			echo $am_html;
		}
		
		/*------------------------------------------------
		 * Process Form Input type Upload
		 *-----------------------------------------------*/
		public function form_process_gallery( $atts = '' ){
			global $post;
			extract( $atts );
			$post_type		= get_post_type( $post->ID );
			$array_vals 	= get_post_meta($post->ID, 'am_'.$post_type.'_data',true);
	
			if( !empty( $array_vals['am_' . $id] ) ){
				$val		= $array_vals['am_' . $id];
			} else {
				$val = $std;
			}
			
			$gallery_ids	= array();
			$display		= !empty( $val ) ? 'inline-block' : 'none' ;
			$gallery_ids	= $val;
			$am_html = '';
			$classes	= !empty( $classes ) ? $classes.'-wrapper' : '';
			$am_html = '';

			if( !empty( $wrapper_start ) && $wrapper_start === 'yes' ){
				$am_html .= '<div class="am_main_wraper '.$wrapper_class.'">';
			}
			
			$am_html .= '<div class="am_option_wraper '.$classes.'">';
				$am_html .= '<div class="am_desc">';
					$am_html .= '<label for="am_' . $this->am_sanitize_id( $id ) . '">';
					$am_html .= $name;
					$am_html .= '</label>';
					if($desc) {
						$am_html .= '<p>' . $desc . '</p>';
					}
				$am_html .= '</div>';
				$am_html .= '<div class="am_field">';
					$am_html	.= '<div class="section-upload">';
					$am_html	.= '<div class="z-option-uploader z-gallery" data-id="'.$id.'">';
				
					$am_html	.= '<div class="system-buttons">';
					$am_html	.= '<span id="upload" class="button multi_open">'.esc_html__('Add gallery','doccure_core').'</span>';
					$am_html	.= '<span style="display:' .$display. ';" id="reset_gallery" class="button remove-gallery" title="Upload">'.esc_html__('Remove all','doccure_core').'</span>';
					$am_html 	.= '</div>';
					$am_html 	.= '<div class="gallery-container" style="display:'.$display.'">';
					$am_html 	.= '<ul class="gallery-list">';			
					
					if( isset( $gallery_ids ) && $gallery_ids !='' ) {
						foreach( $gallery_ids as $key ){
							$image_path = wp_get_attachment_image_src( $key['attachment_id'] , array( 150 , 150 ) );
							
							if( isset( $image_path[0] ) && $image_path[0] != '' ) {
								$am_html 	.= '<li class="image" data-attachment_id="' . $key['attachment_id'] . '">';
								$am_html 	.= '<input type="hidden" name="am_'.$id.'['.$key['attachment_id'].'][attachment_id]" value="'.$key['attachment_id'].'">';
								$am_html 	.= '<input type="hidden" name="am_'.$id.'['.$key['attachment_id'].'][url]" value="'.$key['url'].'">';
								$am_html 	.= '<img src="' . esc_url($image_path[0]) . '" alt="gallery" />';
								$am_html 	.= '<a href="javascript:;" class="del-node"  title=""><i class="fa fa-times"></i></a>';
									
								$am_html 	.= '</li>';
							}
						}
					}

					$am_html 	.= '</ul>';
					$am_html 	.= '</div>';
					$am_html 	.= '</div>';
					$am_html 	.= '</div>';
				$am_html .= '</div>';
			$am_html .= '</div>';
			
			if( !empty( $wrapper_end ) && $wrapper_end === 'yes' ){
				$am_html .= '</div>';
			}
			echo $am_html;
		}
		
		/*------------------------------------------------
		 * Process Form Input type Color
		 *-----------------------------------------------*/
		public function form_process_color( $atts = '' ){
			global $post;
			extract( $atts );
			$post_type		= get_post_type( $post->ID );
			$array_vals 	= get_post_meta($post->ID, 'am_'.$post_type.'_data',true);
			if( !empty( $array_vals['am_' . $id] ) ){
				$db_val		= $array_vals['am_' . $id];
			}
	
			if( isset( $db_val ) && $db_val !='' ) {
				$val = $db_val;
			} else {
				$val = $std;
			}
			
			$classes	= !empty( $classes ) ? $classes.'-wrapper' : '';
			$am_html = '';
			if( !empty( $wrapper_start ) && $wrapper_start === 'yes' ){
				$am_html .= '<div class="am_main_wraper '.$wrapper_class.'">';
			}
			
			$am_html .= '<div class="am_option_wraper '.$classes.'">';
				$am_html .= '<div class="am_desc">';
					$am_html .= '<label for="am_' . $this->am_sanitize_id( $id ) . '">';
					$am_html .= $name;
					$am_html .= '</label>';
					if($desc) {
						$am_html .= '<p>' . $desc . '</p>';
					}
				$am_html .= '</div>';
				$am_html .= '<div class="am_field">';
					$am_html .= '<input type="text" class="am_color_picker" id="am_' . $this->am_sanitize_id( $id ) . '" name="am_' . $this->am_sanitize_name( $id ) . '" value="' . esc_attr( $val ) . '" />';
				$am_html .= '</div>';
			$am_html .= '</div>';
			if( !empty( $wrapper_end ) && $wrapper_end === 'yes' ){
				$am_html .= '</div>';
			}
			echo $am_html;
		}
		
		/*------------------------------------------------
		 * Process Form Input type Checkbox
		 *-----------------------------------------------*/
		public function form_process_general_menu( $menu = '' ){
		  global $post;
		  if( !empty( $menu ) ){
			  foreach( $menu as $key => $item ){
				  $this->do_process_menu( $item[0],$item[1],$item[2],$item[3] );
			  }
		  }
		}
	}
}
