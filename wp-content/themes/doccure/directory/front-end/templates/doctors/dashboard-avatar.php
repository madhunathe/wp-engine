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
global $current_user, $wp_roles, $userdata, $post;
$user_identity 	 = $current_user->ID;
$linked_profile  = doccure_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;

if( has_post_thumbnail($post_id) ){
	$attachment_id 			= get_post_thumbnail_id($post_id);
	$image_url 				= !empty( $attachment_id ) ? wp_get_attachment_image_src( $attachment_id, 'doccure_doctors_type', true ) : '';
	$file_size 				= !empty( $attachment_id) ? filesize(get_attached_file($attachment_id)) : '';	
	$document_name   		= !empty( $attachment_id ) ? get_the_title( $attachment_id ) : '';
	$filetype        		= !empty( $image_url[0] ) ? wp_check_filetype( $image_url[0] ) : '';
	$extension       		= !empty( $filetype['ext'] ) ? $filetype['ext'] : '';
}

$rand 						= rand(9999, 999);
?>
<div class="dc-profilephoto dc-tabsinfo">
 
	<div class="dc-profilephotocontent">	
		<div class="dc-formtheme dc-formprojectinfo dc-formcategory" id="dc-img-<?php echo esc_attr( $rand ); ?>">
			<fieldset>
				<div class="form-group form-group-label" id="dc-image-container-<?php echo esc_attr( $rand ); ?>">
				<label><?php esc_html_e('Profile Photo', 'doccure'); ?></label>
					<div class="dc-labelgroup"  id="image-drag-<?php echo esc_attr( $rand ); ?>">
						<label for="file" class="dc-image-file">
							<span class="dc-btn" id="image-btn-<?php echo esc_attr( $rand ); ?>"><?php esc_html_e('Select File', 'doccure'); ?></span>								
						</label>
						<span><?php esc_html_e('Drop files here to upload', 'doccure'); ?></span>
						<em class="dc-fileuploading"><?php esc_html_e('Uploading', 'doccure'); ?><i class="fa fa-spinner fa-spin"></i></em>
					</div>
				</div>
				<div class="form-group uploaded-placeholder">
					<?php if( !empty( $image_url[0] ) ){ ?>
						<ul class="dc-attachfile dc-attachfilevtwo">						
							<li class="dc-uploadingholder dc-companyimg-user">
								<div class="dc-uploadingbox">
									<figure><img class="img-thumb" src="<?php echo esc_url( $image_url[0] ); ?>" alt="<?php echo esc_attr( get_the_title( $post_id )); ?>"></figure>
									<div class="dc-uploadingbar">
										<span class="uploadprogressbar"></span>
										<span><?php echo esc_attr( $document_name ); ?>.<?php echo esc_attr( $extension ); ?></span>
										<em><?php esc_html_e('File size:', 'doccure'); ?> <?php echo esc_attr( size_format($file_size, 2) ); ?><a href="javascript:;" class="dc-remove-image fa fa-close"></a></em>
									</div>	
									<input type="hidden" name="basics[avatar][attachment_id]" value="<?php echo esc_attr( $attachment_id ); ?>">	
								</div>
							</li>						
						</ul>						
					<?php } ?>
				</div>		
			</fieldset>
		</div>
	</div>
</div>

<?php
	$inline_script = 'jQuery(document).on("ready", function() { init_image_uploader_v2("' . esc_js( $rand ). '", "profile"); });';
	wp_add_inline_script( 'doccure-dashboard', $inline_script, 'after' );
?>
<script type="text/template" id="tmpl-load-default-image">
	<ul class="dc-attachfile dc-attachfilevtwo">
		<li class="award-new-item dc-uploadingholder dc-doc-parent" id="thumb-{{data.id}}">
			<div class="dc-uploadingbox">
				<figure><img class="img-thumb" src="<?php echo get_template_directory_uri();?>/images/profile.jpg" alt="<?php echo esc_attr( get_the_title( $post_id )); ?>"></figure>
				<div class="dc-uploadingbar dc-uploading">
					<span class="uploadprogressbar" style="width:{{data.percentage}}%"></span>
					<span>{{data.name}}</span>
					<em><?php esc_html_e('File size:', 'doccure'); ?> {{data.size}}<a href="javascript:;" class="dc-remove-image fa fa-close"></a></em>	
				</div>	
			</div>
		</li>
	</ul>	
</script>
<script type="text/template" id="tmpl-load-profile-image">
	<div class="dc-uploadingbox">
		<figure><img class="img-thumb" src="{{data.url}}" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>"></figure>
		<div class="dc-uploadingbar">
			<span class="uploadprogressbar"></span>
			<span>{{data.name}}</span>
			<em><?php esc_html_e('File size:', 'doccure'); ?> {{data.size}}<a href="javascript:;" class="dc-remove-image fa fa-close"></a></em>
			<input type="hidden" name="basics[avatar]" value="{{data.url}}">	
		</div>	
	</div>	
</script>