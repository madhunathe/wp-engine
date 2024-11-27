<?php 
/**
 *
 * The template part for add/update prescription 
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user;
$booking_id			= !empty($_GET['booking_id']) ? intval($_GET['booking_id']) : "";
$timezone_string	= !empty(get_option('timezone_string')) ? get_option('timezone_string') : 'UTC';
$male_checked		= '';
$female_checked		= '';
$shemale_checked	= '';
$prescription_id	= '';
$marital_status		= '';
$medical_history	= '';
$medicine			= array();
$diseases			= array();
$childhood_illness	= array();
$diseases_list		= array();
$vital_signs		= array();
if( !empty($booking_id) ){
	
	$doctor_profile_id	= doccure_get_linked_profile_id($current_user->ID);
	$specialities 		= wp_get_post_terms( $doctor_profile_id, 'specialities', array( 'fields' => 'ids' ) );
	
	if(!empty($specialities) ){
		$diseases_arg = array(
				'hide_empty' => false,
				/*'meta_query' => array(
					array(
					'key'       => 'speciality',
					'value'     => $specialities,
					'compare'   => 'IN'
					)
				),*/
				'taxonomy'  => 'diseases',
				'fields'	=> 'ids'
			);
		$diseases = get_terms( $diseases_arg );
	}
	$prescription_id	= get_post_meta( $booking_id, '_prescription_id', true );
}

if( !empty($booking_id) && empty($prescription_id) ){
	$bk_username	= get_post_meta( $booking_id, 'bk_username', true );
	$bk_phone		= get_post_meta( $booking_id, 'bk_phone', true );
	$patient_id		= get_post_field( 'post_author', $booking_id );
	$patient_id		= !empty($patient_id) ? $patient_id : '';
	$patient_profile_id	= doccure_get_linked_profile_id($patient_id);

	$patient_address	= get_post_meta( $patient_profile_id , '_address',true );
	$base_name			= doccure_get_post_meta( $patient_profile_id , 'am_name_base' );
	$base_name			= !empty($base_name) ? $base_name : '';

	$dob				= get_post_meta( $patient_profile_id , '_dob',true );
	$dob				= !empty($dob) ? $dob : '12/12/1990';

	$time_zone  = new DateTimeZone($timezone_string);
	$age 		= !empty($dob) ? DateTime::createFromFormat('d/m/Y', $dob, $time_zone)->diff(new DateTime('now', $time_zone))->y : '';

	if( !empty($base_name) ){
		if($base_name === 'mr'){
			$male_checked	= 'checked';
		} else if($base_name === 'miss'){
			$female_checked	= 'checked';
		}
	}
	$location 			= apply_filters('doccure_get_tax_query',array(),$patient_profile_id,'locations','');
	//Get country
	

} else if( !empty($prescription_id) ){
	$prescription	= get_post_meta( $prescription_id, '_detail', true );
	
	$patient_id		= get_post_meta( $prescription_id, '_patient_id', true );
	
	$patient_id			= !empty($patient_id) ? $patient_id : '';
	$patient_profile_id	= doccure_get_linked_profile_id($patient_id);


	$bk_username	= !empty($prescription['_patient_name']) ? $prescription['_patient_name'] : '';
	$bk_phone		= !empty($prescription['_phone']) ? $prescription['_phone'] : '';
	$age			= !empty($prescription['_age']) ? $prescription['_age'] : '';
	$gender			= !empty($prescription['_gender']) ? $prescription['_gender'] : '';
	
	$medical_history	= !empty($prescription['_medical_history']) ? $prescription['_medical_history'] : '';
	$medicine			= !empty($prescription['_medicine']) ? $prescription['_medicine'] : array();
	$vital_signs		= !empty($prescription['_vital_signs']) ? $prescription['_vital_signs'] : '';
	$patient_address	= !empty($prescription['_address']) ? $prescription['_address'] : '';
	$marital_status		= !empty($prescription['_marital_status']) ? $prescription['_marital_status'] : '';
	$childhood_illness	= !empty($prescription['_childhood_illness']) ? $prescription['_childhood_illness'] : array();

	if( !empty($gender) && $gender === 'male'){
		$male_checked	= 'checked';
	} else if(!empty($gender) && $gender === 'female'){
		$female_checked	= 'checked';
	} 
	
	$location 			= apply_filters('doccure_get_tax_query',array(),$prescription_id,'locations','');
	$diseases_list 		= wp_get_post_terms( $prescription_id, 'diseases', array( 'fields' => 'ids' ) );
	
}

$prescription_id	= !empty($prescription_id) ? $prescription_id : '';
$username			= !empty($bk_username) ? $bk_username : '';
$phone				= !empty($bk_phone) ? $bk_phone : '';
$patient_address	= !empty($patient_address) ? $patient_address : '';

