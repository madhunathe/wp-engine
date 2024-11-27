<?php
/**
 *
 * The template part for displaying the dashboard statistics
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user,$doccure_options,$post;

$user_identity 	 	= $current_user->ID;
$linked_profile  	= doccure_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-bubble';

$new_messages_img	= !empty( $doccure_options['new_messages']['url'] ) ? $doccure_options['new_messages']['url'] : '';
$user_type	= apply_filters('doccure_get_user_type', $user_identity );

$post_id 		 	= $linked_profile;
$meta_query_args	= array();
$date_format		= get_option('date_format');
$appointment_date 	= !empty( $_GET['appointment_date']) ? $_GET['appointment_date'] : '';
$show_posts 		= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$pg_page 			= get_query_var('page') ? get_query_var('page') : 1; 
$pg_paged 			= get_query_var('paged') ? get_query_var('paged') : 1;
$paged 				= max($pg_page, $pg_paged);
$order 	 			= 'DESC';
$sorting 			= 'ID';

//consulation fee
$starting_price	= doccure_get_post_meta( $post->ID, 'am_starting_price');
$starting_price		= !empty( $starting_price ) ? $starting_price : '' ;

$args = array(
	'posts_per_page' 	=> 5,
    'post_type' 		=> 'booking',
    'orderby' 			=> $sorting,
    'order' 			=> $order,
	'author'			=> $user_identity,
	'post_status' 		=> array('publish','pending','cancelled'),
    'paged' 			=> $paged,
    'suppress_filters'  => false
);

if( !empty( $appointment_date ) ) {
	$meta_query_args[] = array(
							'key' 		=> '_appointment_date',
							'value' 	=> $appointment_date,
							'compare' 	=> '='
						);
	$query_relation 	= array('relation' => 'AND',);
	$args['meta_query'] = array_merge($query_relation, $meta_query_args);
}

$query 		= new WP_Query($args);
$count_post = $query->found_posts;


if( ( !empty( $user_type ) 
	 && ( $user_type === 'doctors' && apply_filters('doccure_is_feature_allowed', 'dc_chat', $user_identity) === true ) ) 
	|| $user_type === 'hospitals' 
    || $user_type === 'regular_users'
) {?>

<?php } ?>
<div class="">
	<div class="dc-dashboardbox dc-dashboardtabsholder">
		<div class="dc-dashboardboxtitle"><h2><?php esc_html_e('Patient Appointments','doccure');?></h2></div>
		<div class="card card-table mb-0">
		<div class="card-body">
		<div class="table-responsive">
				<table class="table table-hover table-center mb-0">
					<?php if( $query->have_posts() ){ ?>

																<thead>
																	<tr>
																		<th>Doctor</th>
																		<th>Appt Date</th>
																		<th>Status</th>
																		<th></th>
																	</tr>
																</thead>
																<tbody>
															
															<div class="dc-recentapoint-holder dc-recentapoint-holdertest">
																<?php
																	while ($query->have_posts()) : $query->the_post(); 
																		global $post;
																		$post_auter	= get_post_field( 'post_author',$post->ID );
																		$link_id	= doccure_get_linked_profile_id( $post_auter );

																		$doctor_id	= get_post_meta( $post->ID,'_doctor_id',true);


																		$name		= doccure_full_name( $doctor_id );
																		$name		= !empty( $name ) ? $name : ''; 
												
																		$width = 300;
																		$height = 300;
																		$thumbnail      = doccure_prepare_thumbnail($doctor_id, $width, $height);
																		$post_status	= get_post_status( $post->ID );
																		if($post_status === 'pending'){
																			$post_status	= esc_html__('Pending','doccure');
																		} elseif($post_status === 'publish'){
																			$post_status	= esc_html__('Confirmed','doccure');
																		} elseif($post_status === 'draft'){
																			$post_status	= esc_html__('Pending','doccure');
																		}
																		$ap_date		= get_post_meta( $post->ID,'_appointment_date',true);
																		$ap_date		= !empty( $ap_date ) ? strtotime($ap_date) : '';
																		?>
																	    <tr>
																		<td>
																			<div class="patient-info">
																		<div class="patient_img">
																		<a href="<?php doccure_Profile_Menu::doccure_profile_menu_link('appointment', $user_identity,'','listing',$post->ID); ?>">
																				<?php if( !empty( $thumbnail ) ){?>
																					<figure><img src="<?php echo esc_url( $thumbnail );?>" alt="<?php echo esc_attr( $name );?>"></figure>
																				<?php } else{ ?>
																					<img src="<?php echo get_template_directory_uri();?>/assets/images/dravatar-40x40.jpg"/>
																					
																				<?php } ?>
																				</a>
																			</div>
																			<div class="dc-recentapoint">
																			<a href="<?php doccure_Profile_Menu::doccure_profile_menu_link('appointment', $user_identity,'','listing',$post->ID); ?>">
																				<span><?php echo esc_html( $name );?></span>
																				</a>

																			
																		</div>
																		</div>
																
																		</td>
																		<td>
																		<?php if( !empty( $ap_date ) ){?>
																					<span><?php echo date_i18n('d M Y',$ap_date);?></span>
																			<?php } ?>
																		</td>
																		<td>
																			 <?php echo esc_html( $post_status );?>
																		</td>
																		
																			<td>
																			<div class="dc-recent-content">
																			 <a href="<?php doccure_Profile_Menu::doccure_profile_menu_link('appointment', $user_identity,'','listing',$post->ID); ?>" class="dc-btn dc-btn-sm btn btn-sm bg-info-light">				<i class="far fa-eye"></i><?php esc_html_e('View','doccure');?>
																			</a>
													
																			</div>
																		</td>

																			<?php
																		endwhile;
																		wp_reset_postdata();
																	?>
															</div>
																
															<?php 
															} 
															else{  ?>
																<td> <?php do_action('doccure_empty_records_html','dc-empty-booking dc-emptyholder-sm',esc_html__( 'There are no appointments.', 'doccure' )); ?></td>
															<?php } 
															
														?>
														</tr>

													</tbody>
															</table>
			</div>

					
		</div>
		</div>
		
	</div>
</div>