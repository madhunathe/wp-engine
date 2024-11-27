<?php 
/**
 *
 * The template part for displaying doctors in listing
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user, $post;

$user_identity 	 = $current_user->ID;
$linked_profile  = doccure_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;
$location_id	= get_post_meta($post_id, '_doctor_location', true);
$id				= !empty( $location_id ) ? intval( $location_id ) : '';
if ( FALSE === get_post_status( $id ) ) {
	$id	= '';	
}

$times		= doccure_get_time();
$intervals	= doccure_get_padding_time();
$durations	= doccure_get_meeting_time();
$days		= doccure_get_week_array();

$time_format = get_option('time_format');

$am_slots_data 		= get_post_meta( $id,'am_slots_data',true);
$am_slots_data		= !empty( $am_slots_data ) ? $am_slots_data : array();
$hospital_name		= !empty( $hospital_name ) ? $hospital_name : '';
$am_consultant_fee	= get_post_meta( $id ,'_consultant_fee',true);
$am_consultant_fee	= !empty( $am_consultant_fee ) ? $am_consultant_fee : '';

?>
<div class="">
	<div class="dc-dashboardbox available_timings">
		<div class="dc-dashboardboxtitle dc-titlewithbtn dc-addnew">
			<h2><?php esc_html_e('Appointment Settings','doccure');?></h2>
			<a href="<?php doccure_Profile_Menu::doccure_profile_menu_link('profile', $user_identity, '','location'); ?>" target="_blank" class="dc-add_education"><?php esc_html_e('Add New Location','doccure');?></a>
		</div>
		<div class="dc-dashboardboxcontent dc-offerday-holder">
			<?php do_action('doccure_doctor_location', $id); ?>
			<div class="dc-tabscontenttitle">
				<h3><?php esc_html_e('Time Slots','doccure');?></h3>
			</div>
			<?php if( !empty( $days ) ){?>
				<div class="dc-childaccordion dc-offeraccordion" role="tablist" aria-multiselectable="true">
				<?php 
					foreach( $days as $key => $day ) { 
						$day_slots	= !empty( $am_slots_data[$key] ) ? $am_slots_data[$key] : array();
						$day_start	= !empty( $day_slots['start_time'] ) ? $day_slots['start_time'] : '';
						$day_end	= !empty( $day_slots['end_time'] ) ? $day_slots['end_time'] : '';
						$slots		= !empty( $day_slots['slots'] ) ? $day_slots['slots'] : '';						
						?>
					<div class="dc-subpanel">
						<div class="dc-subpaneltitle dc-subpaneltitlevtwo">
							<?php if( !empty( $day ) ) {?><span><?php echo esc_html( $day );?></span><?php } ?>
							<div class="dc-rightarea">
								<div class="dc-btnaction"><a href="javascript:;" class="dc-editbtn"><i class="fa fa-pencil"></i></a></div>
							</div>
						</div>
						<div class="dc-subpanelcontent">
							<div class="dc-dayspaces-holder dc-titlewithbtn">
								<div class="dc-rightarea">
									<a href="javascript:;" data-day="<?php echo esc_attr($key);?>" class="dc-btn dc-btn-block dc-add-appointment"><?php esc_html_e('Add New Slot','doccure');?></a>
									<a href="javascript:;" data-id="<?php echo intval( $id );?>" data-day="<?php echo esc_attr( $key );?>" class="dc-btn dc-btn-del dc-remove-appointment-all"><?php esc_html_e('Delete All','doccure');?></a>
								</div>
								<div class="dc-tabscontenttitle">
				<h3><?php esc_html_e('Available Timings','doccure');?></h3>
			</div>
								<div class="dc-spaces-holder">
								

									<ul class="dc-spaces-wrap dc-spaces-ul-<?php echo esc_html( $key );?>">
										<?php 
											if( !empty( $slots ) ){
												foreach( $slots as $slot_key => $slot_val ) { 
													$slot_key_val = explode('-', $slot_key);
												?>
												<li>
													<a href="javascript:;" class="dc-spaces">
														<span><?php echo date_i18n($time_format, strtotime('2016-01-01' . $slot_key_val[0]));?></span>
														<span><?php esc_html_e('Spaces','doccure');?>: <?php echo esc_html( $slot_val['spaces'] );?></span>
														<i class="fa fa-close" data-id="<?php echo intval( $id );?>" data-day="<?php echo esc_attr( $key );?>" data-key="<?php echo esc_attr( $slot_key );?>"></i>
													</a>
												</li>
											<?php } ?>
										<?php } ?>
									</ul>
								</div>
							</div>
							<div class="dc-dashboardboxcontent dc-appsetting dc-<?php echo esc_attr( $key );?>">
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
			<?php }?>
		</div>
	</div>
</div>
<div class="dc-dashboardbox-mt mt-xl-0 dashboard_available">
	<div class="dc-dashboardbox">
		<div class="dc-dashboardboxcontent dc-appsetting">
			 
			<form class="dc-update-providingservices">
				<div class="dc-tabscontenttitle">
					<h3><?php esc_html_e('Consultation Fee','doccure');?></h3>
				</div>
 				<div class="form-group">
					<input type="text" name="consultant_fee" class="form-control" value="<?php echo esc_attr($am_consultant_fee);?>" placeholder="<?php esc_attr_e('Consultation Fee','doccure');?>">
				</div>

				<div class="dc-tabscontenttitle">
					<div class="row">
						<div class="col-xl-6">
						<h3><?php esc_html_e('Specialties &amp; Services','doctreat');?></h3>
						</div>
						
							<?php 

global $current_user, $wp_roles, $userdata, $post;

$reference 		 = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : '';
$mode 			 = (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';
$url_identity 	 = $current_user->ID;
$user_type		 = apply_filters('doccure_get_user_type', $url_identity );
if( !empty( $user_type ) && $user_type === 'regular_users' ){ } else {
?>
<div class="col-xl-6 text-end">
						<a class="dc-btn dc-btn-sm btn btn-sm bg-info-light mb-2 mt-0" href="<?php doccure_Profile_Menu::doccure_profile_menu_link('specialities', $url_identity); ?>">
	 
			<span><?php esc_html_e('Add Specialities &amp; Services','doccure');?></span>
		</a>
		</div>
		<?php } ?>
						
					</div>
					
				</div>
				<?php if( !empty( $id ) && !empty( $post_id )){ ?>
					<div class="dc-providingservices dashboard_servies">
						<?php 
							$db_services 	= get_post_meta($id, '_team_services',true);
							$db_services	= !empty( $db_services ) ? $db_services : array();
							do_action('doccure_get_group_services_with_speciality',$post_id,$db_services);
						?>
						
					</div>
				<?php } ?>

				<div class="dc-providingservices dc-btnarea dashboard_appointbtn">
					<a class="dc-btn dc-update-ap-location" data-id="<?php echo intval($id);?>" href="javascript:;"><?php esc_html_e('Save &amp; Continue','doccure'); ?></a>
				</div>
			</form>
			
		</div>
	</div>
</div>
<script type="text/template" id="tmpl-load-appointment">
	<div class="dc-tabscontenttitle">
	
		<h3><?php esc_html_e( 'Add New Slot For Doctor Availability','doccure' );?></h3>
	</div>
	<form class="dc-formtheme dc-userform dc-form-appointment">
		<fieldset>
			<div class="form-group form-group-half">
				<div class="me-2">
				<span class="dc-select">
					<select name="start_time">
						<option value=""><?php esc_html_e( 'Start Time','doccure' );?></option>
						<?php 
							if( !empty( $times ) ) {
								foreach( $times as $key => $val ) {
							?>
							<option value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $val );?></option>
						<?php } ?>
					<?php } ?>
					</select>
				</div>
			</div>
			</div>
			<div class="form-group form-group-half">
				<span class="dc-select">
					<select name="end_time">
						<option value=""><?php  esc_html_e( 'End Time','doccure' );?></option>
						<?php 
							if( !empty( $times ) ) {
								foreach( $times as $key => $val ) {
							?>
							<option value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $val );?></option>
						<?php } ?>
					<?php } ?>
					</select>
				</div>
			</div>
			<div class="form-group form-group-half">
				<div class="me-2">
				<span class="dc-select">
					<select name="intervals">
						<?php 
							if( !empty( $intervals ) ) {
								foreach( $intervals as $key => $val ) {
							?>
							<option value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $val );?></option>
						<?php } ?>
					<?php } ?>
					</select>
				</span>
				</div>
			</div>
			<div class="form-group form-group-half">
				<span class="dc-select">
					<select name="durations">
						<?php 
							if( !empty( $durations ) ) {
								foreach( $durations as $key => $val ) {
							?>
							<option value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $val );?></option>
						<?php } ?>
					<?php } ?>
					</select>
				</span>
			</div>
		</fieldset>
		<fieldset class="dc-spacesholder">
			<legend><?php esc_html_e('Assign Appointment Spaces','doccure');?>:</legend>
			<div class="form-group form-group-half dc-radio-holder dc-radio-holdertest">
				<span class="dc-radio">
					<input id="dc-spaces-{{data.day}}-1" class="dc-spaces" type="radio" name="spaces" value="<?php echo intval(1);?>">
					<label for="dc-spaces-{{data.day}}-1">01</label>
				</span>
				<span class="dc-radio">
					<input id="dc-spaces-{{data.day}}-2" class="dc-spaces" type="radio" name="spaces" value="<?php echo intval(2);?>">
					<label for="dc-spaces-{{data.day}}-2">02</label>
				</span>
				<span class="dc-radio">
					<input id="dc-spaces-{{data.day}}-3" class="dc-spaces" type="radio" name="spaces" value="<?php echo intval(3);?>">
					<label for="dc-spaces-{{data.day}}-3">03</label>
				</span>
				<span class="dc-radio">
					<input id="dc-spaces-{{data.day}}-4" class="dc-spaces" type="radio" name="spaces" value="<?php echo intval(4);?>">
					<label for="dc-spaces-{{data.day}}-4">04</label>
				</span>
				
			</div>
		</div>
		<div class="form-group dc-btnarea"><a data-id="<?php echo intval($id);?>" data-day="{{data.day}}" href="javascript:;" class="dc-btn dc-update-appointment"><?php esc_html_e('Add Now','doccure');?></a></div>
	</fieldset>
</form>
</script>
<?php
	$script = "
		themeAccordion();
		childAccordion();
	";
	wp_add_inline_script('doccure-dashboard', $script, 'after');