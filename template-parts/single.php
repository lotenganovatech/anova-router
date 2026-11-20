<?php
/**
 * Single post / page.
 *
 * @package AnovaRouter
 */

defined( 'ABSPATH' ) || exit;

while ( have_posts() ) :
    the_post();
    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class( 'ar-container ar-entry' ); ?>>
        <?php if ( anova_router_show_title() ) : ?>
            <header class="ar-entry__header">
                <?php the_title( '<h1 class="ar-entry__title">', '</h1>' ); ?>
                <?php if ( 'post' === get_post_type() ) : ?>
                    <p class="ar-entry__meta">
                        <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
                        <span aria-hidden="true">·</span>
                        <?php the_author_posts_link(); ?>
                    </p>
                <?php endif; ?>
            </header>
        <?php endif; ?>
        <?php if ( 'post' === get_post_type() && has_post_thumbnail() ) : ?>
            <figure class="ar-entry__thumb"><?php the_post_thumbnail( 'large' ); ?></figure>
        <?php endif; ?>
        <div class="ar-entry__content">
            <?php
            the_content();
            wp_link_pages( array( 'before' => '<nav class="ar-page-links">' . esc_html__( 'Pages:', 'anova-router' ), 'after' => '</nav>' ) );
            ?>
        </div>
        <?php if ( 'post' === get_post_type() ) : ?>
            <footer class="ar-entry__footer">
                <?php
                $anova_router_tags = get_the_tag_list( '', ' ' );
                if ( $anova_router_tags ) {
                    echo '<p class="ar-tags">' . wp_kses_post( $anova_router_tags ) . '</p>';
                }
                the_post_navigation( array( 'prev_text' => '← %title', 'next_text' => '%title →' ) );
                ?>
            </footer>
        <?php endif; ?>
        <?php
        if ( comments_open() || get_comments_number() ) {
            comments_template();
        }
        ?>
    </article>
<?php endwhile; ?>
