<?php
/**
 *
 * The template used for doctors experience
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;

$post_id 		= $post->ID;
$am_experiences	= doccure_get_post_meta( $post_id,'am_experiences');
if( !empty( $am_experiences ) ) {
?>
<div class="dc-experience-holder dc-experiencedoc dc-aboutinfo">
	<div class="dc-infotitle">
		<h3><?php esc_html_e('Experience','doccure');?></h3>
	</div>
	<ul class="experience-list">
		<?php 
			foreach( $am_experiences as $exp ){
				$company_name	= !empty( $exp['company_name'] ) ? $exp['company_name'] : '';
				$job_title		= !empty( $exp['job_title'] ) ? $exp['job_title'] : '';
				$job_description		= !empty( $exp['job_description'] ) ? $exp['job_description'] : '';
				$start		= !empty( $exp['start_date'] ) ? date_i18n('Y', strtotime($exp['start_date'])) : '';
				$ending		= !empty( $exp['ending_date'] ) ? date_i18n('Y', strtotime($exp['ending_date'])) : esc_html__('Present','doccure');
				$des_class	= !empty($job_description) ? 'dc-tab-des-enb' : '';
				if( !empty( $job_title ) ){ ?>
					<li class="<?php echo esc_attr($des_class);?>">

					<ul class="experience-list">
								<li>
									<div class="experience-user">
										<div class="before-circle"></div>
									</div>
							<div class="experience-content">
								<div class="timeline-content">
									<div><?php echo esc_html( $company_name);?> </div>

									<span class="time"><?php if( !empty( $start ) && !empty( $ending ) ) {?>( <?php echo esc_html( $start.' - '.$ending);?> )<?php } ?></span>
									<?php if( !empty(  $job_title )) {?><?php echo esc_html(  $job_title );?><?php } ?>
									<?php if( !empty(  $job_description )) {?>
									<p>
										<?php echo esc_html(  $job_description );?>
									</p>
									<?php 
								} ?>
									
							</div>
							</div>
							
							
							</li>
							
							</ul>
					</li>
			<?php } ?>
		<?php } ?>
	</ul>
</div>
<?php }