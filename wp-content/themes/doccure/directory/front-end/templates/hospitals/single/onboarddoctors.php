<?php
/**
 *
 * The template used for hospital onboard doctors
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id 	= $post->ID;
$name		= doccure_full_name( $post_id );
$name		= !empty( $name ) ? $name : ''; 
$author_id 	= get_post_field ('post_author', $post_id);

$show_posts 	= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$pg_page 		= get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged 		= get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
$paged 			= max($pg_page, $pg_paged);

$order 			= 'DESC';
$sorting 		= 'ID';

$meta_query_args	= array();
$args 			= array(
					'posts_per_page' 	=> $show_posts,
					'post_type' 		=> 'hospitals_team',
					'orderby' 			=> $sorting,
					'order' 			=> $order,
					'post_status' 		=> array('publish'),
					'paged' 			=> $paged,
					'suppress_filters' 	=> false
				);

$meta_query_args[] = array(
						'key' 		=> 'hospital_id',
						'value' 	=> $post_id,
						'compare' 	=> '='
					);

$query_relation 	= array('relation' => 'AND',);
$args['meta_query'] = array_merge($query_relation, $meta_query_args);

$query 				= new WP_Query($args);
$count_post 		= $query->found_posts;

?>
<div class="dc-contentdoctab dc-location-holder tab-pane fade" id="locations">
	<div class="dc-searchresult-holder">
		<div class="dc-searchresult-head">
			<div class="dc-title"><h4><?php esc_html_e('Onboard Doctors','doccure');?></h4></div>
		</div>
		<div class="dc-searchresult-grid dc-searchresult-list dc-searchvlistvtwo">
			<?php if( $query->have_posts() ){ ?>
				<?php 
					while ($query->have_posts()) : $query->the_post();
						global $post,$doc_post_id;
						$doctors_id 	= get_post_field ('post_author', $post->ID);
						if( !empty( $doctors_id ) ){
							$doc_id		= doccure_get_linked_profile_id($doctors_id);
							if( !empty( $doc_id ) ){ ?>
								<div class="dc-docpostholder dc-search-doctors">
									<div class="dc-docpostcontent 3">
										<div class="dc-searchvtwo">
											<?php do_action('doccure_get_doctor_thumnail',$doc_id);?>
											<?php do_action('doccure_get_doctor_details',$doc_id);?>
											<?php do_action('doccure_get_doctor_services',$doc_id,'services');?>
										</div>
										<?php do_action('doccure_get_doctor_booking_information',$doc_id);?>
									</div>
								</div>
								<?php
							}
							
						}
					endwhile;
					wp_reset_postdata();
	
					if (!empty($count_post) && $count_post > $show_posts) {
						doccure_prepare_pagination($count_post, $show_posts);
					}
				 } else{ ?>
				<?php do_action('doccure_empty_records_html','dc-empty-offered-services dc-emptyholder-sm',esc_html__( 'No Onboard Doctors.', 'doccure' ));?>
			<?php } ?>
		</div>
	</div>
</div>