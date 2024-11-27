<?php
/**
 * @File Type	General Options for pages, posts and custom post type
 * @package	 	WordPress
 * @link 		https://themeforest.net/user/dreamstechnologies
 */

// die if accessed directly
if (!defined('ABSPATH')) {
    die('no kiddies please!');
}

global $wp_registered_sidebars,$doccure_options;
$json	 = array();
$form_el = new AMetaboxes();

$name_bases	= doccure_get_name_bases('','doctor');

$booking_option			= '';
if( function_exists( 'doccure_get_booking_oncall_option' ) ) {
	$booking_option	= doccure_get_booking_oncall_option();
}

$gallery_option		= !empty($doccure_options['enable_gallery']) ? $doccure_options['enable_gallery'] : '';
$enable_options		= !empty($doccure_options['doctors_contactinfo']) ? $doccure_options['doctors_contactinfo'] : '';

$form_el = new AMetaboxes();
$menu	= array();
$menu[]	= array( esc_html__('Personal details', 'doccure_core') , 'drpersonaldetails' , 'pushpin' , true );
$menu[]	= array( esc_html__('Location', 'doccure_core') , 'drlocation' , 'pushpin' , false );
$menu[]	= array( esc_html__('Membership', 'doccure_core') , 'drmemberships' , 'pushpin' , false );
$menu[]	= array( esc_html__('Education & Experience', 'doccure_core') , 'drexperience' , 'pushpin' , false );
$menu[]	= array( esc_html__('Awards & Downloads', 'doccure_core') , 'drawards' , 'pushpin' , false );
$menu[]	= array( esc_html__('Registration', 'doccure_core') , 'drregistration' , 'pushpin' , false );
$menu[]	= array( esc_html__('Specialities & Services', 'doccure_core') , 'drservices' , 'pushpin' , false );
$menu[]	= array( esc_html__('Gallery', 'doccure_core') , 'drgallery' , 'pushpin' , false );	

if(empty($booking_option)){
	$menu[]	= array( esc_html__('Booking Details', 'doccure_core') , 'drbooking' , 'pushpin' , false );
}

$specialities	= doccure_get_taxonomy_array('specialities');
$specialities_json	= array();
$specialities_json['categories'] = array();
if( !empty( $specialities ) ){
	foreach( $specialities as $speciality ) {
		$services_array				= doccure_list_service_by_specialities($speciality->term_id);
		$json[$speciality->term_id] = $services_array;
	}
	
	$specialities_json['categories'] = $json;
}

