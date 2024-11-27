<?php
/**
 *
 * The template used for doctors specialiizations
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id = $post->ID;
$languages	= get_the_term_list( $post->ID, 'languages', '<ul class="dc-specializationslist"><li><span>', '</span></li><li><span>', '</span></li></ul>' );

$languages	= !empty( $languages ) ? $languages : '';
if( !empty( $languages ) ){ ?>
	<div class="dc-specializations dc-aboutinfo">
		<div class="dc-infotitle">
			<h3><?php esc_html_e('Languages','doccure');?></h3>
		</div>
		<?php echo do_shortcode($languages);?>
	</div>
<?php } ?>

