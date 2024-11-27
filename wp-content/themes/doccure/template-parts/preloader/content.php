<?php

/**
 * Template part for displaying preloaders
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package doccure
 */


if( doccure_get_option('preloader_enable') ){
$preloader_style = doccure_get_option('preloader_style', 'default');
?>

<div class="doccure_preloader doccure_preloader-<?php echo esc_attr($preloader_style) ?>">
  <div class="doccure_preloader-inner">
    <?php get_template_part( 'template-parts/preloader/styles/' . $preloader_style ); ?>
  </div>
</div>
<?php } ?>
