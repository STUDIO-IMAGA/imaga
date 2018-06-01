<?php

namespace IMAGA\Theme\Navigation;

use Walker_Nav_Menu;

/**
 * WP Bootstrap Navwalker
 *
 * @package WP-Bootstrap-Navwalker
 */

/*
 * Class Name: WP_Bootstrap_Navwalker
 * Plugin Name: WP Bootstrap Navwalker
 * Plugin URI:  https://github.com/wp-bootstrap/wp-bootstrap-navwalker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 4 navigation style in a custom theme using the WordPress built in menu manager.
 * Author: Edward McIntyre - @twittem, WP Bootstrap, William Patton - @pattonwebz
 * Version: 4.1.0
 * Author URI: https://github.com/wp-bootstrap
 * GitHub Plugin URI: https://github.com/wp-bootstrap/wp-bootstrap-navwalker
 * GitHub Branch: master
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
*/

/* Check if Class Exists. */
if ( ! class_exists( 'WP_Bootstrap_Navwalker' ) ) {
	/**
	 * WP_Bootstrap_Navwalker class.
	 *
	 * @extends Walker_Nav_Menu
	 */
	class WP_Bootstrap_Navwalker extends Walker_Nav_Menu {

		/**
		 * Starts the list before the elements are added.
		 *
		 * @since WP 3.0.0
		 *
		 * @see Walker_Nav_Menu::start_lvl()
		 *
		 * @param string   $output Used to append additional content (passed by reference).
		 * @param int      $depth  Depth of menu item. Used for padding.
		 * @param stdClass $args   An object of wp_nav_menu() arguments.
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}
			$indent = str_repeat( $t, $depth );
			// Default class to add to the file.
			$classes = array( 'dropdown-menu' );
			/**
			 * Filters the CSS class(es) applied to a menu list element.
			 *
			 * @since WP 4.8.0
			 *
			 * @param array    $classes The CSS classes that are applied to the menu `<ul>` element.
			 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
			 * @param int      $depth   Depth of menu item. Used for padding.
			 */
			$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
			/**
			 * The `.dropdown-menu` container needs to have a labelledby
			 * attribute which points to it's trigger link.
			 *
			 * Form a string for the labelledby attribute from the the latest
			 * link with an id that was added to the $output.
			 */
			$labelledby = '';
			// find all links with an id in the output.
			preg_match_all( '/(<a.*?id=\"|\')(.*?)\"|\'.*?>/im', $output, $matches );
			// with pointer at end of array check if we got an ID match.
			if ( end( $matches[2] ) ) {
				// build a string to use as aria-labelledby.
				$labelledby = 'aria-labelledby="' . end( $matches[2] ) . '"';
			}
			$output .= "{$n}{$indent}<ul$class_names $labelledby role=\"menu\">{$n}";
		}

		/**
		 * Starts the element output.
		 *
		 * @since WP 3.0.0
		 * @since WP 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
		 *
		 * @see Walker_Nav_Menu::start_el()
		 *
		 * @param string   $output Used to append additional content (passed by reference).
		 * @param WP_Post  $item   Menu item data object.
		 * @param int      $depth  Depth of menu item. Used for padding.
		 * @param stdClass $args   An object of wp_nav_menu() arguments.
		 * @param int      $id     Current item ID.
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}
			$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

			$classes = empty( $item->classes ) ? array() : (array) $item->classes;

			// Initialize some holder variables to store specially handled item
			// wrappers and icons.
			$linkmod_classes = array();
			$icon_classes    = array();

			/**
			 * Get an updated $classes array without linkmod or icon classes.
			 *
			 * NOTE: linkmod and icon class arrays are passed by reference and
			 * are maybe modified before being used later in this function.
			 */
			$classes = self::seporate_linkmods_and_icons_from_classes( $classes, $linkmod_classes, $icon_classes, $depth );

			// Join any icon classes plucked from $classes into a string.
			$icon_class_string = join( ' ', $icon_classes );

			/**
			 * Filters the arguments for a single nav menu item.
			 *
			 *  WP 4.4.0
			 *
			 * @param stdClass $args  An object of wp_nav_menu() arguments.
			 * @param WP_Post  $item  Menu item data object.
			 * @param int      $depth Depth of menu item. Used for padding.
			 */
			$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

			// Add .dropdown or .active classes where they are needed.
			if ( isset( $args->has_children ) && $args->has_children ) {
				$classes[] = 'dropdown';
			}
			if ( in_array( 'current-menu-item', $classes, true ) || in_array( 'current-menu-parent', $classes, true ) ) {
				$classes[] = 'active';
			}

			// Add some additional default classes to the item.
			$classes[] = 'menu-item-' . $item->ID;
			$classes[] = 'nav-item';

			// Allow filtering the classes.
			$classes = apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth );

			// Form a string of classes in format: class="class_names".
			$class_names = join( ' ', $classes );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			/**
			 * Filters the ID applied to a menu item's list item element.
			 *
			 * @since WP 3.0.1
			 * @since WP 4.1.0 The `$depth` parameter was added.
			 *
			 * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
			 * @param WP_Post  $item    The current menu item.
			 * @param stdClass $args    An object of wp_nav_menu() arguments.
			 * @param int      $depth   Depth of menu item. Used for padding.
			 */
			$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement"' . $id . $class_names . '>';

			// initialize array for holding the $atts for the link item.
			$atts = array();

			// Set title from item to the $atts array - if title is empty then
			// default to item title.
			if ( empty( $item->attr_title ) ) {
				$atts['title'] = ! empty( $item->title ) ? strip_tags( $item->title ) : '';
			} else {
				$atts['title'] = $item->attr_title;
			}

			$atts['target'] = ! empty( $item->target ) ? $item->target : '';
			$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
			// If item has_children add atts to <a>.
			if ( isset( $args->has_children ) && $args->has_children && 0 === $depth && $args->depth > 1 ) {
				$atts['href']          = '#';
				$atts['data-toggle']   = 'dropdown';
				$atts['aria-haspopup'] = 'true';
				$atts['aria-expanded'] = 'false';
				$atts['class']         = 'dropdown-toggle nav-link';
				$atts['id']            = 'menu-item-dropdown-' . $item->ID;
			} else {
				$atts['href'] = ! empty( $item->url ) ? $item->url : '#';
				// Items in dropdowns use .dropdown-item instead of .nav-link.
				if ( $depth > 0 ) {
					$atts['class'] = 'dropdown-item';
				} else {
					$atts['class'] = 'nav-link';
				}
			}

			// update atts of this item based on any custom linkmod classes.
			$atts = self::update_atts_for_linkmod_type( $atts, $linkmod_classes );
			// Allow filtering of the $atts array before using it.
			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

			// Build a string of html containing all the atts for the item.
			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			/**
			 * Set a typeflag to easily test if this is a linkmod or not.
			 */
			$linkmod_type = self::get_linkmod_type( $linkmod_classes );

			/**
			 * START appending the internal item contents to the output.
			 */
			$item_output = isset( $args->before ) ? $args->before : '';
			/**
			 * This is the start of the internal nav item. Depending on what
			 * kind of linkmod we have we may need different wrapper elements.
			 */
			if ( '' !== $linkmod_type ) {
				// is linkmod, output the required element opener.
				$item_output .= self::linkmod_element_open( $linkmod_type, $attributes );
			} else {
				// With no link mod type set this must be a standard <a> tag.
				$item_output .= '<a' . $attributes . '>';
			}

			/**
			 * Initiate empty icon var, then if we have a string containing any
			 * icon classes form the icon markup with an <i> element. This is
			 * output inside of the item before the $title (the link text).
			 */
			$icon_html = '';
			if ( ! empty( $icon_class_string ) ) {
				// append an <i> with the icon classes to what is output before links.
				$icon_html = '<i class="' . esc_attr( $icon_class_string ) . '" aria-hidden="true"></i> ';
			}

			/** This filter is documented in wp-includes/post-template.php */
			$title = apply_filters( 'the_title', $item->title, $item->ID );

			/**
			 * Filters a menu item's title.
			 *
			 * @since WP 4.4.0
			 *
			 * @param string   $title The menu item's title.
			 * @param WP_Post  $item  The current menu item.
			 * @param stdClass $args  An object of wp_nav_menu() arguments.
			 * @param int      $depth Depth of menu item. Used for padding.
			 */
			$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

			/**
			 * If the .sr-only class was set apply to the nav items text only.
			 */
			if ( in_array( 'sr-only', $linkmod_classes, true ) ) {
				$title         = self::wrap_for_screen_reader( $title );
				$keys_to_unset = array_keys( $linkmod_classes, 'sr-only' );
				foreach ( $keys_to_unset as $k ) {
					unset( $linkmod_classes[ $k ] );
				}
			}

			// Put the item contents into $output.
			$item_output .= isset( $args->link_before ) ? $args->link_before . $icon_html . $title . $args->link_after : '';
			/**
			 * This is the end of the internal nav item. We need to close the
			 * correct element depending on the type of link or link mod.
			 */
			if ( '' !== $linkmod_type ) {
				// is linkmod, output the required element opener.
				$item_output .= self::linkmod_element_close( $linkmod_type, $attributes );
			} else {
				// With no link mod type set this must be a standard <a> tag.
				$item_output .= '</a>';
			}

			$item_output .= isset( $args->after ) ? $args->after : '';

			/**
			 * END appending the internal item contents to the output.
			 */
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

		}

		/**
		 * Traverse elements to create list from elements.
		 *
		 * Display one element if the element doesn't have any children otherwise,
		 * display the element and its children. Will only traverse up to the max
		 * depth and no ignore elements under that depth. It is possible to set the
		 * max depth to include all depths, see walk() method.
		 *
		 * This method should not be called directly, use the walk() method instead.
		 *
		 * @since WP 2.5.0
		 *
		 * @see Walker::start_lvl()
		 *
		 * @param object $element           Data object.
		 * @param array  $children_elements List of elements to continue traversing (passed by reference).
		 * @param int    $max_depth         Max depth to traverse.
		 * @param int    $depth             Depth of current element.
		 * @param array  $args              An array of arguments.
		 * @param string $output            Used to append additional content (passed by reference).
		 */
		public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
			if ( ! $element ) {
				return; }
			$id_field = $this->db_fields['id'];
			// Display this element.
			if ( is_object( $args[0] ) ) {
				$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] ); }
			parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		}

		/**
		 * Menu Fallback
		 * =============
		 * If this function is assigned to the wp_nav_menu's fallback_cb variable
		 * and a menu has not been assigned to the theme location in the WordPress
		 * menu manager the function with display nothing to a non-logged in user,
		 * and will add a link to the WordPress menu manager if logged in as an admin.
		 *
		 * @param array $args passed from the wp_nav_menu function.
		 */
		public static function fallback( $args ) {
			if ( current_user_can( 'edit_theme_options' ) ) {

				/* Get Arguments. */
				$container       = $args['container'];
				$container_id    = $args['container_id'];
				$container_class = $args['container_class'];
				$menu_class      = $args['menu_class'];
				$menu_id         = $args['menu_id'];

				// initialize var to store fallback html.
				$fallback_output = '';

				if ( $container ) {
					$fallback_output .= '<' . esc_attr( $container );
					if ( $container_id ) {
						$fallback_output .= ' id="' . esc_attr( $container_id ) . '"';
					}
					if ( $container_class ) {
						$fallback_output .= ' class="' . esc_attr( $container_class ) . '"';
					}
					$fallback_output .= '>';
				}
				$fallback_output .= '<ul';
				if ( $menu_id ) {
					$fallback_output .= ' id="' . esc_attr( $menu_id ) . '"'; }
				if ( $menu_class ) {
					$fallback_output .= ' class="' . esc_attr( $menu_class ) . '"'; }
				$fallback_output .= '>';
				$fallback_output .= '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '" title="' . esc_attr__( 'Add a menu', 'wp-bootstrap-navwalker' ) . '">' . esc_html__( 'Add a menu', 'wp-bootstrap-navwalker' ) . '</a></li>';
				$fallback_output .= '</ul>';
				if ( $container ) {
					$fallback_output .= '</' . esc_attr( $container ) . '>';
				}

				// if $args has 'echo' key and it's true echo, otherwise return.
				if ( array_key_exists( 'echo', $args ) && $args['echo'] ) {
					echo $fallback_output; // WPCS: XSS OK.
				} else {
					return $fallback_output;
				}
			}
		}

		/**
		 * Find any custom linkmod or icon classes and store in their holder
		 * arrays then remove them from the main classes array.
		 *
		 * Supported linkmods: .disabled, .dropdown-header, .dropdown-divider, .sr-only
		 * Supported iconsets: Font Awesome 4/5, Glypicons
		 *
		 * NOTE: This accepts the linkmod and icon arrays by reference.
		 *
		 * @since 4.0.0
		 *
		 * @param array   $classes         an array of classes currently assigned to the item.
		 * @param array   $linkmod_classes an array to hold linkmod classes.
		 * @param array   $icon_classes    an array to hold icon classes.
		 * @param integer $depth           an integer holding current depth level.
		 *
		 * @return array  $classes         a maybe modified array of classnames.
		 */
		private function seporate_linkmods_and_icons_from_classes( $classes, &$linkmod_classes, &$icon_classes, $depth ) {
			// Loop through $classes array to find linkmod or icon classes.
			foreach ( $classes as $key => $class ) {
				// If any special classes are found, store the class in it's
				// holder array and and unset the item from $classes.
				if ( preg_match( '/^disabled|^sr-only/i', $class ) ) {
					// Test for .disabled or .sr-only classes.
					$linkmod_classes[] = $class;
					unset( $classes[ $key ] );
				} elseif ( preg_match( '/^dropdown-header|^dropdown-divider|^dropdown-item-text/i', $class ) && $depth > 0 ) {
					// Test for .dropdown-header or .dropdown-divider and a
					// depth greater than 0 - IE inside a dropdown.
					$linkmod_classes[] = $class;
					unset( $classes[ $key ] );
				} elseif ( preg_match( '/^fa-(\S*)?|^fa(s|r|l|b)?(\s?)?$/i', $class ) ) {
					// Font Awesome.
					$icon_classes[] = $class;
					unset( $classes[ $key ] );
				} elseif ( preg_match( '/^glyphicon-(\S*)?|^glyphicon(\s?)$/i', $class ) ) {
					// Glyphicons.
					$icon_classes[] = $class;
					unset( $classes[ $key ] );
				}
			}

			return $classes;
		}

		/**
		 * Return a string containing a linkmod type and update $atts array
		 * accordingly depending on the decided.
		 *
		 * @since 4.0.0
		 *
		 * @param array $linkmod_classes array of any link modifier classes.
		 *
		 * @return string                empty for default, a linkmod type string otherwise.
		 */
		private function get_linkmod_type( $linkmod_classes = array() ) {
			$linkmod_type = '';
			// Loop through array of linkmod classes to handle their $atts.
			if ( ! empty( $linkmod_classes ) ) {
				foreach ( $linkmod_classes as $link_class ) {
					if ( ! empty( $link_class ) ) {

						// check for special class types and set a flag for them.
						if ( 'dropdown-header' === $link_class ) {
							$linkmod_type = 'dropdown-header';
						} elseif ( 'dropdown-divider' === $link_class ) {
							$linkmod_type = 'dropdown-divider';
						} elseif ( 'dropdown-item-text' === $link_class ) {
							$linkmod_type = 'dropdown-item-text';
						}
					}
				}
			}
			return $linkmod_type;
		}

		/**
		 * Update the attributes of a nav item depending on the limkmod classes.
		 *
		 * @since 4.0.0
		 *
		 * @param array $atts            array of atts for the current link in nav item.
		 * @param array $linkmod_classes an array of classes that modify link or nav item behaviors or displays.
		 *
		 * @return array                 maybe updated array of attributes for item.
		 */
		private function update_atts_for_linkmod_type( $atts = array(), $linkmod_classes = array() ) {
			if ( ! empty( $linkmod_classes ) ) {
				foreach ( $linkmod_classes as $link_class ) {
					if ( ! empty( $link_class ) ) {
						// update $atts with a space and the extra classname...
						// so long as it's not a sr-only class.
						if ( 'sr-only' !== $link_class ) {
							$atts['class'] .= ' ' . esc_attr( $link_class );
						}
						// check for special class types we need additional handling for.
						if ( 'disabled' === $link_class ) {
							// Convert link to '#' and unset open targets.
							$atts['href'] = '#';
							unset( $atts['target'] );
						} elseif ( 'dropdown-header' === $link_class || 'dropdown-divider' === $link_class || 'dropdown-item-text' === $link_class ) {
							// Store a type flag and unset href and target.
							unset( $atts['href'] );
							unset( $atts['target'] );
						}
					}
				}
			}
			return $atts;
		}

		/**
		 * Wraps the passed text in a screen reader only class.
		 *
		 * @since 4.0.0
		 *
		 * @param string $text the string of text to be wrapped in a screen reader class.
		 * @return string      the string wrapped in a span with the class.
		 */
		private function wrap_for_screen_reader( $text = '' ) {
			if ( $text ) {
				$text = '<span class="sr-only">' . $text . '</span>';
			}
			return $text;
		}

		/**
		 * Returns the correct opening element and attributes for a linkmod.
		 *
		 * @since 4.0.0
		 *
		 * @param string $linkmod_type a sting containing a linkmod type flag.
		 * @param string $attributes   a string of attributes to add to the element.
		 *
		 * @return string              a string with the openign tag for the element with attribibutes added.
		 */
		private function linkmod_element_open( $linkmod_type, $attributes = '' ) {
			$output = '';
			if ( 'dropdown-item-text' === $linkmod_type ) {
				$output .= '<span class="dropdown-item-text"' . $attributes . '>';
			} elseif ( 'dropdown-header' === $linkmod_type ) {
				// For a header use a span with the .h6 class instead of a real
				// header tag so that it doesn't confuse screen readers.
				$output .= '<span class="dropdown-header h6"' . $attributes . '>';
			} elseif ( 'dropdown-divider' === $linkmod_type ) {
				// this is a divider.
				$output .= '<div class="dropdown-divider"' . $attributes . '>';
			}
			return $output;
		}

		/**
		 * Return the correct closing tag for the linkmod element.
		 *
		 * @since 4.0.0
		 *
		 * @param string $linkmod_type a string containing a special linkmod type.
		 *
		 * @return string              a string with the closing tag for this linkmod type.
		 */
		private function linkmod_element_close( $linkmod_type ) {
			$output = '';
			if ( 'dropdown-header' === $linkmod_type || 'dropdown-item-text' === $linkmod_type ) {
				// For a header use a span with the .h6 class instead of a real
				// header tag so that it doesn't confuse screen readers.
				$output .= '</span>';
			} elseif ( 'dropdown-divider' === $linkmod_type ) {
				// this is a divider.
				$output .= '</div>';
			}
			return $output;
		}
	}
}

