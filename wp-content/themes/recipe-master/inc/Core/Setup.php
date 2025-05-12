<?php
/**
 * Theme Setup
 *
 * @package RecipeMaster\Core
 */

namespace RecipeMaster\Core;

/**
 * Setup class for theme initialization
 */
class Setup {
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'after_setup_theme', [ $this, 'theme_setup' ] );
        add_action( 'widgets_init', [ $this, 'widgets_init' ] );
    }

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * @return void
     */
    public function theme_setup() {
        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        // Let WordPress manage the document title.
        add_theme_support( 'title-tag' );

        // Enable support for Post Thumbnails on posts and pages.
        add_theme_support( 'post-thumbnails' );

        // Add support for responsive embeds
        add_theme_support( 'responsive-embeds' );

        // Add support for editor styles
        add_theme_support( 'editor-styles' );

        // Register navigation menus
        register_nav_menus(
            [
                'menu-1' => esc_html__( 'Primary', 'recipe-master' ),
                'footer' => esc_html__( 'Footer Menu', 'recipe-master' ),
            ]
        );

        // Switch default core markup for search form, comment form, and comments to output valid HTML5.
        add_theme_support(
            'html5',
            [
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            ]
        );

        // Add theme support for selective refresh for widgets.
        add_theme_support( 'customize-selective-refresh-widgets' );

        // Add support for custom logo.
        add_theme_support(
            'custom-logo',
            [
                'height'      => 250,
                'width'       => 250,
                'flex-width'  => true,
                'flex-height' => true,
            ]
        );

        // Add support for full and wide align images.
        add_theme_support( 'align-wide' );

        // Add support for Block Styles.
        add_theme_support( 'wp-block-styles' );
    }

    /**
     * Register widget area.
     *
     * @return void
     */
    public function widgets_init() {
        register_sidebar(
            [
                'name'          => esc_html__( 'Sidebar', 'recipe-master' ),
                'id'            => 'sidebar-1',
                'description'   => esc_html__( 'Add widgets here.', 'recipe-master' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ]
        );
    }
} 