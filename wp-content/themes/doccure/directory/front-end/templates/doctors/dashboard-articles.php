<?php 
/**
 *
 * The template part for displaying the user profile avatar
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user,$post;
$post_id			= $post->ID;
$user_identity  	= doccure_get_linked_profile_id( $post_id,'post' );

$avatar_url 		= apply_filters(
							'doccure_doctor_avatar_fallback', doccure_get_doctor_avatar(array('width' => 30, 'height' => 30), $post_id), array('width' => 30, 'height' => 30) 
						);

$auther_name		= get_the_title( $post_id );
$show_posts 		= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$pg_page 			= get_query_var('page') ? get_query_var('page') : 1; 
$pg_paged 			= get_query_var('paged') ? get_query_var('paged') : 1;
$paged 				= max($pg_page, $pg_paged);
$order 	 			= 'DESC';
$sorting 		= 'ID';

$args = array(
	'posts_per_page' 	=> $show_posts,
    'post_type' 		=> 'post',
    'orderby' 			=> $sorting,
    'order' 			=> $order,
	'post_status' 		=> array('publish','pending'),
    'author' 			=> $user_identity,
    'paged' 			=> $paged,
    'suppress_filters'  => false
);

$query 		= new WP_Query($args);
$count_post = $query->found_posts;

$width	= 271;
$height	= 194;
?>
<div class="dc-dashboardboxcontent">
	<div class="dc-contentdoctab dc-articles-holder  dc-listedarticle">
		<div class="dc-articles">
			<?php if( $query->have_posts() ){ ?>
				<div class="dc-articleslist-content dc-articles-list">
					<?php 
						while ($query->have_posts()) : $query->the_post(); 
							global $post;
							$thumbnail      = doccure_prepare_thumbnail($post->ID, $width, $height);
							$post_likes		= get_post_meta($post->ID,'post_likes',true);
							$post_likes		= !empty( $post_likes ) ? $post_likes : 0 ;

							$post_views		= get_post_meta($post->ID,'post_views',true);
							$post_views		= !empty( $post_views ) ? $post_views : 0 ;
							$edit_url		= doccure_Profile_Menu::doccure_profile_menu_link('manage-article', $user_identity, true,'listings',$post->ID);
							$categries		= $specialities	= get_the_term_list( $post->ID, 'category', '', ',', '' );
							?>
							<div class="dc-article">
								<figure class="dc-articleimg">
									<?php if( !empty( $thumbnail ) ){?>
										<img src="<?php echo esc_url( $thumbnail );?>" alt="<?php echo esc_attr( get_the_title() );?>" />
									<?php } ?>
									<figcaption>
										<div class="dc-articlesdocinfo">
											<?php if( !empty( $avatar_url ) ) {?>
												<img src="<?php echo esc_url( $avatar_url );?>" alt="<?php echo esc_attr( $auther_name );?>">
											<?php } ?>
											<?php if( !empty( $auther_name ) ) {?><span><?php echo esc_html( $auther_name );?></span><?php } ?>
										</div>
									</figcaption>
								</figure>
								<div class="dc-articlecontent">
									<div class="dc-title">
										<?php if( !empty( $categries ) ) { ?><div class="dc-tag-v2"><?php echo do_shortcode( $categries );?></div><?php }?>
										<h3><a href="<?php echo esc_url( get_the_permalink() );?>"><?php the_title();?></a></h3>
										<?php do_action('doccure_post_date',$post->ID);?>
									</div>
									<div class="dc-optionarea">
										<ul class="dc-moreoptions">
											<?php if( class_exists( 'doccureGlobalSettings' ) ) {?>
												<li class="dcget-likes" data-key="<?php echo esc_attr($post->ID);?>"><a href="javascript:;"><i class="ti-heart"></i><?php echo sprintf( _n( '%s Like', '%s Likes', $post_likes, 'doccure' ), $post_likes );?></a></li>
												<li><a href="javascript:;"><i class="ti-eye"></i><?php echo sprintf( _n( '%s View', '%s Views', $post_views, 'doccure' ), $post_views );?></a></li>
											<?php }?>
											<li><a href="javascript:;" class="dc-share-link"><i class="ti-share"></i> <?php esc_html_e('Share','doccure');?></a></li>
										</ul>
										<div class="dc-rightarea dc-btnaction">
											<a href="<?php echo esc_url( $edit_url );?>" class="dc-addinfo"><i class="fa fa-pencil"></i></a>
											<a href="javascript:;" class="dc-deleteinfo dc-article-delete" data-id="<?php echo intval($post->ID);?>"><i class="fa fa-trash"></i></a>
										</div>		

									</div>
								</div>
							</div>
						<?php
						endwhile;
						wp_reset_postdata();			 

						if (!empty($count_post) && $count_post > $show_posts) {
							doccure_prepare_pagination($count_post, $show_posts);
						}

					?>
				</div>
			<?php 
				} else{ 
					do_action('doccure_empty_records_html','dc-empty-articls dc-emptyholder-sm',esc_html__( 'No Articles posted yet.', 'doccure' ));
				} 
			?>
		</div>
	</div>
</div>