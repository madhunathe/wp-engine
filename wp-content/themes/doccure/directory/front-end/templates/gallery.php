<?php
/**
 *
 * The template used for displaying Gallery
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id = $post->ID;
$images  	= doccure_get_post_meta( $post_id,'am_gallery');
$title		= get_the_title($post_id);
$videos		= doccure_get_post_meta( $post_id,'am_videos');
if( !empty( $images ) && is_array( $images ) ){?>
	<div class="dc-downloads-holder dc-aboutinfo dc-gall-holder">
		<div class="dc-infotitle">
			<h3><?php esc_html_e('Gallery','doccure');?></h3>
		</div>
		<div class="gallery-img">
			<div class="dc-projects">
				<?php 
					foreach( $images as $key => $gallery_image ){ 
						$gallery_thumnail_image_url 	= !empty( $gallery_image['attachment_id'] ) ? wp_get_attachment_image_src( $gallery_image['attachment_id'], array(255,200), true ) : '';
						$gallery_image_url 				= !empty( $gallery_image['url'] ) ? $gallery_image['url'] : '';
						
				?>
				<div class="dc-project dc-crprojects">
					<?php if( !empty($gallery_thumnail_image_url[0]) ){?>
						<a  data-rel="prettyPhoto[gallery]" href="<?php echo esc_url($gallery_image_url);?>"  rel="prettyPhoto[gallery]">
							<figure><img src="<?php echo esc_url( $gallery_thumnail_image_url[0] );?>" alt="<?php echo esc_attr($title);?>"></figure>
						</a>
					<?php }?>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php }?>
<?php if( !empty( $videos ) && is_array( $videos ) ){?>
	<div class="dc-gallery-holder dc-aboutinfo dc-videogallery">
		<div class="dc-infotitle">
			<h3><?php esc_html_e('Videos','doccure');?></h3>
		</div>
		<div class="dc-haslayout">
			<div class="row">
			<?php 
				$total_videos	= !empty($videos) ? count(array_filter($videos)) : 0;
				$count_item		= 0;
				foreach( $videos as $key => $media ){
					if( !empty( $media ) ){
						$count_item ++;
					?>
					<div class="col-xs-12 col-md-6">
						<?php
							$media_url  = parse_url($media);
							$height 	= 250;
							$width 		= 370;

							$url = parse_url($media);
							if ( isset( $url['host'] ) && ( $url['host'] == 'vimeo.com' || $url['host'] == 'player.vimeo.com' ) ) {
								echo '<div class="sp-videos-frame">';
								$content_exp = explode("/", $media);
								$content_vimo = array_pop($content_exp);
								echo '<iframe width="' . intval($width) . '" height="' . intval($height) . '" src="https://player.vimeo.com/video/' . $content_vimo . '" 
	></iframe>';
								echo '</div>';
							} elseif ( isset( $url['host'] ) && $url['host'] == 'soundcloud.com') {
								$video = wp_oembed_get($media, array('height' => intval($height)));
								$search = array('webkitallowfullscreen', 'mozallowfullscreen', 'frameborder="no"', 'scrolling="no"');
								echo '<div class="audio">';
								$video = str_replace($search, '', $video);
								echo str_replace('&', '&amp;', $video);
								echo '</div>';
							} else {
								echo '<div class="sp-videos-frame">';
								echo do_shortcode('[video width="' . intval($width) . '" height="' . intval($height) . '" src="' . esc_url($media) . '"][/video]');
								echo '</div>';
							}
						?>
					</div>
			<?php }}?>
			</div>
		</div>
	</div>
<?php } ?>
	<script type="application/javascript">
		jQuery(document).ready(function () {
			jQuery("a[data-rel]").each(function () {
				jQuery(this).attr("rel", jQuery(this).data("rel"));
			});
			jQuery("a[data-rel^='prettyPhoto']").prettyPhoto({
				animation_speed: 'normal',
				theme: 'dark_square',
				slideshow: 3000,
				default_width: 800,
				default_height: 500,
				allowfullscreen: true,
				autoplay_slideshow: false,	
				social_tools: false,
				iframe_markup: "<iframe src='{path}' width='{width}' height='{height}' frameborder='no' allowfullscreen='true'></iframe>", 
				deeplinking: false
			});
		});
	</script>


