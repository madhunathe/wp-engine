<?php
/**
 *
 * The template used for inivitation
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $current_user;
$rand_val			= rand(1, 9999);
?>
<div class="modal fade dc-invitationmodel" tabindex="-1" role="dialog" id="dc-invitationmodel">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="dc-modalcontent modal-content">
			<div class="dc-popuptitle">
				<h3><?php esc_html_e('Send invitation request','doccure');?></h3>
				<a href="javascript;;" class="dc-closebtn close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e('Close','doccure');?>"><i class="ti-close"></i></a>
			</div>
			<div class="modal-body">
				<form class="dc-formtheme dc-invitation-form" method="post">
					<fieldset class="dc-improvedinfo">
						<div class="form-group">
							<select name="emails[]" class="form-control invitation-<?php echo esc_attr($rand_val );?>" multiple="multiple">
							</select>
							<p><?php esc_html_e('You can add multiple email address to send emails in bulk to invite. Type email address and hit enter','doccure');?></p>
						</div>
						<div class="form-group">
							<textarea class="form-control" name="content" placeholder="<?php esc_attr_e('Your message','doccure');?>"></textarea>
						</div>
					</fieldset>
					<fieldset class="dc-formsubmit">
						<div class="dc-btnarea">
							<a href="javascript:;" data-id="<?php echo intval($current_user->ID);?>" class="dc-btn dc-invitation-btn"><?php esc_html_e('Submit Now','doccure');?></a>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>
<?php
$js_script	= "
		jQuery(document).ready(function(){
			jQuery('.invitation-".esc_js( $rand_val )."').select2({
				tags: true,
				minimumResultsForSearch: -1,
				insertTag: function (data, tag) {
					if(doccure_validate_email(tag.text)){
						data.push(tag);
					}
					
				  },
				createTag: function (params) {
					if (params.term.indexOf('@') === -1) {
					  return null;
					}
					return {
					  id: params.term,
					  text: params.term
					}
				  }
			  });
			  
		} );
		
	";
	
	wp_add_inline_script( 'doccure-dashboard', $js_script, 'after' );