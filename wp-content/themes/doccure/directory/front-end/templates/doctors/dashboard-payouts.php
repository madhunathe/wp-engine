<?php
/**
 *
 * The template part for displaying account settings
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post;
$user_identity 	= $current_user->ID;
$linked_profile = doccure_get_linked_profile_id($user_identity);
$post_id 		= $linked_profile;
$mode 			= (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';
$user_type		= apply_filters('doccure_get_user_type', $user_identity );

?>
<div class="dc-haslayout dc-account-settings">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 float-left">

	<div class="dc-dashboardtabs">
				<ul class="dc-tabstitle nav navbar-nav">
					<li class="nav-item">
						<a class="<?php echo !empty( $mode ) && $mode === 'settings' ? 'active' : ''; ?>" href="<?php doccure_Profile_Menu::doccure_profile_menu_link('payouts', $user_identity,'','settings'); ?>"><?php esc_html_e('Payouts Settings','doccure');?></a>
					</li>
					<li class="nav-item">
						<a class="<?php echo !empty( $mode ) && $mode === 'payments' ? 'active' : ''; ?>" href="<?php doccure_Profile_Menu::doccure_profile_menu_link('payouts', $user_identity,'','payments'); ?>"><?php esc_html_e('Your Payouts','doccure');?></a>
					</li>
				</ul>
			</div>
			
		<div class="dc-dashboardbox dc-dashboardtabsholder dc-accountsettingholder dc-payout-holder">
			
			<div class="dc-tabscontent">
				<?php if ( ( isset($_GET['ref']) && $_GET['ref'] === 'payouts' )  && ( isset($mode) && $mode === 'settings' ) ) {?>			
					<div class="dc-securityhold" id="dc-account">
						<?php get_template_part('directory/front-end/templates/doctors/dashboard', 'payouts-settings'); ?>	
					</div>
				<?php }else if ( ( isset($_GET['ref']) && $_GET['ref'] === 'payouts' )  && ( isset($mode) && $mode === 'payments' ) ) {?>
					<div class="dc-securityhold" id="dc-account">
						<?php get_template_part('directory/front-end/templates/doctors/dashboard', 'payments'); ?>	
					</div>
				<?php }?>
			</div>
		</div>
	</div>
</div>