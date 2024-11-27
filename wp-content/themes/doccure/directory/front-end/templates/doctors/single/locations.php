<?php
/**
 *
 * The template used for doctors locations
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post,$doccure_options;
$post_id 	= $post->ID;
$name		= doccure_full_name( $post_id );
$name		= !empty( $name ) ? $name : ''; 
$author_id 	= doccure_get_linked_profile_id($post_id,'post');

$show_posts 	= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$pg_page 		= get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged 		= get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
$paged 			= max($pg_page, $pg_paged);
$order 			= 'DESC';
$sorting 		= 'ID';

$args 			= array(
					'posts_per_page' 	=> $show_posts,
					'post_type' 		=> 'hospitals_team',
					'orderby' 			=> $sorting,
					'order' 			=> $order,
					'post_status' 		=> array('publish'),
					'author' 			=> $author_id,
					'paged' 			=> $paged,
					'suppress_filters' 	=> false
				);
$query 				= new WP_Query($args);
$count_post 		= $query->found_posts;
$doctor_location	= !empty($doccure_options['doctor_location']) ? $doccure_options['doctor_location'] : 'hospitals';
?>
<div class="dc-location-holder  " id="locations">
	<div class="dc-searchresult-holder">
		<div class="dc-searchresult-head">
			<div class="dc-title"><h4> <?php echo esc_html( $name );?>  <?php esc_html_e('Locations','doccure');?></h4></div>
		</div>
		<div class="dc-searchresult-grid dc-searchresult-list dc-searchvlistvtwo">
			<?php 
			if(!empty($doctor_location) && $doctor_location === 'both'){
			
				if( $query->have_posts() ){ 
					while ($query->have_posts()) : $query->the_post();
						global $post;
						$hospital_id	= get_post_meta($post->ID,'hospital_id',true);
	
						if( !empty( $hospital_id ) ){
							$post->ID		= $hospital_id;
							get_template_part('directory/front-end/templates/hospitals/hospitals-listing');
						}
					endwhile;
					wp_reset_postdata();
	
					if (!empty($count_post) && $count_post > $show_posts) {
						doccure_prepare_pagination($count_post, $show_posts);
					}
				}
			
				get_template_part('directory/front-end/templates/doctors/single/clinic-listing','',array( 'post_id' => $post_id)); // clinic location
			}else if(!empty($doctor_location) && $doctor_location === 'clinic'){
				get_template_part('directory/front-end/templates/doctors/single/clinic-listing','',array( 'post_id' => $post_id)); // clinic location
			}else if(!empty($doctor_location) && $doctor_location === 'hospitals'){
				if( $query->have_posts() ){ 
					while ($query->have_posts()) : $query->the_post();
						global $post;
						$hospital_id	= get_post_meta($post->ID,'hospital_id',true);
	
						if( !empty( $hospital_id ) ){
							$post->ID		= $hospital_id;
							get_template_part('directory/front-end/templates/hospitals/hospitals-listing');
						}
					endwhile;
					wp_reset_postdata();
	
					if (!empty($count_post) && $count_post > $show_posts) {
						doccure_prepare_pagination($count_post, $show_posts);
					}
				}
			}
			?>
		</div>
	</div>
</div>