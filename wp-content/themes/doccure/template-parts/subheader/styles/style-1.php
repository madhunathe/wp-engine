<?php
/**
 * subheader style 1.
 *
 * @package doccure
 */
global $post;
// Get the page title and caption
$page_title = doccure_subheader_get_title();
//$page_caption = doccure_subheader_get_caption();
$page_background_image = doccure_subheader_get_background_image();
$breacrumb_position = doccure_get_option('breadcrumb_position', 'after-title');
$delimiter = doccure_get_option('breadcrumb_custom_separator') && !empty(doccure_get_option('breadcrumb_custom_icon')) ? '<i class="' . esc_attr(doccure_get_option('breadcrumb_custom_icon')) . '"></i>' : '';
$subheader_alignment_class = doccure_get_option('subheader_alignment', 'text-left');

 $top_header_width_class = (doccure_get_option('adjust-custom-header-top-width') == true && doccure_get_option('header-top-width-style') == 'full-width') ? 'container-fluid' : 'container';
$header_controls_style = doccure_get_option('header_controls_style');

$post_id	= get_the_ID();
 $post_meta	= doccure_get_post_meta( $post_id );
if(isset( $post_meta['am_page_title'] ) && $post_meta['am_page_title']=='hide') { } else {
?>
<div class="doccure_subheader <?php echo esc_attr(doccure_subheader_classes()) ?>" <?php if ($page_background_image) {
    echo 'style="background-image:url(' . esc_url($page_background_image) . ')"';
} ?>>
    <div class="container">
        <div class="doccure_subheader-inner">
            <?php if (doccure_get_option('display_breadcrumb') && $breacrumb_position == 'before-title') { ?>
                <div class="breadcrumb-nav">
                    <?php echo doccure_subheader_get_breadcrumbs($delimiter); ?>
                </div>
            <?php } ?>
           
            <?php if ($page_title) { ?>
                <h1 class="page-title"> <?php echo esc_html($page_title) ?> </h1>
            <?php } ?>
            <?php if (doccure_get_option('display_breadcrumb') && $breacrumb_position == 'after-title') { ?>
                <div class="breadcrumb-nav">
                    <?php echo doccure_subheader_get_breadcrumbs($delimiter); ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php if (doccure_get_option('display_breadcrumb') && $breacrumb_position == 'below-image') { ?>
    <div class="breadcrumb-nav below-subheader <?php echo esc_attr($subheader_alignment_class); ?>">
        <div class="container">
            <?php echo doccure_subheader_get_breadcrumbs($delimiter); ?>
        </div>
    </div>
<?php } } ?>