/**
 * =======================================
 * WP Nav Plus
 * =======================================
 *
 *
 * @author Matt Keys <matt@mattkeys.me>
 */

class WP_Nav_Plus_Start_Depth
{

	private $menu_ids_info  	= array();
	private $menu_objects		= array();
	private $custom_menu_items	= array();
	private $current_object;

	public function init()
	{
		add_filter( 'wp_nav_menu_args', array( $this, 'current_nav_args' ), 10, 1 );
		add_filter( 'wp_get_nav_menu_items', array( $this, 'filter_nav_menu_items' ), 10, 3 );
		add_filter( 'wp_nav_plus_find_children', array( $this, 'find_children' ), 10, 4 );
	}

	public function current_nav_args( $args )
	{
		global $wp_nav_plus_start_depth_options;

		$wp_nav_plus_start_depth_options = array(
			'start_depth' 		=> false,
			'default_category'	=> false
		);

		if ( isset( $args['start_depth'] ) && ( 0 < (int) $args['start_depth'] ) ) {
			$wp_nav_plus_start_depth_options['start_depth'] = (int) $args['start_depth'];

			if ( ! isset( $args['fallback_cb'] ) || 'wp_page_menu' == $args['fallback_cb'] ) {
				$args['fallback_cb'] = 'WP_Nav_Plus_Start_Depth::fallback';
			}
		}

		if ( isset( $args['default_category'] ) && is_numeric( $args['default_category'] ) ) {
			$wp_nav_plus_start_depth_options['default_category'] = (int) $args['default_category'];
		}

		return $args;
	}

