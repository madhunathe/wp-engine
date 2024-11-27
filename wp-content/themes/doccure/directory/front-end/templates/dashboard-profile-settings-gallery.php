<?php
/**
 *
 * The template part for displaying the dashboard menu
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post;
$user_identity 	 = $current_user->ID;
$linked_profile  = doccure_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;
$gallery_images = doccure_get_post_meta( $post_id,'am_gallery');
$am_videos	= doccure_get_post_meta( $post_id,'am_videos');

$banner_rand	= rand(9999, 999);
?>

<div class="dc-profilephoto dc-tabsinfo">
	<div class="dc-tabscontenttitle mt-0">
		<h3><?php esc_html_e('Profile Gallery', 'doccure'); ?></h3>
	</div>
	<div class="dc-profilephotocontent">	
		<div class="dc-formtheme dc-formprojectinfo dc-formcategory" id="dc-img-<?php echo esc_attr( $banner_rand ); ?>">
			<fieldset>
				<div class="form-group form-group-label" id="dc-image-container-<?php echo esc_attr( $banner_rand ); ?>">
					<div class="dc-labelgroup"  id="image-drag-<?php echo esc_attr( $banner_rand ); ?>">
						<label for="file" class="dc-image-file">
							<span class="dc-btn" id="image-btn-<?php echo esc_attr( $banner_rand ); ?>"><?php esc_html_e('Select File', 'doccure'); ?></span>								
						</label>
						<span><?php esc_html_e('Drop files here to upload', 'doccure'); ?></span>
						<em class="dc-fileuploading"><?php esc_html_e('Uploading', 'doccure'); ?><i class="fa fa-spinner fa-spin"></i></em>
					</div>
				</div>
				<div class="form-group uploaded-placeholder">
						<ul class="dc-attachfile dc-attachfilevtwo dc-galler-images">
						<?php if( !empty( $gallery_images ) ){ 
							foreach($gallery_images as $key => $gallery_image ) {
								$banner_file_size 		= !empty( $gallery_image['attachment_id']) ? filesize(get_attached_file($gallery_image['attachment_id'])) : '';	
								$banner_document_name	= !empty( $gallery_image['attachment_id'] ) ? esc_html( get_the_title( $gallery_image['attachment_id'] ) ) : '';
								$banner_filetype        = !empty( $gallery_image['attachment_id'] ) ? wp_check_filetype( $gallery_image['url'] ) : '';
								$banner_extension  		= !empty( $banner_filetype['ext'] ) ? $banner_filetype['ext'] : '';
								$gallery_image_url 		= !empty( $gallery_image['attachment_id'] ) ? wp_get_attachment_image_src( $gallery_image['attachment_id'], array(150,150), true ) : '';
							?>
							<li class="dc-uploadingholder dc-companyimg-user">
								<div class="dc-uploadingbox">
									<figure><img class="img-thumb" src="<?php echo esc_url( $gallery_image_url[0] ); ?>" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>"></figure>
									<div class="dc-uploadingbar">
										<span class="uploadprogressbar"></span>
										<span><?php echo esc_html( $banner_document_name ); ?></span>
										<em><?php esc_html_e('File size:', 'doccure'); ?> <?php echo esc_html( size_format($banner_file_size, 2) ); ?><a href="javascript:;" class="dc-remove-gallery-image fa fa-close"></a></em>
									</div>	
									<input type="hidden" name="am_gallery[<?php echo intval($key);?>][attachment_id]" value="<?php echo esc_attr( $gallery_image['attachment_id'] ); ?>">	
									<input type="hidden" name="am_gallery[<?php echo intval($key);?>][url]" value="<?php echo esc_url( $gallery_image['url'] ); ?>">	
								</div>
							</li>	
						<?php }} ?>							
						</ul>	
				</div>		
			</fieldset>
		</div>
	</div>
</div>
<div class="dc-skills dc-tabsinfo">
	<div class="dc-tabscontenttitle">
		<h3><?php esc_html_e('Videos','doccure');?></h3>
	</div>
	<div class="dc-skillscontent-holder">
		<div class="dc-formtheme dc-skillsform">
			<fieldset>
				<div class="form-group">
					<div class="form-group-holder">
						<input type="text" class="form-control" id="input_membership" placeholder="<?php esc_html_e('Your Video URL','doccure');?>">
					</div>
				</div>
				<div class="form-group dc-btnarea">
					<a href="javascript:;" class="dc-btn dc-add_membership"><?php esc_html_e('Add Now','doccure');?></a>
				</div>
			</fieldset>
		</div>
 		<div class="dc-myskills">
			<ul class="sortable list dc-memberships dc-sortable-list">
				<?php foreach( $am_videos as $key => $am_video ) {?>
					<li class="dc-membership-list">
						<div class="dc-dragdroptool">
							<a href="javascript:" class="lnr lnr-menu"></a>
						</div>
						<span class="skill-dynamic-html"><em class="skill-val"><?php echo esc_html($am_video);?></em></span>
						<span class="skill-dynamic-field">
							<input type="text" name="am_videos[<?php echo intval($key);?>]" value="<?php echo esc_attr($am_video);?>">
						</span>
						<div class="dc-rightarea">
							<a href="javascript:;" class="dc-addinfo"><i class="fa fa-pencil"></i></a>
							<a href="javascript:;" class="dc-deleteinfo"><i class="fa fa-trash"></i></a>
						</div>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>	
<?php
	$inline_script_v = 'jQuery(document).on("ready", function() { init_image_uploader_gallery("' . esc_js( $banner_rand ). '", "gallery"); });';
	wp_add_inline_script( 'doccure-dashboard', $inline_script_v, 'after' );
?>
<script type="text/template" id="tmpl-load-gallery-image">
	<li class="dc-uploadingholder dc-companyimg-user" id="thumb-{{data.id}}">
		<div class="dc-uploadingbox">
			<figure><img class="img-thumb" src="<?php echo get_template_directory_uri();?>/images/avatar.jpg" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>"></figure>
			<div class="dc-uploadingbar dc-uploading">
				<span class="uploadprogressbar" style="width:{{data.percentage}}%"></span>
				<span>{{data.name}}</span>
				<em><?php esc_html_e('File size:', 'doccure'); ?> {{data.size}}<a href="javascript:;" class="dc-remove-gallery-image fa fa-close"></a></em>	
			</div>	
		</div>
	</li>
</script>
<script type="text/template" id="tmpl-load-append-gallery-image">
	<div class="dc-uploadingbox">
		<figure><img class="img-thumb" src="{{data.url}}" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>"></figure>
		<div class="dc-uploadingbar">
			<span class="uploadprogressbar"></span>
			<span>{{data.name}}</span>
			<em><?php esc_html_e('File size:', 'doccure'); ?> {{data.size}}<a href="javascript:;" class="dc-remove-gallery-image fa fa-close"></a></em>
			<input type="hidden" name="gallery[images_gallery_new][]" value="{{data.url}}">	
		</div>	
	</div>	
</script>
<script type="text/template" id="tmpl-load-memberships">
	<li>
		<div class="dc-dragdroptool">
			<a href="javascript:" class="lnr lnr-menu"></a>
		</div>
		<span class="skill-dynamic-html"><em class="skill-val">{{data.name}}</em></span>
		<span class="skill-dynamic-field">
			<input type="text" name="am_videos[{{data.id}}]" value="{{data.name}}">
		</span>
		<div class="dc-rightarea">
			<a href="javascript:;" class="dc-addinfo"><i class="fa fa-pencil"></i></a>
			<a href="javascript:;" class="dc-deleteinfo"><i class="fa fa-trash"></i></a>
		</div>
	</li>	
</script>