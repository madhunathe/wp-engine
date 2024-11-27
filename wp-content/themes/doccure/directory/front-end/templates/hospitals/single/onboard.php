<?php
/**
 *
 * The template used for hospital doctors
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id 	= $post->ID;
?>
<div class="dc-contentdoctab dc-location-holder tab-pane fade show" id="onboard">
	<div class="dc-searchresult-holder">
		<div class="dc-searchresult-head">
			<div class="dc-title"><h4><?php esc_html_e('All Onboard Doctors','doccure');?></h4></div>
		</div>
	</div>
</div>