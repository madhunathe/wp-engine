<?php
/**
 *
 * The template used for displaying default languages result
 *
 * @package   doccure
 */
global $wp_query;
get_header();
$archive_show_posts    = get_option('posts_per_page');
?>
<div class="dc-haslayout dc-parent-section">
<div class="content">
	<div class="container">
		<div class="row">
			<div id="dc-twocolumns" class="dc-twocolumns dc-haslayout d-flex dc-disabled">

			 
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 float-left">
					<div class="dc-searchresult-holder">
						<div class="dc-searchresult-grid dc-searchresult-list dc-searchvlistvtwo blog-list-view-template">
							<?php
								if( have_posts() ) {
									$counter	= 0;
									while ( have_posts() ) : the_post(); 
										global $post;
										$counter ++;
										$post_type	= get_post_type( $post->ID );
									
										if( $post_type === 'doctors') {
											get_template_part('directory/front-end/templates/doctors/doctors-listing');	
										} else if( $post_type === 'hospitals') {
											get_template_part('directory/front-end/templates/hospitals/hospitals-listing');
										} else{
											$width 		= 1140;
											$height 	= 400;
											$thumbnail  = doccure_prepare_thumbnail($post->ID , $width , $height);
											$stickyClass = '';

											if (is_sticky()) {
												$stickyClass = 'sticky';
											}
											?>
											<article class="dc-article">
												<div class="dc-articlecontent">
													<?php if( !empty( $thumbnail ) ){?>
														<figure class="dc-classimg">
															<?php doccure_get_post_thumbnail($thumbnail,$post->ID,'linked');?>
														</figure>
													<?php }?>
													<div class="dc-title">
														<h3><?php doccure_get_post_title($post->ID); ?></h3>
													</div>
													<ul class="dc-postarticlemeta">
														<li><?php doccure_get_post_date($post->ID);?></li>
														<?php if( !empty( get_the_author() ) ){?>
															<li>
																<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
																	<i class="lnr lnr-user"></i>
																	<span><?php echo get_the_author(); ?></span>
																</a>
															</li>
														<?php }?>
													</ul>
													<div class="dc-description">
														<p><?php echo doccure_prepare_excerpt(350); ?></p>
													</div>
													<?php if (is_sticky()) {?>
														<span class="sticky-wrap dc-themetag dc-tagclose"><i class="fa fa-bolt" aria-hidden="true"></i>&nbsp;<?php esc_html_e('Featured','doccure');?></span>
													<?php }?>
												</div>
											</article>
											<?php
										}
									endwhile;
									wp_reset_postdata(); 
									if ( $wp_query->found_posts > $archive_show_posts) { ?>
										<div class="theme-nav">
											<?php 
												if (function_exists('doccure_prepare_pagination')) {
													echo doccure_prepare_pagination($wp_query->found_posts , $archive_show_posts);
												}
											?>
										</div>
								<?php }
								} else {
									do_action('doccure_empty_records_html','dc-empty-hospital-location',esc_html__( 'No result found.', 'doccure' ));
								}
							?>
						</div>
					</div>
				</div>
				<?php if(  is_active_sidebar( 'doctor-sidebar-right' ) ){ ?>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 col-xl-3 float-left">
						<aside id="dc-sidebar" class="dc-sidebar dc-sidebar-grid float-left mt-md-0 mt-lg-0 mt-xl-0">
							<?php dynamic_sidebar( 'doctor-sidebar-right' );?>
						</aside>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
</div>
<?php
get_footer();