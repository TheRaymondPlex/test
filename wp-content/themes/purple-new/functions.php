<?php
/**
 * Purple Theme functions and definitions
 *
 * @package Purple
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Define constants
 */
define('PURPLE_VERSION', '1.0.0');
define('PURPLE_THEME_DIR', get_template_directory());
define('PURPLE_THEME_URI', get_template_directory_uri());

/**
 * Enqueue scripts and styles
 */
function purple_enqueue_scripts() {    
    wp_enqueue_style('purple-main-css', PURPLE_THEME_URI . '/assets/css/main.css', array(), PURPLE_VERSION);
    wp_enqueue_script('purple-main-js', PURPLE_THEME_URI . '/assets/js/main.js', array('jquery'), PURPLE_VERSION, true);
}
add_action('wp_enqueue_scripts', 'purple_enqueue_scripts');