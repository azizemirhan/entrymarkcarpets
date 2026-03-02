<?php
/**
 * nextcore functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package nextcore
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.2' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function nextcore_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on nextcore, use a find and replace
		* to change 'nextcore' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'nextcore', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'nextcore' ),
			'footer'  => esc_html__( 'Footer Menu', 'nextcore' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'nextcore_custom_background_args',
			array(
				'default-color' => 'F7F8F8',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'nextcore_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function nextcore_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'nextcore_content_width', 1280 );
}
add_action( 'after_setup_theme', 'nextcore_content_width', 0 );

/**
 * Giriş yap sayfası URL’i (slug: giris veya “Giriş yap” şablonlu sayfa)
 */
function nextcore_get_giris_url() {
	$page = get_page_by_path( 'giris' );
	if ( $page ) {
		return get_permalink( $page );
	}
	$pages = get_pages( array( 'meta_key' => '_wp_page_template', 'meta_value' => 'template-giris.php' ) );
	if ( ! empty( $pages ) ) {
		return get_permalink( $pages[0] );
	}
	return home_url( '/giris/' );
}

/**
 * Kayıt ol sayfası URL’i (slug: kayit veya “Kayıt ol” şablonlu sayfa)
 */
function nextcore_get_kayit_url() {
	$page = get_page_by_path( 'kayit' );
	if ( $page ) {
		return get_permalink( $page );
	}
	$pages = get_pages( array( 'meta_key' => '_wp_page_template', 'meta_value' => 'template-kayit.php' ) );
	if ( ! empty( $pages ) ) {
		return get_permalink( $pages[0] );
	}
	return home_url( '/kayit/' );
}

/**
 * Hesabım sayfası URL’i (slug: hesabim veya “Hesabım” şablonlu sayfa)
 */
function nextcore_get_hesabim_url() {
	$page = get_page_by_path( 'hesabim' );
	if ( $page ) {
		return get_permalink( $page );
	}
	$pages = get_pages( array( 'meta_key' => '_wp_page_template', 'meta_value' => 'template-hesabim.php' ) );
	if ( ! empty( $pages ) ) {
		return get_permalink( $pages[0] );
	}
	return home_url( '/hesabim/' );
}

/**
 * Slug veya şablona göre sayfa URL’i döndürür (sayfa yoksa home_url fallback).
 *
 * @param string $slug    Sayfa slug (örn. galeri, iletisim).
 * @param string $template Opsiyonel. Şablon dosya adı (örn. template-gallery.php).
 * @return string Sayfa permalink veya home_url( '/slug/' ).
 */
function nextcore_get_sifre_sifirlama_url() {
	$page = get_page_by_path( 'sifre-sifirlama' );
	if ( $page ) {
		return get_permalink( $page );
	}
	$pages = get_pages( array( 'meta_key' => '_wp_page_template', 'meta_value' => 'template-sifre-sifirlama.php' ) );
	if ( ! empty( $pages ) ) {
		return get_permalink( $pages[0] );
	}
	return home_url( '/sifre-sifirlama/' );
}

/**
 * Slug veya şablona göre sayfa URL'i döndürür (sayfa yoksa home_url fallback).
 *
 * @param string $slug    Sayfa slug (örn. galeri, iletisim).
 * @param string $template Opsiyonel. Şablon dosya adı (örn. template-gallery.php).
 * @return string Sayfa permalink veya home_url( '/slug/' ).
 */
function nextcore_get_page_url( $slug, $template = '' ) {
	$page = get_page_by_path( $slug );
	if ( $page ) {
		return get_permalink( $page );
	}
	if ( $template ) {
		$pages = get_pages( array(
			'meta_key'   => '_wp_page_template',
			'meta_value' => $template,
			'number'     => 1,
		) );
		if ( ! empty( $pages ) ) {
			return get_permalink( $pages[0] );
		}
	}
	return home_url( '/' . $slug . '/' );
}

/**
 * Paspas özelleştirici sayfası URL’i (paspas-ozellestir veya ozellestir).
 *
 * @return string Özelleştirici sayfası permalink.
 */
