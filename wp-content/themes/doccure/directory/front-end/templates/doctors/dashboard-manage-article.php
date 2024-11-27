<?php 
/**
 *
 * The template part for displaying the articles
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post;
$user_identity 	 = $current_user->ID;
$linked_profile  = doccure_get_linked_profile_id($user_identity);

?>
<div class="doctors_articlelisting">
<div class="">
	<div class="dc-dashboardbox dc-offered-holder">
		<div class="dc-dashboardboxtitle">
			<h2><?php esc_html_e('Article Listing','doccure');?></h2>
		</div>
		<?php get_template_part('directory/front-end/templates/doctors/single/dashboard-articles'); ?>
	</div>
</div>
<?php
$article_id 	= !empty($_GET['id']) ? intval( $_GET['id'] ) : '';
if( empty( $article_id ) ) {
	get_template_part('directory/front-end/templates/doctors/dashboard', 'add-article'); 
} else {
	get_template_part('directory/front-end/templates/doctors/dashboard', 'edit-article'); 
}?>
</div>

