<?php 
/**
 *
 * The template part for displaying the template to display email settings
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $doccure_options;
$user_identity 	 = $current_user->ID;
$linked_profile  = doccure_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;
$payrols_db		= get_user_meta($user_identity,'payrols',true);
$renew			= !empty($_GET['id']) ? $_GET['id'] : '';
$payrols		= array();
if( function_exists('doccure_get_payouts_lists') ){
	$payrols		= doccure_get_payouts_lists();
}

?>
<div class="dc-tabsinfo dc-email-settings">
	<div class="dc-tabscontenttitle">
		<h3><?php esc_html_e('Payouts Settings', 'doccure'); ?></h3>
	</div>
	<div class="dc-settingscontent">
		<div class="dc-description">
			<p><?php esc_html_e('All the earning will be sent to below selected payout method','doccure');?></p>
		</div>
		<div class="dc-formtheme dc-userform payout-holder">
			<form class="dc-payout-settings">
				<?php 
					if( !empty($payrols) ) {
						foreach ($payrols as $pay_key	=> $payrol) {
							if( !empty($payrol['status']) && $payrol['status'] === 'enable' ) {
								$contents	= get_user_meta($user_identity,'payrols',true);
								$db_option	= !empty( $contents['type'] ) ? $contents['type'] : '';
								$db_option_display	= !empty( $contents['type'] ) && $pay_key === $contents['type'] ? 'display:block' : 'display:none';
								$db_option_display	= !empty($contents['payrol']) && $contents['payrol'] === 'paypal' ? 'display:block' : $db_option_display; //only for migration
								?>
								<fieldset>
									<div class="dc-checkboxholder">
										<span class="dc-radio">
											<input id="payrols-<?php echo esc_attr( $payrol['id'] ); ?>" <?php checked( $pay_key, $db_option); ?> type="radio" name="payout_settings[type]" value="<?php echo esc_attr( $payrol['id'] ); ?>">
											<label for="payrols-<?php echo esc_attr( $payrol['id'] ); ?>">
												<?php if(!empty($payrol['img_url'])){?>
												<figure class="dc-userlistingimg">
													<img src="<?php echo esc_url( $payrol['img_url'] ); ?>" alt="<?php echo esc_attr( $payrol['title'] ); ?>">
												</figure>
												<?php }else{?>
													<h5><?php echo esc_html( $payrol['title'] ); ?></h5>
												<?php }?>
											</label>											
										</span>
									</div>
									<div class="fields-wrapper dc-haslayout dc-description" style="<?php echo esc_attr( $db_option_display );?>">
									<?php if( empty($renew) && !empty($contents['type']) && $pay_key === $contents['type'] ){?>
											<div class="dc-save-payouts-settings">
											<?php
												$sc_info	= '';
												$lable		= esc_html__('Your Payout Details','doccure');
												if($contents['type'] === 'bacs'){
													$sc_info	= !empty($contents['bank_account_number']) ? '*****'.substr($contents['bank_account_number'],'-4') : '';
													$db_option_display	= 'none';
												} else if( $contents['type'] === 'paypal' ){
													$sc_info	= !empty($contents['paypal_email']) ? substr($contents['paypal_email'],0,4).'*****' : '';
													$db_option_display	= 'none';
												}else{
													$saved_key		= '';
													foreach( $payrol['fields'] as $key => $field ){
														if($field['show_this']){
															$saved_key	= $key;
															continue;
														}
													}
													
													$sc_info	= !empty($contents[$saved_key]) ? substr($contents[$saved_key],0,4).'*****' : '';
													$db_option_display	= 'none';
												}

												if(!empty($sc_info)){?>
													<div class="dc-account-info">
														<strong><?php echo esc_html($lable);?></strong>
														<p><?php echo esc_html($sc_info);?></p>
													</div>
													<div class="edit-payout dc-haslayout"><a href="<?php doccure_Profile_Menu::doccure_profile_menu_link('payouts', $user_identity,'','settings','renew'); ?>" class="dc-btn"><?php esc_html_e('Change Payout Details','doccure');?></a></div>
												<?php }?>
											</div>
										<?php }?>
										<div class="dc-payouts-settings-details" style="display:<?php echo esc_attr($db_option_display);?>;">
											<?php if( !empty($payrol['desc'])) {?>
												<div class="dc-description"><p><?php echo do_shortcode($payrol['desc']);?></p></div>
											<?php }?>
											<?php 
											if( !empty($payrol['fields'])) {
												foreach( $payrol['fields'] as $key => $field ){
													$db_value = !empty($contents[$key]) ? $contents[$key] : "";
												?>
												<div class="form-group form-group-half toolip-wrapo">
													<input type="<?php echo esc_attr($field['type']);?>" name="payout_settings[<?php echo esc_attr($key);?>]" id="<?php echo esc_attr($key);?>-payrols" class="form-control" placeholder="<?php echo esc_attr($field['placeholder']);?>" value="">
													<?php do_action('doccure_get_tooltip','element',$key);?>
												</div>
											<?php }}?>
											<div class="form-group dc-btnarea">
												<button type="submit" class="dc-btn dc-payrols-settings" data-id="<?php echo esc_attr( $payrol['id'] ); ?>"><?php esc_html_e("Submit",'doccure');?></button>
											</div>
										</div>
									</div>
								</fieldset>
								<?php
							}
						}
					}
				?>
			</form>	
		</div>
	</div>
</div>