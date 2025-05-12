<?php
/**
 * Recipe Post Type
 *
 * @package RecipeMaster\PostTypes
 */

namespace RecipeMaster\PostTypes;

/**
 * Recipe class for registering and managing Recipe post type
 */
class Recipe {
    /**
     * Post type name
     *
     * @var string
     */
    private $post_type = 'recipe';

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_post_type' ] );
        add_action( 'init', [ $this, 'register_taxonomies' ] );
        add_action( 'template_include', [ $this, 'template_include' ] );
    }

    /**
     * Register the Recipe custom post type
     *
     * @return void
     */
    public function register_post_type() {
        $labels = [
            'name'                  => _x( 'Recipes', 'Post type general name', 'recipe-master' ),
            'singular_name'         => _x( 'Recipe', 'Post type singular name', 'recipe-master' ),
            'menu_name'             => _x( 'Recipes', 'Admin Menu text', 'recipe-master' ),
            'name_admin_bar'        => _x( 'Recipe', 'Add New on Toolbar', 'recipe-master' ),
            'add_new'               => __( 'Add New', 'recipe-master' ),
            'add_new_item'          => __( 'Add New Recipe', 'recipe-master' ),
            'new_item'              => __( 'New Recipe', 'recipe-master' ),
            'edit_item'             => __( 'Edit Recipe', 'recipe-master' ),
            'view_item'             => __( 'View Recipe', 'recipe-master' ),
            'all_items'             => __( 'All Recipes', 'recipe-master' ),
            'search_items'          => __( 'Search Recipes', 'recipe-master' ),
            'parent_item_colon'     => __( 'Parent Recipes:', 'recipe-master' ),
            'not_found'             => __( 'No recipes found.', 'recipe-master' ),
            'not_found_in_trash'    => __( 'No recipes found in Trash.', 'recipe-master' ),
            'featured_image'        => _x( 'Recipe Cover Image', 'Overrides the "Featured Image" phrase', 'recipe-master' ),
            'set_featured_image'    => _x( 'Set cover image', 'Overrides the "Set featured image" phrase', 'recipe-master' ),
            'remove_featured_image' => _x( 'Remove cover image', 'Overrides the "Remove featured image" phrase', 'recipe-master' ),
            'use_featured_image'    => _x( 'Use as cover image', 'Overrides the "Use as featured image" phrase', 'recipe-master' ),
            'archives'              => _x( 'Recipe archives', 'The post type archive label used in nav menus', 'recipe-master' ),
            'attributes'            => _x( 'Recipe attributes', 'The post type attributes label', 'recipe-master' ),
            'insert_into_item'      => _x( 'Insert into recipe', 'Overrides the "Insert into post" phrase', 'recipe-master' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this recipe', 'Overrides the "Uploaded to this post" phrase', 'recipe-master' ),
            'filter_items_list'     => _x( 'Filter recipes list', 'Screen reader text for the filter links', 'recipe-master' ),
            'items_list_navigation' => _x( 'Recipes list navigation', 'Screen reader text for the pagination', 'recipe-master' ),
            'items_list'            => _x( 'Recipes list', 'Screen reader text for the items list', 'recipe-master' ),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => [ 'slug' => 'recipe' ],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-food',
            'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'author' ],
            'show_in_rest'       => true,
        ];

        register_post_type( $this->post_type, $args );
    }

    /**
     * Register taxonomies for Recipe post type
     *
     * @return void
     */
    public function register_taxonomies() {
        // Register Meal Type Taxonomy (Category)
        $meal_type_labels = [
            'name'              => _x( 'Meal Types', 'taxonomy general name', 'recipe-master' ),
            'singular_name'     => _x( 'Meal Type', 'taxonomy singular name', 'recipe-master' ),
            'search_items'      => __( 'Search Meal Types', 'recipe-master' ),
            'all_items'         => __( 'All Meal Types', 'recipe-master' ),
            'parent_item'       => __( 'Parent Meal Type', 'recipe-master' ),
            'parent_item_colon' => __( 'Parent Meal Type:', 'recipe-master' ),
            'edit_item'         => __( 'Edit Meal Type', 'recipe-master' ),
            'update_item'       => __( 'Update Meal Type', 'recipe-master' ),
            'add_new_item'      => __( 'Add New Meal Type', 'recipe-master' ),
            'new_item_name'     => __( 'New Meal Type Name', 'recipe-master' ),
            'menu_name'         => __( 'Meal Types', 'recipe-master' ),
        ];

        $meal_type_args = [
            'hierarchical'      => true,
            'labels'            => $meal_type_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => [ 'slug' => 'meal-type' ],
            'show_in_rest'      => true,
        ];

        register_taxonomy( 'meal_type', [ $this->post_type ], $meal_type_args );

        // Register Recipe Tags Taxonomy
        $recipe_tag_labels = [
            'name'              => _x( 'Recipe Tags', 'taxonomy general name', 'recipe-master' ),
            'singular_name'     => _x( 'Recipe Tag', 'taxonomy singular name', 'recipe-master' ),
            'search_items'      => __( 'Search Recipe Tags', 'recipe-master' ),
            'all_items'         => __( 'All Recipe Tags', 'recipe-master' ),
            'parent_item'       => __( 'Parent Recipe Tag', 'recipe-master' ),
            'parent_item_colon' => __( 'Parent Recipe Tag:', 'recipe-master' ),
            'edit_item'         => __( 'Edit Recipe Tag', 'recipe-master' ),
            'update_item'       => __( 'Update Recipe Tag', 'recipe-master' ),
            'add_new_item'      => __( 'Add New Recipe Tag', 'recipe-master' ),
            'new_item_name'     => __( 'New Recipe Tag Name', 'recipe-master' ),
            'menu_name'         => __( 'Recipe Tags', 'recipe-master' ),
        ];

        $recipe_tag_args = [
            'hierarchical'      => false,
            'labels'            => $recipe_tag_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => [ 'slug' => 'recipe-tag' ],
            'show_in_rest'      => true,
        ];

        register_taxonomy( 'recipe_tag', [ $this->post_type ], $recipe_tag_args );
    }

    /**
     * Set template for single recipe
     *
     * @param string $template The current template path.
     * @return string
     */
    public function template_include( $template ) {
        if ( is_singular( $this->post_type ) ) {
            $custom_template = locate_template( [ 'templates/single-recipe.php' ] );
            if ( $custom_template ) {
                return $custom_template;
            }
        } elseif ( is_post_type_archive( $this->post_type ) ) {
            $custom_template = locate_template( [ 'templates/archive-recipe.php' ] );
            if ( $custom_template ) {
                return $custom_template;
            }
        }

        return $template;
    }
} 