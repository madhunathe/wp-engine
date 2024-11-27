<?php
/**
 *
 * The template used for displaying doctor Share
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id = $post->ID;

$doctor_avatar = apply_filters(
		'doccure_doctor_avatar_fallback', doccure_get_doctor_avatar( array( 'width' => 225, 'height' => 225 ), $post_id ), array( 'width' => 225, 'height' => 225 )
	);
?>
<div class="dc-widget dc-sharejob">
	<div class="dc-widgettitle">
		<h2><?php esc_html_e('Share This Doctor', 'doccure'); ?></h2>
	</div>
	<?php
		if( function_exists('doccure_prepare_profile_social_sharing') ){
			doccure_prepare_profile_social_sharing(false, '', 'true', '', $doctor_avatar);
		}
	?>
</div>