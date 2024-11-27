<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Ovveride vc_row_inner template in theme.
 *
 * @package doccure
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$atts               = vc_map_get_attributes( $this->getShortcode(), $atts );
$el_class           = isset( $atts['el_class'] ) ? $atts['el_class'] : '';
$equal_height       = isset( $atts['equal_height'] ) ? $atts['equal_height'] : '';
$content_placement  = isset( $atts['content_placement'] ) ? $atts['content_placement'] : '';
$css                = isset( $atts['css'] ) ? $atts['css'] : '';
$el_id              = isset( $atts['el_id'] ) ? $atts['el_id'] : '';
$disable_element    = isset( $atts['disable_element'] ) ? $atts['disable_element'] : '';
$responsive_css   = isset( $atts['responsive_css'] ) ? $atts['responsive_css'] : '';
$bg_color_scheme    = isset( $atts['bg_color_scheme'] ) ? $atts['bg_color_scheme'] : '';
$bg_position        = isset( $atts['bg_position'] ) ? $atts['bg_position'] : '';
$inner_row_position = isset( $atts['inner_row_position'] ) ? $atts['inner_row_position'] : '';
$title_color_scheme = isset( $atts['title_color_scheme'] ) ? $atts['title_color_scheme'] : '';
$inner_row_z_index = isset( $atts['inner_row_z_index'] ) ? $atts['inner_row_z_index'] : '';
$inner_row_overflow = isset( $atts['inner_row_overflow'] ) ? $atts['inner_row_overflow'] : '';
$inner_row_shadow = isset( $atts['inner_row_shadow'] ) ? $atts['inner_row_shadow'] : '';
$inner_row_hover_shadow = isset( $atts['inner_row_hover_shadow'] ) ? $atts['inner_row_hover_shadow'] : '';
// Responsive editor css
if(function_exists('doccurecore_get_responsive_style')) {
	$responsive_id = uniqid('doccurecore-vc-inner-row-');
	$responsive_editor_css = doccurecore_get_responsive_style( $responsive_css, $responsive_id );
	$doccurecore_generate_css = isset($responsive_editor_css) && !empty($responsive_editor_css) ? '<style>' . $responsive_editor_css . '</style>' : '';
}
$output             = '';
$after_output       = '';
$el_class    = $this->getExtraClass( $el_class );
$css_classes = array(
	'vc_row',
	'wpb_row',
	// deprecated
	'vc_inner',
	'vc_row-fluid',
	$el_class,
	vc_shortcode_custom_css_class( $css ),
	vc_shortcode_custom_css_class( $responsive_css ),
);
if(isset($responsive_id) && !empty($responsive_id)) {
	$css_classes[] = $responsive_id;
}
if ( 'yes' === $disable_element ) {
	if ( vc_is_page_editable() ) {
		$css_classes[] = 'vc_hidden-lg vc_hidden-xs vc_hidden-sm vc_hidden-md';
	} else {
		return '';
	}
}
if ( vc_shortcode_custom_css_has_property( $css, array( 'border', 'background' ) ) ) {
	$css_classes[] = 'vc_row-has-fill';
}
if ( ! empty( $atts['gap'] ) ) {
	$css_classes[] = 'vc_column-gap-' . $atts['gap'];
}
if ( isset( $bg_color_scheme ) && ! empty( $bg_color_scheme ) ) {
	$css_classes[] = 'doccure-bg-color-' . $bg_color_scheme;
}
if ( isset( $bg_position ) && ! empty( $bg_position ) ) {
	$css_classes[] = 'doccure-background-position-' . $bg_position;
}
if ( isset( $title_color_scheme ) && ! empty( $title_color_scheme ) ) {
	$css_classes[] = 'doccure-title-color-' . $title_color_scheme;
}
if ( isset( $inner_row_position ) && ! empty( $inner_row_position ) ) {
	$css_classes[] = 'doccure-inner-row-position-' . $inner_row_position;
}
if ( isset( $inner_row_z_index ) && ! empty( $inner_row_z_index ) ) {
	$css_classes[] = 'doccure-inner-row-z-index-' . $inner_row_z_index;
}
if ( isset( $inner_row_overflow ) && ! empty( $inner_row_overflow ) ) {
	$css_classes[] = 'doccure-inner-row-overflow-' . $inner_row_overflow;
}
if ( isset( $inner_row_shadow ) && ! empty( $inner_row_shadow ) ) {
	$css_classes[] = 'doccure-inner-row-' . $inner_row_shadow;
}
if ( isset( $inner_row_hover_shadow ) && ! empty( $inner_row_hover_shadow ) ) {
	$css_classes[] = 'doccure-inner-row-hover-' . $inner_row_hover_shadow;
}
if ( ! empty( $equal_height ) ) {
	$flex_row      = true;
	$css_classes[] = 'vc_row-o-equal-height';
}
if ( ! empty( $atts['rtl_reverse'] ) ) {
	$css_classes[] = 'vc_rtl-columns-reverse';
}
if ( ! empty( $content_placement ) ) {
	$flex_row      = true;
	$css_classes[] = 'vc_row-o-content-' . $content_placement;
}
if ( ! empty( $flex_row ) ) {
	$css_classes[] = 'vc_row-flex';
}
$wrapper_attributes = array();
// build attributes for wrapper
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( array_unique( $css_classes ) ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';
$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';
$output .= wpb_js_remove_wpautop( $content );
$output .= '</div>';
$output .= $doccurecore_generate_css;
$output .= $after_output;
return $output;
