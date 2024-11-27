<?php
/**
 * Template part for header call to action button.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package doccure
 */
$header_cta_btn_title = doccure_get_option('header_cta_btn_title');
$header_cta_btn_link = doccure_get_option('header_cta_btn_link');
if (!$header_cta_btn_title || !$header_cta_btn_link) {
    return;
}
?>
<a href="<?php echo esc_url($header_cta_btn_link); ?>"
   class="doccure_btn ml-5"><?php echo esc_html($header_cta_btn_title); ?></a>