function nextcore_get_customizer_url() {
	$page = get_page_by_path( 'paspas-ozellestir' );
	if ( $page ) {
		return get_permalink( $page );
	}
	$page = get_page_by_path( 'ozellestir' );
	if ( $page ) {
		return get_permalink( $page );
	}
	return home_url( '/paspas-ozellestir/' );
}

/**
 * /hesabim/ adresi 404 veriyorsa Hesabım şablonunu göster (sayfa oluşturulmamış olsa bile)
 */
function nextcore_hesabim_404_fallback( $template ) {
	if ( ! is_404() ) {
		return $template;
	}
	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$path = trim( parse_url( $request_uri, PHP_URL_PATH ), '/' );
	$parts = array_filter( explode( '/', $path ) );
	$first = isset( $parts[0] ) ? $parts[0] : '';
	if ( $first === 'hesabim' || $first === 'hesabim.php' ) {
		$GLOBALS['nextcore_hesabim_fallback'] = true;
		status_header( 200 );
		return get_template_directory() . '/template-hesabim.php';
	}
	if ( $first === 'giris' || $first === 'giris.php' ) {
		$GLOBALS['nextcore_giris_fallback'] = true;
		status_header( 200 );
		return get_template_directory() . '/template-giris.php';
	}
	if ( $first === 'kayit' || $first === 'kayit.php' ) {
		$GLOBALS['nextcore_kayit_fallback'] = true;
		status_header( 200 );
		return get_template_directory() . '/template-kayit.php';
	}
	if ( $first === 'sifre-sifirlama' || $first === 'sifre-sifirlama.php' ) {
		$GLOBALS['nextcore_sifre_sifirlama_fallback'] = true;
		status_header( 200 );
		return get_template_directory() . '/template-sifre-sifirlama.php';
	}
	return $template;
}
add_filter( 'template_include', 'nextcore_hesabim_404_fallback', 99 );

/**
 * Hesabım / Kayıt ol 404 fallback kullanıldığında sayfa başlığını düzelt
 */
function nextcore_hesabim_document_title( $title_parts ) {
	if ( ! empty( $GLOBALS['nextcore_hesabim_fallback'] ) ) {
		$title_parts['title'] = __( 'Hesabım', 'nextcore' );
	}
	if ( ! empty( $GLOBALS['nextcore_giris_fallback'] ) ) {
		$title_parts['title'] = __( 'Giriş yap', 'nextcore' );
	}
	if ( ! empty( $GLOBALS['nextcore_kayit_fallback'] ) ) {
		$title_parts['title'] = __( 'Kayıt ol', 'nextcore' );
	}
	if ( ! empty( $GLOBALS['nextcore_sifre_sifirlama_fallback'] ) ) {
		$title_parts['title'] = __( 'Şifre sıfırlama', 'nextcore' );
	}
	return $title_parts;
}
add_filter( 'document_title_parts', 'nextcore_hesabim_document_title', 99 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function nextcore_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'nextcore' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'nextcore' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'nextcore_widgets_init' );

/**
 * Arama: hem yazı hem sayfa dahil olsun (varsayılan sadece post).
 */
function nextcore_search_post_types( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
		$query->set( 'post_type', array( 'post', 'page' ) );
	}
}
add_action( 'pre_get_posts', 'nextcore_search_post_types' );

/**
 * Veritabanındaki sayfa başlıklarında Türkçe karakter (UTF-8) bozulmasını tek seferlik düzeltir.
 * Yönetim paneli > Sayfalar listesinde "Çerez Politikası", "Değerlerimiz" vb. doğru görünür.
 */
