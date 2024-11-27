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
global $current_user;
$user_identity 	 = $current_user->ID;
$linked_profile  = doccure_get_linked_profile_id($user_identity);
$mode 			 = !empty($_GET['mode']) ? esc_attr( $_GET['mode'] ) : 'doctors';

$doctors_url 	= doccure_Profile_Menu::doccure_profile_menu_link('saved', $user_identity, true,'doctors');
$hosptials_url 	= doccure_Profile_Menu::doccure_profile_menu_link('saved', $user_identity, true,'hosptials');

?>
<div class="">
	<form class="dc-user-account" method="post">	
		<div class="dc-dashboardbox dc-databdnoneshboardtabsholder dc-accountsettingholder">
			<div class="dc-dashboardtabs tabdnone">
				<ul class="dc-tabstitle nav navbar-nav">
					<li class="nav-item">
						<a class="<?php echo !empty( $mode ) && $mode === 'doctors' ? 'active' : '';?>" href="<?php echo esc_url( $doctors_url );?>">
							<?php esc_html_e('Doctors', 'doccure'); ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="<?php echo !empty( $mode ) && $mode === 'hosptials' ? 'active' : '';?>" href="<?php echo esc_url( $hosptials_url );?>">
							<?php esc_html_e('Hosptials', 'doccure'); ?>
						</a>
					</li>
				</ul>
			</div>
			<div class="dc-tabscontent saved_items100 tab-content">
				<?php 
					if( !empty( $mode ) && $mode === 'doctors' ){
						get_template_part('directory/front-end/templates/dashboard', 'saved-doctors'); 
						$class	= 'dc-update-account';
					} elseif( !empty( $mode ) && $mode === 'hosptials' ){
						get_template_part('directory/front-end/templates/dashboard', 'saved-hosptials'); 
					}
				?>
			</div>
		</div>
	</form>
</div>
