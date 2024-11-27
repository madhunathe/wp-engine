<?php

/**
 *
 * Class used as base to create theme Notifications
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @since 1.0
 */
if (!class_exists('doccure_Prepare_Notification')) {

    class doccure_Prepare_Notification {

        function __construct() {
            // 
        }

        /**
         * 
         * @param type $message
         */
        public static function doccure_success($msg_type = '', $message = '',$button='',$button_text='') {
            global $post;
            $output = '';
			$output .= '<div class="dc-jobalerts">';
            $output .= '<div class = "alert alert-success alert-dismissible fade show">';
			
			if( !empty( $msg_type ) ){
				$output .= '<em>' . esc_html( $msg_type ) . '&nbsp;:&nbsp;</em>';
			}
			
            $output .= '<span>';
            $output .= $message;
            $output .= '</span>';
			
			if( !empty( $button ) ){
				$output .= '<a target="_blank" href="'.esc_attr( $button ).'" class="dc-alertbtn success">'.esc_html( $button_text ).'</a>';
			}
			
			$output .= '<a href="javascript:;" class="close" data-dismiss="alert" aria-label=""><i class="fa fa-close"></i></a>';
            $output .= '</div>';
			$output .= '</div>';
            echo do_shortcode($output);
        }

        /**
         * 
         * @param type $message
         */
        public static function doccure_error($msg_type = '', $message = '',$button='',$button_text='') {
            global $post;
            $output = '';
			$output .= '<div class="dc-jobalerts">';
            $output .= '<div class = "alert alert-danger alert-dismissible fade show">';
			
			if( !empty( $msg_type ) ){
				$output .= '<em>' . esc_html( $msg_type ) . '&nbsp;:&nbsp;</em>';
			}
			
            $output .= '<span>';
            $output .= $message;
            $output .= '</span>';
			
			if( !empty( $button ) ){
				$output .= '<a target="_blank" href="'.esc_attr( $button ).'" class="dc-alertbtn danger">'.esc_html( $button_text).'</a>';
			}
			
			$output .= '<a href="javascript:;" class="close" data-dismiss="alert" aria-label=""><i class="fa fa-close"></i></a>';
            $output .= '</div>';
			$output .= '</div>';
            echo do_shortcode($output);
        }

        /**
         * 
         * @param type $message
         */
        public static function doccure_info($msg_type = '', $message = '',$button='',$button_text='') {
            global $post;
            $output = '';
			$output .= '<div class="dc-jobalerts">';
            $output .= '<div class = "alert alert-primary alert-dismissible fade show">';
			
			if( !empty( $msg_type ) ){
				$output .= '<em>' . esc_html( $msg_type ) . '&nbsp;:&nbsp;</em>';
			}
			
            $output .= '<span>';
            $output .= $message;
            $output .= '</span>';
			
			if( !empty( $button ) ){
				$output .= '<a target="_blank" href="'.esc_attr( $button ).'" class="dc-alertbtn primary">'.esc_html( $button_text ).'</a>';
			}
			
			$output .= '<a href="javascript:;" class="close" data-dismiss="alert" aria-label=""><i class="fa fa-close"></i></a>';
            $output .= '</div>';
			$output .= '</div>';
            echo do_shortcode($output);
        }

        /**
         * 
         * @param type $message
         */
        public static function doccure_warning($msg_type = '', $message = '',$button='',$button_text='') {
            global $post;
            $output = '';
			$output .= '<div class="dc-jobalerts">';
            $output .= '<div class = "alert alert-warning fade show">';
            if( !empty( $msg_type ) ){
                $output .= '<em>' . esc_html( $msg_type ) . '&nbsp;:&nbsp;</em>';
            }
			
			$output .= '<span>';
            $output .= $message;
            $output .= '</span>';
			
			if( !empty( $button ) ){
				$output .= '<a target="_blank" href="'.esc_attr( $button ).'" class="dc-alertbtn warning">'.esc_html( $button_text ).'</a>';
			}

            $output .= '</div>';
			$output .= '</div>';
            echo do_shortcode($output);
        }

    }

    new doccure_Prepare_Notification();
}
