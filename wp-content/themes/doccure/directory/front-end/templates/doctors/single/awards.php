<?php
/**
 *
 * The template used for doctors award
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id = $post->ID;
$am_awards		= doccure_get_post_meta( $post_id,'am_award'); 
if( !empty( $am_awards ) ){?>
	<div class="dc-awards-holder dc-aboutinfo">
		<div class="dc-infotitle">
			<h3><?php esc_html_e('Awards and Recognitions','doccure');?></h3>
		</div>
		<ul class="dc-expandedu">
			<?php 
				foreach( $am_awards as $award ){
					if( !empty( $award['title'] ) ) { ?>
						<li><span><?php echo esc_html( $award['title'] );?> <?php if( !empty( $award['year'] ) ) { ?><em>(&nbsp;<?php echo intval( $award['year'] );?>&nbsp;)</em><?php } ?></span></li>
					<?php } ?>
			<?php } ?>
		</ul>
	</div>
<?php  }

