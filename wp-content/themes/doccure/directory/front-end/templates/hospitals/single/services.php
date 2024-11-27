	<?php
/**
 *
 * The template used for hospital services
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id 			= $post->ID;
$am_specialities	= doccure_get_post_meta( $post_id,'am_specialities');

if( !empty( $am_specialities ) ) {
?>
<div class="dc-services-holder dc-aboutinfo">
	<div class="dc-infotitle">
		<h3><?php esc_html_e('Offered Services','doccure');?></h3>
	</div>
	<div id="dc-accordion" class="dc-accordion" role="tablist" aria-multiselectable="true">
		<?php 
			foreach ( $am_specialities as $key => $specialities) { 
				$specialities_title	= doccure_get_term_name($key ,'specialities');
				$logo 				= get_term_meta( $key, 'logo', true );
				$logo				= !empty( $logo['url'] ) ? $logo['url'] : '';
				$services			= !empty( $specialities ) ? $specialities : '';
				$service_count		= count($services);
			?>
			<div class="dc-panel">
				<?php if( !empty( $specialities_title ) ){?>
					<div class="dc-paneltitle">
						<?php if( !empty( $logo ) ){?>
							<figure class="dc-titleicon">
								<img src="<?php echo esc_url( $logo );?>" alt="<?php echo esc_attr( $specialities_title );?>">
							</figure>
						<?php } ?>
						<span>
							<?php echo esc_html( $specialities_title );?>
							 <?php if( !empty( $service_count ) ){ ?>
								<em><?php echo intval($service_count);?>&nbsp;<?php esc_html_e( 'Services','doccure');?></em>
							 <?php } ?>
						 </span>
					</div>
				<?php } ?>
				<?php if( !empty( $services ) ){ ?>
					<div class="dc-panelcontent">
						<div class="dc-childaccordion" role="tablist" aria-multiselectable="true">
							<?php 
								foreach ( $services as $key => $service ) {
									$service_title	= doccure_get_term_name($key ,'services');
									$service_title	= !empty( $service_title ) ? $service_title : '';
									$service_price	= !empty( $service['price'] ) ? $service['price'] : '';
									$description	= !empty( $service['description'] ) ? $service['description'] : '';
								?>
								<div class="dc-subpanel">
									<?php if( !empty( $service_title ) ) { ?>
										<div class="dc-subpaneltitle">
											<span>
												<?php echo esc_html( $service_title );?>
												<?php if( !empty( $service_price ) ) {?><em><?php doccure_price_format($service_price); ?></em><?php } ?>
											</span>
										</div>
									<?php } ?>
									<?php if( !empty( $description ) ){?>
										<div class="dc-subpanelcontent">
											<div class="dc-description">
												<p><?php echo nl2br( $description );?></p>
											</div>
										</div>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
			</div>	
		<?php } ?>
	</div>
</div>
<?php } ?>
<?php
	$inline_script = 'jQuery(document).on("ready", function() { 
		themeAccordion();
		childAccordion(); });';
	wp_add_inline_script( 'doccure-callback', $inline_script, 'after' );