	public function filter_nav_menu_items( $items, $menu, $args )
	{
		global $wp_nav_plus_start_depth_options;

		if ( false == $wp_nav_plus_start_depth_options['start_depth'] ) {
			return $items;
		}

		$start_depth = $wp_nav_plus_start_depth_options['start_depth'] - 1;

		$this->current_object = get_queried_object();

		if ( empty( $this->current_object ) ) {
			return array();
		}

		if ( isset( $this->current_object->ID ) ) {
			$object_id = $this->current_object->ID;
		} else if ( isset( $this->current_object->term_id ) ) {
			$object_id = $this->current_object->term_id;
		} else {
			$object_id = $this->find_cpt_archive_page_object_id( $items );
		}

		if ( is_category() ) {
			$parent_object_id 	= isset( $this->current_object->category_parent ) ? $this->current_object->category_parent : -1;
		} else if ( is_tax() ) {
			$parent_object_id 	= isset( $this->current_object->parent ) ? $this->current_object->parent : -1;
		} else {
			$parent_object_id 	= isset( $this->current_object->post_parent ) ? $this->current_object->post_parent : -1;
		}

		if ( $parent_object_id < 0 ) {
			return array();
		}

		$object_menu_id	= $this->menu_ids_info( $items, $object_id, $parent_object_id );

		if ( ! $object_menu_id && is_single() ) {
			$category_ids 	= $this->find_category_ids();
			$object_menu_id = $this->find_category_menu_id( $category_ids );
		}

		if ( ! $object_menu_id ) {
			$object_menu_id = $this->find_custom_menu_id();
		}

		if ( empty( $object_menu_id ) ) {
			return array();
		}

		$object_menu_id_array = array( $object_menu_id );

		$ancestors		= $this->find_menu_ancestors( $object_menu_id, $object_menu_id_array );
		$start_menu_id	= isset( $ancestors[ $start_depth ] ) ? $ancestors[ $start_depth ] : false;

		if ( $start_menu_id ) {
			$items = $this->find_children( $items, $start_menu_id );
		} else if ( 0 == $start_depth ) {
			$items = $this->find_children( $items, $object_menu_id );
		} else {
			$items = array();
		}

		return $items;
	}

