<?php 
/**
 *
 * The template part for displaying location
 * details of doctor
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user;

$user_identity 	 	= $current_user->ID;
$id				 	= !empty($_GET['id']) ? intval($_GET['id']) : '';

$days				= doccure_get_week_array();
$time_format 		= get_option('time_format');

$am_slots_data 		= get_post_meta( $id, 'am_slots_data', true );
$am_slots_data		= !empty( $am_slots_data ) ? $am_slots_data : array();
$am_consultant_fee	= get_post_meta( $id ,'_consultant_fee',true);
$am_consultant_fee	= !empty( $am_consultant_fee ) ? $am_consultant_fee : '';

?>
<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
	<div class="dc-dashboardbox">
		<div class="dc-dashboardboxtitle dc-titlewithbtn">
			<h2><?php esc_html_e('Location Details', 'doccure');?></h2>
		</div>
		<div class="dc-dashboardboxcontent dc-offerday-holder">
			<?php do_action('doccure_get_doctor_info_by_teamID', $id, $user_identity); ?>
			<div class="dc-tabscontenttitle">
				<h3><?php esc_html_e('Days, I offer my services', 'doccure');?></h3>
			</div>
			<?php if( !empty( $days ) ){?>
				<div class="dc-childaccordion dc-offeraccordion" role="tablist" aria-multiselectable="true">
				<?php 
                    foreach ($days as $key => $day) {
                        $day_slots	= !empty($am_slots_data[$key]) ? $am_slots_data[$key] : array();
                        $day_start	= !empty($day_slots['start_time']) ? $day_slots['start_time'] : '';
                        $day_end	= !empty($day_slots['end_time']) ? $day_slots['end_time'] : '';
                        $slots		= !empty($day_slots['slots']) ? $day_slots['slots'] : '';
						$day_name	= doccure_get_week_keys_translation($key);
					?>
					<?php if (!empty($slots)) { ?>	
					<div class="dc-subpanel">
						<div class="dc-subpaneltitle dc-subpaneltitlevtwo">
							<?php if (!empty($day)) {?><span><?php echo esc_html($day);?></span><?php } ?>
						</div>
						<div class="dc-subpanelcontent">
							<div class="dc-dayspaces-holder dc-titlewithbtn">
								<div class="dc-spaces-holder">
									<ul class="dc-spaces-wrap dc-spaces-ul-<?php echo esc_html($key);?>">
										<?php
                                            if (!empty($slots)) {
                                                foreach ($slots as $slot_key => $slot_val) {
                                                    $slot_key_val = explode('-', $slot_key); ?>
												<li>
													<a href="javascript:;" class="dc-spaces">
														<span><?php echo date($time_format, strtotime('2016-01-01' . $slot_key_val[0])); ?></span>
														<span><?php esc_html_e('Spaces', 'doccure'); ?>: <?php echo esc_html($slot_val['spaces']); ?></span>
													</a>
												</li>
											<?php } ?>
										<?php } ?>
									</ul>
								</div>
							</div>
							<div class="dc-dashboardboxcontent dc-appsetting dc-<?php echo esc_attr($key);?>"></div>
						</div>
					</div>
				<?php } } ?>
			</div>
			<?php }?>
		</div>
	</div>
</div>
<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 dc-dashboardbox-mt mt-xl-0">
	<div class="dc-dashboardbox">
		<div class="dc-dashboardboxtitle">
			<h2><?php esc_html_e('Providing Services', 'doccure');?></h2>
		</div>
		<div class="dc-dashboardboxcontent dc-appsetting">
			<div class="dc-update-providingservices">
	 
				<div class="dc-tabscontenttitle">
					<h3><?php esc_html_e('Consultation fee','doccure');?></h3>
				</div>
				<div class="form-group">
					<input type="text" disabled name="consultant_fee" class="form-control" value="<?php echo esc_attr($am_consultant_fee);?>" placeholder="<?php esc_attr_e('Consultation fee', 'doccure');?>">
				</div>
				<div class="dc-tabscontenttitle">
					<h3><?php esc_html_e('Specialties &amp; Services','doccure');?></h3>
				</div>
				<?php if( !empty( $id ) ){ ?>
					<div class="dc-providingservices">
						<?php 
							$db_services = get_post_meta($id, '_team_services', true);
							$db_services	= !empty( $db_services ) ? $db_services : array();
							do_action('doccure_get_group_services_with_speciality', $id, $db_services, 'echo', 'location');
						?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>