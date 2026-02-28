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