	private function menu_ids_info( $items, $object_id, $parent_object_id )
	{
		$possible_matches = array();
		$match = false;

		foreach ( $items as $menu_item )
		{
			$this->menu_objects[ $menu_item->object_id ] 				= $menu_item->ID;
			$this->menu_ids_info[ $menu_item->ID ]['object_id'] 		= $menu_item->object_id;
			$this->menu_ids_info[ $menu_item->ID ]['parent_menu_id'] 	= $menu_item->menu_item_parent;

			if ( 'custom' === $menu_item->type || 'post_type_archive' === $menu_item->type ) {
				$custom_url = $this->normalize_url( $menu_item->url );
				$this->custom_menu_items[ $menu_item->ID ] = $custom_url;
			}

			if ( $menu_item->object_id != $object_id ) {
				continue;
			}

			if ( ( is_tax() || is_category() ) && $menu_item->object != $this->current_object->taxonomy ) {
				continue;
			}

			if ( $match ) {
				continue;
			}

			$possible_matches[] = $menu_item->ID;
			$match = $this->check_match( $menu_item, $menu_item->menu_item_parent, $parent_object_id );
		}

		if ( $match ) {
			return $match;
		}

		if ( isset( $possible_matches[0] ) ) {
			return $possible_matches[0];
		}
	}

