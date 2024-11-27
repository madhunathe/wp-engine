<?php

/**

 * Taxonomy API: Walker_Category class

 *

 * @package WordPress

 * @subpackage Template

 * @since 4.4.0

 */



/**

 * Core class used to create an HTML list of categories.

 *

 * @since 2.1.0

 *

 * @see Walker

 */

class doccure_Walker_Category extends Walker_Category {



	/**

	 * Starts the element output.

	 *

	 * @since 2.1.0

	 *

	 * @see Walker::start_el()

	 *

	 * @param string $output   Used to append additional content (passed by reference).

	 * @param object $category Category data object.

	 * @param int    $depth    Optional. Depth of category in reference to parents. Default 0.

	 * @param array  $args     Optional. An array of arguments. See wp_list_categories(). Default empty array.

	 * @param int    $id       Optional. ID of the current category. Default 0.

	 */

	public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {

		/** This filter is documented in wp-includes/category-template.php */

		$cat_name = apply_filters( 'list_cats', esc_attr( $category->name ), $category );



		// Don't generate an element if the category name is empty.

		if ( '' === $cat_name ) {

			return;

		}



		$atts         = array();

		$atts['href'] = get_term_link( $category );



		if ( $args['use_desc_for_title'] && ! empty( $category->description ) ) {

			/**

			 * Filters the category description for display.

			 *

			 * @since 1.2.0

			 *

			 * @param string $description Category description.

			 * @param object $category    Category object.

			 */

			$atts['title'] = strip_tags( apply_filters( 'category_description', $category->description, $category ) );

		}



		/**

		 * Filters the HTML attributes applied to a category list item's anchor element.

		 *

		 * @since 5.2.0

		 *

		 * @param array   $atts {

		 *     The HTML attributes applied to the list item's `<a>` element, empty strings are ignored.

		 *

		 *     @type string $href  The href attribute.

		 *     @type string $title The title attribute.

		 * }

		 * @param WP_Term $category Term data object.

		 * @param int     $depth    Depth of category, used for padding.

		 * @param array   $args     An array of arguments.

		 * @param int     $id       ID of the current category.

		 */

		$atts = apply_filters( 'category_list_link_attributes', $atts, $category, $depth, $args, $id );



		$attributes = '';

		foreach ( $atts as $attr => $value ) {

			if ( is_scalar( $value ) && '' !== $value && false !== $value ) {

				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );

				$attributes .= ' ' . $attr . '="' . $value . '"';

			}

		}



		$link = sprintf(

			'<a%s>%s</a>',

			$attributes,

			$cat_name

		);



		if ( ! empty( $args['feed_image'] ) || ! empty( $args['feed'] ) ) {

			$link .= ' ';



			if ( empty( $args['feed_image'] ) ) {

				$link .= '(';

			}



			$link .= '<a href="' . esc_url( get_term_feed_link( $category->term_id, $category->taxonomy, $args['feed_type'] ) ) . '"';



			if ( empty( $args['feed'] ) ) {

				/* translators: %s: Category name. */

				$alt = ' alt="' . sprintf( esc_html__( 'Feed for all posts filed under %s', 'doccure' ), $cat_name ) . '"';

			} else {

				$alt   = ' alt="' . $args['feed'] . '"';

				$name  = $args['feed'];

				$link .= empty( $args['title'] ) ? '' : $args['title'];

			}



			$link .= '>';



			if ( empty( $args['feed_image'] ) ) {

				$link .= $name;

			} else {

				$link .= "<img src='" . esc_url( $args['feed_image'] ) . "'$alt" . ' />';

			}

			$link .= '</a>';



			if ( empty( $args['feed_image'] ) ) {

				$link .= ')';

			}

		}



		if ( ! empty( $args['show_count'] ) ) {

			$link .= '<span>' . number_format_i18n( $category->count ) . '</span>';

		}

		if ( 'list' === $args['style'] ) {

			$output     .= "\t<li";

			$css_classes = array(

				'cat-item',

				'cat-item-' . $category->term_id,

			);



			if ( ! empty( $args['current_category'] ) ) {

				// 'current_category' can be an array, so we use `get_terms()`.

				$_current_terms = get_terms(

					array(

						'taxonomy'   => $category->taxonomy,

						'include'    => $args['current_category'],

						'hide_empty' => false,

					)

				);



				foreach ( $_current_terms as $_current_term ) {

					if ( (int) $category->term_id === (int) $_current_term->term_id ) {

						$css_classes[] = 'current-cat';

						$link          = str_replace( '<a', '<a aria-current="page"', $link );

					} elseif ( (int) $category->term_id === (int) $_current_term->parent ) {

						$css_classes[] = 'current-cat-parent';

					}

					while ( $_current_term->parent ) {

						if ( (int) $category->term_id === (int) $_current_term->parent ) {

							$css_classes[] = 'current-cat-ancestor';

							break;

						}

						$_current_term = get_term( $_current_term->parent, $category->taxonomy );

					}

				}

			}



			/**

			 * Filters the list of CSS classes to include with each category in the list.

			 *

			 * @since 4.2.0

			 *

			 * @see wp_list_categories()

			 *

			 * @param array  $css_classes An array of CSS classes to be applied to each list item.

			 * @param object $category    Category data object.

			 * @param int    $depth       Depth of page, used for padding.

			 * @param array  $args        An array of wp_list_categories() arguments.

			 */

			$css_classes = implode( ' ', apply_filters( 'category_css_class', $css_classes, $category, $depth, $args ) );

			$css_classes = $css_classes ? ' class="' . esc_attr( $css_classes ) . '"' : '';



			$output .= $css_classes;

			$output .= ">$link\n";

		} elseif ( isset( $args['separator'] ) ) {

			$output .= "\t$link" . $args['separator'] . "\n";

		} else {

			$output .= "\t$link<br />\n";

		}

	}

}

