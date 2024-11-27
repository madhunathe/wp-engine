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
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doccure_get_linked_profile_id($user_identity);
$post_id 		 	= $linked_profile;
$post_meta			= doccure_get_post_meta( $post_id );

$am_document_url	= !empty( $post_meta['am_document']['url'] ) ? $post_meta['am_document']['url'] : '';
$am_document_id		= !empty( $post_meta['am_document']['id'] ) ? $post_meta['am_document']['id'] : '';
$am_is_verified		= !empty( $post_meta['am_is_verified'] ) ? $post_meta['am_is_verified'] : '';

$am_registration_number	= !empty( $post_meta['am_registration_number'] ) ? $post_meta['am_registration_number'] : '';
$display				= !empty( $post_meta['am_document']['url'] ) ? 'dc-display-none' : '';
$attachment_id			= $am_document_id;
$image_url				= $am_document_url;
$file_size 				= !empty( $attachment_id) ? filesize(get_attached_file($attachment_id)) : '';	
$document_name   		= !empty( $attachment_id ) ? get_the_title( $attachment_id ) : '';
$filetype        		= !empty( $image_url ) ? wp_check_filetype( $image_url ) : '';
$extension       		= !empty( $filetype['ext'] ) ? $filetype['ext'] : ''; 
$file_image				= $default_img 	= get_template_directory_uri().'/images/file.jpg';
$rand 					= rand(9999, 999);
?>
<div class="dc-profilephoto dc-tabsinfo dc-registration-wraper">
	<div class="dc-tabscontenttitle">
		<h3><?php esc_html_e('Registrations', 'doccure'); ?></h3>
	</div>
	<div class="dc-profilephotocontent">	
		<div class="dc-formtheme dc-formprojectinfo dc-formcategory"  id="dc-img-<?php echo esc_attr( $rand ); ?>" data-default="<?php echo esc_attr($file_image);?>"> 
			<fieldset>
				<div class="form-group form-group-label registration-option <?php echo esc_attr($display);?>" id="dc-image-container-<?php echo esc_attr( $rand ); ?>">
					<div class="dc-labelgroup"  id="image-drag-<?php echo esc_attr( $rand ); ?>">
						<label for="file" class="dc-image-file">
							<span class="dc-btn" id="image-btn-<?php echo esc_attr( $rand ); ?>"><?php esc_html_e('Select File', 'doccure'); ?></span>								
						</label>
						<span><?php esc_html_e('Drop files here to upload', 'doccure'); ?></span>
						<em class="dc-fileuploading"><?php esc_html_e('Uploading', 'doccure'); ?><i class="fa fa-spinner fa-spin"></i></em>
					</div>
				</div>
				<div class="form-group uploaded-placeholder">
					<?php if( !empty( $image_url ) ){ ?>
						<ul class="dc-attachfile dc-attachfilevtwo">						
							<li class="dc-uploadingholder dc-companyimg-user">
								<div class="dc-uploadingbox">
								<div class="dc-designimg">
									<?php if( !empty( $am_is_verified ) && $am_is_verified === 'yes' ) {?>
										<input id="demoq" type="radio" checked="">
									<?php } ?>
									<label for="demoq">
										<img class="img-thumb" src="<?php echo esc_url( $file_image ); ?>" alt="<?php esc_attr_e('File'.'doccure') ?>">
										<?php if( !empty( $am_is_verified ) && $am_is_verified === 'yes' ) {?>
											<i class="fa fa-check"></i>
										<?php } ?>
									</label>
								</div>
									<div class="dc-uploadingbar">
										<span class="uploadprogressbar"></span>
										<span><?php echo esc_html( $document_name ); ?>.<?php echo esc_html( $extension ); ?></span>
										<em><?php esc_html_e('File size:', 'doccure'); ?> <?php echo esc_html( size_format($file_size, 2) ); ?><a href="javascript:;" class="dc-remove-attachment fa fa-close"></a></em>
									</div>	
									<input type="hidden" name="am_document[id]" value="<?php echo esc_attr( $attachment_id ); ?>">	
									<input type="hidden" id="reg_url" name="am_document[url]" value="<?php echo esc_attr( $image_url ); ?>">	
								</div>
							</li>						
						</ul>						
					<?php } ?>
				</div>
			</fieldset>
			<div class="form-group toolip-wrapo">
				<input type="text" name="am_registration_number" class="form-control" value="<?php echo esc_attr( $am_registration_number ); ?>" placeholder="<?php esc_attr_e('Registration Number', 'doccure'); ?>">
				<?php do_action('doccure_get_tooltip','element','am_registration_number');?>
			</div>
		</div>
	</div>
</div>

<?php
	$inline_script = 'jQuery(document).on("ready", function() { init_uploader_registrations("' . esc_js( $rand ). '", "profile"); });';
	wp_add_inline_script( 'doccure-dashboard', $inline_script, 'after' );
?>
<script type="text/template" id="tmpl-load-default-image">
	<ul class="dc-attachfile dc-attachfilevtwo">
		<li class="award-new-item dc-uploadingholder dc-doc-parent" id="thumb-{{data.id}}">
			<div class="dc-uploadingbox">
				<figure><img class="img-thumb" src="{{data.url}}" alt="<?php esc_attr_e('File','doccure'); ?>"></figure>
				<div class="dc-uploadingbar dc-uploading">
					<span class="uploadprogressbar" style="width:{{data.percentage}}%"></span>
					<span>{{data.name}}</span>
					<em><?php esc_html_e('File size:', 'doccure'); ?> {{data.size}}<a href="javascript:;" class="dc-remove-attachment fa fa-close"></a></em>	
				</div>	
			</div>
		</li>
	</ul>	
</script>
<script type="text/template" id="tmpl-load-profile-image">
	
	<div class="dc-uploadingbox">
		<figure><img class="img-thumb" src="{{data.preview}}" alt="<?php esc_attr_e('File','doccure'); ?>"></figure>
		<div class="dc-uploadingbar">
			<span class="uploadprogressbar"></span>
			<span>{{data.name}}</span>
			<em><?php esc_html_e('File size:', 'doccure'); ?> {{data.size}}
			<a href="javascript:;" class="dc-remove-attachment fa fa-close"></a></em>
			<input type="hidden" name="am_document[url]" value="{{data.url}}">
		</div>	
	</div>	
</script>