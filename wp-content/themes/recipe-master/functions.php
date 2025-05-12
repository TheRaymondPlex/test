<?php
/**
 * Recipe Master Theme functions and definitions
 *
 * @package RecipeMaster
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Define theme constants
define( 'RECIPE_MASTER_VERSION', '1.0.0' );
define( 'RECIPE_MASTER_DIR', get_template_directory() );
define( 'RECIPE_MASTER_URI', get_template_directory_uri() );

// Check if Composer autoload exists
if ( file_exists( RECIPE_MASTER_DIR . '/vendor/autoload.php' ) ) {
    require_once RECIPE_MASTER_DIR . '/vendor/autoload.php';
}

// Include core theme files
require_once RECIPE_MASTER_DIR . '/inc/Core/Setup.php';
require_once RECIPE_MASTER_DIR . '/inc/Core/Template.php';
require_once RECIPE_MASTER_DIR . '/inc/Core/RecipeMaster.php';
require_once RECIPE_MASTER_DIR . '/inc/PostTypes/Recipe.php';
require_once RECIPE_MASTER_DIR . '/inc/Admin/RecipeImporter.php';
require_once RECIPE_MASTER_DIR . '/inc/ACF/FieldGroups.php';

// Initialize theme
$setup = new \RecipeMaster\Core\Setup();
$recipe = new \RecipeMaster\PostTypes\Recipe();
$recipe_importer = new \RecipeMaster\Admin\RecipeImporter();
$field_groups = new \RecipeMaster\ACF\FieldGroups();

// Initialize main RecipeMaster class
$recipe_master = \RecipeMaster\Core\recipe_master();

/**
 * Helper function to get icon SVG
 * 
 * @param string $icon_name Icon name
 * @return string SVG markup
 */
function get_icon_svg( $icon_name ) {
    $template = \RecipeMaster\Core\recipe_master()->template;
    if ( $template ) {
        return $template->get_icon_svg( $icon_name );
    }
    
    return '';
}
