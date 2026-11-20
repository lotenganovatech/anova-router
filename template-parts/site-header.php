<?php
/**
 * Default header (shown when no builder provides one).
 *
 * @package AnovaRouter
 */

defined( 'ABSPATH' ) || exit;
?>
<header class="ar-header" role="banner">
    <div class="ar-container ar-header__inner">
        <div class="ar-brand">
            <?php if ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a class="ar-brand__name" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
                <?php if ( get_bloginfo( 'description' ) ) : ?>
                    <span class="ar-brand__tagline"><?php bloginfo( 'description' ); ?></span>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php if ( has_nav_menu( 'primary' ) ) : ?>
            <button class="ar-nav-toggle" type="button" aria-controls="ar-primary-nav" aria-expanded="false">
                <span class="ar-nav-toggle__bar" aria-hidden="true"></span>
                <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'anova-router' ); ?></span>
            </button>
            <nav id="ar-primary-nav" class="ar-nav" aria-label="<?php esc_attr_e( 'Primary menu', 'anova-router' ); ?>">
                <?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'ar-menu', 'depth' => 3, 'fallback_cb' => false ) ); ?>
            </nav>
        <?php endif; ?>
    </div>
</header>
