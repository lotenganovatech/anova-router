<?php
/**
 * Main template: every request is routed to a small template part.
 * Builders (Anova Canvas page templates, Elementor Pro locations) take over before this runs.
 *
 * @package AnovaRouter
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( is_singular() ) {
    if ( ! anova_router_content_location( 'single' ) ) {
        get_template_part( 'template-parts/single' );
    }
} elseif ( is_404() ) {
    if ( ! anova_router_content_location( 'single' ) ) {
        get_template_part( 'template-parts/404' );
    }
} elseif ( ! anova_router_content_location( 'archive' ) ) {
    get_template_part( 'template-parts/archive' );
}

get_footer();