if( !empty( $location[0]->term_id ) ){
	$location = !empty( $location[0]->term_id ) ? $location[0]->term_id : '';
}

$location 				= !empty( $location ) ? $location : '';
$laboratory_tests 		= doccure_get_taxonomy_array('laboratory_tests');
$rand_val				= rand(1, 9999);

?>
<div class="">		
	<div class="dc-haslayout dc-prescription-wrap dc-dashboardbox dc-dashboardtabsholder">
		<div class="dc-dashboardboxtitle">
			<h2><?php esc_html_e('Generate Patient Prescription','doccure');?></h2>
		</div>
		<div class="dc-dashboardboxcontent">
			<form class="dc-prescription-form" method="post">
				<div class="dc-dashboardbox dc-prescriptionbox">
					<div class="dc-title">
						<h5><?php esc_html_e('Patient Information','doccure');?>:</h5>
					</div>
					<div class="dc-formtheme dc-userform">
						<fieldset>
							<div class="form-group form-group-half">
								<input type="text" name="patient_name" class="form-control" value="<?php echo esc_attr($username);?>" placeholder="<?php esc_attr_e('Patient Name','doccure');?>">
							</div>
							<div class="form-group form-group-half">
								<input type="text" name="phone" class="form-control" value="<?php echo esc_attr($bk_phone);?>" placeholder="<?php esc_attr_e('Patient Phone','doccure');?>">
							</div>
							<div class="form-group form-group-half">
								<input type="text" name="age" class="form-control" value="<?php echo esc_attr($age);?>" placeholder="<?php esc_attr_e('Age','doccure');?>">
							</div>
							<div class="form-group form-group-half">
								<input type="text" name="address" value="<?php echo esc_attr($patient_address);?>" class="form-control" placeholder="<?php esc_attr_e('Address','doccure');?>">
							</div>
							<div class="form-group form-group-half">
								<span class="dc-select">
									<?php do_action('doccure_get_locations_list','location',$location);?>
								</span>
							</div>
							<div class="form-group form-group-half">
							<div class="dc-title">
								<h5><?php esc_html_e('Gender','doccure');?>:</h5>
							</div>
								<div class="dc-radio-holder">
									<span class="dc-radio">
										<input id="dc-mo-male" type="radio" name="gender" value="male" <?php echo esc_attr($male_checked);?>>
										<label for="dc-mo-male"><?php esc_html_e('Male','doccure');?></label>
									</span>
									<span class="dc-radio">
										<input id="dc-mo-female" type="radio" name="gender" value="female" <?php echo esc_attr($female_checked);?>>
										<label for="dc-mo-female"><?php esc_html_e('Female','doccure');?></label>
									</span>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
				<div class="dc-dashboardbox dc-prescriptionbox">
					<div class="dc-title">
						<h5><?php esc_html_e('Marital Status','doccure');?>:</h5>
					</div>
					<div class="dc-formtheme dc-userform">
					<?php do_action( 'doccure_get_texnomy_radio','marital_status','marital_status',$marital_status);?>
					</div>
				</div>

				<div class="dc-dashboardbox dc-prescriptionbox">
					<div class="dc-title">
						<h5><?php esc_html_e('Childhood illness','doccure');?>:</h5>
					</div>
					<div class="dc-formtheme dc-userform">
						<?php do_action( 'doccure_get_texnomy_checkbox','childhood_illness','childhood_illness[]',$childhood_illness);?>
					</div>
				</div>

				<div class="dc-dashboardbox dc-prescriptionbox">
					<div class="dc-title">
						<h5><?php esc_html_e('Diseases','doccure');?>:</h5>
					</div>
					<div class="dc-formtheme dc-userform">
						<?php do_action( 'doccure_get_texnomy_checkbox','diseases','diseases[]',$diseases_list,$diseases);?>
					</div>
				</div>

				<div class="dc-dashboardbox dc-prescriptionbox">
					<div class="dc-title">
						<h5><?php esc_html_e('Select Laboratory Tests', 'doccure'); ?></h5>
					</div>
					<div class="dc-settingscontent">
						<div class="dc-formtheme dc-userform">
							<fieldset>
								<div class="form-group">
									<select data-placeholder="<?php esc_attr_e('Laboratory Tests', 'doccure'); ?>" class="form-control tests-<?php echo esc_attr($rand_val );?>" name="laboratory_tests[]" multiple="multiple">
										<?php if( !empty( $laboratory_tests ) ){
											foreach( $laboratory_tests as $key => $item ){
												$selected = '';
												if( has_term( $item->term_id, 'laboratory_tests', $prescription_id )  ){
													$selected = 'selected';
												}
											?>
											<option <?php echo esc_attr($selected);?> value="<?php echo intval( $item->term_id );?>"><?php echo esc_html( $item->name );?></option>
										<?php }}?>
									</select>
								</div>
							</fieldset>
						</div>
					</div>
				</div>

				<div class="dc-dashboardbox dc-prescriptionbox">
					<div class="dc-title">
						<h5><?php esc_html_e('Common Issue','doccure');?>:</h5>
					</div>
					<div class="dc-formtheme dc-userform" id="dc-vital-signs">
						<fieldset>
							<div class="form-group form-group-half">
								<?php do_action( 'doccure_get_texnomy_select','vital_signs','',esc_html__('Select vital sign','doccure') ,'','vital_signs');?>
							</div>
							<div class="form-group form-group-half dc-delete-group">
								<input type="text" id="dc-vital-signs-val" class="form-control" placeholder="<?php esc_attr_e('Value','doccure');?>">
							</div>
						</fieldset>
					</div>
					<div class="dc-title-add"><a href="javascript:;" class="dc-add-vitals dc-btn"><?php esc_html_e('Add New','doccure');?></a></div>
					<div class="vital-sign-list">
						<?php 
							if(!empty($vital_signs) ){
								foreach($vital_signs as $vital_key	=> $vital_values ){
									$vital_val	= !empty($vital_values['value']) ? $vital_values['value'] : '';
									?>
									<div class="dc-formtheme dc-userform dc-visal-sign dc-visal-<?php echo esc_attr($vital_key);?>">
										<fieldset>
											<div class="form-group form-group-half">
												<?php do_action( 'doccure_get_texnomy_select','vital_signs','vital_signs['.esc_attr($vital_values['name']).'][name]',esc_html__('Select vital sign','doccure') ,$vital_key);?>
											</div>
											<div class="form-group form-group-half dc-delete-group">
												<input type="text" name="vital_signs[<?php echo esc_attr($vital_values['name']);?>][value]" value="<?php echo esc_attr($vital_val);?>" class="form-control" placeholder="<?php esc_attr_e('Value','doccure');?>">
												<a href="javascript:;" class="dc-deletebtn dc-remove-visual"><i class="fa fa-trash"></i></a>
											</div>
										</fieldset>
									</div>
								<?php }
							}
						?>
					</div>
				</div>
				<div class="dc-dashboardbox dc-prescriptionbox">
					<div class="dc-title">
						<h5><?php esc_html_e('Medical History','doccure');?>:</h5>
					</div>
					<div class="dc-formtheme dc-userform">
						<fieldset>
							<div class="form-group">
								<textarea name="medical_history" class="form-control" placeholder="<?php esc_attr_e('Your Patient Medical History','doccure');?>"><?php echo do_shortcode($medical_history);?></textarea>
							</div>
						</fieldset>
					</div>
				</div>
				<div class="dc-dashboardbox dc-prescriptionbox dc-medications">
					<div class="dc-title">
						<h5><?php esc_html_e('Medications','doccure');?>:</h5> 
					</div>
					<div class="dc-formtheme dc-userform" id="dc-medican-html">
						<fieldset>
							<div class="form-group form-group-half">
								<input type="text" id="medicine_name" class="form-control" placeholder="<?php esc_attr_e('Name','doccure');?>">
							</div>
							<div class="form-group form-group-half">
								<?php do_action( 'doccure_get_texnomy_select','medicine_types','',esc_html__('Select type','doccure') ,'','medicine_types');?>
							</div>
							<div class="form-group form-group-half">
								<?php do_action( 'doccure_get_texnomy_select','medicine_duration','',esc_html__('Select medicine duration','doccure') ,'','medicine_duration');?>
							</div>
							<div class="form-group form-group-half">
								<?php do_action( 'doccure_get_texnomy_select','medicine_usage','',esc_html__('Select medician Usage','doccure') ,'','medicine_usage');?>
							</div>
							<div class="form-group">
								<input type="text" id="medicine_details" class="form-control" placeholder="<?php esc_attr_e('Add Comment','doccure');?>">
							</div>
						</fieldset>
						<div class="dc-title-add"><a href="javascript:;" class="dc-add-medician dc-btn"><?php esc_html_e('Add New','doccure');?></a></div>
						<?php
							if( !empty($medicine) ){
								foreach( $medicine as $key => $values ){
									$name_val				= !empty($values['name']) ? $values['name'] : '';
									$medicine_types_val		= !empty($values['medicine_types']) ? $values['medicine_types'] : '';
									$medicine_duration_val	= !empty($values['medicine_duration']) ? $values['medicine_duration'] : '';
									$medicine_usage_val		= !empty($values['medicine_usage']) ? $values['medicine_usage'] : '';
									$detail_val				= !empty($values['detail']) ? $values['detail'] : '';
								?>
									<div class="dc-visal-sign dc-medician-<?php echo esc_attr($key);?>">
										<fieldset>
											<div class="form-group form-group-half">
												<input type="text" name="medicine[<?php echo esc_attr($key);?>][name]" class="form-control" value="<?php echo esc_attr($name_val);?>" placeholder="<?php esc_attr_e('Name','doccure');?>">
											</div>
											<div class="form-group form-group-half">
												<?php do_action( 'doccure_get_texnomy_select','medicine_types','medicine['.esc_attr($key).'][medicine_types]',esc_html__('Select type','doccure') ,$medicine_types_val,'medicine_types-.'.esc_attr($key).'');?>
											</div>
											<div class="form-group form-group-half">
												<?php do_action( 'doccure_get_texnomy_select','medicine_duration','medicine['.esc_attr($key).'][medicine_duration]',esc_html__('Select medicine duration','doccure') ,$medicine_duration_val,'medicine_duration-'.esc_attr($key).'');?>
											</div>
											<div class="form-group form-group-half">
												<?php do_action( 'doccure_get_texnomy_select','medicine_usage','medicine['.esc_attr($key).'][medicine_usage]',esc_html__('Select medician Usage','doccure') ,$medicine_usage_val,'medicine_usage-'.esc_attr($key).'');?>
											</div>
											<div class="form-group dc-delete-group">
												<input type="text" name="medicine[<?php echo esc_attr($key);?>][detail]" value="<?php echo esc_attr($detail_val);?>" class="form-control" placeholder="<?php esc_attr_e('Add Comment','doccure');?>">
												<a href="javascript:;" class="dc-deletebtn dc-remove-visual"><i class="fa fa-trash"></i></a>
											</div>
										</fieldset>
									</div>
								<?php
								}
							}
						?>
					</div>
				</div>
				<div class="dc-updatall">
					<a class="dc-btn dc-update-prescription" data-booking_id="<?php echo intval( $booking_id ); ?>" href="javascript:;"><?php esc_html_e('Save Changes', 'doccure'); ?></a>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/template" id="tmpl-load-dc-visals">
	<div class="dc-formtheme dc-userform dc-visal-sign dc-visal-{{data.id}}">
		<fieldset>
			<div class="form-group form-group-half">
				<?php do_action( 'doccure_get_texnomy_select','vital_signs','vital_signs[{{data.id}}][name]',esc_html__('Select vital sign','doccure') ,'');?>
			</div>
			<div class="form-group form-group-half dc-delete-group">
				<input type="text" name="vital_signs[{{data.id}}][value]" value="{{data.value}}" class="form-control" placeholder="<?php esc_attr_e('Value','doccure');?>">
				<a href="javascript:;" class="dc-deletebtn dc-remove-visual"><i class="fa fa-trash"></i></a>
			</div>
		</fieldset>
	</div>