	public function find_menu_ancestors( $last_menu_id, &$ancestors = array() )
	{
		$keep_looping = false;

		foreach ( $this->menu_ids_info as $menu_id => $info )
		{
			if ( $menu_id == $last_menu_id ) {
				if ( 0 != $info['parent_menu_id'] ) {
					$ancestors[] = $info['parent_menu_id'];
					$last_menu_id = $info['parent_menu_id'];
					$keep_looping = true;
				}
			}
		}

		if ( $keep_looping ) {
			$this->find_menu_ancestors( $last_menu_id, $ancestors );
		}

		return array_reverse( $ancestors );
	}

	private function check_match( $menu_item, $parent_menu_id, $parent_object_id )
	{
		if ( 0 == $parent_menu_id && 0 == $parent_object_id ) {
			return $menu_item->ID;
		}

		if ( isset( $this->menu_ids_info[ $parent_menu_id ]['object_id'] ) ) {
			$associated_parent_object_id = $this->menu_ids_info[ $parent_menu_id ]['object_id'];
		} else {
			$associated_parent_object_id = get_post_meta( $parent_menu_id, '_menu_item_object_id', true );
		}

		if ( $associated_parent_object_id == $parent_object_id ) {
			return $menu_item->ID;
		}

		if ( is_post_type_archive() && isset( $menu_item->object ) ) {
			$post_type = get_query_var('post_type');
			if ( $menu_item->object == $post_type ) {
				return $menu_item->ID;
			}
		}

		return false;
	}

