<?php
/**
 *
 * All wolker classed would be in this file
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfoliot
 * @since 1.0
 */
if( !class_exists('doccure_Walker_Location_Dropdown') ){
	
	class doccure_Walker_Location_Dropdown extends Walker_CategoryDropdown {
		/**
     * @see Walker::$tree_type
     * @since 2.1.0
     * @var string
     */
    var $tree_type = 'category';

    /**
     * @see Walker::$db_fields
     * @since 2.1.0
     * @todo Decouple this
     * @var array
     */
    var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

    /**
     * Start the element output.
     *
     * @see Walker::start_el()
     * @since 2.1.0
     *
     * @param string $output   Passed by reference. Used to append additional content.
     * @param object $category Category data object.
     * @param int    $depth    Depth of category. Used for padding.
     * @param array  $args     Uses 'selected' and 'show_count' keys, if they exist. @see wp_dropdown_categories()
     */
    function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
        $pad = str_repeat('-&nbsp;', $depth * 1);
        /** This filter is documented in wp-includes/category-template.php */
        $cat_name = apply_filters( 'list_cats', $category->name, $category );
        $cat_permalink = get_term_link( $category );
		
		//get location flag
		$logo 			= get_term_meta( $category->term_id, 'logo', true );
		$country		= !empty( $logo['attachment_id'] ) ? wp_get_attachment_image_src($logo['attachment_id'],array(20,20)) : '';
		$country		= !empty( $country[0] ) ? $country[0] : '';
		$flag	= '';
		$class  = ''; 
		
		if( !empty( $country ) ){
			$flag	= $country;
			$class  = 'option-with-flag'; 
		}

        $output .= "\t<option data-flag='$flag'  class=\"$class level-$depth\" value=\"".$category->slug."\"";
        if ( !empty( $args['selected'] ) && $category->term_id === $args['selected'] )
            $output .= ' selected="selected"';
        $output .= '>';
        $output .= $pad.$cat_name;
		
        if ( $args['show_count'] )
            $output .= '&nbsp;&nbsp;('. number_format_i18n( $category->count ) .')';
			$output .= "</option>\n";
		}
	}
}