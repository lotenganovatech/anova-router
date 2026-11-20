<?php
/**
 * Blog, archive and search results.
 *
 * @package AnovaRouter
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="ar-container ar-archive">
    <?php if ( ! is_front_page() ) : ?>
        <header class="ar-archive__header">
            <?php if ( is_search() ) : ?>
                <h1 class="ar-entry__title">
                    <?php
                    /* translators: %s: search query */
                    echo esc_html( sprintf( __( 'Search results: %s', 'anova-router' ), get_search_query() ) );
                    ?>
                </h1>
            <?php elseif ( is_home() ) : ?>
                <h1 class="ar-entry__title"><?php echo esc_html( get_the_title( (int) get_option( 'page_for_posts' ) ) ?: __( 'Blog', 'anova-router' ) ); ?></h1>
            <?php else : ?>
                <?php the_archive_title( '<h1 class="ar-entry__title">', '</h1>' ); ?>
                <?php the_archive_description( '<div class="ar-archive__desc">', '</div>' ); ?>
            <?php endif; ?>
        </header>
    <?php endif; ?>

    <?php if ( have_posts() ) : ?>
        <div class="ar-cards">
            <?php
            while ( have_posts() ) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'ar-card' ); ?>>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a class="ar-card__thumb" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php the_post_thumbnail( 'medium_large' ); ?></a>
                    <?php endif; ?>
                    <div class="ar-card__body">
                        <?php the_title( '<h2 class="ar-card__title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
                        <p class="ar-entry__meta"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
                        <div class="ar-card__excerpt"><?php the_excerpt(); ?></div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
        <?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => '←', 'next_text' => '→' ) ); ?>
    <?php else : ?>
        <div class="ar-empty">
            <p><?php is_search() ? esc_html_e( 'Nothing matched. Try another keyword.', 'anova-router' ) : esc_html_e( 'No content yet.', 'anova-router' ); ?></p>
            <?php get_search_form(); ?>
        </div>
    <?php endif; ?>
</div>
