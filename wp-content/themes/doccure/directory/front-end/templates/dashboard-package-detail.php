<?php
/**
 *
 * The template part for Current Package poupup
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user,$woocommerce;
if (class_exists('WooCommerce')) {
	$user_identity 	 	= $current_user->ID;
	$linked_profile  	= doccure_get_linked_profile_id($user_identity);

	$product_id			= doccure_get_subscription_metadata( 'subscription_id',intval($user_identity) );
	$title				= esc_html( get_the_title($product_id));
	$title				= !empty( $title ) ? esc_html( $title ) : esc_html__('Nill', 'doccure');
	$user_role			= doccure_get_user_type( $user_identity );
	$pakeges_features 	= doccure_get_pakages_features();
	?>
	<div class="dc-uploadimages dc-package-modal modal fade" id="dc-package-details" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="dc-modaldialog modal-dialog modal-dialog-centered" role="document">
			<div class="dc-modalcontent modal-content">
				<div class="dc-boxtitle">
					<h2><?php echo esc_html($title);?><a href="javascript:;" data-bs-dismiss="modal" aria-label="<?php esc_attr_e('Close','doccure');?>"><i class=" dc-btncancel fa fa-times"></i></a></h2>
				</div>
				<div class="dc-modalbody modal-body">
					<div class="dc-dashboardboxcontent dc-packages">
						<?php if ( !empty ( $pakeges_features )) {?>
							<div class="dc-package dc-packagedetails">
								<div class="dc-packagecontent">
									<ul class="dc-packageinfo">
										<?php foreach ( $pakeges_features as $key => $values ) { 
											if( $values['user_type'] === $user_role || $values['user_type'] === 'common' ) {
												if(!empty($values['title'])) { ?>
												<li><span><?php echo esc_html($values['title']);?></span></li>
										<?php }}} ?>
									</ul>
								</div>
							</div>
						<?php }?>
						<div class="dc-package dc-baiscpackage">
							<div class="dc-packagecontent">
								<ul class="dc-packageinfo">
									<?php if ( !empty ( $pakeges_features )) {
											foreach( $pakeges_features as $key => $vals ) { 
												if( $vals['user_type'] === $user_role || $vals['user_type'] === 'common' ) {
													$text	 = !empty( $vals['text'] ) ? $vals['text'] : '';
													$feature	= doccure_get_subscription_metadata($key,$user_identity);
													
													if( isset( $item ) && ( $item === 'no' || empty($item) ) ){
														$feature = '<i class="ti-na"></i>';
													}elseif( $key	=== 'dc_duration_type') {
														$feature = doccure_get_duration_types($feature,'value');
													}elseif($key	=== 'dc_badget' ) {
														if(!empty($feature) ){
															$badges		= get_term( intval($feature) );
															if(!empty($badges->name)) {
																$feature	= $badges->name;
															} else {
																$feature	= '<i class="ti-na"></i>';
															}
														} else{
															$feature	= '<i class="ti-na"></i>';
														}
													}elseif( !empty( $feature ) && $feature === 'yes') {
														$feature	= '<i class="ti-check"></i>';
													} elseif( !empty( $feature ) && $feature === 'no') {
														$feature	= '<i class="ti-na"></i>';
													}
													
													$feature	= !empty( $feature ) ? $feature : '0';
													?>
														<li><span><?php echo do_shortcode($feature);?>&nbsp;<?php echo esc_html($text);?></span></li>
													<?php
												}
											}
										}
									?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }