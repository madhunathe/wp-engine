<?php
/**
 *
 * The template part for displaying the dashboard menu
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
$user_type		 = apply_filters('doccure_get_user_type', $user_identity );
$manage_url 		= doccure_Profile_Menu::doccure_profile_menu_link('account-settings', $user_identity, true,'manage');
$password_url		= doccure_Profile_Menu::doccure_profile_menu_link('account-settings', $user_identity, true,'password');

$remove_account_url			= doccure_Profile_Menu::doccure_profile_menu_link('account-settings', $user_identity, true,'delete');
$emails_notification_url	= doccure_Profile_Menu::doccure_profile_menu_link('account-settings', $user_identity, true,'emails');
$mode 			 			= !empty($_GET['mode']) ? esc_html( $_GET['mode'] ) : 'settings';
$class						= '';
?>
<div class="">
	<form class="dc-haslayout dc-user-account" method="post">	

	<div class="dc-dashboardtabs">
				<ul class="dc-tabstitle nav navbar-nav">
					<?php if(!empty($user_type) && $user_type != 'regular_users'){?>
						<li class="nav-item">
							<a class="<?php echo !empty( $mode ) && $mode === 'manage' ? 'active' : '';?>" href="<?php echo esc_url( $manage_url );?>">
								<?php esc_html_e('Security & Settings', 'doccure'); ?>
							</a>
						</li>
					<?php } ?>
					<li class="nav-item">
						<a class="<?php echo !empty( $mode ) && $mode === 'password' ? 'active' : '';?>" href="<?php echo esc_url( $password_url );?>">
							<?php esc_html_e('Password', 'doccure'); ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="<?php echo !empty( $mode ) && $mode === 'emails' ? 'active' : '';?>" href="<?php echo esc_url( $emails_notification_url );?>">
							<?php esc_html_e('Email Notification', 'doccure'); ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="<?php echo !empty( $mode ) && $mode === 'delete' ? 'active' : '';?>" href="<?php echo esc_url( $remove_account_url );?>">
							<?php esc_html_e('Delete Account', 'doccure'); ?>
						</a>
					</li>	
				</ul>
			</div>
			
		<div class="dc-dashboardbox dc-dashboardtabsholder dc-accountsettingholder">
			
			<div class="dc-tabscontent tab-content pt-0 pb-0">
				<?php 
					if( !empty( $mode ) && $mode === 'manage' ){
						if( $user_type === 'doctors' ){
							get_template_part('directory/front-end/templates/doctors/dashboard', 'manage-account-settings');
						} else if( $user_type === 'hospitals' ){
							get_template_part('directory/front-end/templates/dashboard', 'manage-account-settings'); 
						}
						
						$class	= 'dc-update-account';
					} elseif( !empty( $mode ) && $mode === 'password' ){
						get_template_part('directory/front-end/templates/dashboard', 'reset-password'); 
						$class	= 'dc-reset-password';
					}elseif( !empty( $mode ) && $mode === 'emails' ){
						get_template_part('directory/front-end/templates/dashboard', 'email-notifications'); 
						$class	= 'dc-change-email';
					}elseif( !empty( $mode ) && $mode === 'delete' ){
						get_template_part('directory/front-end/templates/dashboard', 'delete-account'); 
						$class	= 'dc-delete-user-account';
					}
				?>
			</div>
		</div>

		<div class="dc-updatall">
			<i class="ti-announcement"></i>
			<a class="dc-btn <?php echo esc_attr( $class );?> " data-id="<?php echo esc_attr( $user_identity ); ?>" data-post="<?php echo esc_attr( $post_id ); ?>" href="javascript:;"><?php esc_html_e('Save Changes', 'doccure'); ?></a>
		</div>
	</form>
</div>

