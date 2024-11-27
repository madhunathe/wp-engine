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

$am_downloads	= doccure_get_post_meta( $post_id,'am_downloads');
$default_img 	= get_template_directory_uri().'/images/file-icon.png';
$rand 			= rand(9999, 999);
?>
<?php
	$inline_script = 'jQuery(document).on("ready", function() { init_uploader_downloads(); });';
	wp_add_inline_script( 'doccure-dashboard', $inline_script, 'after' );
?>
<script type="text/template" id="tmpl-load-download-attachments">
	<li class="dc-uploadingholder dc-companyimg-user" >
		<div class="dc-files-content">
			<img class="img-thumb" src="<?php echo esc_url( $default_img ); ?>" alt="{{data.name}}">
			<div class="dc-filecontent">
				<span>
					{{data.name}}
					<em>
						<?php esc_html_e('File size:', 'doccure'); ?> {{data.size}}						
					</em>
				</span>
				<a href="javascript:;" class="dc-closediv"><i class="fa fa-close"></i></a>
			</div>	
			<input type="hidden" id="thumb-{{data.id}}" name="am_downloads[{{data.counter}}][media]" value="{{data.url}}">	
		</div>
	</li>
</script>