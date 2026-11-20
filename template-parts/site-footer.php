<?php
/**
 * Default footer (shown when no builder provides one).
 *
 * @package AnovaRouter
 */

defined( 'ABSPATH' ) || exit;

$anova_router_text = trim( (string) anova_router_option( 'footer_text' ) );
?>
<footer class="ar-footer" role="contentinfo">
    <div class="ar-container ar-footer__inner">
        <p class="ar-footer__copy">
            <?php
            if ( '' !== $anova_router_text ) {
                echo wp_kses_post( str_replace( array( '{year}', '{site}' ), array( wp_date( 'Y' ), get_bloginfo( 'name' ) ), $anova_router_text ) );
            } else {
                echo '&copy; ' . esc_html( wp_date( 'Y' ) ) . ' ' . esc_html( get_bloginfo( 'name' ) );
            }
            ?>
        </p>
        <?php if ( has_nav_menu( 'footer' ) ) : ?>
            <nav class="ar-footer__nav" aria-label="<?php esc_attr_e( 'Footer menu', 'anova-router' ); ?>">
                <?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'menu_class' => 'ar-menu ar-menu--footer', 'depth' => 1, 'fallback_cb' => false ) ); ?>
            </nav>
        <?php endif; ?>
    </div>
</footer>
