<?php
/**
 * Template part for displaying subheader
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package doccure
 */
if (is_404()) {
    return;
}
// Check if subheader is active
$subheader_is_active = doccure_subheader_is_active();
if (!$subheader_is_active) {
    return;
}
$subheader_type = doccure_get_option('subheader_type', 'static');
if ($subheader_type == 'static') {
    $subheader_style = doccure_get_option('subheader_style', 'style-1');
    get_template_part('template-parts/subheader/styles/' . $subheader_style);
} else {
    $subheader_template = doccure_get_option('subheader_type_page_template');
    if (empty($subheader_template)) {
        return;
    }
    $post = get_post($subheader_template);
    ?>
    <div class="doccure-template doccure_subheader-template doccure-template-<?php echo esc_attr($subheader_template) ?>">
        <div class="container">
            <div class="entry-content clearfix">
                <?php echo do_shortcode($post->post_content); ?>
            </div>
        </div>
    </div>
    <?php
}
?>
