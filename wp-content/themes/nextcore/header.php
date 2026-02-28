<?php
/**
 * Header - Admin panelinden yönetilebilir
 * @package nextcore
 */

// Header ayarlarını al
$header_logo    = get_option( 'eternal_general_header_logo', '' );
$ticker_items   = get_option( 'eternal_general_ticker_items', [] );
$topbar_email   = get_option( 'eternal_general_topbar_email', 'info@entrymarkcarpets.com' );
$topbar_phone   = get_option( 'eternal_general_topbar_phone', '+90 123 456 78 90' );
$social_ig      = get_option( 'eternal_general_social_instagram', '' );
$social_fb      = get_option( 'eternal_general_social_facebook', '' );
$social_li      = get_option( 'eternal_general_social_linkedin', '' );
$social_pin     = get_option( 'eternal_general_social_pinterest', '' );

// Varsayılan ticker items
if ( ! is_array( $ticker_items ) || empty( $ticker_items ) ) {
    $ticker_items = [
        [ 'text' => 'Free Shipping on Orders Over $500' ],
        [ 'text' => 'New Collection — Aegean Luxe Series Now Available' ],
        [ 'text' => 'Custom Design Lab — Create Your Own Carpet' ],
        [ 'text' => 'Trusted by 500+ Hotels Worldwide' ],
        [ 'text' => 'Premium Quality Since 1992' ],
        [ 'text' => 'Request a Free Sample Today' ],
    ];
}

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Outfit:wght@300;400;500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
    
    <style id="nextcore-header-styles">
        :root {
            --dark: #2D3748;
            --navy: #215387;
            --gold: #f5a524;
            --light: #F7F8F8;
            --gold-muted: #DDA944;
            --dark-90: rgba(45, 55, 72, 0.9);
            --dark-50: rgba(45, 55, 72, 0.5);
            --navy-light: rgba(33, 83, 135, 0.08);
            --transition-smooth: cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-sm: 0 2px 8px rgba(45, 55, 72, 0.06);
            --shadow-md: 0 8px 30px rgba(45, 55, 72, 0.1);
        }

        /* Ticker Bar */
        .ticker-bar {
            background: var(--dark);
            color: var(--light);
            overflow: hidden;
            position: relative;
            z-index: 1001;
            height: 38px;
            display: flex;
            align-items: center;
        }
        .ticker-bar::before, .ticker-bar::after {
            content: '';
            position: absolute;
            top: 0; bottom: 0;
            width: 80px;
            z-index: 2;
            pointer-events: none;
        }
        .ticker-bar::before {
            left: 0;
            background: linear-gradient(to right, var(--dark) 20%, transparent);
        }
        .ticker-bar::after {
            right: 0;
            background: linear-gradient(to left, var(--dark) 20%, transparent);
        }
        .ticker-track {
            display: flex;
            animation: tickerScroll 40s linear infinite;
            white-space: nowrap;
        }
        .ticker-track:hover { animation-play-state: paused; }
        .ticker-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0 40px;
            font-size: 12.5px;
            font-weight: 400;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            flex-shrink: 0;
        }
        .ticker-item .ticker-dot {
            width: 4px;
            height: 4px;
            background: var(--gold);
            border-radius: 50%;
            flex-shrink: 0;
        }
        .ticker-item .ticker-gold { color: var(--gold); font-weight: 600; }
        @keyframes tickerScroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* Utility Bar */
        .utility-bar {
            background: var(--light);
            border-bottom: 1px solid rgba(45, 55, 72, 0.06);
            padding: 0 50px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12.5px;
            color: var(--dark-50);
        }
        .utility-left {
            display: flex;
            align-items: center;
            gap: 24px;
        }
        .utility-left a {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--dark-50);
            transition: color 0.3s ease;
            text-decoration: none;
        }
        .utility-left a:hover { color: var(--navy); }
        .utility-left svg { width: 14px; height: 14px; }
        .utility-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .utility-social {
            display: flex;
            gap: 12px;
        }
        .utility-social a {
            width: 26px;
            height: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
            color: var(--dark-50);
        }
        .utility-social a:hover {
            color: var(--navy);
            background: var(--navy-light);
        }
        .utility-social a svg { width: 14px; height: 14px; }

        /* Main Header */
        .main-header {
            background: #fff;
            padding: 0 50px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow-sm);
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 50px;
            flex: 1;
        }
        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            flex-shrink: 0;
        }
        .logo-img {
            height: 58px;
            width: auto;
            display: block;
            object-fit: contain;
        }

        /* Main Navigation */
        .main-nav {
            display: flex;
            align-items: center;
            height: 100%;
            flex: 1;
        }
        .main-nav > ul {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            list-style: none !important;
            margin: 0 !important;
            padding: 0 !important;
            height: 100%;
            gap: 6px;
        }
        .main-nav > ul > li {
            position: relative !important;
            height: 100% !important;
            display: flex !important;
            align-items: center !important;
        }
        .nav-link {
            padding: 8px 18px;
            font-size: 14px;
            font-weight: 500;
            color: var(--dark);
            letter-spacing: 0.2px;
            display: flex;
            align-items: center;
            gap: 5px;
            position: relative;
            transition: all 0.3s ease;
            border-radius: 6px;
            height: 100%;
            text-decoration: none;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2.5px;
            background: linear-gradient(90deg, var(--gold), var(--gold-muted));
            border-radius: 2px;
            transition: width 0.4s ease;
        }
        .nav-link:hover { color: var(--navy); }
        .nav-link:hover::after,
        .nav-link.active::after { width: 60%; }
        .nav-link .chevron {
            width: 10px;
            height: 10px;
            transition: transform 0.3s ease;
            opacity: 0.5;
            flex-shrink: 0;
        }
        .main-nav > ul > li:hover > .nav-link .chevron {
            transform: rotate(180deg);
            opacity: 1;
        }

        /* Sub Menu (Dropdown) */
        .sub-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: #fff;
            border-radius: 0 0 8px 8px;
            box-shadow: var(--shadow-md);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 999;
            min-width: 180px;
            padding: 8px 0;
            list-style: none;
            margin: 0;
        }
        .main-nav > ul > li:hover > .sub-menu {
            opacity: 1;
            visibility: visible;
        }
        .sub-menu li {
            height: auto;
            display: block;
        }
        .sub-menu .nav-link {
            padding: 10px 18px;
            height: auto;
            font-size: 13px;
            white-space: nowrap;
        }
        .sub-menu .nav-link::after { display: none; }
        .sub-menu .nav-link:hover { background: var(--navy-light); }

        /* Header Actions */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .header-search-toggle {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark);
            transition: all 0.3s ease;
            background: none;
            border: none;
            cursor: pointer;
        }
        .header-search-toggle:hover {
            background: var(--navy-light);
            color: var(--navy);
        }
        .header-search-toggle svg { width: 20px; height: 20px; }
        .action-btn {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            color: var(--dark);
            transition: all 0.3s ease;
            background: none;
            border: none;
            cursor: pointer;
        }
        .action-btn:hover {
            background: var(--navy-light);
            color: var(--navy);
            transform: translateY(-1px);
        }
        .action-btn svg { width: 21px; height: 21px; }
        .header-lang-switch {
            display: flex;
            align-items: center;
            margin-left: 4px;
            margin-right: 2px;
        }
        .header-lang-switch #google_translate_element,
        .header-lang-switch .goog-te-gadget {
            display: flex !important;
            align-items: center !important;
            font-size: 13px !important;
        }
        .header-lang-switch .goog-te-gadget-simple {
            background: transparent !important;
            border: none !important;
            padding: 6px 10px !important;
            min-height: 38px !important;
            border-radius: 8px !important;
            transition: background 0.2s ease;
        }
        .header-lang-switch .goog-te-gadget-simple:hover {
            background: var(--navy-light) !important;
        }
        .action-badge {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 18px;
            height: 18px;
            background: linear-gradient(135deg, var(--gold), var(--gold-muted));
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .action-divider {
            width: 1px;
            height: 28px;
            background: rgba(45, 55, 72, 0.1);
            margin: 0 6px;
        }
        .auth-area {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: 8px;
        }
        .btn-login {
            padding: 9px 20px;
            font-size: 13px;
            font-weight: 600;
            color: var(--dark);
            border: 1.5px solid rgba(45, 55, 72, 0.15);
            border-radius: 8px;
            transition: all 0.3s ease;
            letter-spacing: 0.3px;
            background: transparent;
            cursor: pointer;
        }
        .btn-login:hover {
            border-color: var(--navy);
            color: var(--navy);
            background: var(--navy-light);
        }
        .btn-register {
            padding: 9px 22px;
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, var(--dark), var(--navy));
            border-radius: 8px;
            transition: all 0.4s ease;
            letter-spacing: 0.3px;
            border: none;
            cursor: pointer;
        }
        .btn-register:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            width: 48px;
            height: 48px;
            min-width: 48px;
            min-height: 48px;
            border-radius: 10px;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--dark);
            margin-left: 8px;
            padding: 0;
        }
        .mobile-menu-toggle:hover {
            background: var(--navy-light);
            color: var(--navy);
        }
        .mobile-menu-toggle svg {
            width: 28px;
            height: 28px;
            flex-shrink: 0;
        }

        /* Mobile Menu Overlay */
        .mobile-menu-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(45, 55, 72, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1001;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .mobile-menu-overlay.active {
            display: block;
            opacity: 1;
        }
        .mobile-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 85%;
            min-width: 280px;
            max-width: 360px;
            height: 100vh;
            height: 100dvh;
            background: #fff;
            z-index: 1002;
            box-shadow: var(--shadow-md);
            transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            -webkit-overflow-scrolling: touch;
        }
        .mobile-menu.active {
            right: 0;
        }
        .mobile-menu-header {
            padding: 20px;
            border-bottom: 1px solid rgba(45, 55, 72, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .mobile-menu-title {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
        }
        .mobile-menu-close {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--dark);
        }
        .mobile-menu-close:hover {
            background: var(--navy-light);
            color: var(--navy);
        }
        .mobile-menu-close svg {
            width: 22px;
            height: 22px;
        }
        .mobile-menu-content {
            flex: 1;
            padding: 16px 0;
        }
        .mobile-menu-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .mobile-menu-list li {
            border-bottom: 1px solid rgba(45, 55, 72, 0.06);
        }
        .mobile-menu-list a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            font-size: 15px;
            font-weight: 500;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .mobile-menu-list a:hover {
            background: var(--navy-light);
            color: var(--navy);
        }
        .mobile-menu-list .sub-menu {
            display: none;
            background: rgba(45, 55, 72, 0.02);
            padding: 8px 0;
        }
        .mobile-menu-list .sub-menu.active {
            display: block;
        }
        .mobile-menu-list .sub-menu a {
            padding: 12px 20px 12px 36px;
            font-size: 14px;
        }
        .mobile-menu-toggle-arrow {
            width: 20px;
            height: 20px;
            transition: transform 0.3s ease;
        }
        .mobile-menu-list li.active .mobile-menu-toggle-arrow {
            transform: rotate(180deg);
        }

        /* Mobile Menu Auth Buttons */
        .mobile-menu-auth {
            display: flex;
            gap: 12px;
            padding: 16px 20px;
            border-bottom: 1px solid rgba(45, 55, 72, 0.08);
        }
        .mobile-menu-btn {
            flex: 1;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .mobile-menu-btn-register {
            background: linear-gradient(135deg, var(--dark), var(--navy));
            color: #fff;
        }
        .mobile-menu-btn-login {
            background: var(--light);
            color: var(--dark);
            border: 1.5px solid rgba(45, 55, 72, 0.15);
        }

        /* Mobile Menu Quick Actions */
        .mobile-menu-quick {
            display: flex;
            padding: 12px 20px;
            gap: 16px;
            border-bottom: 1px solid rgba(45, 55, 72, 0.08);
        }
        .mobile-menu-quick-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            background: var(--light);
            border-radius: 10px;
            color: var(--dark);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            flex: 1;
            position: relative;
        }
        .mobile-menu-quick-item svg {
            width: 18px;
            height: 18px;
        }
        .mobile-menu-utility {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 12px 20px;
            padding: 12px 16px;
            border-bottom: 1px solid rgba(45, 55, 72, 0.08);
            background: rgba(247, 248, 248, 0.6);
        }
        .mobile-menu-utility-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--dark-50);
            text-decoration: none;
            font-size: 13px;
            padding: 6px 0;
            transition: color 0.2s ease;
        }
        .mobile-menu-utility-link:hover {
            color: var(--navy);
        }
        .mobile-menu-utility-link svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }
        .mobile-menu-utility-lang {
            display: flex;
            align-items: center;
        }
        .mobile-menu-utility-lang .goog-te-gadget,
        .mobile-menu-utility-lang #google_translate_element {
            display: flex !important;
            align-items: center !important;
        }
        .mobile-menu-utility-lang .goog-te-gadget-simple {
            background: transparent !important;
            border: none !important;
            padding: 4px 8px !important;
            font-size: 13px !important;
        }
        .mobile-menu-badge {
            position: absolute;
            top: 6px;
            right: 10px;
            width: 18px;
            height: 18px;
            background: linear-gradient(135deg, var(--gold), var(--gold-muted));
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Responsive Styles */
        @media (max-width: 1024px) {
            .utility-bar {
                padding: 0 16px;
                height: 36px;
                flex-wrap: nowrap;
                gap: 8px;
                justify-content: space-between;
                align-items: center;
            }
            .utility-left {
                gap: 12px;
                font-size: 11px;
                display: flex;
                align-items: center;
                flex-shrink: 0;
            }
            .utility-left a {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                white-space: nowrap;
            }
            .utility-right {
                gap: 12px;
                display: flex;
                align-items: center;
                flex-shrink: 0;
            }
            .utility-social a {
                width: 22px;
                height: 22px;
            }
            .utility-social a svg {
                width: 12px;
                height: 12px;
            }
            .lang-switch {
                display: flex !important;
                align-items: center !important;
                flex-shrink: 0;
            }
            .utility-right .lang-switch #google_translate_element,
            .utility-right .lang-switch .goog-te-gadget {
                display: flex !important;
                align-items: center !important;
            }
            .utility-right .lang-switch .goog-te-gadget-simple {
                padding: 2px 6px !important;
                min-height: 28px !important;
            }
            .main-header {
                padding: 0 16px !important;
                height: 60px !important;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                z-index: 1000 !important;
            }
            .header-left {
                gap: 12px !important;
                flex: 0 0 auto !important;
            }
            .logo-img {
                height: 40px !important;
                max-width: 140px !important;
            }
            .main-nav {
                display: none !important;
            }
            .mobile-menu-toggle {
                display: flex !important;
                width: 48px !important;
                height: 48px !important;
                min-width: 48px !important;
                min-height: 48px !important;
                margin-left: 6px !important;
            }
            .mobile-menu-toggle svg {
                width: 28px !important;
                height: 28px !important;
            }
            .header-actions {
                gap: 2px !important;
                flex: 0 0 auto !important;
            }
            .header-search-toggle {
                display: none !important;
            }
            .action-btn[aria-label="Wishlist"] {
                display: none !important;
            }
            .action-divider {
                display: none !important;
            }
            .auth-area {
                display: none !important;
            }
            .action-btn[aria-label="Sepet"],
            .emc-header-cart {
                display: flex !important;
                width: 40px !important;
                height: 40px !important;
            }
            /* Body padding for fixed header */
            body {
                padding-top: 60px !important;
            }
        }

        @media (max-width: 640px) {
            .ticker-bar {
                height: 34px;
                min-height: 34px;
            }
            .ticker-item {
                font-size: 11px;
                padding: 0 24px;
            }
            .ticker-bar::before,
            .ticker-bar::after {
                width: 40px;
            }
            .utility-bar {
                padding: 0 12px;
                height: auto;
                min-height: 36px;
                gap: 6px;
                flex-wrap: wrap;
                justify-content: center;
                align-items: center;
            }
            .utility-left {
                gap: 8px;
                font-size: 10px;
                display: flex;
                align-items: center;
            }
            .utility-left a {
                padding: 2px 4px;
                display: inline-flex;
                align-items: center;
                gap: 4px;
            }
            .utility-left a svg {
                width: 12px;
                height: 12px;
                flex-shrink: 0;
            }
            .utility-right {
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .utility-right .lang-switch {
                display: flex !important;
                align-items: center !important;
            }
            .utility-right .lang-switch .goog-te-gadget-simple {
                padding: 2px 4px !important;
                min-height: 26px !important;
                font-size: 11px !important;
            }
            .main-header {
                height: 56px !important;
                padding: 0 12px !important;
            }
            .logo-img {
                height: 36px !important;
                max-width: 120px !important;
            }
            .mobile-menu-toggle {
                width: 48px !important;
                height: 48px !important;
                min-width: 48px !important;
                min-height: 48px !important;
            }
            .mobile-menu-toggle svg {
                width: 28px !important;
                height: 28px !important;
            }
            body {
                padding-top: 56px !important;
            }
        }
    </style>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Ticker Bar -->
<div class="ticker-bar">
    <div class="ticker-track">
        <?php foreach ( $ticker_items as $ti ) : ?>
        <div class="ticker-item">
            <span class="ticker-dot"></span>
            <?php echo wp_kses_post( $ti['text'] ?? '' ); ?>
        </div>
        <?php endforeach; ?>
        <?php foreach ( $ticker_items as $ti ) : ?>
        <div class="ticker-item">
            <span class="ticker-dot"></span>
            <?php echo wp_kses_post( $ti['text'] ?? '' ); ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Utility Bar -->
<div class="utility-bar">
    <div class="utility-left">
        <?php if ( $topbar_email ) : ?>
        <a href="mailto:<?php echo esc_attr( $topbar_email ); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <rect x="2" y="4" width="20" height="16" rx="2"/>
                <path d="m2 4 10 8 10-8"/>
            </svg>
            <?php echo esc_html( $topbar_email ); ?>
        </a>
        <?php endif; ?>
        
        <?php if ( $topbar_phone ) : ?>
        <a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $topbar_phone ) ); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
            <?php echo esc_html( $topbar_phone ); ?>
        </a>
        <?php endif; ?>
    </div>
    
