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
global $current_user, $wp_roles, $userdata, $post;
$user_identity 	 = $current_user->ID;
$linked_profile  = doccure_get_linked_profile_id($user_identity);
$post_id		= $linked_profile;
$am_memberships	= doccure_get_post_meta( $post_id,'am_memberships_name');
?>
<div class="dc-skills dc-tabsinfo">
	<div class="dc-tabscontenttitle">
		
	</div>
	<div class="dc-skillscontent-holder">
		<div class="dc-formtheme dc-skillsform">
			<fieldset>
				<div class="form-group">
				<label><?php esc_html_e('Memberships','doccure');?></label>
					<div class="form-group-holder">
						<input type="text" class="form-control" id="input_membership" placeholder="<?php esc_attr_e('Your Memberships','doccure');?>">
					</div>
				</div>
				<div class="form-group dc-btnarea">
					<a href="javascript:;" class="dc-btn dc-add_membership"><?php esc_html_e('Add Now','doccure');?></a>
				</div>
			</fieldset>
		</div>
 
		<div class="dc-myskills">
			<ul class="sortable list dc-memberships dc-sortable-list">
				<?php foreach( $am_memberships as $key => $am_membership ) {?>
					<li class="dc-membership-list">
						<div class="dc-dragdroptool">
							<a href="javascript:" class="lnr lnr-menu"></a>
						</div>
						<span class="skill-dynamic-html"><em class="skill-val"><?php echo esc_html($am_membership);?></em></span>
						<span class="skill-dynamic-field">
							<input type="text" name="am_memberships_name[<?php echo intval($key);?>]" value="<?php echo esc_attr($am_membership);?>">
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

<script type="text/template" id="tmpl-load-memberships">
	<li>
		<div class="dc-dragdroptool">
			<a href="javascript:" class="lnr lnr-menu"></a>
		</div>
		<span class="skill-dynamic-html"><em class="skill-val">{{data.name}}</em></span>
		<span class="skill-dynamic-field">
			<input type="text" name="am_memberships_name[{{data.id}}]" value="{{data.name}}">
		</span>
		<div class="dc-rightarea">
			<a href="javascript:;" class="dc-addinfo"><i class="fa fa-pencil"></i></a>
			<a href="javascript:;" class="dc-deleteinfo"><i class="fa fa-trash"></i></a>
		</div>
	</li>	
</script>