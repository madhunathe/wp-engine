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

if (is_user_logged_in()) { ?>
	<li><a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>"><i class="fas fa-sign-out-alt"></i> <span><?php esc_html_e('Logout', 'doccure'); ?></span></a></li>
<?php }