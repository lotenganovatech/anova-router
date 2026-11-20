<?php
/**
 * Template Name: Anova Router — Full width
 * Template Post Type: page, post
 *
 * Header & footer tetap tampil, konten selebar layar tanpa judul (cocok untuk builder).
 * Template ini mengabaikan tata letak situs "Boxed" dan selalu selebar layar.
 *
 * @package AnovaRouter
 */

defined( 'ABSPATH' ) || exit;

get_header();
while ( have_posts() ) {
    the_post();
    echo '<div id="post-' . (int) get_the_ID() . '" class="' . esc_attr( implode( ' ', get_post_class( 'ar-full-width' ) ) ) . '">';
    the_content();
    echo '</div>';
}
get_footer();
