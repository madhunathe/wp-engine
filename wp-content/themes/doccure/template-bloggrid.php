<?php
/**
 * Template Name: Blog Grid Template
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WPVoyager
 */
get_header();
?>
	<div class="bloggrid_page">
	<div class="container">
			<div class="row">
                <?php 
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$post_per_page = get_option( 'posts_per_page' );
                $args=array(
                    'post_type' => 'post',
                    'paged' => $paged,
                    'posts_per_page' => $post_per_page,
                    'parent'                   => '',
                    'orderby'                  => 'id',
                    'order'                    => 'ASC'

                );
            
                $wp_query = new WP_Query($args);
                if( have_posts() ) :
               while ($wp_query->have_posts()) : $wp_query->the_post(); 
                ?>
				<div class="col-lg-4">
					<article>
						<div class="bloggrid_img">
							<?php if (has_post_thumbnail( $post->ID ) ): ?>
							<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
							<a href="<?php the_permalink(); ?>">
							<img src="<?php echo esc_html($image[0]); ?>"/>
							</a>
							<?php endif; ?>
						</div>
						<div class="blogrid_body">
							<div class="blogrid_content">
								<div class="bloggrid_meta">
									<span class="author">
									<?php 
										$get_author_id = get_the_author_meta('ID');
										$get_author_gravatar = get_avatar_url($get_author_id, array('size' => 30));
										$url = get_author_posts_url(get_the_author_meta('ID'));
									?>
									<img src="<?php echo esc_html($get_author_gravatar);?>">
									<a href="<?php echo the_permalink(); ?>">
									<?php echo get_the_author(); ?>
									</a>
									</span>
									<span class="posted-on">
									<i class="far fa-clock"></i>
									<a href="<?php the_permalink();?>" rel="bookmark">
									<?php 
										$post_date = get_the_date(); 
										echo esc_html($post_date);
									
									?>	</a>
									</span>
									<span class="meta-comment">
										<i class="far fa-comment-dots"></i>
										<a href="<?php the_permalink(); ?>"><span class="comment-count">
											<?php $commentcount = get_comments_number( $post->ID );
												echo esc_html($commentcount); 
											?></span>
									</a> 
									</span>	
								</div>
							</div>
						</div>
						<h5><a href="<?php echo the_permalink(); ?>"><?php the_title();?></a></h5>
						<p class="mb-0"><?php echo wp_trim_words( get_the_content(), 10, '...' );?></p>
					</article>
				</div>
       	 <?php endwhile;  
		 	doccure_paginationblog();
			wp_reset_query();
			endif; ?>
</div>
 
</div> <!-- row -->

</div>

<?php get_footer(); ?>