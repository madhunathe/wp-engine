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
$post_id 		 = $linked_profile;
$education 		 = array();
$am_awards		 = doccure_get_post_meta( $post_id,'am_award');
?>
<div class="dc-useraward dc-tabsinfo">
	<div class="dc-tabscontenttitle dc-addnew mt-0">
		<h3><?php esc_html_e('Add Your Awards','doccure');?></h3>
		<a href="javascript:;" class="dc-add_award"><?php esc_html_e('Add New','doccure');?></a>
	</div>
	<ul class="dc-awardaccordion accordion dc-award">
		<?php 
			if( !empty( $am_awards ) ) {
				$count_edu	= 1;
				foreach( $am_awards as $key => $val ) {
					if( $count_edu === 1 ) { $show = 'show'; }else { $show = '';}
					$count_edu ++;
					$title			= !empty( $val['title'] ) ? $val['title'] : '';
					$year			= !empty( $val['year'] ) ? $val['year'] : ''; ?>
					<li>
						<div class="dc-accordioninnertitle">
							<span id="accordioninnertitle<?php echo intval( $key );?>" data-bs-toggle="collapse" data-bs-target="#innertitle<?php echo intval( $key );?>"><?php echo esc_html( $title );?><?php if(!empty($year)){?><em> (<?php echo esc_html( $year);?>)</em><?php }?></span>
							<div class="dc-rightarea">
								<a href="javascript:;" class="dc-addinfo dc-skillsaddinfo" id="accordioninnertitle<?php echo intval( $key );?>" data-bs-toggle="collapse" data-bs-target="#innertitle<?php echo intval( $key );?>" aria-expanded="true"><i class="fa fa-pencil"></i></a>
								<a href="javascript:;" class="dc-deleteinfo"><i class="fa fa-trash"></i></a>
							</div>
						</div>
						<div class="dc-collapseexp collapse" id="innertitle<?php echo intval( $key );?>" aria-labelledby="accordioninnertitle<?php echo intval( $key );?>" data-parent="#accordion">
							<div class="dc-formtheme dc-userform">
								<fieldset>
									<div class="form-group form-group-half">
										<input type="text" name="am_award[<?php echo intval($key);?>][title]" class="form-control" placeholder="<?php esc_attr_e('Title','doccure');?>" value="<?php echo esc_attr( $title );?>">
									</div>
									<div class="form-group form-group-half">
										<input type="text" name="am_award[<?php echo intval($key);?>][year]" class="form-control dc-year-pick" placeholder="<?php esc_attr_e('Year','doccure');?>" value="<?php echo esc_attr($year);?>">
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
<script type="text/template" id="tmpl-load-award">
	<li>
		<div class="dc-accordioninnertitle">
			<span id="accordioninnertitle{{data.counter}}" data-bs-toggle="collapse" data-bs-target="#innertitle{{data.counter}}"><?php esc_html_e('Award Title', 'doccure'); ?></span>&nbsp;<em><?php esc_html_e('(Year)', 'doccure'); ?></em></span>
			<div class="dc-rightarea">
				<a href="javascript:;" class="dc-addinfo dc-skillsaddinfo" id="accordioninnertitle{{data.counter}}" data-bs-toggle="collapse" data-bs-target="#innertitle{{data.counter}}" aria-expanded="true"><i class="fa fa-pencil"></i></a>
				<a href="javascript:void(0);" class="dc-deleteinfo"><i class="fa fa-trash"></i></a>
			</div>
		</div>
		<div class="dc-collapseexp collapse show" id="innertitle{{data.counter}}" aria-labelledby="accordioninnertitle{{data.counter}}" data-parent="#accordion">
			<div class="dc-formtheme dc-userform">
				<fieldset>
					<div class="form-group form-group-half">
						<input type="text" name="am_award[{{data.counter}}][title]" class="form-control" placeholder="<?php esc_attr_e('Title','doccure');?>" value="">
					</div>
					<div class="form-group form-group-half">
						<input type="text" name="am_award[{{data.counter}}][year]" class="form-control dc-year-pick" placeholder="<?php esc_attr_e('Year','doccure');?>" value="">
					</div>
					<div class="form-group">
						<span>* <?php esc_html_e('Leave ending date empty if its your current job','doccure');?></span>
					</div>
				</fieldset>
			</div>
		</div>
	</li>
</script>