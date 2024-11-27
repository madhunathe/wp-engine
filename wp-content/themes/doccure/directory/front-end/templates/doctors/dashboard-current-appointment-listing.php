<?php 
/**
 *
 * The template part for displaying appointment in listing Dashboard
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */


global $current_user,$doccure_options;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doccure_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-hourglass';
$expiry_string			= doccure_get_subscription_metadata( 'subscription_package_string',intval( $user_identity ) );
$package_expiry_img		= !empty( $doccure_options['package_expiry']['url'] ) ? $doccure_options['package_expiry']['url'] : '';
$formatted_date			= ''; 

if( $expiry_string != false ){
	$formatted_date = date("Y, n, d, H, i, s", strtotime("-1 month",intval($expiry_string))); 
}


global $current_user, $post;
$post_id 	= $post->ID; 
$user_identity 	 	= $current_user->ID;
//$linked_profile  	= doccure_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-cart';

$available_balance_img	= !empty( $doccure_options['available_balance']['url'] ) ? $doccure_options['available_balance']['url'] : '';
$available_balance		= doccure_get_sum_earning_doctor($user_identity,'completed','doctor_amount');

$user_identity 	 	= $current_user->ID;
$linked_profile  	= doccure_get_linked_profile_id($user_identity);
$post_id 		 	= $linked_profile;

$date_format		= get_option('date_format');
$appointment_date 	= !empty( $_GET['appointment_date']) ? $_GET['appointment_date'] : date('Y-m-d');
$order 	 			= 'DESC';
$sorting 			= 'ID';

$args = array(
	'posts_per_page' 	=> 6,
    'post_type' 		=> 'booking',
    'orderby' 			=> $sorting,
    'order' 			=> $order,
	'post_status' 		=> array('publish','pending','on-hold'),
    'suppress_filters'  => false
);

$meta_query_args[] = array(
						'key' 		=> '_doctor_id',
						'value' 	=> $linked_profile,
						'compare' 	=> '='
					);

$query_relation 	= array('relation' => 'AND',);
$args['meta_query'] = array_merge($query_relation, $meta_query_args);



$query 		= new WP_Query($args);
$count_post = $query->found_posts;

//consulation fee
$starting_price	= doccure_get_post_meta( $post_id, 'am_starting_price');
$starting_price		= !empty( $starting_price ) ? $starting_price : '' ;



$width		= 40;
$height		= 40;
$flag 		= rand(9999, 999999);
?>


<div class="row">
<div class="col-md-12">
	<div class="card dash-card">
		<div class="card-body">
			<div class="row">
			<?php // Get total number of posts in post-type-name
			
	$count_posts = wp_count_posts('booking');
	$total_posts = $count_posts->publish;
?>

<div class="col-sm-12 col-md-6">
					<div class="dash-widget dct-border-rht">
							<div class="circle-bar circle-bar1">
								<div class="circle-graph1" data-percent="75"><canvas width="400" height="400"></canvas>
										<img src="<?php  echo get_template_directory_uri();?>/assets/images/icon-01.png" class="img-fluid" alt="patient">
								</div>
							</div>
							<div class="dash-widget-info">
									<h6>Total Patients</h6>
									<h3><?php echo esc_html($count_post);?></h3>
								</div>
					 </div>				 
</div>

<div class="col-sm-12 col-md-6">
<div class="dash-widget dct-border-rht">
<div class="circle-bar circle-bar3">
								<div class="circle-graph1" data-percent="75"><canvas width="400" height="400"></canvas>
										<img src="<?php  echo get_template_directory_uri();?>/assets/images/icon-03.png" class="img-fluid" alt="patient">
								</div>
							</div>
	 <div class="dash-widget-info">
		<h6><?php esc_html_e('Available balance', 'doccure'); ?></h6>
				<h3><?php doccure_price_format($available_balance);?></h3>
				
	
	</div>


</div>
</div>
	
			</div>
		</div>
	</div>
</div>
</div>

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
																		<th>Patient Name</th>
																		<th>Appt Date</th>
																		<th>Status</th>
																		<th>Consulation Fee</th>
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
																		$name		= doccure_full_name( $link_id );
																		$name		= !empty( $name ) ? $name : ''; 
																		
																		$thumbnail      = doccure_prepare_thumbnail($link_id, $width, $height);
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
																		<span class="hello"><?php doccure_price_format($starting_price);?></span>

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

