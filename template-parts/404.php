<?php
/**
 * 404.
 *
 * @package AnovaRouter
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="ar-container ar-404">
    <p class="ar-404__code" aria-hidden="true">404</p>
    <h1 class="ar-entry__title"><?php esc_html_e( 'Page not found', 'anova-router' ); ?></h1>
    <p><?php esc_html_e( 'The link may be broken or the page may have been moved.', 'anova-router' ); ?></p>
    <?php get_search_form(); ?>
    <p><a class="ar-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to homepage', 'anova-router' ); ?></a></p>
</div>