	public function find_children( $menu_items, $parent_ID = 0, &$children = array(), &$depth = 0 )
	{
		foreach ( $menu_items as $key => $menu_item )
		{
			if ( $menu_item->menu_item_parent == $parent_ID ) {

				$menu_item->depth = $depth;

				if ( 0 == $depth ) {
					$menu_item->menu_item_parent = 0;
				}

				array_push( $children, $menu_item );
				unset( $menu_items[ $key ] );
				$oldParent = $parent_ID;
				$parent_ID = $menu_item->ID;
				$depth++;
				$this->find_children( $menu_items, $parent_ID, $children, $depth );
				$parent_ID = $oldParent;
				$depth--;
			}
		}

		return $children;
	}

	private function find_category_ids()
	{
		global $wp_nav_plus_start_depth_options;

		$possible_matches = array();

		$category_ids = get_the_category();

		if ( ! empty( $category_ids ) ) {
			foreach ( $category_ids as $category ) {
				$possible_matches[] = $category->term_id;
			}
		}

		if ( false != $wp_nav_plus_start_depth_options['default_category'] ) {
			$possible_matches[] = $wp_nav_plus_start_depth_options['default_category'];
		}

		$posts_page_id = get_option('page_for_posts');

		if ( ! empty( $posts_page_id ) ) {
			$possible_matches[] = $posts_page_id;
		}

		return $possible_matches;
	}

