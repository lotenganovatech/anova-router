<?php
/**
 * Info tema di Tampilan → Tema mengikuti bahasa WordPress.
 *
 * Screenshot:
 * - Bahasa Indonesia (id_ID) → screenshot.jpg (bawaan).
 * - Bahasa lain              → screenshot-en.jpg.
 * Bahasa = bahasa admin pengguna (Profil → Bahasa), yang bawaannya mengikuti Bahasa Situs —
 * sama dengan bahasa deskripsi tema & teks lain (terjemahan di folder languages/).
 *
 * Tag: label tag yang tidak diterjemahkan WordPress (custom-menu, threaded-comments,
 * translation-ready) diberi label terjemahan tema.
 *
 * WordPress tidak punya filter untuk WP_Theme::get_screenshot(), jadi URL diganti lewat
 * 'wp_prepare_themes_for_js' — data yang dipakai grid & modal detail di Tampilan → Tema
 * serta daftar tema di Customizer.
 *
 * @package AnovaRouter
 */

defined( 'ABSPATH' ) || exit;

/** 'id' atau 'en' sesuai bahasa WordPress yang sedang dipakai. Bisa diubah lewat filter 'anova_router/screenshot_lang'. */
function anova_router_screenshot_lang() {
    $locale = (string) determine_locale();
    $lang   = 0 === strpos( $locale, 'id' ) ? 'id' : 'en';
    return (string) apply_filters( 'anova_router/screenshot_lang', $lang, $locale );
}

/** Label terjemahan untuk tag yang tidak dikenal daftar fitur WordPress. */
function anova_router_tag_labels() {
    return array(
        'custom-menu'       => __( 'Custom Menu', 'anova-router' ),
        'threaded-comments' => __( 'Threaded Comments', 'anova-router' ),
        'translation-ready' => __( 'Translation Ready', 'anova-router' ),
    );
}

/** URL screenshot sesuai bahasa; '' bila memakai screenshot bawaan. */
function anova_router_screenshot_url() {
    $lang = anova_router_screenshot_lang();
    if ( 'id' === $lang ) {
        return '';
    }
    foreach ( array( 'png', 'jpg', 'webp' ) as $ext ) {
        $file = 'screenshot-' . sanitize_key( $lang ) . '.' . $ext;
        if ( file_exists( ANOVA_ROUTER_DIR . '/' . $file ) ) {
            return ANOVA_ROUTER_URI . '/' . $file . '?ver=' . ANOVA_ROUTER_VERSION;
        }
    }
    return '';
}

add_filter( 'wp_prepare_themes_for_js', 'anova_router_localized_screenshot' );
function anova_router_localized_screenshot( $themes ) {
    $slug = get_template(); // folder tema induk (Anova Router), walau aktif lewat child theme.
    $url  = anova_router_screenshot_url();
    if ( ! isset( $themes[ $slug ] ) ) {
        return $themes;
    }
    if ( $url ) {
        $themes[ $slug ]['screenshot'] = array( $url );
    }
    if ( ! empty( $themes[ $slug ]['tags'] ) ) {
        $labels = anova_router_tag_labels();
        $tags   = array_map( 'trim', explode( ',', (string) $themes[ $slug ]['tags'] ) );
        foreach ( $tags as &$tag ) {
            if ( isset( $labels[ $tag ] ) ) {
                $tag = esc_html( $labels[ $tag ] );
            }
        }
        unset( $tag );
        $themes[ $slug ]['tags'] = implode( ', ', $tags );
    }
    return $themes;
}
