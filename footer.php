<?php
/**
 * Footer: builder location first, simple theme footer as fallback.
 *
 * @package AnovaRouter
 */

defined( 'ABSPATH' ) || exit;
?>
</main>
<?php
if ( ! anova_router_location( 'footer' ) && anova_router_show_default( 'footer' ) ) {
    get_template_part( 'template-parts/site-footer' );
}
anova_router_shell_close();
wp_footer();
?>
</body>
</html>
