<?php
/**
 *
 * The template used for doctors basics
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post,$doccure_options,$current_user;
$post_id 	= $post->ID; 
$user_id	= doccure_get_linked_profile_id( $post_id,'post' );
$sub_heading	= doccure_get_post_meta( $post_id ,'am_sub_heading');

$verified	= get_post_meta($post_id, '_is_verified', true);
$verified	= !empty( $verified ) ? $verified	: '';

$location		= doccure_get_location($post_id);
$location		= !empty( $location['_country'] ) ? $location['_country'] : '';	

$shoert_des		= doccure_get_post_meta( $post_id, 'am_short_description');
$tagline		= doccure_get_post_meta( $post_id, 'am_sub_heading');
$mrv			= doccure_get_post_meta( $post_id, 'am_registration_number');
$starting_price	= doccure_get_post_meta( $post_id, 'am_starting_price');
$name			= doccure_full_name( $post_id );
$name			= !empty( $name ) ? $name : '';
$package_expiry	= doccure_get_subscription_metadata('dc_bookings',$user_id);
$booking_option	= doccure_get_booking_oncall_option('oncall');

$feedback			= get_post_meta($post_id,'review_data',true);
$feedback			= !empty( $feedback ) ? $feedback : array();
$total_rating		= !empty( $feedback['dc_total_rating'] ) ? $feedback['dc_total_rating'] : 0 ;
$total_percentage	= !empty( $feedback['dc_total_percentage'] ) ? $feedback['dc_total_percentage'] : 0 ;
$starting_price		= !empty( $starting_price ) ? $starting_price : '' ;

$doctor_avatar = apply_filters(
					'doccure_doctor_avatar_fallback', doccure_get_doctor_avatar( array( 'width' => 255, 'height' => 250 ), $post_id ), array( 'width' => 255, 'height' => 250 )
				);

$doctor_avatar_2x = apply_filters(
					'doccure_doctor_avatar_fallback', doccure_get_doctor_avatar( array( 'width' => 545, 'height' => 428 ), $post_id ), array( 'width' => 545, 'height' => 428 )
				);

$featured	= get_post_meta($post_id,'is_featured',true);

$bookig_days	= doccure_get_booking_days( $user_id );
$bookig_days	= !empty( $bookig_days ) ? $bookig_days : array();


$review_meta		= array(
	'_feedback_recommend' => 'yes'
	);


$votes				= doccure_get_total_posts_by_multiple_meta('reviews','publish',$review_meta,$user_id);
$votes				= !empty( $votes ) ? intval( $votes ) : 0 ;

if(isset($current_user->roles[0])){
	$role = $current_user->roles[0];
	$role_name = $role ? wp_roles()->get_names()[ $role ] : '';
}

			

?>
<div class="card">
 
<div class="dc-docsingle-content">
		<div class="dc-title doc-info-left">
			<div class="doctor-img">
				<?php if( !empty( $doctor_avatar ) ){?>
				<figure class="dc-docsingleimg">
					<img class="dc-ava-detail" src="<?php echo esc_url( $doctor_avatar );?>" alt="<?php echo esc_attr( get_the_title() );?>">
					<img class="dc-ava-detail-2x" src="<?php echo esc_url( $doctor_avatar_2x );?>" alt="<?php echo esc_attr( get_the_title() );?>">
					<?php if( !empty( $featured ) && intval($featured) > 0 ){ ?>
						<figcaption>
							<span class="dc-featuredtag"><i class="fa fa-bolt"></i></span>
						</figcaption>
					<?php } ?>
				</figure>
			<?php }?>
			</div>
			<div class="doc-info-cont <?php echo esc_html($role_name);?>">
			<h2>
				<a href="<?php echo esc_url( get_the_permalink() );?>"><?php echo esc_html( $name );?></a>
				<?php do_action('doccure_get_drverification_check',$post_id,esc_html__('Medical Registration Verified','doccure'));?>
				<?php do_action('doccure_get_verification_check',$post_id,'');?>
			</h2>
			<?php if( !empty( $sub_heading ) ){?>
				<p class="doc-speciality">
					 
					<?php echo esc_html( $sub_heading );?>
				</p>
				<?php } ?>
			<?php do_action('doccure_specilities_list',$post_id,1);?>

			<ul class="dc-docinfo">
				<?php if( !empty( $tagline ) ) { ?>
					
				<?php } ?>
				<li class="stars_info">
				<div class="ratings">
					<div class="empty-stars"></div>
					<div class="full-stars" style="width: <?php echo intval( $total_percentage );?>%"></div>
				</div>
				</span>
				</li>
				<li class="<?php echo esc_html($role_name);?>">
					<?php do_action('doccure_get_favorit_check',$post_id,'large');?>
				<li>
			</ul>
			

		</div>
		<?php if( !empty( $shoert_des ) ) {?>
			<div class="dc-description">
				<p><?php echo esc_html( $shoert_des );?></p>
			</div>
		<?php }?>
		</div>
		<div class="doctors_experiance">
			 <?php $am_experiences	= doccure_get_post_meta( $post_id,'am_experiences'); ?>
			 <?php 
			if( !empty( $am_experiences ) ) {
				$count_edu	= 1;
				foreach( $am_experiences as $key => $val ) {
					$job_title			= !empty( $val['job_title'] ) ? $val['job_title'] : '';
					  $total_exp			= !empty( $val['total_exp'] ) ? $val['total_exp'] : '';
				}
			} else {
				$total_exp= '';
			}
			?>
			
			<?php 
  $location_id	= get_post_meta($post_id, '_doctor_location',true);
 $location_id	= !empty($location_id) ? $location_id : 0;

 ?>
		<?php 	
global $current_user, $post;
$post_id 	= $post->ID; 
$user_identity 	 	= $current_user->ID;
//$linked_profile  	= doccure_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-cart';

$available_balance_img	= !empty( $doccure_options['available_balance']['url'] ) ? $doccure_options['available_balance']['url'] : '';
$available_balance		= doccure_get_sum_earning_doctor($user_identity,'completed','doctor_amount');

$user_identity 	 	= $current_user->ID;
$linked_profile  	= doccure_get_linked_profile_id($user_identity);
//$post_id 		 	= $linked_profile;

$date_format		= get_option('date_format');
$appointment_date 	= !empty( $_GET['appointment_date']) ? $_GET['appointment_date'] : date('Y-m-d');
$order 	 			= 'DESC';
$sorting 			= 'ID';

$args = array(
	'posts_per_page' 	=> 6,
    'post_type' 		=> 'booking',
    'orderby' 			=> $sorting,
    'order' 			=> $order,
	'post_status' 		=> array('publish'),
    'suppress_filters'  => false
);

$meta_query_args[] = array(
						'key' 		=> '_doctor_id',
						'value' 	=> $post->ID,
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
 $count_post = $query->found_posts;
?>
<?php 

if( !empty($location_id) && has_post_thumbnail($location_id) ){
	$attachment_id 			= get_post_thumbnail_id($location_id);
	$image_url 				= !empty( $attachment_id ) ? wp_get_attachment_image_src( $attachment_id, 'doccure_doctors_type', true ) : '';
	$file_size 				= !empty( $attachment_id) ? filesize(get_attached_file($attachment_id)) : '';	
	$document_name   		= !empty( $attachment_id ) ? get_the_title( $attachment_id ) : '';
	$filetype        		= !empty( $image_url[0] ) ? wp_check_filetype( $image_url[0] ) : '';
	$extension       		= !empty( $filetype['ext'] ) ? $filetype['ext'] : '';
 }
$location_title		= !empty($location_id) ? get_the_title($location_id) : '';

 ?>

 				 <ul class="doctor_details_experiance">
					<?php if($total_exp) { ?> 
					 <li><i class="fas fa-bookmark"></i>
					  <?php echo $total_exp; ?>
					 </li>
					 <?php } ?>
 			   	     <li><i class="fas fa-user"></i>

					 

					  <?php echo $count_post; ?> Patients Treated
					 </li>
				</ul>
 <?php if(get_post_meta( $location_id , '_address',true ) || get_the_title( $location_id ) ) { ?>
<div class="clenic_location">
  <div class="clenic_location_inner">
  <?php if(get_the_title( $location_id )) { ?>
	  <div class="clenic_name">
	  <?php if( !empty( $image_url[0] ) ){ ?>
 		 <img class="img-fluid" src="<?php echo esc_url( $image_url[0] ); ?>" alt="<?php echo esc_attr( get_the_title( $post_id )); ?>"> 
									 
 <?php } ?>

		<div>
		   <b>Name</b><br>
	       <?php echo get_the_title( $location_id );?>
		</div>
		
	  </div>
	  <?php } ?>
	  <?php if(get_post_meta( $location_id , '_address',true )) { ?>

	  <div class="clenic_locationname">
	  <b>Location</b><br>
	  <?php 
        echo $address = get_post_meta( $location_id , '_address',true );
 		?>
	  </div>
	  <?php } ?>
 </div>
</div>
<?php } ?>

 
 


		</div>
		<div class="doc-info-right <?php echo esc_html($role_name);?>">
		<span><i class="far fa-thumbs-up"></i><?php echo intval( $votes );?> <?php esc_html_e( 'Votes','doccure');?></span>
		<?php if( !empty( $bookig_days ) ){?>
					<span>
					<i class="fa-regular fa-calendar"></i><?php 
							$total_bookings	= count( $bookig_days );
							$start			= 0;
							foreach( $bookig_days as $val ){ 
								$day_name	= doccure_get_week_keys_translation($val);
								$start ++;
								if( $val == $day ){  
									$availability	= 'yes';
									echo '<em class="dc-bold">'.$day_name.'</em>'; 
								} else {
									echo esc_html( $day_name );
								}
								
								if( $start != $total_bookings ) {
									echo ', ';
								}
							}
						?>
					</span>
				<?php } ?>
				
				<span class="dc-feedback">
				<i class="far fa-comment"></i><?php echo intval( $total_rating );?>&nbsp;<?php esc_html_e('Feedback','doccure');?>
				</span>
				
				<?php if( !empty( $location ) ){?>
					<span><i class="feather-map-pin"></i><?php echo esc_html( $location );?></span>
				<?php } ?>
				
				<?php if( !empty( $starting_price ) ) { ?>
					<span><i class="far fa-money-bill-alt"></i><?php doccure_price_format($starting_price);?></span>
				<?php } ?>

				<?php if( !empty( $am_starting_price ) ){?>
					<span><i class="far fa-money-bill-alt"></i> <?php doccure_price_format($am_starting_price);?></span>
				<?php } ?>
				<div class="dc-btnarea">
<?php
$feedback_option = $doccure_options['feedback_option'];
  if(  $feedback_option ==true ){?>

					<a href="javascript:;" data-id="<?php echo intval( $post_id );?>" class="dc-btn dc-add-feedback"><?php esc_html_e('Add Feedback','doccure');?></a>
 <?php } ?>
					<?php
					if( apply_filters('doccure_is_appointment_allowed', 'dc_bookings', $user_id) === true ){
						if( !empty($package_expiry)){
							if(empty($booking_option)){ ?>
							<a href="javascript:;" data-doctor_id="<?php echo intval( $post_id );?>" class="dc-btn dc-booking-model"><?php esc_html_e('Book Appointment','doccure');?></a>
						<?php } else { ?>
							<a href="javascript:;" data-id="<?php echo intval( $post_id );?>" class="dc-btn dc-booking-contacts"><?php esc_html_e('Call Now','doccure');?></a>
						<?php } ?>
					<?php }} ?>
				</div>	
		</div>
	


		

	</div>

	</div>
