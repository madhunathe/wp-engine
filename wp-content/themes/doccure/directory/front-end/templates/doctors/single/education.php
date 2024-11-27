<?php
/**
 *
 * The template used for doctors Education
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;

$post_id 		= $post->ID;
$education		= doccure_get_post_meta( $post_id,'am_education');
//fw_print($education);
if( !empty( $education ) ){ ?>
	<div class="dc-education-holder dc-aboutinfo">
		<div class="dc-infotitle">
			<h3><?php esc_html_e('Education','doccure');?></h3>
		</div>
		<ul class="experience-list">
			<?php
				foreach( $education as $edu ) {
					$degree_title	= !empty( $edu['degree_title'] ) ? $edu['degree_title'] : '';
					$institute_name	= !empty( $edu['institute_name'] ) ? $edu['institute_name'] : '';
					$degree_description	= !empty( $edu['degree_description'] ) ? $edu['degree_description'] : '';
					$start		= !empty( $edu['start_date'] ) ? date_i18n('Y', strtotime($edu['start_date'])) : '';
					$ending		= !empty( $edu['ending_date'] ) ? date_i18n('Y', strtotime($edu['ending_date'])) : '';
					$des_class	= !empty($degree_description) ? 'dc-tab-des-enb' : '';
					if( !empty( $degree_title ) ){ ?>
						<li class="<?php echo esc_attr($des_class);?>">
							<div class="dc-subpaneltitle">
								
							</div>
							<ul class="experience-list">
								<li>
									<div class="experience-user">
										<div class="before-circle"></div>
									</div>
							<div class="experience-content">
								<div class="timeline-content">
									<div><?php echo esc_html( $degree_title );?> </div>

									<span class="time"><?php if( !empty( $start ) && !empty( $ending ) ) {?>( <?php echo esc_html( $start.' - '.$ending);?> )<?php } ?></span>
									<?php if( !empty( $institute_name )) {?><?php echo esc_html( $institute_name );?><?php } ?>
									<?php if( !empty(  $degree_description )) {?>
									<p>
										<?php echo esc_html(  $degree_description );?>
									</p>
									<?php 
								} ?>

							</div>
							</div>
							
							
							</li>
							
							</ul>
						</li>
				<?php }?>
			<?php }?>
		</ul>
	</div>
<?php } ?>