<?php
/**
 * Header: builder location first, simple theme header as fallback.
 *
 * @package AnovaRouter
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'anova-router' ); ?></a>
<?php
anova_router_shell_open();
if ( ! anova_router_location( 'header' ) && anova_router_show_default( 'header' ) ) {
    get_template_part( 'template-parts/site-header' );
}
?>
<main id="content" class="ar-site-main">
