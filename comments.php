<?php
/**
 * Comments.
 *
 * @package AnovaRouter
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
    return;
}
?>
<section id="comments" class="ar-comments">
    <?php if ( have_comments() ) : ?>
        <h2 class="ar-comments__title">
            <?php
            $anova_router_count = get_comments_number();
            /* translators: %s: number of comments */
            echo esc_html( sprintf( _n( '%s comment', '%s comments', $anova_router_count, 'anova-router' ), number_format_i18n( $anova_router_count ) ) );
            ?>
        </h2>
        <ol class="ar-comment-list">
            <?php wp_list_comments( array( 'style' => 'ol', 'short_ping' => true, 'avatar_size' => 40 ) ); ?>
        </ol>
        <?php the_comments_navigation(); ?>
    <?php endif; ?>
    <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
        <p class="ar-muted"><?php esc_html_e( 'Comments are closed.', 'anova-router' ); ?></p>
    <?php endif; ?>
    <?php comment_form(); ?>
</section>
