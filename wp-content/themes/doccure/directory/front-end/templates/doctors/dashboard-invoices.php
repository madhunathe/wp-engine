<?php
/**
 *
 * The template part for displaying the dashboard Available balance for doctor
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user,$doccure_options;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doccure_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-file-empty';
$invoices_image	= !empty( $doccure_options['invoice_img']['url'] ) ? $doccure_options['invoice_img']['url'] : '';
$available_balance			= doccure_get_sum_earning_doctor($user_identity,'pending','doctor_amount');
?>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="dc-insightsitem dc-dashboardbox">
		<figure class="dc-userlistingimg">
			<?php if( !empty($invoices_image) ) {?>
				<img src="<?php echo esc_url($invoices_image);?>" alt="<?php esc_attr_e('Invoices', 'doccure'); ?>">
			<?php } else {?>
					<span class="<?php echo esc_attr($icon);?>"></span>
			<?php }?>
		</figure>
		<div class="dc-insightdetails">
			<div class="dc-title">
				<h3><?php esc_html_e('Check Your Invoices', 'doccure'); ?></h3>
				<a href="<?php doccure_Profile_Menu::doccure_profile_menu_link('invoices', $user_identity); ?>">
					<?php esc_html_e('Click To View', 'doccure'); ?>
				</a>
			</div>													
		</div>	
	</div>
</div>