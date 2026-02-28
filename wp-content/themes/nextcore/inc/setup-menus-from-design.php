<?php
/**
 * Tasarım dosyasındaki (tasarim/header_footer.html) menü yapısını WordPress menülerine ekler.
 * Tema aktifleştirildiğinde veya admin'de "Menüleri tasarımdan oluştur" ile çalıştırılabilir.
 *
 * @package nextcore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Header (Primary) ve Footer menülerini tasarıma göre oluşturur.
 */
function nextcore_setup_menus_from_design() {
	$primary_name = 'Header Menu';
	$footer_name  = 'Footer Menu';

	// Primary Menu
	$primary = wp_get_nav_menu_object( $primary_name );
	if ( ! $primary ) {
		$primary_id = wp_create_nav_menu( $primary_name );
		if ( is_wp_error( $primary_id ) ) {
			return;
		}
	} else {
		$primary_id = $primary->term_id;
		$items      = wp_get_nav_menu_items( $primary_id );
		if ( $items && count( $items ) > 10 ) {
			// Menü zaten dolu, tekrar ekleme
			$footer_obj = wp_get_nav_menu_object( $footer_name );
			nextcore_assign_menu_locations( $primary_id, $footer_obj ? $footer_obj->term_id : null );
			return;
		}
	}

	$home_url = home_url( '/' );

	// 1. Home
	wp_update_nav_menu_item( $primary_id, 0, array(
		'menu-item-title'   => 'Home',
		'menu-item-url'    => $home_url,
		'menu-item-status' => 'publish',
		'menu-item-type'   => 'custom',
	) );

	// 2. Collections (mega)
	$id_collections = wp_update_nav_menu_item( $primary_id, 0, array(
		'menu-item-title'   => 'Collections',
		'menu-item-url'    => $home_url . '#collections',
		'menu-item-status' => 'publish',
		'menu-item-type'   => 'custom',
	) );

	// Collections > By Style (sütun başlığı) + linkler
	wp_update_nav_menu_item( $primary_id, 0, array(
		'menu-item-title'     => 'By Style',
		'menu-item-url'       => '#',
		'menu-item-status'    => 'publish',
		'menu-item-type'      => 'custom',
		'menu-item-parent-id' => $id_collections,
		'menu-item-classes'   => 'mega-col-title',
	) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Modern & Contemporary', 'menu-item-url' => $home_url . '#modern', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_collections ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Classic & Traditional', 'menu-item-url' => $home_url . '#classic', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_collections ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Geometric Patterns', 'menu-item-url' => $home_url . '#geometric', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_collections ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Minimalist', 'menu-item-url' => $home_url . '#minimalist', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_collections ) );

	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'By Space', 'menu-item-url' => '#', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_collections, 'menu-item-classes' => 'mega-col-title' ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Hotel & Hospitality', 'menu-item-url' => $home_url . '#hotel', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_collections ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Office & Corporate', 'menu-item-url' => $home_url . '#office', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_collections ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Restaurant & Café', 'menu-item-url' => $home_url . '#restaurant', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_collections ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Residential & Living', 'menu-item-url' => $home_url . '#residential', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_collections ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Automotive Showroom', 'menu-item-url' => $home_url . '#automotive', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_collections ) );

	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Popular', 'menu-item-url' => '#', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_collections, 'menu-item-classes' => 'mega-col-title' ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Aegean Luxe Series', 'menu-item-url' => $home_url . '#aegean-luxe', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_collections ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Anatolian Heritage', 'menu-item-url' => $home_url . '#anatolian', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_collections ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Urban Slate', 'menu-item-url' => $home_url . '#urban-slate', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_collections ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Golden Hour', 'menu-item-url' => $home_url . '#golden-hour', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_collections ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Nordic Frost', 'menu-item-url' => $home_url . '#nordic-frost', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_collections ) );

	// 3. Products (mega)
	$id_products = wp_update_nav_menu_item( $primary_id, 0, array(
		'menu-item-title'   => 'Products',
		'menu-item-url'    => $home_url . '#products',
		'menu-item-status' => 'publish',
		'menu-item-type'   => 'custom',
	) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Carpet Types', 'menu-item-url' => '#', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_products, 'menu-item-classes' => 'mega-col-title' ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Wall-to-Wall Carpets', 'menu-item-url' => $home_url . '#wall-to-wall', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_products ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Carpet Tiles', 'menu-item-url' => $home_url . '#carpet-tiles', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_products ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Area Rugs', 'menu-item-url' => $home_url . '#area-rugs', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_products ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Logo & Custom Mats', 'menu-item-url' => $home_url . '#custom-mats', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_products ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Runners', 'menu-item-url' => $home_url . '#runners', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_products ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Materials', 'menu-item-url' => '#', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_products, 'menu-item-classes' => 'mega-col-title' ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => '100% Wool', 'menu-item-url' => $home_url . '#wool', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_products ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Nylon Blend', 'menu-item-url' => $home_url . '#nylon', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_products ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Polypropylene', 'menu-item-url' => $home_url . '#polypropylene', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_products ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Silk Touch', 'menu-item-url' => $home_url . '#silk', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_products ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Eco-Friendly Recycled', 'menu-item-url' => $home_url . '#eco', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_products ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Services', 'menu-item-url' => '#', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_products, 'menu-item-classes' => 'mega-col-title' ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Custom Design Lab', 'menu-item-url' => $home_url . '#design-lab', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_products ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Color Matching', 'menu-item-url' => $home_url . '#color-matching', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_products ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Sample Request', 'menu-item-url' => $home_url . '#sample', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_products ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Bulk Orders', 'menu-item-url' => $home_url . '#bulk', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $id_products ) );

	// 4. Projects, 5. About Us, 6. Contact
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Projects', 'menu-item-url' => $home_url . '#projects', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom' ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'About Us', 'menu-item-url' => $home_url . '#about', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom' ) );
	wp_update_nav_menu_item( $primary_id, 0, array( 'menu-item-title' => 'Contact', 'menu-item-url' => $home_url . '#contact', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom' ) );

	// Footer Menu
	$footer = wp_get_nav_menu_object( $footer_name );
	if ( ! $footer ) {
		$footer_id = wp_create_nav_menu( $footer_name );
		if ( is_wp_error( $footer_id ) ) {
			nextcore_assign_menu_locations( $primary_id, null );
			return;
		}
	} else {
		$footer_id = $footer->term_id;
		$items     = wp_get_nav_menu_items( $footer_id );
		if ( $items && count( $items ) > 15 ) {
			nextcore_assign_menu_locations( $primary_id, $footer_id );
			return;
		}
	}

	$footer_items = array(
		'Collections' => array(
			'Modern & Contemporary' => $home_url . '#modern',
			'Classic & Traditional'  => $home_url . '#classic',
			'Geometric Patterns'    => $home_url . '#geometric',
			'Minimalist'            => $home_url . '#minimalist',
			'Aegean Luxe Series'     => $home_url . '#aegean-luxe',
			'Anatolian Heritage'    => $home_url . '#anatolian',
		),
		'Solutions' => array(
			'Hotels & Hospitality'   => $home_url . '#hotel',
			'Restaurants & Cafés'    => $home_url . '#restaurant',
			'Corporate Offices'      => $home_url . '#office',
			'Residential Spaces'     => $home_url . '#residential',
			'Automotive Showrooms'   => $home_url . '#automotive',
			'Education Centers'      => $home_url . '#education',
		),
		'Company' => array(
			'About Entry Mark' => $home_url . '#about',
			'Our Projects'     => $home_url . '#projects',
			'Designer Lab'      => $home_url . '#design-lab',
			'Sustainability'   => $home_url . '#sustainability',
			'Careers'          => $home_url . '#careers',
			'Press & Media'    => $home_url . '#press',
		),
		'Support' => array(
			'Contact Us'        => $home_url . '#contact',
			'Request a Sample'   => $home_url . '#sample',
			'Get a Quote'        => $home_url . '#quote',
			'FAQs'               => $home_url . '#faqs',
			'Shipping & Delivery' => $home_url . '#shipping',
			'Returns Policy'     => $home_url . '#returns',
		),
	);

	foreach ( $footer_items as $col_title => $links ) {
		$parent_id = wp_update_nav_menu_item( $footer_id, 0, array(
			'menu-item-title'   => $col_title,
			'menu-item-url'    => '#',
			'menu-item-status' => 'publish',
			'menu-item-type'   => 'custom',
			'menu-item-classes' => 'footer-col-title',
		) );
		foreach ( $links as $label => $url ) {
			wp_update_nav_menu_item( $footer_id, 0, array(
				'menu-item-title'     => $label,
				'menu-item-url'       => $url,
				'menu-item-status'   => 'publish',
				'menu-item-type'     => 'custom',
				'menu-item-parent-id' => $parent_id,
			) );
		}
	}

	nextcore_assign_menu_locations( $primary_id, $footer_id );
}

/**
 * Menü konumlarını atar.
 */
function nextcore_assign_menu_locations( $primary_id, $footer_id ) {
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations['primary'] = $primary_id;
	if ( $footer_id ) {
		$locations['footer'] = $footer_id;
	}
	set_theme_mod( 'nav_menu_locations', $locations );
}

add_action( 'after_switch_theme', 'nextcore_setup_menus_from_design' );

// Admin'de "Menüleri tasarımdan oluştur" aksiyonu
add_action( 'admin_init', function () {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	if ( isset( $_GET['nextcore_setup_menus'] ) && $_GET['nextcore_setup_menus'] === '1' ) {
		check_admin_referer( 'nextcore_setup_menus' );
		nextcore_setup_menus_from_design();
		wp_safe_redirect( add_query_arg( 'nextcore_menus_done', '1', admin_url( 'nav-menus.php' ) ) );
		exit;
	}
} );

add_action( 'admin_notices', function () {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || $screen->id !== 'nav-menus' ) {
		if ( isset( $_GET['nextcore_menus_done'] ) ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Header ve Footer menüleri tasarım dosyasına göre oluşturuldu. Görünüm → Menüler üzerinden düzenleyebilirsiniz.', 'nextcore' ) . '</p></div>';
		}
		return;
	}
	if ( isset( $_GET['nextcore_menus_done'] ) ) {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Header ve Footer menüleri tasarım dosyasına göre oluşturuldu / güncellendi.', 'nextcore' ) . '</p></div>';
		return;
	}
	$url = wp_nonce_url( add_query_arg( 'nextcore_setup_menus', '1', admin_url( 'nav-menus.php' ) ), 'nextcore_setup_menus' );
	echo '<div class="notice notice-info"><p>' . esc_html__( 'Tasarım dosyasındaki (header_footer.html) menü yapısını kullanmak için:', 'nextcore' ) . ' <a href="' . esc_url( $url ) . '" class="button button-secondary">' . esc_html__( 'Menüleri tasarımdan oluştur', 'nextcore' ) . '</a></p></div>';
} );
