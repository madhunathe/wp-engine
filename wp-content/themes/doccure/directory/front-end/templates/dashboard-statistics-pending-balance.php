<?php
/**
 *
 * The template part for displaying the Save item statistics
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user,$doccure_options;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doccure_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-cart';

$pending_balance_img	= !empty( $doccure_options['pending_balance']['url'] ) ? $doccure_options['pending_balance']['url'] : '';
$available_balance		= doccure_get_sum_earning_doctor($user_identity,'completed','doctor_amount');
?>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="dc-insightsitem dc-dashboardbox">
		<figure class="dc-userlistingimg">
			<?php if( !empty($pending_balance_img) ) {?>
				<img src="<?php echo esc_url($pending_balance_img);?>" alt="<?php esc_attr_e('Pending Balance', 'doccure'); ?>">
			<?php } else {?>
					<span class="<?php echo esc_attr($icon);?>"></span>
			<?php }?>
		</figure>
		<div class="dc-insightdetails">
			<div class="dc-title">
				<h3><?php doccure_price_format($available_balance);?></h3>
				<span><?php esc_html_e('Pending balance', 'doccure'); ?></span>
			</div>													
		</div>
	</div>
</div>