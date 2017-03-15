<?php

class ISeek_Bootstrap_Taxonomy_Walker extends Walker_Category {

	/**
	 * What the class handles.
	 *
	 * @since 2.1.0
	 * @access public
	 * @var string
	 *
	 * @see Walker::$tree_type
	 */
	public $tree_type = 'taxonomy';

	/**
	 * Starts the list before the elements are added.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string $output Used to append additional content. Passed by reference.
	 * @param int    $depth  Optional. Depth of category. Used for tab indentation. Default 0.
	 * @param array  $args   Optional. An array of arguments. Will only append content if style argument
	 *                       value is 'nav'. See wp_list_categories(). Default empty array.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'nav' != $args['style'] )
			return;

		// Get the ID of the current term and use it to generate the ID of the children group
																						/*$output .= "<pre>";
																						ob_start();
																						var_dump($args);
																						$result = ob_get_clean();
																						$output .= $result;
																						$output .= "</pre>";*/

		$indent = str_repeat("\t", $depth);

		$output .= "$indent<div class='children collapse-wrapper collapse'><div class='list-group'>\n";
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @see Walker::end_lvl()
	 *
	 * @param string $output Used to append additional content. Passed by reference.
	 * @param int    $depth  Optional. Depth of category. Used for tab indentation. Default 0.
	 * @param array  $args   Optional. An array of arguments. Will only append content if style argument
	 *                       value is 'nav'. See wp_list_categories(). Default empty array.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'nav' != $args['style'] )
			return;

		$indent = str_repeat("\t", $depth);
		$output .= "$indent</div>\n";
	}

	/**
	 * Starts the element output.
	 *
	 * @since 2.1.0wp_list_categories
	 * @access public
	 *
	 * @see Walker::start_el()
	 *
	 * @param string $output   Passed by reference. Used to append additional content.
	 * @param object $category Category data object.
	 * @param int    $depth    Optional. Depth of category in reference to parents. Default 0.
	 * @param array  $args     Optional. An array of arguments. See wp_list_categories(). Default empty array.
	 * @param int    $id       Optional. ID of the current category. Default 0.
	 */
  public function start_el(&$output, $category, $depth, $args) {
    /** This filter is documented in wp-includes/category-template.php */
		$cat_name = apply_filters(
			'list_cats',
			esc_attr( $category->name ),
			$category
		);

		// check if the taxonomy has children
		$children = get_term_children($category->term_id, $category->taxonomy);

		// Don't generate an element if the category name is empty.
		if ( ! $cat_name ) {
			return;
		}

		$link = '<a class="list-group-link" href="' . esc_url( get_term_link( $category ) ) . '" ';
		if ( $args['use_desc_for_title'] && ! empty( $category->description ) ) {
			/**
			 * Filters the category description for display.
			 *
			 * @since 1.2.0
			 *
			 * @param string $description Category description.
			 * @param object $category    Category object.
			 */
			$link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
		}

		$link .= '>';
		$link .= $cat_name . '</a>';

		// Toggle for the children group
		// The ID of the children is added in widget.js
		if( $children ){
			$link .= "<button class='navbar-toggler children-list-toggler pull-xs-right collapsed' type='button' data-toggle='collapse' data-target='#taxonomyCollapse" . $category->term_id . "' aria-controls='taxonomyCollapse" . $category->term_id . "' aria-expanded='false' aria-label='Toggle navigation'>";
			$link .= "<span class='fa fa-angle-up'></span></button>";
		}

		if ( ! empty( $args['show_count'] ) ) {
			$link .= ' (' . number_format_i18n( $category->count ) . ')';
		}
		if ( 'nav' == $args['style'] ) {
			$output .= "\t<div";
			$css_classes = array(
				'list-group-item',
				'cat-item',
				'cat-item-' . $category->term_id,
			);

			if ( ! empty( $args['current_category'] ) ) {
				// 'current_category' can be an array, so we use `get_terms()`.
				$_current_terms = get_terms( $category->taxonomy, array(
					'include' => $args['current_category'],
					'hide_empty' => false,
				) );

				foreach ( $_current_terms as $_current_term ) {
					if ( $category->term_id == $_current_term->term_id ) {
						$css_classes[] = 'current-cat';
					} elseif ( $category->term_id == $_current_term->parent ) {
						$css_classes[] = 'current-cat-parent';
					}
					while ( $_current_term->parent ) {
						if ( $category->term_id == $_current_term->parent ) {
							$css_classes[] =  'current-cat-ancestor';
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

			$output .=  ' class="' . $css_classes . '"';
			$output .= ">$link\n";
		} elseif ( isset( $args['separator'] ) ) {
			$output .= "\t$link" . $args['separator'] . "\n";
		} else {
			$output .= "\t$link<br />\n";
		}
  }

  /**
	 * Ends the element output, if needed.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @see Walker::end_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $page   Not used.
	 * @param int    $depth  Optional. Depth of category. Not used.
	 * @param array  $args   Optional. An array of arguments. Only uses 'nav' for whether should append
	 *                       to output. See wp_list_categories(). Default empty array.
	 */
	public function end_el( &$output, $category, $depth = 0, $args = array() ) {
		if ( 'nav' != $args['style'] )
			return;

		$output .= "</div><!-- end_el-lvl". $depth ." -->\n";

		$children = get_term_children($category->term_id, $category->taxonomy);

		if( $children )
			$output .= "</div><!-- end_children -->";

	}
}
