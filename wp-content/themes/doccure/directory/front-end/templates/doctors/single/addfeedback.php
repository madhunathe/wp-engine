<?php
/**
 *
 * The template used for doctors feedback
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id 	= $post->ID;
$name		= doccure_full_name( $post_id );
$name		= !empty( $name ) ? $name : ''; 
$rating_headings	= doccure_doctor_ratings();
$waiting_time		= doccure_get_waiting_time();
$rand_val			= rand(1, 9999);
?>
<div class="modal fade dc-feedbackpopup" tabindex="-1" role="dialog" id="feedbackmodal">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="dc-modalcontent modal-content">
			<div class="dc-popuptitle">
				<h3><?php esc_html_e('Add Your Feedback','doccure');?></h3>
				<a href="javascript;;" class="dc-closebtn close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e('Close','doccure');?>"><i class="fas fa-times"></i></a>
			</div>
			<div class="modal-body">
				<form class="dc-formtheme dc-formfeedback" method="post">
					<div class="dc-popupsubtitle dc-subtitlewithbtn">
						<h3><?php esc_html_e('I Recommend This Doctor','doccure');?>:</h3>
						<div class="dc-btnarea">
							<label for="recommend_yes" class="dc-btn dc-recommend-click">
								<i class="fas fa-thumbs-up"></i> <?php esc_html_e('Yes','doccure');?>
							</label>
							<input type="radio" name="feedback_recommend" id="recommend_yes" value="yes">

							<label for="recommend_no" class="dc-btn dc-recommend-click">
								<i class="fas fa-thumbs-down"></i> <?php esc_html_e('no','doccure');?>
							</label>
							<input type="radio" id="recommend_no" name="feedback_recommend" value="no">
						</div>
					</div>
				
					<fieldset class="dc-improvedinfo">
						<input type="hidden" name="waiting_time" id="waiting_time" value="1" >
						<?php 
						if( !empty( $rating_headings ) ) {
							foreach ( $rating_headings  as $key => $rating ) {
								$flag 		= rand(1, 9999);
								$field_name = $key;
								?>
								<div class="form-group dc-rating-holder" data-ratingtitle="<?php echo esc_attr( $key ); ?>">
									<div class="dc-ratingtitle">
										<h3><span><?php echo esc_html( $rating );?></span></h3>
									</div>
									<div class="dc-ratingarea">
										<div class="dc-ratepoints dc-ratingbox-<?php echo esc_attr( $flag ); ?>">
											<div id="jRate-<?php echo esc_attr( $flag ); ?>" class="dc-jrate"></div>
											<input type="hidden" name="feedback[<?php echo esc_attr( $field_name ); ?>]" class="rating-<?php echo esc_attr( $flag ); ?>" value="1" />
										</div>
									</div>
									
									<?php
										$script = "
											jQuery(document).ready(function(){
												var that = this;
												var toolitup = jQuery('#jRate-" . esc_js( $flag ) . "').jRate({
													rtl: ".doccure_owl_rtl_check().",
													rating: 1,
													min: 0,
													max: 5,
													precision: 1,
													shapeGap: '3px',
													startColor: '#fdd003',
													endColor: '#fdd003',
													width: 16,
													height: 16,
													touch: true,
													backgroundColor: '#DFDFE0',
													onChange: function (rating) {
														jQuery('.rating-" . $flag . "').val(rating);
														jQuery('.dc-ratingbox-" . esc_js( $flag ) . " .dc-pointscounter').html(rating+'.0');
													},
													onSet: function (rating) {
														jQuery('.rating-" . esc_js( $flag ) . "').val(rating);
													}
												});
											});";
										wp_add_inline_script('jrate', $script, 'after');
									?>
								</div>
							<?php } ?>
						<?php } ?>
						<div class="form-group">
							<textarea class="form-control" name="feedback_description" placeholder="<?php esc_attr_e('Share Your Experience','doccure');?>*"></textarea>
							<input type="hidden" name="doctor_id" value="<?php echo intval($post_id);?>">
						</div>
					</fieldset>
					<fieldset class="dc-formsubmit">
						<div class="dc-btnarea">
							<a href="javascript:;" class="dc-btn dc-formfeedback-btn"><?php esc_html_e('Submit Now','doccure');?></a>
							<span class="dc-checkbox">
								<input id="feedbackpublicly" type="checkbox" name="feedbackpublicly" value="yes">
								<label for="feedbackpublicly"><span><?php esc_html_e('Keep this feedback publicly anonymous.','doccure');?>*</span></label>
							</span>
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
			jQuery( '#dc-productrangeslider-".esc_js( $rand_val )."').slider({
				isRTL: ".doccure_owl_rtl_check().",
				range: 'max',
				min: 1,
				max: 4,
				value: 1,
				slide: function( event, ui ) {
					jQuery( '#amount' ).val( ui.value );
				}
			});

			jQuery( '#amount' ).val( jQuery( '#dc-productrangeslider').slider( 'value' ) );
		} );
	";
	
	wp_add_inline_script( 'jquery-ui-slider', $js_script, 'after' );