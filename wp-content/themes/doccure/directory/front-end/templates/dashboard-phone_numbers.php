<?php 
/**
 *
 * The template part for displaying the user profile avatar
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user, $post;
$user_identity 	 = $current_user->ID;
$linked_profile  = doccure_get_linked_profile_id($user_identity);
$post_id		= $linked_profile;
$am_phone_numbers	= doccure_get_post_meta( $post_id,'am_phone_numbers');
?>
<div class="dc-skills dc-tabsinfo">
	<div class="dc-tabscontenttitle">
		<h3><?php esc_html_e('Contact phone numbers','doccure');?></h3>
	</div>
	<div class="dc-skillscontent-holder">
		<div class="dc-formtheme dc-skillsform">
			<fieldset>
				<div class="form-group">
					<div class="form-group-holder">
						<input type="text" class="form-control" id="input_phone_numbers" placeholder="<?php esc_attr_e('Phone numbers','doccure');?>">
					</div>
				</div>
				<div class="form-group dc-btnarea">
					<a href="javascript:;" class="dc-btn dc-add_phone_number"><?php esc_html_e('Add Now','doccure');?></a>
				</div>
			</fieldset>
		</div>
 		<div class="dc-myskills">
			<ul class="sortable list dc-phone_numbers dc-sortable-list">
				<?php foreach( $am_phone_numbers as $key => $am_phone_number ) {?>
					<li class="dc-membership-list">
						<div class="dc-dragdroptool">
							<a href="javascript:" class="lnr lnr-menu"></a>
						</div>
						<span class="skill-dynamic-html"><em class="skill-val"><?php echo esc_html($am_phone_number);?></em></span>
						<span class="skill-dynamic-field">
							<input type="text" name="am_phone_numbers[<?php echo intval($key);?>]" value="<?php echo esc_attr($am_phone_number);?>">
						</span>
						<div class="dc-rightarea">
							<a href="javascript:;" class="dc-addinfo"><i class="fa fa-pencil"></i></a>
							<a href="javascript:;" class="dc-deleteinfo"><i class="fa fa-trash"></i></a>
						</div>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>

<script type="text/template" id="tmpl-load-phone_numbers">
	<li>
		<div class="dc-dragdroptool">
			<a href="javascript:" class="lnr lnr-menu"></a>
		</div>
		<span class="skill-dynamic-html"><em class="skill-val">{{data.name}}</em></span>
		<span class="skill-dynamic-field">
			<input type="text" name="am_phone_numbers[{{data.id}}]" value="{{data.name}}">
		</span>
		<div class="dc-rightarea">
			<a href="javascript:;" class="dc-addinfo"><i class="fa fa-pencil"></i></a>
			<a href="javascript:;" class="dc-deleteinfo"><i class="fa fa-trash"></i></a>
		</div>
	</li>	
</script>