</div>

<!-- Main Header -->
<header class="main-header" id="mainHeader">
    <div class="header-left">
        <!-- Logo -->
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
            <?php if ( ! empty( $header_logo ) ) : ?>
                <img src="<?php echo esc_url( nextcore_fix_image_url( $header_logo ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="logo-img">
            <?php else : ?>
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo-header.png' ); ?>" alt="Entry Mark Carpets" class="logo-img">
            <?php endif; ?>
        </a>

        <!-- Navigation -->
        <nav class="main-nav">
            <?php
            if ( has_nav_menu( 'primary' ) ) {
                wp_nav_menu( [
                    'theme_location' => 'primary',
                    'container'      => false,
                    'items_wrap'     => '<ul>%3$s</ul>',
                    'walker'         => new Nextcore_Mega_Menu_Walker(),
                ] );
            } else {
                echo '<ul><li><a href="' . esc_url( home_url( '/' ) ) . '" class="nav-link">Home</a></li></ul>';
            }
            ?>
        </nav>
    </div>

    <!-- Header Actions -->
    <div class="header-actions">
        <button class="header-search-toggle" aria-label="Search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.35-4.35"/>
            </svg>
        </button>

        <button class="action-btn" aria-label="Wishlist">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
            <span class="action-badge">2</span>
        </button>

        <div class="header-lang-switch lang-switch">
            <?php echo do_shortcode( '[gtranslate]' ); ?>
        </div>

        <?php
        // Sepet sayfası URL'sini al
        $cart_page_id = get_option('emc_cart_page_id', 0);
        $cart_url = $cart_page_id ? get_permalink((int) $cart_page_id) : home_url('/sepet/');
        $cart_count = function_exists('EMC_Cart') ? EMC_Cart::get_count() : 0;
        ?>
        <a href="<?php echo esc_url($cart_url); ?>" class="action-btn emc-header-cart" aria-label="Sepet" data-cart-count="<?php echo intval($cart_count); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18"/>
                <path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
            <span class="action-badge" id="headerCartCount" style="<?php echo $cart_count > 0 ? '' : 'display:none;'; ?>"><?php echo intval($cart_count); ?></span>
        </a>

        <div class="action-divider"></div>

        <div class="auth-area">
            <button class="btn-login">Sign In</button>
            <button class="btn-register">Register</button>
        </div>

        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Menü">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>
    </div>
