<?php
/**
 * Customizer: Tampilan → Sesuaikan → Anova Router.
 *
 * @package AnovaRouter
 */

defined( 'ABSPATH' ) || exit;

add_action( 'customize_register', 'anova_router_customize' );
function anova_router_customize( $wp_customize ) {
    $d = anova_router_defaults();
    $wp_customize->add_section( 'anova_router', array(
        'title'       => esc_html__( 'Anova Router', 'anova-router' ),
        'priority'    => 30,
        'description' => esc_html__( 'Headers & footers from Anova Canvas or Elementor Pro always take priority. The settings below only affect the theme default output.', 'anova-router' ),
    ) );

    /* ------------------------------------------------------------------ *
     * Tata letak halaman: Full / Boxed / Custom
     * ------------------------------------------------------------------ */
    $wp_customize->add_setting( 'anova_router_layout', array(
        'default'           => $d['layout'],
        'sanitize_callback' => 'anova_router_sanitize_layout',
    ) );
    $wp_customize->add_control( 'anova_router_layout', array(
        'type'        => 'radio',
        'section'     => 'anova_router',
        'priority'    => 5,
        'label'       => esc_html__( 'Page layout', 'anova-router' ),
        'description' => esc_html__( 'Sets the width of the whole page (header, content, footer). The "Blank canvas" & "Full width" page templates always span the full screen.', 'anova-router' ),
        'choices'     => array(
            'full'   => esc_html__( 'Full — content spans the screen, no maximum width', 'anova-router' ),
            'boxed'  => esc_html__( 'Boxed — the site is wrapped in a fixed-width box', 'anova-router' ),
            'custom' => esc_html__( 'Custom — set your own maximum width', 'anova-router' ),
        ),
    ) );

    $is_boxed  = function () { return 'boxed' === anova_router_layout(); };
    $is_custom = function () { return 'custom' === anova_router_layout(); };

    $wp_customize->add_setting( 'anova_router_container_width', array(
        'default'           => $d['container_width'],
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'anova_router_container_width', array(
        'type'            => 'number',
        'section'         => 'anova_router',
        'priority'        => 6,
        'label'           => esc_html__( 'Boxed width (px)', 'anova-router' ),
        'description'     => esc_html__( 'Used with the "Boxed" layout. Also used by Anova Canvas when Canvas does not set its own width.', 'anova-router' ),
        'input_attrs'     => array( 'min' => 600, 'max' => 2400, 'step' => 10 ),
        'active_callback' => $is_boxed,
    ) );

    $wp_customize->add_setting( 'anova_router_custom_width', array(
        'default'           => $d['custom_width'],
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'anova_router_custom_width', array(
        'type'            => 'number',
        'section'         => 'anova_router',
        'priority'        => 7,
        'label'           => esc_html__( 'Custom width (px)', 'anova-router' ),
        'description'     => esc_html__( 'Used with the "Custom" layout (480–2400 px).', 'anova-router' ),
        'input_attrs'     => array( 'min' => 480, 'max' => 2400, 'step' => 10 ),
        'active_callback' => $is_custom,
    ) );

    $wp_customize->add_setting( 'anova_router_boxed_bg', array(
        'default'           => $d['boxed_bg'],
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'anova_router_boxed_bg', array(
        'section'         => 'anova_router',
        'priority'        => 8,
        'label'           => esc_html__( 'Outer background color (boxed)', 'anova-router' ),
        'description'     => esc_html__( 'Page color outside the box. Leave empty for the default background.', 'anova-router' ),
        'active_callback' => $is_boxed,
    ) ) );

    /* ------------------------------------------------------------------ *
     * Bagian tema
     * ------------------------------------------------------------------ */
    $checkbox = function ( $value ) { return $value ? 1 : 0; };
    $fields = array(
        'default_header' => array( 'checkbox', esc_html__( 'Show the default header when no builder provides one', 'anova-router' ) ),
        'default_footer' => array( 'checkbox', esc_html__( 'Show the default footer when no builder provides one', 'anova-router' ) ),
        'page_title'     => array( 'checkbox', esc_html__( 'Show page & post titles', 'anova-router' ) ),
        'load_styles'    => array( 'checkbox', esc_html__( 'Load theme CSS (turn off when the whole design is built in a builder)', 'anova-router' ) ),
    );
    foreach ( $fields as $key => $field ) {
        $wp_customize->add_setting( 'anova_router_' . $key, array( 'default' => $d[ $key ], 'sanitize_callback' => $checkbox ) );
        $wp_customize->add_control( 'anova_router_' . $key, array( 'type' => 'checkbox', 'section' => 'anova_router', 'label' => $field[1], 'priority' => 20 ) );
    }

    $wp_customize->add_setting( 'anova_router_accent', array( 'default' => $d['accent'], 'sanitize_callback' => 'sanitize_hex_color' ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'anova_router_accent', array( 'section' => 'anova_router', 'label' => esc_html__( 'Accent color', 'anova-router' ), 'priority' => 30 ) ) );

    $wp_customize->add_setting( 'anova_router_footer_text', array( 'default' => $d['footer_text'], 'sanitize_callback' => 'wp_kses_post' ) );
    $wp_customize->add_control( 'anova_router_footer_text', array(
        'type' => 'text', 'section' => 'anova_router', 'label' => esc_html__( 'Default footer text', 'anova-router' ), 'priority' => 40,
        'description' => esc_html__( 'Leave empty for "© year site name". Tokens: {year}, {site}.', 'anova-router' ),
    ) );
}
