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
$user_identity 	= $current_user->ID;
$link_id		= doccure_get_linked_profile_id( $user_identity );
?>
<li>
	<a target="_blank" href="<?php echo esc_url(get_the_permalink( $link_id ) );?>">
		<i class="fas fa-user-md"></i>
		<span><?php esc_html_e('View My Profile','doccure');?></span>
	</a>
</li>
