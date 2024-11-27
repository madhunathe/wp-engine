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

$cats			= doccure_get_taxonomy_array('category');
$tags			= doccure_get_taxonomy_array('post_tag');
$description 	= esc_html__('Article contents...','doccure');
$name 			= 'post_content';								
$settings 		= array('media_buttons' => false,'textarea_name'=> $name,'editor_class'=> 'customwp_editor','media_buttons','editor_height'=>300 );
$db_cat			= '';
$db_tag			= '';
$rand 			= rand(9999, 999);
?>
<div class="">
	<div class="dc-dashboardbox">
		<form class="dc-post-artical" method="post">
			<div class="dc-dashboardboxcontent dc-addservices dc-articlesservices">
				<div class="dc-tabscontenttitle">
					<h3><?php esc_html_e('Add New Article','doccure');?></h3>
				</div>
				<div class="dc-formtheme dc-userform">
					<fieldset>
						<div class="form-group toolip-wrapo">
							<input type="text" name="post_title" class="form-control" placeholder="<?php esc_attr_e('Article Title','doccure');?>">
							<?php do_action('doccure_get_tooltip','element','post_title');?>
						</div>
						<div class="form-group dc-tinymceeditor">
							<?php wp_editor($description, 'post_content', $settings);?>
						</div>
					</fieldset>
				</div>
				<div class="dc-featuredphoto-holder dc-tabsinfo">
					<div class="dc-tabscontenttitle">
						<h3><?php esc_html_e('Featured Photo','doccure');?></h3>
					</div>
					<div class="dc-profilephotocontent">
						<div class="dc-formtheme dc-formprojectinfo dc-formcategory" id="dc-img-<?php echo esc_attr( $rand ); ?>">
							<fieldset>
								<div class="form-group form-group-label" id="dc-image-container-<?php echo esc_attr( $rand ); ?>">
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
													<figure><img class="img-thumb" src="<?php echo esc_url( $image_url[0] ); ?>" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>"></figure>
													<div class="dc-uploadingbar">
														<span class="uploadprogressbar"></span>
														<span><?php echo esc_html( $document_name ); ?>.<?php echo esc_html( $extension ); ?></span>
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
				<div class="dc-category-holder dc-tabsinfo">
					<div class="dc-tabscontenttitle">
						<h3><?php esc_html_e('Select Category','doccure');?></h3>
					</div>
					<div class="dc-articletag-holder">
						<div class="dc-formtheme dc-skillsform">
							<fieldset>
								<div class="form-group">
									<span class="dc-select">
										<select class="chosen-select" data-placeholder="<?php esc_attr_e('Choose category','doccure');?>" name="post_categories[]" multiple>
											<?php 
												if( !empty( $cats ) ){							
													foreach ($cats as $key => $item) {
														$term_id   = $item->term_id;
														?>
															<option value="<?php echo esc_attr( $term_id ); ?>"><?php echo esc_html( $item->name ); ?></option>
														<?php 
													}
												}
											?>				
										</select>
									</span>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
				<div class="dc-addtag-holder dc-tabsinfo">
					<div class="dc-tabscontenttitle">
						<h3><?php esc_html_e('Add Tags','doccure');?></h3>
					</div>
					<div class="dc-articletag-holder">
						<div class="dc-formtheme dc-skillsform">
							<fieldset>
								<div class="form-group">
									<span class="dc-select">
										<select class="chosen-select" data-placeholder="<?php esc_attr_e('Choose tags','doccure');?>" name="post_tags[]" multiple>
											<?php 
												if( !empty( $tags ) ){							
													foreach ($tags as $key => $item) {
														$term_id   = $item->term_id;
														?>
															<option value="<?php echo esc_attr( $item->name ); ?>"><?php echo esc_html( $item->name ); ?></option>
														<?php 
													}
												}
											?>				
										</select>
									</span>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
				<div class="dc-btnarea">
					<a href="javascript:;" class="dc-btn dc-add-post mt-3"><?php esc_html_e('Post Now','doccure');?></a>
				</div>						
			</div>
		</form>	
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