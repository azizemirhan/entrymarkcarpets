<?php
/**
 * Mobile Menu Walker
 * @package nextcore
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Nextcore_Mobile_Menu_Walker extends Walker_Nav_Menu {
    
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '<ul class="sub-menu">';
    }
    
    public function end_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '</ul>';
    }
    
    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $has_children = in_array( 'menu-item-has-children', $item->classes );
        
        $output .= '<li' . ( $has_children ? ' class="has-submenu"' : '' ) . '>';
        
        $output .= '<a href="' . esc_url( $item->url ) . '">';
        $output .= '<span>' . esc_html( $item->title ) . '</span>';
        
        if ( $has_children ) {
            $output .= '<svg class="mobile-menu-toggle-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">';
            $output .= '<polyline points="6 9 12 15 18 9"/>';
            $output .= '</svg>';
        }
        
        $output .= '</a>';
    }
    
    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= '</li>';
    }
}
