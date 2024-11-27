<?php
/**
 *
 * The template part for displaying the dashboard current balance for doctor
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user,$doccure_options;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doccure_get_linked_profile_id($user_identity);
$icon					= 'lnr lnr-book';
$published_articles_img	= !empty( $doccure_options['published_articles_img']['url'] ) ? $doccure_options['published_articles_img']['url'] : '';
$published_articles		= count_user_posts($user_identity);
?>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="dc-insightsitem dc-dashboardbox">
		<figure class="dc-userlistingimg">
			<?php if( !empty($published_articles_img) ) {?>
				<img src="<?php echo esc_url($published_articles_img);?>" alt="<?php esc_attr_e('Total published articles', 'doccure'); ?>">
			<?php } else {?>
					<span class="<?php echo esc_attr($icon);?>"></span>
			<?php }?>
		</figure>
		<div class="dc-insightdetails">
			<div class="dc-title">
				<h3><?php echo intval($published_articles);?></h3>
				<span><?php esc_html_e('Articles published', 'doccure'); ?></span>
			</div>													
		</div>	
	</div>
</div>