</header>

<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
    <div class="mobile-menu-header">
        <span class="mobile-menu-title">Menü</span>
        <button class="mobile-menu-close" id="mobileMenuClose" aria-label="Kapat">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    </div>
    
    <!-- Mobile Auth Buttons -->
    <div class="mobile-menu-auth">
        <a href="#" class="mobile-menu-btn mobile-menu-btn-register">Kayıt Ol</a>
        <a href="#" class="mobile-menu-btn mobile-menu-btn-login">Giriş Yap</a>
    </div>
    
    <!-- Mobile Utility: Mail, Phone, Language -->
    <div class="mobile-menu-utility">
        <?php if ( $topbar_email ) : ?>
        <a href="mailto:<?php echo esc_attr( $topbar_email ); ?>" class="mobile-menu-utility-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 4 10 8 10-8"/></svg>
            <span><?php echo esc_html( $topbar_email ); ?></span>
        </a>
        <?php endif; ?>
        <?php if ( $topbar_phone ) : ?>
        <a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $topbar_phone ) ); ?>" class="mobile-menu-utility-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            <span><?php echo esc_html( $topbar_phone ); ?></span>
        </a>
        <?php endif; ?>
        <div class="mobile-menu-utility-lang">
            <?php echo do_shortcode( '[gtranslate]' ); ?>
        </div>
    </div>
    
    <!-- Mobile Quick Actions -->
    <div class="mobile-menu-quick">
        <a href="#" class="mobile-menu-quick-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.35-4.35"/>
            </svg>
            <span>Ara</span>
        </a>
        <a href="#" class="mobile-menu-quick-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
            <span>Favorilerim</span>
            <span class="mobile-menu-badge">2</span>
        </a>
    </div>
    
    <div class="mobile-menu-content">
        <?php
        if ( has_nav_menu( 'primary' ) ) {
            wp_nav_menu( [
                'theme_location' => 'primary',
                'container'      => false,
                'items_wrap'     => '<ul class="mobile-menu-list">%3$s</ul>',
                'walker'         => new Nextcore_Mobile_Menu_Walker(),
            ] );
        } else {
            echo '<ul class="mobile-menu-list"><li><a href="' . esc_url( home_url( '/' ) ) . '">Anasayfa</a></li></ul>';
        }
        ?>
    </div>