	private function find_category_menu_id( $category_ids )
	{
		foreach ( $category_ids as $category_id ) {
			if ( isset( $this->menu_objects[ $category_id ] ) ) {
				return $this->menu_objects[ $category_id ];
			}
		}
	}

	private function find_custom_menu_id()
	{
		global $post;
		$url = false;

		if ( is_post_type_archive() && $url = get_post_type_archive_link( get_query_var('post_type') ) ) :
		elseif ( is_single() && $this->is_custom_post_type( $post ) && $url = get_post_type_archive_link( $post->post_type ) ) :
		elseif ( is_tax() && $url = get_term_link( $this->current_object ) ) :
		else :
			$url = get_permalink();
		endif;

		$url = $this->normalize_url( $url );

		$menu_id = array_search( $url, $this->custom_menu_items );

		return $menu_id;
	}

	private function find_cpt_archive_page_object_id( $items )
	{
		$cpt_archive_url = get_post_type_archive_link( get_query_var('post_type') );

		foreach ( $items as $item ) {
			if ( $item->url == $cpt_archive_url ) {
				$this->current_object->post_parent = $item->post_parent;

				return $item->object_id;
			}
		}
	}

	private function normalize_url( $url )
	{
		$url = str_replace( site_url( '', 'http' ), '', $url );

		$url = str_replace( site_url( '', 'https' ), '', $url );

		if ( '' == $url ) {
			$url = '/';
		}

		if ( '/' != substr( $url, -1 ) ) {
			$url .= '/';
		}

		return $url;
	}

	private function is_custom_post_type( $post )
	{
		$all_custom_post_types = get_post_types( array( '_builtin' => false ) );

		if ( empty ( $all_custom_post_types ) ) {
			return false;
		}

		$custom_types      = array_keys( $all_custom_post_types );
		$current_post_type = get_post_type( $post );

		if ( ! $current_post_type ) {
			return false;
		}

		return in_array( $current_post_type, $custom_types );
	}

	static function fallback()
	{
		return false;
	}

}

add_action( 'wp', array( new WP_Nav_Plus_Start_Depth, 'init' ) );


function brand( $image_url ){

  $html = '<a class="navbar-brand" href="' .esc_url( home_url('/') ) .'">';
  $html .= '<img src="' . $image_url . '" width="180" class="d-inline-block align-top" alt="' . get_bloginfo('name') . '">';
  $html .= '</a>';

  return $html;
}

function toggler( $theme_location = 'primary_navigation' ){

  $html = '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="' . $theme_location . '" aria-controls="' . $theme_location . '" aria-expanded="false" aria-label="' . __('Toggle navigation','imaga') . '">';
  $html .= '<span class="navbar-toggler-icon"></span>';
  $html .= '</button>';

  return $html;
}

function navigation( $theme_location = "primary_navigation", $container_id = "primary_navigation", $start_depth = 0, $depth = 2, $menu_class = "ml-auto nav navbar-nav" ){
  return wp_nav_menu(
    array(
      'theme_location'    => $theme_location,
      'start_depth'       => $start_depth,
      'depth'             => $depth,
      'container'         => 'div',
      'container_class'   => 'collapse navbar-collapse navbar-toggle',
      'container_id'      => $container_id,
      'menu_class'        => $menu_class,
      'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
      'walker'            => new WP_Bootstrap_Navwalker()
    )
  );
}