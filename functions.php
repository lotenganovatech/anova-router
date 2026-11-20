<?php
/**
 * Anova Router — tema kosong untuk page builder.
 *
 * Urutan render lokasi header / footer:
 *   1. Anova Canvas (Theme Builder)        → acv_do_location()
 *   2. Elementor Pro (Theme Builder)       → elementor_theme_do_location()
 *   3. Builder lain lewat filter            → 'anova_router/do_location'
 *   4. Header / footer bawaan tema (bisa dimatikan di Customizer)
 *
 * @package AnovaRouter
 */

defined( 'ABSPATH' ) || exit;

define( 'ANOVA_ROUTER_VERSION', '1.0.3' );
define( 'ANOVA_ROUTER_DIR', get_template_directory() );
define( 'ANOVA_ROUTER_URI', get_template_directory_uri() );

require_once ANOVA_ROUTER_DIR . '/inc/customizer.php';
require_once ANOVA_ROUTER_DIR . '/inc/admin.php';
require_once ANOVA_ROUTER_DIR . '/inc/screenshot.php';

/* ------------------------------------------------------------------ *
 * Setup
 * ------------------------------------------------------------------ */
add_action( 'after_setup_theme', 'anova_router_setup' );
function anova_router_setup() {
    load_theme_textdomain( 'anova-router', ANOVA_ROUTER_DIR . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 260, 'flex-height' => true, 'flex-width' => true ) );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style', 'navigation-widgets' ) );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/css/editor.css' );

    // Builders: Canvas prints its header/footer through the theme (no output buffering),
    // WooCommerce gets clean wrappers.
    add_theme_support( 'anova-canvas' );
    add_theme_support( 'woocommerce' );

    register_nav_menus( array(
        'primary' => esc_html__( 'Primary menu', 'anova-router' ),
        'footer'  => esc_html__( 'Footer menu', 'anova-router' ),
    ) );

    $GLOBALS['content_width'] = (int) apply_filters( 'anova_router/content_width', anova_router_container_width() );
}

/* ------------------------------------------------------------------ *
 * Assets
 * ------------------------------------------------------------------ */
