<?php
/**
 * Template functions and classes
 *
 * @package RecipeMaster\Core
 */

namespace RecipeMaster\Core;

/**
 * Template handler class
 */
class Template {
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
        add_action( 'after_setup_theme', [ $this, 'theme_setup' ] );
        add_filter( 'template_include', [ $this, 'template_loader' ] );
    }

    /**
     * Enqueue scripts and styles
     *
     * @return void
     */
    public function enqueue_scripts() {
        // Main styles
        wp_enqueue_style(
            'recipe-master-style',
            get_stylesheet_uri(),
            [],
            filemtime( get_stylesheet_directory() . '/style.css' )
        );
        
        // Main theme CSS (header, footer, etc.)
        wp_enqueue_style(
            'recipe-master-main',
            get_template_directory_uri() . '/assets/css/main.css',
            [],
            filemtime( get_stylesheet_directory() . '/assets/css/main.css' )
        );
        
        // Tailwind CSS (CDN)
        wp_enqueue_style(
            'tailwindcss',
            'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css',
            [],
            '2.2.19'
        );

        // Load on single recipe page
        if ( is_singular( 'recipe' ) ) {
            // Single recipe CSS
            wp_enqueue_style(
                'recipe-master-single',
                get_template_directory_uri() . '/assets/css/recipe/single-recipe.css',
                [],
                filemtime( get_stylesheet_directory() . '/assets/css/recipe/single-recipe.css' )
            );

            // Single recipe JS
             wp_enqueue_script(
                 'recipe-master-single',
                 get_template_directory_uri() . '/assets/js/recipe/single-recipe.js',
                 [],
                 filemtime( get_stylesheet_directory() . '/assets/js/recipe/single-recipe.js' ),
                 true
             );
        }

        // Navigation script
        wp_enqueue_script(
            'recipe-master-navigation',
            get_template_directory_uri() . '/assets/js/navigation.js',
            [],
            filemtime( get_stylesheet_directory() . '/assets/js/navigation.js' ),
            true
        );

        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }
    
    /**
     * Register and enqueue admin assets
     *
     * @param string $hook Current admin page hook.
     * @return void
     */
    public function enqueue_admin_assets( $hook ) {
        // Include assets only on our admin page
        if ( 'toplevel_page_recipe-importer' === $hook ) {
            wp_enqueue_style(
                'recipe-master-admin',
                get_template_directory_uri() . '/assets/css/admin.css',
                [],
                filemtime( get_stylesheet_directory() . '/assets/css/admin.css' )
            );

            wp_enqueue_script(
                'recipe-master-admin',
                get_template_directory_uri() . '/assets/js/admin.js',
                [ 'jquery' ],
                filemtime( get_stylesheet_directory() . '/assets/js/admin.js' ),
                true
            );

            wp_localize_script(
                'recipe-master-admin',
                'recipeImporterData',
                [
                    'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                    'nonce'   => wp_create_nonce( 'recipe_importer_nonce' ),
                ]
            );
        }
    }

    /**
     * Theme setup
     *
     * @return void
     */
    public function theme_setup() {
        // Add theme support
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ] );

        // Register menus
        register_nav_menus( [
            'primary' => esc_html__( 'Primary Menu', 'recipe-master' ),
            'footer'  => esc_html__( 'Footer Menu', 'recipe-master' ),
        ] );
    }

    /**
     * Template loader
     *
     * @param string $template Template.
     * @return string
     */
    public function template_loader( $template ) {
        if ( is_singular( 'recipe' ) ) {
            // Check for custom template in theme
            $custom_template = locate_template( [ 'templates/single-recipe.php' ] );
            if ( $custom_template ) {
                return $custom_template;
            }
        }

        if ( is_post_type_archive( 'recipe' ) ) {
            // Check for custom template in theme
            $custom_template = locate_template( [ 'templates/archive-recipe.php' ] );
            if ( $custom_template ) {
                return $custom_template;
            }
        }

        if ( is_tax( [ 'meal_type', 'recipe_tag' ] ) ) {
            // Check for custom template in theme
            $custom_template = locate_template( [ 'templates/taxonomy-recipe.php', 'templates/archive-recipe.php' ] );
            if ( $custom_template ) {
                return $custom_template;
            }
        }

        return $template;
    }

    /**
     * Helper function to get icon SVG
     * 
     * @param string $icon_name Icon name
     * @return string SVG markup
     */
    public function get_icon_svg( $icon_name ) {
        $icons = [
            'clock' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>',
            'utensils' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-utensils"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"></path><path d="M7 2v20"></path><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"></path></svg>',
            'chef-hat' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chef-hat"><path d="M17 21a1 1 0 0 0 1-1v-5.35c0-.457.316-.844.727-1.041a4 4 0 0 0-2.134-7.589a5 5 0 0 0-9.186 0a4 4 0 0 0-2.134 7.588c.411.198.727.585.727 1.041V20a1 1 0 0 0 1 1Z"></path><path d="M6 17h12"></path></svg>',
            'users' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>',
            'tags' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-tags"><path d="m15 5 6.3 6.3a2.4 2.4 0 0 1 0 3.4L17 19"></path><path d="M9.586 5.586A2 2 0 0 0 8.172 5H3a1 1 0 0 0-1 1v5.172a2 2 0 0 0 .586 1.414L8.29 18.29a2.426 2.426 0 0 0 3.42 0l3.58-3.58a2.426 2.426 0 0 0 0-3.42z"></path><circle cx="6.5" cy="9.5" r=".5" fill="currentColor"></circle></svg>',
            'badge' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"></path></svg>',
            'check-circle' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>',
            'globe' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-globe"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path><path d="M2 12h20"></path></svg>'
        ];
        
        return isset( $icons[$icon_name] ) ? $icons[$icon_name] : '';
    }
} 