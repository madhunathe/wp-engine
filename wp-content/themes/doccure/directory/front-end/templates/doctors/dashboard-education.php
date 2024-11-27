<?php
/**
 *
 * The template part for displaying the dashboard education
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
$education 		= array();

$am_education	= doccure_get_post_meta( $post_id,'am_education');
?>
<div class="dc-usereducation dc-tabsinfo">
	<div class="dc-tabscontenttitle dc-addnew mt-0">
		<h3><?php esc_html_e('Add Your Education','doccure');?></h3>
		<a href="javascript:;" class="dc-add_education"><?php esc_html_e('Add New','doccure');?></a>
	</div>
	<ul class="dc-educationaccordion accordion dc-educations">
		<?php 
			if( !empty( $am_education ) ) {
				$count_edu	= 1;
				foreach( $am_education as $key => $val ) {
					if( $count_edu === 1 ) { $show = 'show'; }else { $show = '';}
					$count_edu ++;
					$institute_name		= !empty( $val['institute_name'] ) ? $val['institute_name'] : '';
					$start_date			= !empty( $val['start_date'] ) ? $val['start_date'] : '';
					$ending_date		= !empty( $val['ending_date'] ) ? $val['ending_date'] : '';
					$degree_title		= !empty( $val['degree_title'] ) ? $val['degree_title'] : '';
					$pstart_date		= !empty( $val['start_date'] ) ? date_i18n('F Y', strtotime($val['start_date'])) : '';
					$pending_date		= !empty( $val['ending_date'] ) ? date_i18n('F Y', strtotime($val['ending_date'])) : '';
					$degree_description	= !empty( $val['degree_description'] ) ? $val['degree_description'] : '';
					
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
							<span id="accordioninnertitle<?php echo intval( $key );?>" data-bs-toggle="collapse" data-bs-target="#innertitle<?php echo intval( $key );?>"><?php echo esc_html( $institute_name );?><em> (<?php echo esc_attr( $period);?>)</em></span>
							<div class="dc-rightarea">
								<a href="javascript:;" class="dc-addinfo dc-skillsaddinfo" id="accordioninnertitle<?php echo intval( $key );?>" data-bs-toggle="collapse" data-bs-target="#innertitle<?php echo intval( $key );?>" aria-expanded="true"><i class="fa fa-pencil"></i></a>
								<a href="javascript:void(0);" class="dc-deleteinfo"><i class="fa fa-trash"></i></a>
							</div>
						</div>
						<div class="dc-collapseexp collapse" id="innertitle<?php echo intval( $key );?>" aria-labelledby="accordioninnertitle<?php echo intval( $key );?>" data-parent="#accordion">
							<div class="dc-formtheme dc-userform">
								<fieldset>
									<div class="form-group form-group-half">
										<input type="text" name="am_education[<?php echo intval($key);?>][institute_name]" class="form-control" placeholder="<?php esc_attr_e('Institute Name','doccure');?>" value="<?php echo esc_attr( $institute_name );?>">
									</div>
									<div class="form-group form-group-half">
										<input type="text" name="am_education[<?php echo intval($key);?>][start_date]" class="form-control dc-date-pick" placeholder="<?php esc_attr_e('Starting Date','doccure');?>" value="<?php echo esc_attr($start_date);?>">
									</div>
									<div class="form-group form-group-half">
										<input type="text" name="am_education[<?php echo intval($key);?>][ending_date]" class="form-control dc-date-pick" placeholder="<?php esc_attr_e('Ending Date','doccure');?>" value="<?php echo esc_attr($ending_date);?>">
									</div>
									<div class="form-group form-group-half">
										<input type="text" name="am_education[<?php echo intval($key);?>][degree_title]" class="form-control" placeholder="<?php esc_attr_e('Degree Title','doccure');?>" value="<?php echo esc_attr($degree_title);?>">
									</div>
									<div class="form-group">
										<textarea name="am_education[<?php echo intval($key);?>][degree_description]" class="form-control" placeholder="<?php esc_attr_e('Your Job Description','doccure');?>"><?php echo esc_html($degree_description);?></textarea>
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

<script type="text/template" id="tmpl-load-education">
	<li>
		<div class="dc-accordioninnertitle">
			<span id="accordioninnertitle{{data.counter}}" data-bs-toggle="collapse" data-bs-target="#innertitle{{data.counter}}"><?php esc_html_e('Education title', 'doccure'); ?></span>&nbsp;<em><?php esc_html_e('(Start Date - End Date)', 'doccure'); ?></em></span>
			<div class="dc-rightarea">
				<a href="javascript:;" class="dc-addinfo dc-skillsaddinfo" id="accordioninnertitle{{data.counter}}" data-bs-toggle="collapse" data-bs-target="#innertitle{{data.counter}}" aria-expanded="true"><i class="fa fa-pencil"></i></a>
				<a href="javascript:void(0);" class="dc-deleteinfo"><i class="fa fa-trash"></i></a>
			</div>
		</div>
		<div class="dc-collapseexp collapse show" id="innertitle{{data.counter}}" aria-labelledby="accordioninnertitle{{data.counter}}" data-parent="#accordion">
			<div class="dc-formtheme dc-userform">
				<fieldset>
					<div class="form-group form-group-half">
						<input type="text" name="am_education[{{data.counter}}][institute_name]" class="form-control" placeholder="<?php esc_attr_e('Institute Name','doccure');?>" value="">
					</div>
					<div class="form-group form-group-half">
						<input type="text" name="am_education[{{data.counter}}][start_date]" class="form-control dc-date-pick" placeholder="<?php esc_attr_e('Starting Date','doccure');?>" value="">
					</div>
					<div class="form-group form-group-half">
						<input type="text" name="am_education[{{data.counter}}][ending_date]" class="form-control dc-date-pick" placeholder="<?php esc_attr_e('Ending Date','doccure');?>" value="">
					</div>
					<div class="form-group form-group-half">
						<input type="text" name="am_education[{{data.counter}}][degree_title]" class="form-control" placeholder="<?php esc_attr_e('Degree Title','doccure');?>" value="">
					</div>
					<div class="form-group">
						<textarea name="am_education[{{data.counter}}][degree_description]" class="form-control" placeholder="<?php esc_attr_e('Your Job Description','doccure');?>"></textarea>
					</div>
					<div class="form-group">
						<span>* <?php esc_html_e('Leave ending date empty if its your current job','doccure');?></span>
					</div>
				</fieldset>
			</div>
		</div>
	</li>
</script>