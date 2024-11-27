<?php 
/**
 *
 * The template part for displaying appointment in listing
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */

global $current_user, $post;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doccure_get_linked_profile_id($user_identity);
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

$args = array(
	'posts_per_page' 	=> $show_posts,
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

$width		= 40;
$height		= 40;
$flag 		= rand(9999, 999999);

?>
<div class="">
		<div class="dc-dashboardbox" id="dc-booking_service_details" style="display:none;">
			
		</div>
	<div class="dc-dashboardbox dc-apointments-wrap dc-apointments-wraptest">	
		<form method="get" id="search_booking" action="<?php doccure_Profile_Menu::doccure_profile_menu_link('appointment', $user_identity,'','listing');?>">
			<div class="dc-apointments-holder dc-apointments-holder-test">
				<div class="dc-appointment-calendar dc-appointment-calendartest">
					<div id="dc-calendar-app-<?php echo esc_attr( $flag );?>" class="dc-calendar"></div>
				</div>
				<div class="dc-appointment-border">
					<div class="dc-dashes">	
						<div class="dc-main-circle">
							<div class="dc-circle-raduis">
								<div class="rounded-circle dc-circle"></div>
							</div>
						</div>										
					</div>
				</div>
				<input type="hidden" id="search_appointment_date" name="appointment_date" value="<?php echo esc_attr($appointment_date);?>" >
				<input type="hidden" name="ref" value="appointment">
				<input type="hidden" name="mode" value="listing">
				<input type="hidden" name="identity" value="<?php echo intval($user_identity);?>">
				<div class="dc-recentapointdate-holder dc-recentapoint-test">
					<div class="dc-recentapointdate dc-recentapointdate-test">
						<h2><?php echo intval($count_post);?></h2>
						<span><?php esc_html_e('Appointments','doccure');?><em><?php echo date_i18n( $date_format,strtotime( $appointment_date ) );?></em></span>
					</div>
				</div>
			</div>
		</form>
		<div class="dc-searchresult-head">
			<div class="dc-title"><h3><?php esc_html_e('Recent Appointments','doccure');?></h3></div>
		</div>
		<?php if( $query->have_posts() ){ ?>
			<div class="appointments">

				<?php
					while ($query->have_posts()) : $query->the_post(); 
						global $post;

						$doctor_id	= get_post_meta( $post->ID,'_doctor_id',true);
						$name		= doccure_full_name( $doctor_id );
						$name		= !empty( $name ) ? $name : ''; 

						$thumbnail   	= doccure_prepare_thumbnail($doctor_id, $width, $height);
						$post_status	= get_post_status( $post->ID );

						if($post_status === 'pending'){
							$post_status	= esc_html__('Pending','doccure');
						} elseif($post_status === 'publish'){
							$post_status	= esc_html__('Confirmed','doccure');
						} elseif($post_status === 'draft'){
							$post_status	= esc_html__('Pending','doccure');
						}

						$doctor_url		= get_the_permalink( $doctor_id );
						$doctor_url		= !empty( $doctor_url ) ? $doctor_url : '';
						$ap_date		= get_post_meta( $post->ID,'_appointment_date',true);
						$ap_date		= !empty( $ap_date ) ? strtotime($ap_date) : '';
						$email		= get_post_meta($post->ID,'bk_email', true);
						$phone		= get_post_meta($post->ID,'bk_phone', true);

						$location		= doccure_get_location($doctor_id);
						$country		= !empty( $location['_country'] ) ? $location['_country'] : '';


						?>
						<div class="appointment-list 2">
							<div class="profile-info-widget">
							<?php if( !empty( $thumbnail ) ){?>
									<a href="<?php echo esc_url( $doctor_url );?>">
										<figure><img src="<?php echo esc_url( $thumbnail );?>" alt="<?php echo esc_attr( $name );?>"></figure>
									</a>
									<?php } else{ ?>
										<a href="<?php echo esc_url( $doctor_url );?>">
										<img src="<?php echo get_template_directory_uri();?>/assets/images/dravatar-255x250.jpg"/>
									</a>
																					
									<?php } ?>
								<div class="profile-det-info">
									<h3><a href="<?php echo esc_url( $doctor_url );?>"><?php echo esc_html( $name );?></a></h3>
									<div class="patient-details">
									<h5>
										<?php if( !empty( $ap_date ) ){?>
											<i class="far fa-clock"></i><?php echo date_i18n('d M Y',$ap_date);?>
									<?php } ?>
									<h5>
									<i class="feather-map-pin"></i><?php echo esc_html($country); ?>
									</h5>
									<h5><?php if( !empty($email) ){?>
										<i class="far fa-envelope"></i> <?php echo esc_html($email);?>
									<?php } ?>
									</h5>
									<h5>
									<?php if( !empty($phone) ){?>
									<i class="fas fa-phone"></i><?php echo esc_html($phone);?>
								<?php } ?>
								</h5>
									</div>
								</div>	
							</div>
							<div class="appointment-action">
								<div class="dc-recent-content">
							     <a href="javascript:;" class="dc-btn dc-btn-sm btn btn-sm bg-info-light mb-2" id="dc-booking-service" data-type="patient" data-id="<?php echo intval($post->ID); ?>"><i class="far fa-eye"></i><?php esc_html_e('View','doccure');?></a>


								 <?php 

$location_id	= get_post_meta($post->ID, '_hospital_id', true);

						      	$post_status_reschedule	= get_post_status( $post->ID ); 
 								if($post_status_reschedule == 'publish'){ ?>
								<a href="javascript:;" class="dc-btn dc-btn-sm btn btn-sm dc-add-booking-reschedule-patient bg-info-sucess " data-postid="<?php echo intval($post->ID); ?>" data-hosid="<?php echo intval($location_id); ?>"><i class="far fa-calendar"></i><?php esc_html_e('Reschedule','doctreat');?></a>
								<?php } ?>

							 </div>
							</div>

							
						</div>
					<?php
						endwhile;
						wp_reset_postdata();
						if (!empty($count_post) && $count_post > $show_posts) {
							doccure_prepare_pagination($count_post, $show_posts);
						}
					?>
		</div>
		<?php } else { ?>
			<?php do_action('doccure_empty_records_html','dc-empty-booking dc-emptyholder-sm',esc_html__( 'There are no appointments booked.', 'doccure' ));?>
		<?php } ?>
	</div>
</div>

<?php 
	$current_date		= date('Y-m-d');
	$appointment_date	= !empty($appointment_date) ? $appointment_date : $current_date;
	$array_date		= !empty($appointment_date) ?  array( 'title' => 'current', 'start'	=> $appointment_date ) : '';
	$inline_script = "
	jQuery(document).on('ready', function() {
		var calendar_locale  = scripts_vars.calendar_locale;
		var calendarEl = document.getElementById('dc-calendar-app-".esc_js($flag)."');
		var calendar = new FullCalendar.Calendar(calendarEl, {
			initialView: 'dayGridMonth',
			locale: calendar_locale,
			height: 'auto',
			headerToolbar:{
			  left:   'title',
			  center: '',
			  right:  'prev,next'
			},
			dateClick: function(date, jsEvent, view) {
				var _date			= date.dateStr;
				jQuery('#search_appointment_date').val(_date);
				jQuery('#search_booking').submit();
			},
			events:[".json_encode($array_date)."],
			eventDidMount : function(event, eventElement) {
				jQuery('#dc-calendar-app-".esc_js($flag)."').find(\"*[data-date='".$appointment_date."']\").addClass('fc-today-clicked');
			},
		});
		calendar.render();

	});";
	wp_add_inline_script( 'fullcalendar-lang', $inline_script, 'after' );
	get_template_part('directory/front-end/templates/regular_users/add-bookings'); 

	do_action('doccure_booking_details');