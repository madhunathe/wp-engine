<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Ovveride vc_column_inner template in theme.
 *
 * @package doccure
 * @since $version
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$atts                = vc_map_get_attributes( $this->getShortcode(), $atts );
$el_class            = isset( $atts['el_class'] ) ? $atts['el_class'] : '';
$width               = isset( $atts['width'] ) ? $atts['width'] : '';
$el_id               = isset( $atts['el_id'] ) ? $atts['el_id'] : '';
$css                 = isset( $atts['css'] ) ? $atts['css'] : '';
$offset              = isset( $atts['offset'] ) ? $atts['offset'] : '';
$responsive_css = isset( $atts['responsive_css'] ) ? $atts['responsive_css'] : '';
$bg_color_scheme     = isset( $atts['bg_color_scheme'] ) ? $atts['bg_color_scheme'] : '';
$bg_position         = isset( $atts['bg_position'] ) ? $atts['bg_position'] : '';
$title_color_scheme  = isset( $atts['title_color_scheme'] ) ? $atts['title_color_scheme'] : '';
$inner_column_position  = isset( $atts['inner_column_position'] ) ? $atts['inner_column_position'] : '';
$inner_column_z_index  = isset( $atts['inner_column_z_index'] ) ? $atts['inner_column_z_index'] : '';
$inner_column_overflow  = isset( $atts['inner_column_overflow'] ) ? $atts['inner_column_overflow'] : '';
$column_inner_shadow  = isset( $atts['column_inner_shadow'] ) ? $atts['column_inner_shadow'] : '';
$column_inner_hover_shadow  = isset( $atts['column_inner_hover_shadow'] ) ? $atts['column_inner_hover_shadow'] : '';
// Responsive editor css
if(function_exists('doccurecore_get_responsive_style')) {
	$responsive_id = uniqid('doccurecore-vc-inner-column-');
	$responsive_editor_css = doccurecore_get_responsive_style( $responsive_css, $responsive_id );
	$doccurecore_generate_css = isset($responsive_editor_css) && !empty($responsive_editor_css) ? '<style>' . $responsive_editor_css . '</style>' : '';
}
$output              = '';
$width = wpb_translateColumnWidthToSpan( $width );
$width = vc_column_offset_class_merge( $offset, $width );
$css_classes = array(
	$this->getExtraClass( $el_class ),
	'wpb_column',
	'vc_column_container',
	$width,
);
if ( vc_shortcode_custom_css_has_property( $css, array( 'border', 'background' ) ) ) {
	$css_classes[] = 'vc_col-has-fill';
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
if ( isset( $inner_column_position ) && ! empty( $inner_column_position ) ) {
	$css_classes[] = 'doccure-column-inner-position-' . $inner_column_position;
}
if ( isset( $inner_column_z_index ) && ! empty( $inner_column_z_index ) ) {
	$css_classes[] = 'doccure-column-inner-z-index-' . $inner_column_z_index;
}
if ( isset( $inner_column_overflow ) && ! empty( $inner_column_overflow ) ) {
	$css_classes[] = 'doccure-column-inner-overflow-' . $inner_column_overflow;
}
if ( isset( $column_inner_shadow ) && ! empty( $column_inner_shadow ) ) {
	$css_classes[] = 'doccure-inner-column-' . $column_inner_shadow;
}
if ( isset( $column_inner_hover_shadow ) && ! empty( $column_inner_hover_shadow ) ) {
	$css_classes[] = 'doccure-inner-column-hover-' . $column_inner_hover_shadow;
}

$wrapper_attributes = array();
$css_class          = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $css_classes ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
$output            .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';
$inner_column_class = 'vc_column-inner ' . esc_attr( trim( vc_shortcode_custom_css_class( $css ) ) );
if(isset($responsive_id) && !empty($responsive_id)) {
	$inner_column_class .= ' '.$responsive_id;
}
if ( isset( $responsive_css ) && ! empty( $responsive_css ) ) {
	$inner_column_class .= ' ' . esc_attr( trim( vc_shortcode_custom_css_class( $responsive_css ) ) );
}
$output .= '<div class="' . trim( $inner_column_class ) . '">';
$output .= '<div class="wpb_wrapper">';
$output .= wpb_js_remove_wpautop( $content );
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';
$output .= $doccurecore_generate_css;
return $output;
