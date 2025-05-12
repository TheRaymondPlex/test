<?php
/**
 * Main RecipeMaster Class
 *
 * @package RecipeMaster\Core
 */

namespace RecipeMaster\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main RecipeMaster Class.
 */
final class RecipeMaster {

    /**
     * RecipeMaster version.
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * The single instance of the class.
     *
     * @var RecipeMaster
     */
    protected static $_instance = null;

    /**
     * Template handler instance.
     *
     * @var \RecipeMaster\Core\Template
     */
    public $template = null;

    /**
     * Main RecipeMaster Instance.
     *
     * Ensures only one instance of RecipeMaster is loaded or can be loaded.
     *
     * @return RecipeMaster - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * RecipeMaster Constructor.
     */
    public function __construct() {
        $this->define_constants();
        $this->init_template();

        do_action( 'recipe_master_loaded' );
    }

    /**
     * Define constants if not already defined
     */
    private function define_constants() {
        if ( ! defined( 'RECIPE_MASTER_VERSION' ) ) {
            define( 'RECIPE_MASTER_VERSION', $this->version );
        }
        
        if ( ! defined( 'RECIPE_MASTER_PATH' ) ) {
            define( 'RECIPE_MASTER_PATH', get_template_directory() );
        }
        
        if ( ! defined( 'RECIPE_MASTER_URI' ) ) {
            define( 'RECIPE_MASTER_URI', get_template_directory_uri() );
        }
    }

    /**
     * Initialize template handler
     */
    private function init_template() {
        $this->template = new \RecipeMaster\Core\Template();
    }
}

/**
 * Main instance of RecipeMaster.
 *
 * @return RecipeMaster
 */
function recipe_master() {
    return RecipeMaster::instance();
} 