</div>

<script>
// Mobile Menu Functionality
(function() {
    const menuToggle = document.getElementById('mobileMenuToggle');
    const menuClose = document.getElementById('mobileMenuClose');
    const menuOverlay = document.getElementById('mobileMenuOverlay');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (!menuToggle || !mobileMenu) return;
    
    function openMenu() {
        mobileMenu.classList.add('active');
        menuOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeMenu() {
        mobileMenu.classList.remove('active');
        menuOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    menuToggle.addEventListener('click', openMenu);
    if (menuClose) menuClose.addEventListener('click', closeMenu);
    if (menuOverlay) menuOverlay.addEventListener('click', closeMenu);
    
    // Sub-menu toggle
    const menuItems = document.querySelectorAll('.mobile-menu-list li');
    menuItems.forEach(item => {
        const link = item.querySelector('a');
        const subMenu = item.querySelector('.sub-menu');
        
        if (subMenu && link) {
            link.addEventListener('click', function(e) {
                if (subMenu.classList.contains('active')) {
                    subMenu.classList.remove('active');
                    item.classList.remove('active');
                } else {
                    e.preventDefault();
                    subMenu.classList.add('active');
                    item.classList.add('active');
                }
            });
        }
    });
    
    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
            closeMenu();
        }
    });
})();
</script>