add_action( 'wp_enqueue_scripts', 'anova_router_assets' );
function anova_router_assets() {
    if ( anova_router_option( 'load_styles' ) ) {
        wp_enqueue_style( 'anova-router', ANOVA_ROUTER_URI . '/assets/css/theme.css', array(), ANOVA_ROUTER_VERSION );
        wp_add_inline_style( 'anova-router', anova_router_inline_css() );
    }
    if ( has_nav_menu( 'primary' ) && anova_router_show_default( 'header' ) ) {
        wp_enqueue_script( 'anova-router-nav', ANOVA_ROUTER_URI . '/assets/js/nav.js', array(), ANOVA_ROUTER_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
    }
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

function anova_router_inline_css() {
    $css = ':root{--ar-container:' . anova_router_container_width() . 'px;';
    $accent = sanitize_hex_color( (string) anova_router_option( 'accent' ) );
    if ( $accent ) { $css .= '--ar-accent:' . $accent . ';'; }
    if ( 'boxed' === anova_router_layout() ) {
        $page_bg = sanitize_hex_color( (string) anova_router_option( 'boxed_bg' ) );
        if ( $page_bg ) { $css .= '--ar-page-bg:' . $page_bg . ';'; }
    }
    return $css . '}';
}

/* ------------------------------------------------------------------ *
 * Options (Customizer)
 * ------------------------------------------------------------------ */
function anova_router_defaults() {
    return array(
        'layout'          => 'custom',
        'container_width' => 1200,
        'custom_width'    => 1200,
        'boxed_bg'        => '#eef2f7',
        'default_header'  => 1,
        'default_footer'  => 1,
        'page_title'      => 1,
        'load_styles'     => 1,
        'accent'          => '#3159dd',
        'footer_text'     => '',
    );
}

function anova_router_option( $key ) {
    $defaults = anova_router_defaults();
    return get_theme_mod( 'anova_router_' . $key, $defaults[ $key ] ?? '' );
}

/**
 * Tata letak halaman: 'full' | 'boxed' | 'custom'.
 *
 * full   — kontainer tanpa batas lebar (mengikuti layar, gutter tetap).
 * boxed  — situs dibungkus kotak berlebar tetap; latar halaman terlihat di luar kotak.
 * custom — lebar maksimum ditentukan sendiri (tanpa kotak/latar luar).
 */
function anova_router_layout() {
    $layout = anova_router_sanitize_layout( (string) anova_router_option( 'layout' ) );
    return (string) apply_filters( 'anova_router/layout', $layout );
}

function anova_router_sanitize_layout( $value ) {
    return in_array( $value, array( 'full', 'boxed', 'custom' ), true ) ? $value : 'boxed';
}

/** Lebar kontainer aktif dalam px (dipakai CSS, content_width, dan Anova Canvas). */
function anova_router_container_width() {
    $layout = anova_router_layout();
    $custom = ( 'custom' === $layout );
    $width  = absint( anova_router_option( $custom ? 'custom_width' : 'container_width' ) );
    // Situs lama (sebelum ada tata letak) memakai "Lebar kontainer" sebagai lebar custom.
    if ( $custom && null === get_theme_mod( 'anova_router_custom_width', null ) ) {
        $width = absint( anova_router_option( 'container_width' ) );
    }
    if ( ! $width ) {
        $width = 1200;
    }
    return min( 2400, max( $custom ? 480 : 600, $width ) );
}

/**
 * Share the container width with Anova Canvas (used when Canvas has no width of its own).
 * Pada tata letak full, Canvas diberi lebar maksimum yang didukung tema (1920) agar konten ikut melebar.
 */
add_filter( 'anova_canvas/theme_container_width', 'anova_router_canvas_container_width' );
function anova_router_canvas_container_width() {
    if ( 'full' === anova_router_layout() ) {
        return (int) apply_filters( 'anova_router/full_canvas_width', 1920 );
    }
    return anova_router_container_width();
}

/* ------------------------------------------------------------------ *
 * Locations
 * ------------------------------------------------------------------ */

/**
 * Let a page builder render a theme location. Returns true when a builder handled it.
 *
 * @param string $location 'header' | 'footer' (other names are passed to Elementor & filters as-is).
 */
function anova_router_location( $location ) {
    if ( function_exists( 'acv_do_location' ) && acv_do_location( $location ) ) {
        return true;
    }
    if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( $location ) ) {
        return true;
    }
    /**
     * Other builders can print their template here and return true.
     *
     * @param bool   $handled  Whether the location was printed.
     * @param string $location Location name.
     */
    return (bool) apply_filters( 'anova_router/do_location', false, $location );
}

/** Whether the theme's own simple header / footer may be printed. */
function anova_router_show_default( $location ) {
    return (bool) apply_filters( 'anova_router/show_default_' . $location, (bool) anova_router_option( 'default_' . $location ) );
}

/** Elementor Pro: register every core location (header, footer, single, archive). */
add_action( 'elementor/theme/register_locations', 'anova_router_elementor_locations' );
function anova_router_elementor_locations( $manager ) {
    if ( is_object( $manager ) && method_exists( $manager, 'register_all_core_location' ) ) {
        $manager->register_all_core_location();
    }
}

/** Single, archive and 404 content through Elementor Pro / filters; returns true when handled. */
function anova_router_content_location( $location ) {
    if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( $location ) ) {
        return true;
    }
    return (bool) apply_filters( 'anova_router/do_location', false, $location );
}

/* ------------------------------------------------------------------ *
 * Body classes & small helpers
 * ------------------------------------------------------------------ */
add_filter( 'body_class', 'anova_router_body_class' );
function anova_router_body_class( $classes ) {
    $classes[] = 'anova-router';
    $classes[] = 'ar-layout-' . anova_router_layout();
    if ( ! anova_router_option( 'load_styles' ) ) { $classes[] = 'ar-no-styles'; }
    if ( is_page_template( 'templates/template-canvas.php' ) ) { $classes[] = 'ar-template-canvas'; }
    if ( is_page_template( 'templates/template-full-width.php' ) ) { $classes[] = 'ar-template-full-width'; }
    return $classes;
}

