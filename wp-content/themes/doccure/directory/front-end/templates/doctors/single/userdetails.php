<?php
/**
 *
 * The template used for doctors details
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post,$doccure_options;
$post_id 		= $post->ID;
$name			= doccure_full_name( $post_id );
$name			= !empty( $name ) ? $name : ''; 

$width			= 271;
$height			= 194;
$thumbnail      = doccure_prepare_thumbnail($post->ID, $width, $height);
$gallery_option	= !empty($doccure_options['enable_gallery']) ? $doccure_options['enable_gallery'] : '';
?>
<div class="dc-userdetails-holder   " id="userdetails">
	<div class="dc-aboutdoc dc-aboutinfo">
		<div class="dc-infotitle">
			<h3><?php esc_html_e( 'About','doccure');?> “<?php echo esc_html( $name );?>”</h3>
		</div>
		<div class="dc-description"><?php the_content();?></div>
	</div>
	<?php get_template_part('directory/front-end/templates/doctors/single/services'); ?>
	<?php get_template_part('directory/front-end/templates/doctors/single/experience'); ?>
	<?php get_template_part('directory/front-end/templates/doctors/single/education'); ?>
	<?php get_template_part('directory/front-end/templates/doctors/single/specializations'); ?>
	<?php get_template_part('directory/front-end/templates/doctors/single/languages'); ?>
	<?php get_template_part('directory/front-end/templates/doctors/single/awards'); ?>
	<?php get_template_part('directory/front-end/templates/doctors/single/memberships'); ?>	
	<?php get_template_part('directory/front-end/templates/doctors/single/registrations'); ?>
	<?php get_template_part('directory/front-end/templates/doctors/single/downloads'); ?>
	<?php
		if(!empty($gallery_option)){
			//get_template_part('directory/front-end/templates/gallery');
		}
	?>
</div>