?>
<div class="dc-main-metaoptions">
	<div class="am_option_tabs">
    <ul><?php $form_el->form_process_general_menu($menu); ?></ul>
	</div>
	<div class='am_metabox'>
	   <div id="am_drpersonaldetails_tab" >
		<?php
		   $form_el->form_process_text(
				array(
					'name' 		=> esc_html__('Featured date','doccure_core'),
					'id' 		=> '_featured_date',
					'meta' 		=> '',
			   		'std' 		=> '',
			   		'option' 	=> 'single',
			   		'classes' 	=> 'dc-datetimepicker',
					'desc' 		=>  esc_html__('Featured user date, leave it empty to remove user from featured.','doccure_core'),
				)
			);
			$form_el->form_process_select(
				array(
					'name' 		=> esc_html__('Name base','doccure_core'),
					'id' 		=> 'name_base',
					'std' 		=> 'default',
					'desc' 		=> '',
					'options' 	=> $name_bases
				)
			);

		   $form_el->form_process_hidden( 'included','name_base');
		   
		   $form_el->form_process_select(
				array(
					'name' 		=> esc_html__('Gender','doccure_core'),
					'id' 		=> 'gender',
					'std' 		=> 'default',
					'desc' 		=> '',
					'options' 	=> array(
		   				'male'   => esc_html__('Male','doccure_core'),
			   			'female' => esc_html__('Female','doccure_core'),
			   			'all' 	 => esc_html__('Others','doccure_core'),
		   			)
				)
			);

		   $form_el->form_process_hidden( 'included','gender');
		   
		   $form_el->form_process_text(
				array('name' 	=> esc_html__('Sub heading','doccure_core'),
					'id' 		=> 'sub_heading',
					'std' 		=> '',
					'desc' 		=> esc_html__('','doccure_core'),
					'meta' 		=> ''
				)
			);

		   $form_el->form_process_text(
				array('name' 	=> esc_html__('First name','doccure_core'),
					'id' 		=> 'first_name',
					'std' 		=> '',
					'desc' 		=> esc_html__('','doccure_core'),
					'meta' 		=> ''
				)
			);
		   $form_el->form_process_text(
				array('name' 	=> esc_html__('Last name','doccure_core'),
					'id' 		=> 'last_name',
					'std' 		=> '',
					'desc' 		=> esc_html__('','doccure_core'),
					'meta' 		=> ''
				)
			);
		   $form_el->form_process_text(
				array('name' 	=> esc_html__('Short description','doccure_core'),
					'id' 		=> 'short_description',
					'std' 		=> '',
					'desc' 		=> esc_html__('','doccure_core'),
					'meta' 		=> ''
				)
			);
			$form_el->form_process_text(
				array('name' 	=> esc_html__('Starting Price','doccure_core'),
					'id' 		=> 'starting_price',
					'std' 		=> '',
					'desc' 		=> esc_html__('','doccure_core'),
					'meta' 		=> ''
				)
			);
			$form_el->form_process_text(
				array('name' 	=> esc_html__('Personal mobile number','doccure_core'),
					'id' 		=> 'mobile_number',
					'std' 		=> '',
					'desc' 		=> esc_html__('','doccure_core'),
					'meta' 		=> ''
				)
			);

			if( !empty($enable_options) && $enable_options === 'yes' ){
				
				$form_el->form_repeater_single(
					array('name' 		=> esc_html__('Phone Number"s','doccure_core'),
						'id' 			=> 'phone_numbers',
						'std' 			=> '',
						'field_type'	=> 'text',
						'desc' 			=> esc_html__('','doccure_core'),
						'meta' 			=> '',
						'btn_text'		=> esc_html__('Add row','doccure_core'),
					)
				);
			
				$form_el->form_process_text(
					array('name' 	=> esc_html__('Web url','doccure_core'),
						'id' 		=> 'web_url',
						'std' 		=> '',
						'desc' 		=> esc_html__('','doccure_core'),
						'meta' 		=> ''
					)
				);
			}
		?>
		</div>	
		<?php if(empty($booking_option)){ ?>
			<div id="am_drbooking_tab" style="display:none" >
				<?php
					$form_el->form_repeater_single(
							array('name' 		=> esc_html__('Contact phone numbers','doccure_core'),
								'id' 			=> 'booking_contact',
								'std' 			=> '',
								'field_type'	=> 'text',
								'desc' 			=> esc_html__('','doccure_core'),
								'meta' 			=> '',
								'btn_text'		=> esc_html__('Add row','doccure_core'),
							)
					);

					$form_el->form_process_textarea(
						array('name' 	=> esc_html__('Booking detail','doccure_core'),
							'id' 		=> 'booking_detail',
							'std' 		=> '',
							'desc' 		=> esc_html__('','doccure_core'),
							'meta' 		=> ''
						)
				);	
				?>
			</div>
		<?php } ?>
		<div id="am_drlocation_tab" style="display:none" >
			 <?php
				$form_el->form_process_text(
					array(
						'name' 	=> esc_html__('Address','doccure_core'),
						'id' 		=> '_address',
						'std' 		=> '',
						'option' 	=> 'single',
						'desc' 		=> esc_html__('','doccure_core'),
						'meta' 		=> '',

					)
				);
				$form_el->form_process_text(
					array(
						'name' 	=> esc_html__('Latitude','doccure_core'),
						'id' 		=> '_latitude',
						'std' 		=> '',
						'option' 	=> 'single',
						'desc' 		=> esc_html__('','doccure_core'),
						'meta' 		=> '',

					)
				);
				$form_el->form_process_text(
					array(
						'name' 	=> esc_html__('Longitude','doccure_core'),
						'id' 		=> '_longitude',
						'std' 		=> '',
						'option' 	=> 'single',
						'desc' 		=> esc_html__('','doccure_core'),
						'meta' 		=> '',

					)
				);
			?>
		</div>
		<?php if(!empty($gallery_option)){ ?>
			<div id="am_drgallery_tab" style="display:none" >
				<?php
					$form_el->form_process_gallery(
						array(
							'name' 		=> esc_html__('Gallery', 'doccure_core'),
							'id' 		=> 'gallery',
							'std' 		=> '',
							'desc' 		=> esc_html__('', 'doccure_core'),
							'meta' 		=> ''
						)
					);
	
					$form_el->form_repeater_single(
						array('name' 		=> esc_html__('Videos','doccure_core'),
							'id' 			=> 'videos',
							'std' 			=> '',
							'field_type'	=> 'text',
							'desc' 			=> esc_html__('','doccure_core'),
							'meta' 			=> '',
							'btn_text'		=> esc_html__('Add video url','doccure_core'),
							'placeholder'	=> esc_html__('Add video url','doccure_core'),
						)
				);
				?>
			</div>
		<?php } ?>
		 <div id="am_drmemberships_tab" style="display:none" >
			 <?php
				$form_el->form_repeater_single(
						array('name' 		=> esc_html__('Membership Name','doccure_core'),
							'id' 			=> 'memberships_name',
							'std' 			=> '',
							'field_type'	=> 'text',
							'desc' 			=> esc_html__('','doccure_core'),
							'meta' 			=> '',
							'btn_text'		=> esc_html__('Add row','doccure_core'),
						)
				);
			?>
		</div>
		<div id="am_drexperience_tab" style="display:none" >
			 <?php
				$form_el->form_repeater_multiple(
					array('name' 	=> esc_html__('Experiences','doccure_core'),
						'id' 		=> 'experiences',
						'std' 		=> '',
						'desc' 		=> esc_html__('','doccure_core'),
						'meta' 		=> '',
						'btn_text'	=> esc_html__('Add Experience','doccure_core'),
						'fields' 	=> array (array(
														'name' 	=> esc_html__('Company Name','doccure_core'),
														'id' 		=> 'company_name',
														'std' 		=> '',
														'desc' 		=> esc_html__('','doccure_core'),
														'meta' 		=> '',
														'field_type' => 'text'
													),
													array(
														'name' 	=> esc_html__('Start date','doccure_core'),
														'id' 		=> 'start_date',
														'std' 		=> '',
														'desc' 		=> esc_html__('','doccure_core'),
														'meta' 		=> '',
														'classes' 	=> 'dc-datetimepicker',
														'field_type' => 'text'
													),array(
														'name' 	=> esc_html__('Ending date','doccure_core'),
														'id' 		=> 'ending_date',
														'std' 		=> '',
														'desc' 		=> esc_html__('','doccure_core'),
														'meta' 		=> '',
														'classes' 	=> 'dc-datetimepicker',
														'field_type' => 'text'
													), array(
														'name' 	=> esc_html__('Job title','doccure_core'),
														'id' 		=> 'job_title',
														'std' 		=> '',
														'desc' 		=> esc_html__('','doccure_core'),
														'meta' 		=> '',
														'field_type' => 'text'
													), array(
														'name' 		=> esc_html__('Job Description','doccure_core'),
														'id' 		=> 'job_description',
														'std' 		=> '',
														'desc' 		=> esc_html__('','doccure_core'),
														'meta' 		=> '',
														'field_type' => 'textarea'
													)
										)
					)
			);
			$form_el->form_repeater_multiple(
					array('name' 	=> esc_html__('Education','doccure_core'),
						'id' 		=> 'education',
						'std' 		=> '',
						'desc' 		=> esc_html__('','doccure_core'),
						'meta' 		=> '',
						'btn_text'	=> esc_html__('Add Education','doccure_core'),
						'fields' 	=> array (array(
													'name' 	=> esc_html__('Institute name','doccure_core'),
													'id' 		=> 'institute_name',
													'std' 		=> '',
													'desc' 		=> esc_html__('','doccure_core'),
													'meta' 		=> '',
													'field_type' => 'text'
												),
												array(
													'name' 	=> esc_html__('Start date','doccure_core'),
													'id' 		=> 'start_date',
													'std' 		=> '',
													'desc' 		=> esc_html__('','doccure_core'),
													'meta' 		=> '',
													'classes' 	=> 'dc-datetimepicker',
													'field_type' => 'text'
												),array(
													'name' 	=> esc_html__('Ending date','doccure_core'),
													'id' 		=> 'ending_date',
													'std' 		=> '',
													'classes' 	=> 'dc-datetimepicker',
													'desc' 		=> esc_html__('','doccure_core'),
													'meta' 		=> '',
													'field_type' => 'text'
												), array(
													'name' 	=> esc_html__('Degree title','doccure_core'),
													'id' 		=> 'degree_title',
													'std' 		=> '',
													'desc' 		=> esc_html__('','doccure_core'),
													'meta' 		=> '',
													'field_type' => 'text'
												), array(
													'name' 		=> esc_html__('Degree description','doccure_core'),
													'id' 		=> 'degree_description',
													'std' 		=> '',
													'desc' 		=> esc_html__('','doccure_core'),
													'meta' 		=> '',
													'field_type' => 'textarea'
												)
										)
					)
			);
			?>
		</div>
		<div id="am_drawards_tab" style="display:none" >
			 <?php
				$form_el->form_repeater_multiple(
					array('name' 	=> esc_html__('Awards','doccure_core'),
						'id' 		=> 'award',
						'std' 		=> '',
						'desc' 		=> esc_html__('','doccure_core'),
						'meta' 		=> '',
						'btn_text'	=> esc_html__('Add row','doccure_core'),
						'fields' 	=> array (array(
														'name' 	=> esc_html__('Title','doccure_core'),
														'id' 		=> 'title',
														'std' 		=> '',
														'desc' 		=> esc_html__('','doccure_core'),
														'meta' 		=> '',
														'field_type' => 'text'
													),
													array(
														'name' 	=> esc_html__('Start Year','doccure_core'),
														'id' 		=> 'year',
														'std' 		=> '',
														'desc' 		=> esc_html__('','doccure_core'),
														'meta' 		=> '',
														'field_type' => 'text'
													)
										)
					)
			);
			$form_el->form_repeater_multiple(
				array('name' 	=> esc_html__('Downloads','doccure_core'),
					'id' 		=> 'downloads',
					'std' 		=> '',
					'desc' 		=> esc_html__('','doccure_core'),
					'meta' 		=> '',
					'btn_text'	=> esc_html__('Add row','doccure_core'),
					'fields' 	=> array (array(
													'name' 	=> esc_html__('Upload','doccure_core'),
													'id' 		=> 'media',
													'std' 		=> '',
													'desc' 		=> esc_html__('','doccure_core'),
													'meta' 		=> '',
													'field_type' => 'media',
													'thumnail'	=> false
												),
										  )
					  )
				);
			?>
		</div>
		 <div id="am_drregistration_tab" style="display:none">
			 <?php
				$form_el->form_process_text(
					array('name' 	=> esc_html__('Registration number','doccure_core'),
						'id' 		=> 'registration_number',
						'std' 		=> '',
						'desc' 		=> esc_html__('','doccure_core'),
						'meta' 		=> ''
					)
				);
				$form_el->form_process_upload(
					array('name' 	=> esc_html__('Upload document','doccure_core'),
						'id' 		=> 'document',
						'std' 		=> '',
						'desc' 		=> esc_html__('','doccure_core'),
						'meta' 		=> '',
						'thumnail'	=> false
					)
				);
				$form_el->form_process_checkbox(
					array(
						'name' 		=> esc_html__('Verified document','doccure_core'),
						'id' 		=> 'is_verified',
						'std' 		=> 'no',
						'desc' 		=> esc_html__('','doccure_core'),
						'meta' 		=> ''
					)
				);
			?>
		</div>
		<div id="am_drservices_tab" style="display:none">
			 <?php
				$form_el->form_nested_repeater(
					array('name' 	=> esc_html__('Manage specialities & services','doccure_core'),
						'id' 		=> 'specialities',
						'std' 		=> '',
						'desc' 		=> esc_html__('','doccure_core'),
						'meta' 		=> '',
						'btn_text'	=> esc_html__('Add row','doccure_core'),
						'fields' 	=> array (
											array(
												'name' 		=> esc_html__('Name base','doccure_core'),
												'id' 		=> 'speciality_id',
												'std' 		=> 'default',
												'desc' 		=> '',
												'options' 	=> $name_bases,
												'field_type' => 'specialities',
											)
										)
						  )
					);
			?>
		</div>
	</div>
	<script>
		var DT_Editor = {};
			DT_Editor.elements = {};
			window.DT_Editor = DT_Editor;
			DT_Editor.elements = jQuery.parseJSON( '<?php echo addslashes(json_encode($specialities_json['categories']));?>' );
	</script>           
	<script type="text/template" id="tmpl-load-memberships_name">
		<div class="repeater-wrap-inner">
			<div class="remove-repeater"><span class="dashicons dashicons-trash"></span></div>
			<div class="am_field">
				<input type="text" placeholder="<?php echo esc_html__('Membership Name','doccure_core');?>" id="field-{{data.counter}}" name="am_{{data.name}}[]" value="">
			</div>
		</div>
	</script>
	<script type="text/template" id="tmpl-load-repeater">
		<div class="repeater-wrap-inner">
			<div class="remove-repeater"><span class="dashicons dashicons-trash"></span></div>
			<div class="am_field">
				<input type="text" placeholder="{{data.placeholder}}" id="field-{{data.counter}}" name="am_{{data.name}}[]" value="">
			</div>
		</div>
	</script>
	<script type="text/template" id="tmpl-load-booking_contact">
		<div class="repeater-wrap-inner">
			<div class="remove-repeater"><span class="dashicons dashicons-trash"></span></div>
			<div class="am_field">
				<input type="text" placeholder="<?php echo esc_html__('Booking Contact number','doccure_core');?>" id="field-{{data.counter}}" name="am_{{data.name}}[]" value="">
			</div>
		</div>
	</script>
	<script type="text/template" id="tmpl-load-specialities">
		<div class="repeater-wrap-inner specialities_parents" id="{{data.counter}}">
			<div class="remove-repeater"><span class="dashicons dashicons-trash"></span></div>
			<div class="am_field">
				<div class="am_field dropdown-style">
					<?php doccure_get_specialities_list('am_specialities[{{data.counter}}][speciality_id]');?>
				</div>
				<div class="services-wrap">
					<div class="system-buttons">
						<a href="javascript:;" id="add-service-{{data.counter}}" data-id="{{data.counter}}" class="add-repeater-services"><?php echo esc_html__('Add Services','doccure_core');?></a>
					</div>
				</div>
			</div>
		</div>
	</script>
	<script type="text/template" id="tmpl-load-services">
		<div class="repeater-wrap-inner services-item" id="{{data.counter}}">
			<div class="remove-repeater"><span class="dashicons dashicons-trash"></span></div>
			<div class="am_field">
				<div class="am_field dropdown-style related-services">
					<select name="am_specialities[{{data.id}}][services][{{data.counter}}][service]" class="sp_services">
						<#if( !_.isEmpty(data['options']) ) {#>
							<#
								var _option	= '';
								_.each( data['options'] , function(element, index, attr) {
									var _checked	= '';

								#>
									<option value="{{index}}" data-id="{{index}}">{{element["name"]}}</option>
								<#	
								});
							#>
						<# } #>
					</select>
				</div>
				<div class="am_field">
					<input type="text" name="am_specialities[{{data.id}}][services][{{data.counter}}][price]" placeholder="<?php esc_html_e('Price','doccure_core');?>" class="">
				</div>
				<div class="am_field">
					<textarea name="am_specialities[{{data.id}}][services][{{data.counter}}][description]" placeholder="<?php esc_html_e('Description','doccure_core');?>" class=""></textarea>
				</div>
			</div>
		</div>
	</script>
	<script type="text/template" id="tmpl-load-services-options">
		<# if( !_.isEmpty(data['options']) ) {#>
			<# _.each( data['options'] , function(element, index, attr) {#>
					<option value="{{index}}" data-id="{{index}}">{{element["name"]}}</option>
				<#	});
			#>
		<# } #>
	</script>
	<script type="text/template" id="tmpl-load-downloads">
		<div class="repeater-wrap-inner">
			<div class="remove-repeater"><span class="dashicons dashicons-trash"></span></div>
			<div class="am_field">
				<div class="section-upload">
					<div class="z-option-uploader">
						<div class="input-sec">
							<input id="field-{{data.counter}}" name="am_{{data.name}}[{{data.counter}}][media]" type="text" value="">
							<input id="field-{{data.counter}}" name="am_{{data.name}}[{{data.counter}}][id]" type="hidden" class="upload" value="">
						</div>
						<div class="system-buttons">
							<span id="upload" class="button system_media_upload_button"><?php esc_html_e('Upload','doccure_core');?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</script>
	<script type="text/template" id="tmpl-load-exprience">
		<div class="repeater-wrap-inner">
			<div class="remove-repeater"><span class="dashicons dashicons-trash"></span></div>
			<div class="am_field">
				<input type="text" placeholder="<?php echo esc_html__('Company name','doccure_core');?>" id="field-{{data.counter}}" name="am_experiences[{{data.counter}}][comapny_name]" value="">
			</div>
			<div class="am_field">
				<input type="text" class="dc-datetimepicker" placeholder="<?php echo esc_html__('Start date','doccure_core');?>" id="field-{{data.counter}}" name="am_experiences[{{data.counter}}][start_date]" value="">
			</div>
			<div class="am_field">
				<input type="text" class="dc-datetimepicker"  placeholder="<?php echo esc_html__('End date','doccure_core');?>" id="field-{{data.counter}}" name="am_experiences[{{data.counter}}][ending_date]" value="">
			</div>
			<div class="am_field">
				<input type="text" placeholder="<?php echo esc_html__('Job title','doccure_core');?>" id="field-{{data.counter}}" name="am_experiences[{{data.counter}}][job_title]" value="">
			</div>
			<div class="am_field">
				<textarea cols="125" rows="8" id="field-{{data.counter}}" name="am_experiences[{{data.counter}}][job_description]"></textarea>
			</div>
		</div>
	</script>
	<script type="text/template" id="tmpl-load-education">
		<div class="repeater-wrap-inner">
			<div class="remove-repeater"><span class="dashicons dashicons-trash"></span></div>
			<div class="am_field">
				<input type="text" placeholder="<?php echo esc_html__('Institute name','doccure_core');?>" id="field-{{data.counter}}" name="am_education[{{data.counter}}][institute_name]" value="">
			</div>
			<div class="am_field">
				<input type="text" class="dc-datetimepicker"  placeholder="<?php echo esc_html__('Start date','doccure_core');?>" id="field-{{data.counter}}" name="am_education[{{data.counter}}][start_date]" value="">
			</div>
			<div class="am_field">
				<input type="text" class="dc-datetimepicker" placeholder="<?php echo esc_html__('End date','doccure_core');?>" id="field-{{data.counter}}" name="am_education[{{data.counter}}][ending_date]" value="">
			</div>
			<div class="am_field">
				<input type="text" placeholder="<?php echo esc_html__('Degree title','doccure_core');?>" id="field-{{data.counter}}" name="am_education[{{data.counter}}][degree_title]" value="">
			</div>
			<div class="am_field">
				<textarea cols="125" rows="8" id="field-{{data.counter}}" name="am_education[{{data.counter}}][degree_description]"></textarea>
			</div>
		</div>
	</script>
	<script type="text/template" id="tmpl-load-award">
		<div class="repeater-wrap-inner">
			<div class="remove-repeater"><span class="dashicons dashicons-trash"></span></div>
			<div class="am_field">
				<input type="text" placeholder="<?php echo esc_html__('Title','doccure_core');?>" id="field-{{data.counter}}" name="am_award[{{data.counter}}][title]" value="">
			</div>
			<div class="am_field">
				<input type="text" placeholder="<?php echo esc_html__('Year','doccure_core');?>" id="field-{{data.counter}}" name="am_award[{{data.counter}}][year]" value="">
			</div>
		</div>
	</script>
	<script type="text/template" id="tmpl-load-phone_numbers">
		<div class="repeater-wrap-inner">
			<div class="remove-repeater"><span class="dashicons dashicons-trash"></span></div>
			<div class="am_field">
				<input type="text" placeholder="<?php echo esc_html__('Phone number','doccure_core');?>" id="field-{{data.counter}}" name="am_{{data.name}}[]" value="">
			</div>
		</div>
	</script>
</div>