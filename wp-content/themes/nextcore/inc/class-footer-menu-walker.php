<?php
/**
 * Footer menu walker: each top-level item = one column (footer-col) with title + links.
 *
 * @package nextcore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Nextcore_Footer_Menu_Walker extends Walker_Nav_Menu {

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$title = apply_filters( 'the_title', $item->title, $item->ID );
		if ( 0 === $depth ) {
			$output .= '<div class="footer-col">';
			$output .= '<div class="footer-col-title">' . esc_html( $title ) . '</div>';
			$output .= '<div class="footer-links">';
			return;
		}
		$atts = array(
			'href'   => $item->url,
			'target' => ! empty( $item->target ) ? $item->target : '',
		);
		$output .= '<a href="' . esc_url( $atts['href'] ) . '"';
		if ( $atts['target'] ) {
			$output .= ' target="' . esc_attr( $atts['target'] ) . '"';
		}
		$output .= '>' . esc_html( $title ) . '</a>';
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( 0 === $depth ) {
			$output .= '</div></div>';
		}
	}

	public function start_lvl( &$output, $depth = 0, $args = null ) {}

	public function end_lvl( &$output, $depth = 0, $args = null ) {}
}
