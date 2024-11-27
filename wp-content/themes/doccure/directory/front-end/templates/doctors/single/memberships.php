<?php
/**
 *
 * The template used for doctors membership
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id = $post->ID;
$am_memberships = doccure_get_post_meta( $post_id,'am_memberships_name');

if( !empty( $am_memberships ) ){?>
	<div class="dc-specializations dc-aboutinfo dc-memberships mt-3 mb-0">
		<div class="dc-infotitle">
			<h3><?php esc_html_e('Memberships','doccure');?></h3>
		</div>
		<ul class="dc-specializationslist  mb-0">
			<?php foreach( $am_memberships as $membership )  {?>
				<li><span><?php echo esc_html( $membership );?></span></li>
			<?php } ?>
		</ul>
	</div>
<?php } ?>

