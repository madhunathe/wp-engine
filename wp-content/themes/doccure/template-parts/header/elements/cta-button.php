<?php
/**
 * Template part for header call to action button.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package doccure
 */
$top_header_cta_btn_title = doccure_get_option('top_cta_btn_title');
$top_header_cta_btn_link = doccure_get_option('top_cta_btn_link');
if (!$top_header_cta_btn_title || !$top_header_cta_btn_link) {
    return;
}
?>
<ul class="doccure_header-top-nav doccure_header-top-cta">
    <li>
        <a href="<?php echo esc_url($top_header_cta_btn_link); ?>" class="doccure_btn"><?php echo esc_html($top_header_cta_btn_title); ?></a>
    </li>
</ul>