function nextcore_fix_turkish_page_titles() {
	if ( ! is_admin() ) {
		return;
	}
	if ( get_option( 'nextcore_turkish_titles_fixed', '' ) === 'yes' ) {
		return;
	}

	$template_to_title = array(
		'template-cerez.php'       => 'Çerez Politikası',
		'template-degerlerimiz.php' => 'Değerlerimiz',
		'template-gizlilik.php'    => 'Gizlilik Politikası',
		'template-kullanim.php'    => 'Kullanım Koşulları',
	);

	foreach ( $template_to_title as $template => $correct_title ) {
		$pages = get_posts( array(
			'post_type'      => 'page',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'meta_key'       => '_wp_page_template',
			'meta_value'     => $template,
			'fields'         => 'ids',
		) );
		foreach ( $pages as $page_id ) {
			wp_update_post( array(
				'ID'         => $page_id,
				'post_title' => $correct_title,
			) );
		}
	}

	update_option( 'nextcore_turkish_titles_fixed', 'yes' );
}
add_action( 'admin_init', 'nextcore_fix_turkish_page_titles' );

/**
 * Enqueue scripts and styles.
 */
function nextcore_scripts() {
	// Theme stylesheet
	wp_enqueue_style( 'nextcore-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'nextcore-style', 'rtl', 'replace' );

	// Google Fonts
	wp_enqueue_style( 'nextcore-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Outfit:wght@300;400;500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap', array(), null );

	// Main JavaScript
	wp_enqueue_script( 'nextcore-main', get_template_directory_uri() . '/js/main.js', array(), _S_VERSION, true );

	// Navigation JavaScript (original)
	wp_enqueue_script( 'nextcore-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Anasayfa bölümleri (Hero, Özellikler, CTA, Teknik Özellikler) — sadece front page
	if ( is_front_page() ) {
		wp_enqueue_style(
			'nextcore-sections-home',
			get_template_directory_uri() . '/assets/css/sections-home.css',
			array( 'nextcore-style' ),
			_S_VERSION
		);
		wp_enqueue_style(
			'nextcore-sections-specs',
			get_template_directory_uri() . '/assets/css/sections-specs.css',
			array( 'nextcore-style' ),
			_S_VERSION
		);
		wp_enqueue_script(
			'nextcore-sections-home',
			get_template_directory_uri() . '/js/sections-home.js',
			array(),
			_S_VERSION,
			true
		);
		wp_enqueue_script(
			'nextcore-sections-specs',
			get_template_directory_uri() . '/js/sections-specs.js',
			array(),
			_S_VERSION,
			true
		);
	}

	// Hakkımızda sayfası şablonu
	if ( is_page_template( 'template-about.php' ) ) {
		wp_enqueue_style(
			'nextcore-page-about',
			get_template_directory_uri() . '/assets/css/page-about.css',
			array( 'nextcore-style' ),
			_S_VERSION
		);
		wp_enqueue_script(
			'nextcore-page-about',
			get_template_directory_uri() . '/js/page-about.js',
			array(),
			_S_VERSION,
			true
		);
	}

	// İletişim sayfası şablonu
	if ( is_page_template( 'template-contact.php' ) ) {
		wp_enqueue_style(
			'nextcore-page-contact',
			get_template_directory_uri() . '/assets/css/page-contact.css',
			array( 'nextcore-style' ),
			_S_VERSION
		);
		wp_enqueue_script(
			'nextcore-page-contact',
			get_template_directory_uri() . '/js/page-contact.js',
			array(),
			_S_VERSION,
			true
		);
	}

	// Kullanım Koşulları sayfası
	if ( is_page_template( 'template-kullanim.php' ) ) {
		wp_enqueue_style(
			'nextcore-page-kullanim',
			get_template_directory_uri() . '/assets/css/page-kullanim.css',
			array( 'nextcore-style' ),
			_S_VERSION
		);
	}

	if ( is_page_template( 'template-degerlerimiz.php' ) ) {
		wp_enqueue_style(
			'nextcore-page-degerlerimiz',
			get_template_directory_uri() . '/assets/css/page-degerlerimiz.css',
			array( 'nextcore-style' ),
			_S_VERSION
		);
	}

	if ( is_page_template( 'template-yardim-merkezi.php' ) ) {
		wp_enqueue_style(
			'nextcore-page-yardim-merkezi',
			get_template_directory_uri() . '/assets/css/page-yardim-merkezi.css',
			array( 'nextcore-style' ),
			_S_VERSION
		);
	}

	if ( is_page_template( 'template-surdurulebilirlik.php' ) ) {
		wp_enqueue_style(
			'nextcore-page-surdurulebilirlik',
			get_template_directory_uri() . '/assets/css/page-surdurulebilirlik.css',
			array( 'nextcore-style' ),
			_S_VERSION
		);
	}

	// Gizlilik Politikası sayfası
	if ( is_page_template( 'template-gizlilik.php' ) ) {
		wp_enqueue_style(
			'nextcore-page-gizlilik',
			get_template_directory_uri() . '/assets/css/page-gizlilik.css',
			array( 'nextcore-style' ),
			_S_VERSION
		);
	}

	// Çerez Politikası sayfası
	if ( is_page_template( 'template-cerez.php' ) ) {
		wp_enqueue_style(
			'nextcore-page-cerez',
			get_template_directory_uri() . '/assets/css/page-cerez.css',
			array( 'nextcore-style' ),
			_S_VERSION
		);
	}

	// KVKK Aydınlatma Metni sayfası
	if ( is_page_template( 'template-kvkk.php' ) ) {
		wp_enqueue_style(
			'nextcore-page-kvkk',
			get_template_directory_uri() . '/assets/css/page-kvkk.css',
			array( 'nextcore-style' ),
			_S_VERSION
		);
	}

	// Galeri sayfası şablonu — Next Content (Galeri) ile yönetilen metinler JS'e aktarılır
	if ( is_page_template( 'template-gallery.php' ) ) {
		wp_enqueue_style(
			'nextcore-page-gallery',
			get_template_directory_uri() . '/assets/css/page-gallery.css',
			array( 'nextcore-style' ),
			_S_VERSION
		);
		wp_enqueue_script(
			'nextcore-page-gallery',
			get_template_directory_uri() . '/js/page-gallery.js',
			array(),
			_S_VERSION,
			true
		);
		$customizer_url = get_option( 'eternal_gallery_hero_customizer_url', get_option( 'eternal_gallery_customizer_url', '' ) );
		if ( empty( $customizer_url ) ) {
			$customizer_url = home_url( '/paspas-ozellestir/' );
		}
		$tag_by_category_raw = get_option( 'eternal_gallery_lightbox_tag_by_category', "hotel|OTEL KOLEKSİYONU\noffice|OFİS KOLEKSİYONU\ncustom|ÖZEL TASARIM\nresidential|KONUT KOLEKSİYONU" );
		$tag_by_category = array();
		foreach ( array_filter( array_map( 'trim', explode( "\n", $tag_by_category_raw ) ) ) as $row ) {
			$parts = array_map( 'trim', explode( '|', $row, 2 ) );
			if ( count( $parts ) >= 2 ) {
				$tag_by_category[ $parts[0] ] = $parts[1];
			}
		}
		$gallery_items = array();
		$products_json = get_option( 'eternal_gallery_products_json', '' );
		if ( ! empty( $products_json ) ) {
			$decoded = json_decode( $products_json, true );
			if ( is_array( $decoded ) && ! empty( $decoded ) ) {
				$gallery_items = $decoded;
			}
		}
		$localize = array(
			'customizerUrl'       => $customizer_url,
			'loadMoreText'        => get_option( 'eternal_gallery_grid_load_more_text', 'Daha Fazla Göster' ),
			'counterFormat'       => get_option( 'eternal_gallery_grid_counter_text', '%shown% / %total% ürün gösteriliyor' ),
			'initialCount'        => (int) get_option( 'eternal_gallery_grid_initial_count', 8 ),
			'tagByCategory'       => $tag_by_category,
			'tagDefault'          => get_option( 'eternal_gallery_lightbox_tag_default', 'KOLEKSİYON' ),
			'labelMaterial'       => get_option( 'eternal_gallery_lightbox_label_material', 'Malzeme' ),
			'labelSize'           => get_option( 'eternal_gallery_lightbox_label_size', 'Boyut' ),
			'labelThickness'      => get_option( 'eternal_gallery_lightbox_label_thickness', 'Kalınlık' ),
			'labelPrice'          => get_option( 'eternal_gallery_lightbox_label_price', 'Fiyat' ),
			'btnCustomize'        => get_option( 'eternal_gallery_lightbox_btn_customize', 'Özelleştir' ),
			'badgeNew'             => get_option( 'eternal_gallery_badge_new', 'Yeni' ),
			'badgePopular'         => get_option( 'eternal_gallery_badge_popular', 'Popüler' ),
			'badgeSale'            => get_option( 'eternal_gallery_badge_sale', 'İndirim' ),
		);
		if ( ! empty( $gallery_items ) ) {
			$localize['items'] = $gallery_items;
		}
		wp_localize_script( 'nextcore-page-gallery', 'nextcoreGallery', $localize );
	}

	// Hesabım sayfası — şablon seçili sayfa veya 404 fallback (/hesabim/) için de yükle
	$is_hesabim = is_page_template( 'template-hesabim.php' );
	if ( ! $is_hesabim ) {
		$req_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$p       = trim( parse_url( $req_uri, PHP_URL_PATH ), '/' );
		$segments = array_filter( explode( '/', $p ) );
		$is_hesabim = ( isset( $segments[0] ) && $segments[0] === 'hesabim' );
	}
	if ( $is_hesabim ) {
		wp_enqueue_style(
			'nextcore-page-hesabim',
			get_template_directory_uri() . '/assets/css/page-hesabim.css',
			array( 'nextcore-style' ),
			_S_VERSION
		);
	}

	// Giriş, Kayıt ol, Şifre sıfırlama sayfaları — şablon seçili veya 404 fallback için auth CSS yükle
	$is_auth_page = is_page_template( 'template-giris.php' ) || is_page_template( 'template-kayit.php' ) || is_page_template( 'template-sifre-sifirlama.php' );
	if ( ! $is_auth_page ) {
		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$path        = trim( parse_url( $request_uri, PHP_URL_PATH ), '/' );
		$parts       = array_filter( explode( '/', $path ) );
		$first       = isset( $parts[0] ) ? $parts[0] : '';
		$is_auth_page = ( $first === 'giris' || $first === 'kayit' || $first === 'sifre-sifirlama' );
	}
	if ( $is_auth_page ) {
		wp_enqueue_style(
			'nextcore-page-auth',
			get_template_directory_uri() . '/assets/css/page-auth.css',
			array( 'nextcore-style' ),
			_S_VERSION
		);
	}
}
add_action( 'wp_enqueue_scripts', 'nextcore_scripts' );

/**
 * İletişim formu gönderimi (admin-post)
 */
function nextcore_handle_contact_submit() {
	if ( ! isset( $_POST['contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['contact_nonce'] ) ), 'entrymark_contact' ) ) {
		wp_safe_redirect( home_url( '/iletisim/?contact_error=1' ) );
		exit;
	}
	$first_name = isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '';
	$last_name  = isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '';
	$email      = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone      = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$company    = isset( $_POST['company'] ) ? sanitize_text_field( wp_unslash( $_POST['company'] ) ) : '';
	$subject    = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
	$message    = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
	if ( ! $first_name || ! $last_name || ! $email || ! $subject || ! $message ) {
		wp_safe_redirect( home_url( '/iletisim/?contact_error=1' ) );
		exit;
	}
	$to      = get_option( 'admin_email' );
	$body    = sprintf(
		"Ad: %s\nSoyad: %s\nE-posta: %s\nTelefon: %s\nŞirket: %s\nKonu: %s\n\nMesaj:\n%s",
		$first_name,
		$last_name,
		$email,
		$phone,
		$company,
		$subject,
		$message
	);
	$headers = array( 'Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $email );
	wp_mail( $to, '[İletişim] ' . $subject, $body, $headers );
	$redirect = isset( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : home_url( '/' );
	if ( strpos( $redirect, home_url() ) !== 0 ) {
		$redirect = home_url( '/' );
	}
	$redirect = add_query_arg( 'contact_sent', '1', $redirect );
	wp_safe_redirect( $redirect );
	exit;
}
add_action( 'admin_post_entrymark_contact_submit', 'nextcore_handle_contact_submit' );
add_action( 'admin_post_nopriv_entrymark_contact_submit', 'nextcore_handle_contact_submit' );

/**
 * Kayıt ol formu gönderimi — şifre belirleme ile
 */
function nextcore_handle_register_submit() {
	$redirect_base = function_exists( 'nextcore_get_kayit_url' ) ? nextcore_get_kayit_url() : home_url( '/kayit/' );
	$redirect_base = trailingslashit( strtok( $redirect_base, '?' ) );
	$hesabim_url   = function_exists( 'nextcore_get_hesabim_url' ) ? nextcore_get_hesabim_url() : home_url( '/hesabim/' );

	if ( ! get_option( 'users_can_register' ) ) {
		wp_safe_redirect( add_query_arg( 'registration', 'disabled', $redirect_base ) );
		exit;
	}

	if ( ! isset( $_POST['register_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['register_nonce'] ) ), 'entrymark_register' ) ) {
		wp_safe_redirect( add_query_arg( 'reg_error', urlencode( __( 'Güvenlik doğrulaması başarısız. Lütfen tekrar deneyin.', 'nextcore' ) ), $redirect_base ) );
		exit;
	}

	$user_login = isset( $_POST['user_login'] ) ? sanitize_user( wp_unslash( $_POST['user_login'] ) ) : '';
	$user_email = isset( $_POST['user_email'] ) ? sanitize_email( wp_unslash( $_POST['user_email'] ) ) : '';
	$user_pass  = isset( $_POST['user_pass'] ) ? wp_unslash( $_POST['user_pass'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	$user_pass2 = isset( $_POST['user_pass2'] ) ? wp_unslash( $_POST['user_pass2'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

	if ( ! $user_login || ! $user_email || ! $user_pass ) {
		wp_safe_redirect( add_query_arg( 'reg_error', urlencode( __( 'Tüm alanları doldurun.', 'nextcore' ) ), $redirect_base ) );
		exit;
	}

	if ( strlen( $user_pass ) < 6 ) {
		wp_safe_redirect( add_query_arg( 'reg_error', urlencode( __( 'Şifre en az 6 karakter olmalıdır.', 'nextcore' ) ), $redirect_base ) );
		exit;
	}

	if ( $user_pass !== $user_pass2 ) {
		wp_safe_redirect( add_query_arg( 'reg_error', urlencode( __( 'Şifreler eşleşmiyor.', 'nextcore' ) ), $redirect_base ) );
		exit;
	}

	if ( email_exists( $user_email ) ) {
		wp_safe_redirect( add_query_arg( array( 'reg_error' => urlencode( __( 'Bu e-posta adresi zaten kayıtlı.', 'nextcore' ) ), 'user_login' => $user_login, 'user_email' => $user_email ), $redirect_base ) );
		exit;
	}

	// Kullanıcı adı benzersiz olmalı (WP zorunluluğu); aynı ad varsa benzersiz login oluştur
	$wp_login = $user_login;
	if ( username_exists( $user_login ) ) {
		$wp_login = $user_login . '_' . wp_rand( 100, 9999 );
		while ( username_exists( $wp_login ) ) {
			$wp_login = $user_login . '_' . wp_rand( 100, 9999 );
		}
	}

	$user_id = wp_insert_user(
		array(
			'user_login'   => $wp_login,
			'user_email'   => $user_email,
			'user_pass'    => $user_pass,
			'display_name' => $user_login,
			'role'         => get_option( 'default_role', 'subscriber' ),
		)
	);

	if ( is_wp_error( $user_id ) ) {
		wp_safe_redirect( add_query_arg( 'reg_error', urlencode( $user_id->get_error_message() ), $redirect_base ) );
		exit;
	}

	// Başarılı — otomatik giriş ve Hesabım'a yönlendir
	$redirect_to = isset( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : $hesabim_url;
	if ( strpos( $redirect_to, home_url() ) !== 0 ) {
		$redirect_to = $hesabim_url;
	}
	wp_set_current_user( $user_id );
	wp_set_auth_cookie( $user_id );
	wp_safe_redirect( $redirect_to );
	exit;
}
add_action( 'admin_post_entrymark_register', 'nextcore_handle_register_submit' );
add_action( 'admin_post_nopriv_entrymark_register', 'nextcore_handle_register_submit' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Mega menu walker for Primary Menu (header nav + mega dropdowns).
 */
require get_template_directory() . '/inc/class-mega-menu-walker.php';

/**
 * Footer menu walker (sütun başlığı + linkler).
 */
require get_template_directory() . '/inc/class-footer-menu-walker.php';

/**
 * Mobile menu walker (mobile slide-out menu).
 */
require get_template_directory() . '/inc/class-mobile-menu-walker.php';

/**
 * Tasarım dosyasındaki menüleri WordPress menülerine ekler (tema aktifleşince veya admin linki ile).
 */
require get_template_directory() . '/inc/setup-menus-from-design.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Custom functions for Entry Mark Carpets theme
 */

/**
 * Custom excerpt length
 */
function nextcore_custom_excerpt_length( $length ) {
	return 25;
}
add_filter( 'excerpt_length', 'nextcore_custom_excerpt_length', 999 );

/**
 * Custom excerpt more
 */
function nextcore_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'nextcore_excerpt_more' );

/**
 * Site taşındığında veya farklı domain'den açıldığında tüm resim URL'lerini mevcut adresle düzeltir.
 * Veritabanındaki siteurl/home eski kaldıysa resimler gelmez; bu filtreler çıktıyı düzeltir.
 */
function nextcore_current_origin() {
	static $origin = null;
	if ( $origin !== null ) {
		return $origin;
	}
	$host = '';
	if ( ! empty( $_SERVER['HTTP_HOST'] ) ) {
		$host = wp_unslash( $_SERVER['HTTP_HOST'] );
	}
	if ( $host === '' ) {
		$parsed = wp_parse_url( home_url(), PHP_URL_HOST );
		$host   = $parsed ? $parsed : '';
	}
	$scheme = is_ssl() ? 'https' : 'http';
	$origin = ( $host !== '' ) ? $scheme . '://' . $host : '';
	return $origin;
}

/**
 * -scaled sonekini path'ten kaldırır (logo-header-scaled.png → logo-header.png).
 * Taşıma sonrası sunucuda sadece orijinal dosya varsa 404 önlenir.
 */
function nextcore_strip_scaled_from_path( $path ) {
	if ( ! is_string( $path ) || $path === '' ) {
		return $path;
	}
	return preg_replace( '/-scaled\.(jpe?g|png|gif|webp)$/i', '.$1', $path );
}

/**
 * Option veya herhangi bir yerde saklanan resim URL'sini mevcut site adresiyle döndürür.
 * Logo, arka plan görseli vb. için kullanılabilir. -scaled dosya yoksa orijinal kullanılır.
 */
function nextcore_fix_image_url( $url ) {
	if ( ! is_string( $url ) || $url === '' || strpos( $url, 'wp-content/uploads' ) === false ) {
		return $url;
	}
	$origin = nextcore_current_origin();
	if ( $origin === '' ) {
		return $url;
	}
	$parsed = wp_parse_url( $url );
	if ( empty( $parsed['path'] ) ) {
		return $url;
	}
	$path = nextcore_strip_scaled_from_path( $parsed['path'] );
	return $origin . $path . ( isset( $parsed['query'] ) ? '?' . $parsed['query'] : '' );
}

add_filter( 'upload_dir', 'nextcore_fix_upload_dir_url', 10, 1 );
function nextcore_fix_upload_dir_url( $uploads ) {
	$origin = nextcore_current_origin();
	if ( $origin === '' ) {
		return $uploads;
	}
	$parsed = wp_parse_url( $uploads['baseurl'] );
	if ( empty( $parsed['path'] ) ) {
		return $uploads;
	}
	$base = $origin . $parsed['path'];
	$uploads['baseurl'] = $base;
	$uploads['url']     = $base . ( isset( $uploads['subdir'] ) ? $uploads['subdir'] : '' );
	return $uploads;
}

add_filter( 'wp_get_attachment_url', 'nextcore_fix_attachment_url', 10, 2 );
function nextcore_fix_attachment_url( $url, $attachment_id ) {
	if ( empty( $url ) ) {
		return $url;
	}
	$origin = nextcore_current_origin();
	if ( $origin === '' ) {
		return $url;
	}
	$parsed = wp_parse_url( $url );
	if ( empty( $parsed['path'] ) ) {
		return $url;
	}
	$path = nextcore_strip_scaled_from_path( $parsed['path'] );
	return $origin . $path . ( isset( $parsed['query'] ) ? '?' . $parsed['query'] : '' );
}

add_filter( 'the_content', 'nextcore_fix_content_image_urls', 20, 1 );
function nextcore_fix_content_image_urls( $content ) {
	if ( ! is_string( $content ) || $content === '' ) {
		return $content;
	}
	$origin = nextcore_current_origin();
	if ( $origin === '' ) {
		return $content;
	}
	// Sayfa/içerikteki img src ve srcset'teki eski domain'i mevcut origin ile değiştir (wp-content/uploads içeren URL'ler)
	if ( strpos( $content, 'wp-content/uploads' ) === false ) {
		return $content;
	}
	$content = preg_replace_callback(
		'#(src|srcset)=(["\'])([^"\']*wp-content/uploads[^"\']*)\2#',
		function ( $m ) use ( $origin ) {
			$attr   = $m[1];
			$quote  = $m[2];
			$url    = $m[3];
			$parsed = wp_parse_url( $url );
			if ( ! empty( $parsed['path'] ) ) {
				$path  = nextcore_strip_scaled_from_path( $parsed['path'] );
				$fixed = $origin . $path . ( isset( $parsed['query'] ) ? '?' . $parsed['query'] : '' );
				return $attr . '=' . $quote . $fixed . $quote;
			}
			return $m[0];
		},
		$content
	);
	return $content;
}

/**
 * REST API attachment yanıtındaki tüm resim URL'lerini düzeltir (Ortam Kütüphanesi grid küçük resimleri).
 */
add_filter( 'rest_prepare_attachment', 'nextcore_fix_rest_attachment_urls', 10, 3 );
function nextcore_fix_rest_attachment_urls( $response, $post, $request ) {
	if ( ! $response instanceof WP_REST_Response ) {
		return $response;
	}
	$origin = nextcore_current_origin();
	if ( $origin === '' ) {
		return $response;
	}
	$data = $response->get_data();
	if ( ! is_array( $data ) ) {
		return $response;
	}
	if ( ! empty( $data['source_url'] ) && strpos( $data['source_url'], 'wp-content/uploads' ) !== false ) {
		$parsed = wp_parse_url( $data['source_url'] );
		if ( ! empty( $parsed['path'] ) ) {
			$path = nextcore_strip_scaled_from_path( $parsed['path'] );
			$data['source_url'] = $origin . $path . ( isset( $parsed['query'] ) ? '?' . $parsed['query'] : '' );
		}
	}
	if ( ! empty( $data['media_details']['sizes'] ) && is_array( $data['media_details']['sizes'] ) ) {
		foreach ( $data['media_details']['sizes'] as $size => $size_data ) {
			if ( ! empty( $size_data['source_url'] ) && strpos( $size_data['source_url'], 'wp-content/uploads' ) !== false ) {
				$parsed = wp_parse_url( $size_data['source_url'] );
				if ( ! empty( $parsed['path'] ) ) {
					$path = nextcore_strip_scaled_from_path( $parsed['path'] );
					$data['media_details']['sizes'][ $size ]['source_url'] = $origin . $path . ( isset( $parsed['query'] ) ? '?' . $parsed['query'] : '' );
				}
			}
		}
	}
	$response->set_data( $data );
	return $response;
}
