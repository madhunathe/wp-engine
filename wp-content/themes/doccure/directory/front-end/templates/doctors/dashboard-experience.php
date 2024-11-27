<?php
/**
 *
 * The template part for displaying the dashboard experience
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post;
$user_identity 	 = $current_user->ID;
$linked_profile  = doccure_get_linked_profile_id($user_identity);
$post_id 		= $linked_profile;

$am_experiences	= doccure_get_post_meta( $post_id,'am_experiences');

?>
<div class="dc-userexperience dc-tabsinfo">
	<div class="dc-tabscontenttitle dc-addnew">
		<h3><?php esc_html_e('Add Your Experience','doccure');?></h3>
		<a href="javascript:;" class="dc-add_experience"><?php esc_html_e('Add New','doccure');?></a>
	</div>
	<ul class="dc-experienceaccordion accordion dc-experiences">
		<?php 
			if( !empty( $am_experiences ) ) {
				$count_edu	= 1;
				foreach( $am_experiences as $key => $val ) {
					if( $count_edu === 1 ) { $show = 'show'; }else { $show = '';}
					$count_edu ++;
					$company_name		= !empty( $val['company_name'] ) ? $val['company_name'] : '';
					$start_date			= !empty( $val['start_date'] ) ? $val['start_date'] : '';
					$ending_date		= !empty( $val['ending_date'] ) ? $val['ending_date'] : '';
					$job_title			= !empty( $val['job_title'] ) ? $val['job_title'] : '';
					$total_exp			= !empty( $val['total_exp'] ) ? $val['total_exp'] : '';
					$pstart_date		= !empty( $val['start_date'] ) ? date_i18n('F Y', strtotime($val['start_date'])) : '';
					$pending_date		= !empty( $val['ending_date'] ) ? date_i18n('F Y', strtotime($val['ending_date'])) : '';
					$job_description	= !empty( $val['job_description'] ) ? $val['job_description'] : '';
					
					$end_date	= '';
					if( empty( $pending_date ) ){
						$end_date = esc_html__('Current', 'doccure');
					}
					
					if( !empty( $pstart_date ) ){
						$period = $pstart_date . ' - ' .$pending_date;		
					}
					
					if( $end_date == 'Current' ){
						$period = $end_date;
					} ?>
					<li>
						<div class="dc-accordioninnertitle">
							<span id="accordioninnertitle<?php echo intval( $key );?>" data-bs-toggle="collapse" data-bs-target="#innertitle<?php echo intval( $key );?>"><?php echo esc_html( $company_name );?><em> (<?php echo esc_html( $period);?>)</em></span>
							<div class="dc-rightarea">
								<a href="javascript:;" class="dc-addinfo dc-skillsaddinfo" id="accordioninnertitle<?php echo intval( $key );?>" data-bs-toggle="collapse" data-bs-target="#innertitle<?php echo intval( $key );?>" aria-expanded="true"><i class="fa fa-pencil"></i></a>
								<a href="javascript:void(0);" class="dc-deleteinfo"><i class="fa fa-trash"></i></a>
							</div>
						</div>
						<div class="dc-collapseexp collapse" id="innertitle<?php echo intval( $key );?>" aria-labelledby="accordioninnertitle<?php echo intval( $key );?>" data-parent="#accordion">
							<div class="dc-formtheme dc-userform">
								<fieldset>
									<div class="form-group form-group-half">
										<input type="text" name="am_experiences[<?php echo intval($key);?>][company_name]" class="form-control" placeholder="<?php esc_attr_e('Company name','doccure');?>" value="<?php echo esc_attr( $company_name );?>">
									</div>
									<div class="form-group form-group-half">
										<input type="text" name="am_experiences[<?php echo intval($key);?>][start_date]" class="form-control dc-date-pick" placeholder="<?php esc_attr_e('Starting Date','doccure');?>" value="<?php echo esc_attr($start_date);?>">
									</div>
									<div class="form-group form-group-half">
										<input type="text" name="am_experiences[<?php echo intval($key);?>][ending_date]" class="form-control dc-date-pick" placeholder="<?php esc_attr_e('Ending Date','doccure');?>" value="<?php echo esc_attr($ending_date);?>">
									</div>
									<div class="form-group form-group-half">
										<input type="text" name="am_experiences[<?php echo intval($key);?>][job_title]" class="form-control" placeholder="<?php esc_attr_e('job title','doccure');?>" value="<?php echo esc_attr($job_title);?>">
									</div>
									<div class="form-group form-group-half">
										<input type="text" name="am_experiences[<?php echo intval($key);?>][total_exp]" class="form-control" placeholder="<?php esc_attr_e('Total Experiance','doccure');?>" value="<?php echo esc_attr($total_exp);?>">
									</div>
									<div class="form-group">
										<textarea name="am_experiences[<?php echo intval($key);?>][job_description]" class="form-control" placeholder="<?php esc_attr_e('Your Job Description','doccure');?>"><?php echo esc_html($job_description);?></textarea>
									</div>
									<div class="form-group">
										<span>* <?php esc_html_e('Leave ending date empty if its your current job','doccure');?></span>
									</div>
								</fieldset>
							</div>
						</div>
					</li>
			<?php } ?>
		<?php } ?>		
	</ul>
</div>
<script type="text/template" id="tmpl-load-experience">
	<li>
		<div class="dc-accordioninnertitle">
			<span id="accordioninnertitle{{data.counter}}" data-bs-toggle="collapse" data-bs-target="#innertitle{{data.counter}}"><?php esc_html_e('Company title', 'doccure'); ?></span>&nbsp;<em><?php esc_html_e('(Start Date - End Date)', 'doccure'); ?></em></span>
			<div class="dc-rightarea">
				<a href="javascript:;" class="dc-addinfo dc-skillsaddinfo" id="accordioninnertitle{{data.counter}}" data-bs-toggle="collapse" data-bs-target="#innertitle{{data.counter}}" aria-expanded="true"><i class="fa fa-pencil"></i></a>
				<a href="javascript:void(0);" class="dc-deleteinfo"><i class="fa fa-trash"></i></a>
			</div>
		</div>
		<div class="dc-collapseexp collapse show" id="innertitle{{data.counter}}" aria-labelledby="accordioninnertitle{{data.counter}}" data-parent="#accordion">
			<div class="dc-formtheme dc-userform">
				<fieldset>
					<div class="form-group form-group-half">
						<input type="text" name="am_experiences[{{data.counter}}][company_name]" class="form-control" placeholder="<?php esc_attr_e('Company Name','doccure');?>" value="">
					</div>
					<div class="form-group form-group-half">
						<input type="text" name="am_experiences[{{data.counter}}][start_date]" class="form-control dc-date-pick" placeholder="<?php esc_attr_e('Starting Date','doccure');?>" value="">
					</div>
					<div class="form-group form-group-half">
						<input type="text" name="am_experiences[{{data.counter}}][ending_date]" class="form-control dc-date-pick" placeholder="<?php esc_attr_e('Ending Date','doccure');?>" value="">
					</div>
					<div class="form-group form-group-half">
						<input type="text" name="am_experiences[{{data.counter}}][job_title]" class="form-control" placeholder="<?php esc_attr_e('Job Title','doccure');?>" value="">
					</div>
					<div class="form-group form-group-half">
						<input type="text" name="am_experiences[{{data.counter}}][total_exp]" class="form-control" placeholder="<?php esc_attr_e('Total Experience','doccure');?>" value="">
					</div>
					<div class="form-group">
						<textarea name="am_experiences[{{data.counter}}][job_description]" class="form-control" placeholder="<?php esc_attr_e('Your Job Description','doccure');?>"></textarea>
					</div>
					<div class="form-group">
						<span>* <?php esc_html_e('Leave ending date empty if its your current job','doccure'); ?></span>
					</div>
				</fieldset>
			</div>
		</div>
	</li>
</script>