/** Whether the page title should be printed for the current singular post. */
function anova_router_show_title() {
    $show = (bool) anova_router_option( 'page_title' );
    if ( is_singular() && get_post_meta( get_the_ID(), '_anova_router_hide_title', true ) ) { $show = false; }
    return (bool) apply_filters( 'anova_router/show_page_title', $show );
}

/** Template halaman yang selalu selebar layar, apa pun tata letak situsnya. */
function anova_router_is_bleed_template() {
    return is_page_template( 'templates/template-full-width.php' );
}

/**
 * Pembungkus kotak untuk tata letak "boxed" (dibuka di header.php, ditutup di footer.php).
 * Pada tata letak full, custom, dan template "Lebar penuh" tidak ada pembungkus sama sekali.
 */
function anova_router_shell_open() {
    if ( 'boxed' === anova_router_layout() && ! anova_router_is_bleed_template() ) {
        echo '<div class="ar-shell">';
    }
}

function anova_router_shell_close() {
    if ( 'boxed' === anova_router_layout() && ! anova_router_is_bleed_template() ) {
        echo '</div><!-- .ar-shell -->';
    }
}

/** WooCommerce: replace the default wrappers with the theme container (after WooCommerce adds its hooks). */
add_action( 'wp', 'anova_router_woocommerce_wrappers' );
function anova_router_woocommerce_wrappers() {
    if ( ! class_exists( 'WooCommerce' ) ) { return; }
    remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
    remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
    add_action( 'woocommerce_before_main_content', 'anova_router_woo_open', 10 );
    add_action( 'woocommerce_after_main_content', 'anova_router_woo_close', 10 );
}
function anova_router_woo_open() { echo '<div class="ar-container ar-woo">'; }
function anova_router_woo_close() { echo '</div>'; }

/** Fallback for wp_body_open on very old WordPress versions. */
if ( ! function_exists( 'wp_body_open' ) ) {
    function wp_body_open() {
        do_action( 'wp_body_open' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
    }
}

/* ------------------------------------------------------------------ *
 * "Sembunyikan judul" per halaman (sidebar block editor & editor klasik)
 * ------------------------------------------------------------------ */
add_action( 'init', 'anova_router_register_meta' );
function anova_router_register_meta() {
    foreach ( array( 'post', 'page' ) as $type ) {
        register_post_meta( $type, '_anova_router_hide_title', array(
            'type' => 'boolean', 'single' => true, 'show_in_rest' => true, 'default' => false,
            'auth_callback' => function () { return current_user_can( 'edit_posts' ); },
        ) );
    }
}

add_action( 'add_meta_boxes', 'anova_router_title_box' );
function anova_router_title_box() {
    foreach ( array( 'post', 'page' ) as $type ) {
        add_meta_box( 'anova-router-title', esc_html__( 'Anova Router', 'anova-router' ), 'anova_router_title_box_html', $type, 'side', 'low' );
    }
}

function anova_router_title_box_html( $post ) {
    wp_nonce_field( 'anova_router_title', 'anova_router_title_nonce' );
    printf(
        '<label><input type="checkbox" name="anova_router_hide_title" value="1" %s> %s</label>',
        checked( (bool) get_post_meta( $post->ID, '_anova_router_hide_title', true ), true, false ),
        esc_html__( 'Hide page title', 'anova-router' )
    );
}

add_action( 'save_post', 'anova_router_save_title_box' );
function anova_router_save_title_box( $post_id ) {
    if ( ! isset( $_POST['anova_router_title_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['anova_router_title_nonce'] ) ), 'anova_router_title' ) ) { return; }
    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) { return; }
    if ( empty( $_POST['anova_router_hide_title'] ) ) {
        delete_post_meta( $post_id, '_anova_router_hide_title' );
    } else {
        update_post_meta( $post_id, '_anova_router_hide_title', 1 );
    }
}
