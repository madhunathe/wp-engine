<?php
/**
 *
 * The template used for doctors brochures
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id 		= $post->ID;
$am_downloads	= doccure_get_post_meta( $post_id,'am_downloads');
$default_img 	= get_template_directory_uri().'/images/file-icon.png';
if( !empty( $am_downloads ) ) { ?>
	<div class="dc-downloads-holder dc-aboutinfo">
		<div class="dc-infotitle">
			<h3><?php esc_html_e('Downloads','doccure');?></h3>
		</div>
		<ul class="dc-downloads-listing">
			<?php foreach( $am_downloads as $key => $am_download ) {
				$image				= !empty( $am_download['media']) ?  $am_download['media'] : '';
				$attachment_id		= !empty($am_download['id']) ? $am_download['id'] : '';
				$file_size 			= !empty( $attachment_id) ? filesize(get_attached_file($attachment_id)) : '';	
				$document_name   	= !empty( $attachment_id ) ? get_the_title( $attachment_id ) : '';
				$filetype        	= !empty( $image ) ? wp_check_filetype( $image	 ) : '';
				$url				= wp_get_attachment_url($attachment_id);
				$extension       	= !empty( $filetype['ext'] ) ? $filetype['ext'] : ''; ?>
					<li><a href="<?php echo esc_url( $url );?>" download><h3><?php echo esc_html( $document_name ); ?><span> <?php echo ( size_format($file_size, 2) ); ?></span></h3></a></li>
			<?php } ?>
		</ul>
	</div>
<?php } ?>