</script>
<script type="text/template" id="tmpl-load-dc-medician">
	<div class="dc-visal-sign dc-medician-{{data.id}}">
		<fieldset>
			<div class="form-group form-group-half">
				<input type="text" name="medicine[{{data.id}}][name]" class="form-control" value="{{data.name}}" placeholder="<?php esc_attr_e('Name','doccure');?>">
			</div>
			<div class="form-group form-group-half">
				<?php do_action( 'doccure_get_texnomy_select','medicine_types','medicine[{{data.id}}][medicine_types]',esc_html__('Select type','doccure') ,'','medicine_types-{{data.id}}');?>
			</div>
			<div class="form-group form-group-half">
				<?php do_action( 'doccure_get_texnomy_select','medicine_duration','medicine[{{data.id}}][medicine_duration]',esc_html__('Select medicine duration','doccure') ,'','medicine_duration-{{data.id}}');?>
			</div>
			<div class="form-group form-group-half">
				<?php do_action( 'doccure_get_texnomy_select','medicine_usage','medicine[{{data.id}}][medicine_usage]',esc_html__('Select medician Usage','doccure') ,'','medicine_usage-{{data.id}}');?>
			</div>
			<div class="form-group dc-delete-group">
				<input type="text" name="medicine[{{data.id}}][detail]" value="{{data.detail}}" class="form-control" placeholder="<?php esc_attr_e('Add Comment','doccure');?>">
				<a href="javascript:;" class="dc-deletebtn dc-remove-visual"><i class="fa fa-trash"></i></a>
			</div>
		</fieldset>
	</div>
</script>
<?php
$js_script	= "
	jQuery(document).ready(function(){
		jQuery('.tests-".esc_js( $rand_val )."').select2({
			tags: true,
			insertTag: function (data, tag) {
				data.push(tag);
			},
			createTag: function (params) {
				return {
				id: params.term,
				text: params.term
				}
			}
		});
		
	} );

	";

	wp_add_inline_script( 'doccure-dashboard', $js_script, 'after' );




