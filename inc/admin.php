<?php
/**
 * Admin: required companion plugin notice for Anova Canvas.
 *
 * @package AnovaRouter
 */

defined( 'ABSPATH' ) || exit;

/** Status of a companion plugin: 'active', 'inactive' (installed) or 'missing'. */
function anova_router_plugin_status( $folder, $constant ) {
    if ( defined( $constant ) ) { return 'active'; }
    if ( ! function_exists( 'get_plugins' ) ) { require_once ABSPATH . 'wp-admin/includes/plugin.php'; }
    foreach ( array_keys( get_plugins() ) as $file ) {
        if ( 0 === strpos( $file, $folder . '/' ) ) { return 'inactive'; }
    }
    return 'missing';
}

add_action( 'admin_notices', 'anova_router_companion_notice' );
function anova_router_companion_notice() {
    $dismiss_key = 'anova_router_canvas_notice_dismissed';
    if ( ! current_user_can( 'install_plugins' ) || get_user_meta( get_current_user_id(), $dismiss_key, true ) ) { return; }
    $screen = get_current_screen();
    if ( ! $screen || ! in_array( $screen->id, array( 'dashboard', 'themes', 'plugins' ), true ) ) { return; }

    $canvas = anova_router_plugin_status( 'anova-canvas', 'ACV_VERSION' );
    if ( 'active' === $canvas ) { return; }

    $plugin_file = 'anova-canvas/anova-canvas.php';
    if ( 'inactive' === $canvas ) {
        $button_url = wp_nonce_url( self_admin_url( 'plugins.php?action=activate&plugin=' . rawurlencode( $plugin_file ) ), 'activate-plugin_' . $plugin_file );
        $button_label = __( 'Activate Anova Canvas', 'anova-router' );
        $status = __( 'installed, not active', 'anova-router' );
    } else {
        $button_url = self_admin_url( 'plugin-install.php?tab=upload' );
        $button_label = __( 'Install Anova Canvas', 'anova-router' );
        $status = __( 'not installed', 'anova-router' );
    }
    $dismiss = wp_nonce_url( admin_url( 'admin-post.php?action=anova_router_dismiss' ), 'anova_router_dismiss' );
    ?>
    <div class="notice notice-info">
        <p><strong><?php esc_html_e( 'Anova Router requires Anova Canvas.', 'anova-router' ); ?></strong>
        <?php esc_html_e( 'Install and activate the Anova Canvas plugin to use the built-in builder integration and Theme Builder. Anova Canvas is a standalone plugin and also works with other themes; Loteng Kit is not required.', 'anova-router' ); ?></p>
        <p>
            <?php
            /* translators: %s: Anova Canvas status */
            echo esc_html( sprintf( __( 'Anova Canvas status: %s.', 'anova-router' ), $status ) );
            ?>
            &nbsp;<a class="button button-primary" href="<?php echo esc_url( $button_url ); ?>"><?php echo esc_html( $button_label ); ?></a>
            <a class="button" href="<?php echo esc_url( $dismiss ); ?>"><?php esc_html_e( 'Dismiss', 'anova-router' ); ?></a>
        </p>
    </div>
    <?php
}

add_action( 'admin_post_anova_router_dismiss', 'anova_router_dismiss_notice' );
function anova_router_dismiss_notice() {
    check_admin_referer( 'anova_router_dismiss' );
    update_user_meta( get_current_user_id(), 'anova_router_canvas_notice_dismissed', 1 );
    wp_safe_redirect( wp_get_referer() ?: admin_url() );
    exit;
}
