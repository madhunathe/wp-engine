<?php
/**
 *
 * The template part for displaying the Submit article link
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user,$doccure_options;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doccure_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-file-add';

$article_add_url_img	= !empty( $doccure_options['article_add_url']['url'] ) ? $doccure_options['article_add_url']['url'] : '';
?>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="dc-insightsitem dc-dashboardbox">
		<figure class="dc-userlistingimg">
			<?php if( !empty($article_add_url_img) ) {?>
				<img src="<?php echo esc_url($article_add_url_img);?>" alt="<?php esc_attr_e('Save Items', 'doccure'); ?>">
			<?php } else {?>
					<span class="<?php echo esc_attr($icon);?>"></span>
			<?php }?>
		</figure>
		<div class="dc-insightdetails">
			<div class="dc-title">
				<h3><?php esc_html_e('Add Article', 'doccure'); ?></h3>
				<a href="<?php doccure_Profile_Menu::doccure_profile_menu_link('manage-article', $user_identity,'','listings'); ?>">
					<?php esc_html_e('Click To View', 'doccure'); ?>
				</a>
			</div>													
		</div>
	</div>
</div>