<?php
/**
 * Simple Menu Walker
 * @package nextcore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Nextcore_Mega_Menu_Walker extends \Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class=\"sub-menu\">\n";
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "$indent</ul>\n";
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$has_children = in_array( 'menu-item-has-children', $classes, true );
		
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
		
		$output .= $indent . '<li' . $class_names . '>';
		
		$attributes = ' href="' . esc_url( $item->url ) . '"';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		
		$title = apply_filters( 'the_title', $item->title, $item->ID );
		
		$item_output = $args->before;
		$item_output .= '<a class="nav-link"' . $attributes . '>';
		$item_output .= $args->link_before . $title . $args->link_after;
		if ( $has_children ) {
			$item_output .= ' <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>';
		}
		$item_output .= '</a>';
		$item_output .= $args->after;
		
		$output .= $item_output;
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= "</li>\n";
	}
}
