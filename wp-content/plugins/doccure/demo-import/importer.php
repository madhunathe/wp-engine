<?php
/**
 * Import dummy data
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */

/**
 * Update healthforum comments authors
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_update_healthforum_comments_author')) {
	function doccure_update_healthforum_comments_author() {
		global $current_user;
		if(apply_filters('doccure_get_user_type', $current_user->ID ) !== 'administrator'){return;}
		
		$the_query = new WP_Query( array ( 'orderby' => 'rand','posts_per_page' => -1 ,'post_type' => array('healthforum')) );
		if( $the_query->have_posts() ){
			while ( $the_query->have_posts() ) : $the_query->the_post();
				$post_id	=  get_the_ID();
				 $comments = get_comments(array(
					'post_id' => $post_id));
				if( !empty( $comments ) ){
					foreach($comments as $comment) {
						$posts = new WP_Query( array ( 'orderby' => 'rand', 'posts_per_page' => 1 ,'post_type' => array('doctors')) );
						while ( $posts->have_posts() ) : $posts->the_post();
							global $post;
							$author_id 							= get_post_meta($post->ID, '_linked_profile', true);
							$comment_data['comment_ID'] 		= $comment->comment_ID;
							$comment_data['user_id'] 			= $author_id;
							wp_update_comment( $comment_data );
						endwhile;
					}
				}
			endwhile;
			wp_reset_postdata();
		}
	}
}

/**
 * Update hospital team author/doctors
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_update_post_auther_hospital_team')) {
	function doccure_update_post_auther_hospital_team() {
		global $current_user;
		if(apply_filters('doccure_get_user_type', $current_user->ID ) !== 'administrator'){return;}
		
		$query_args = array(
			'posts_per_page' 	  => -1,
			'post_type' 	 	  => array( 'hospitals_team' ),
			'ignore_sticky_posts' => 1
		);
		
		$all_posts = get_posts($query_args);
		if( !empty( $all_posts ) ){
			foreach( $all_posts as $key => $item ){
				$comment_post_ID	= $item->ID;
				$doc_args = array(
					'posts_per_page' 	  => 5,
					'post_type' 	 	  => array( 'doctors' ),
					'ignore_sticky_posts' => 1
				);

				$all_doc = get_posts($doc_args);
				
				if( !empty( $all_doc ) ){
					foreach( $all_doc as $key => $item_doc ){
						$author_id 			= get_post_meta($item_doc->ID, '_linked_profile', true);
						if( !empty( $author_id ) ){
							$arg = array(
								'ID' 			=> $comment_post_ID,
								'post_status'	=> 'publish',
								'post_author' 	=> $author_id,
							);

							wp_update_post( $arg );
						}
					}
				}
			}
		}
	}
}

/**
 * Update post authors for healthforum
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_update_post_auther_healthforum')) {
	function doccure_update_post_auther_healthforum() {
		global $current_user;
		if(apply_filters('doccure_get_user_type', $current_user->ID ) !== 'administrator'){return;}
		
		$posts = new WP_Query( array ( 'orderby' => 'rand', 'posts_per_page' => 20 ,'post_type' => array('doctors')) );
		if( $posts->have_posts() ){
			while ( $posts->have_posts() ) : $posts->the_post();
				$comment_post_ID	= get_the_ID();
				$author_id 			= get_post_meta($comment_post_ID, '_linked_profile', true);
					$the_query = new WP_Query( array ( 'orderby' => 'rand', 'posts_per_page' => 3 ,'post_type' => array('healthforum')) );
					// output the random post
					while ( $the_query->have_posts() ) : $the_query->the_post();
						global $post;
						$arg = array(
							'ID' 			=> $post->ID,
							'post_status'	=> 'publish',
							'post_author' 	=> $author_id,
						);
						wp_update_post( $arg );

					endwhile;

			wp_reset_postdata();
			endwhile;
			wp_reset_postdata();
		}
	}
}


/**
 * Update post authors
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_update_post_auther')) {
	function doccure_update_post_auther() {
		global $current_user;
		if(apply_filters('doccure_get_user_type', $current_user->ID ) !== 'administrator'){return;}
		
		$posts = new WP_Query( array ( 'posts_per_page' => -1 ,'post_type' => array('post')) );
		if( $posts->have_posts() ){
			while ( $posts->have_posts() ) : $posts->the_post();
				$comment_post_ID	= get_the_ID();
				$comments = get_comments(array(
					'post_id' => $comment_post_ID));

				if( !empty( $comments ) ){
					foreach($comments as $comment) {
						$doctors = new WP_Query( array ( 'orderby' => 'rand', 'posts_per_page' => 1 ,'post_type' => array('doctors')) );
						while ( $doctors->have_posts() ) : $doctors->the_post();
							global $post;
							$author_id 							= get_post_meta($post->ID, '_linked_profile', true);
							$comment_data['comment_ID'] 		= $comment->comment_ID;
							$comment_data['user_id'] 			= $author_id;
							wp_update_comment( $comment_data );
						endwhile;
					}
				}

				$the_query = new WP_Query( array ( 'orderby' => 'rand', 'posts_per_page' => '1' ,'post_type' => array('doctors')) );
				if( $the_query->have_posts() ){
					// output the random post
					while ( $the_query->have_posts() ) : $the_query->the_post();
						global $post;

						$author_id 		= get_post_meta($post->ID, '_linked_profile', true);
						$arg = array(
							'ID' 			=> $comment_post_ID,
							'post_status'	=> 'publish',
							'post_author' 	=> $author_id,
						);
						wp_update_post( $arg );

					endwhile;

					wp_reset_postdata();
				}
			
			endwhile;
			wp_reset_postdata();
		}
	}
}


/**
 * Import Languages
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_import_languages')) {
	//add_action( 'init', 'doccure_import_languages' );
    function doccure_import_languages() {
		global $current_user;
		if(apply_filters('doccure_get_user_type', $current_user->ID ) !== 'administrator'){return;}
		
        $langs	= doccure_prepare_languages();
		foreach ( $langs as $key => $lang ) {
			$args = array('slug' => $key,'description'=> '','parent'=> 0);
			wp_insert_term( $lang, 'languages', $args );
        }
    }
}

/**
 * Get Random ID
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if(!function_exists('doccure_get_random_ids') ) {
	function doccure_get_random_ids($scale,$start,$end){
		shuffle($scale);
		$random_chords = array_slice($scale, 0, rand($start, $end));

		return $random_chords;
	}
}

/**
 * Get Random Key
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if(!function_exists('doccure_get_random_key') ) {
	function doccure_get_random_key($array){
		$key	= array_rand($array);
	   return $key;
	}
}