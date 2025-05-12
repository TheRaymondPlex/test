# Recipe Master Theme

Developed specially for PURPLE. This is DEV TASK 1.

## Description

Recipe Master is a WordPress theme designed for creating and managing recipes. It features:

- Custom recipe post type with ACF fields
- Recipe importer from external APIs
- Responsive design for all devices
- Clean and modern recipe display
- Pagination support for recipe archives

## Requirements

- WordPress 6.0+ (Developed and tested on WordPress 6.8.1)
- PHP 8.0+ (Developed and tested using PHP 8.2)
- ACF PRO Plugin

## Installation

1. Upload the `recipe-master` directory to the `/wp-content/themes/` directory
2. Install and activate the Advanced Custom Fields PRO plugin
3. Activate the theme through the 'Themes' menu in WordPress
4. Set up menu locations in Appearance > Menus

## Development Setup

For development, you'll need Composer to manage dependencies:

```bash
cd wp-content/themes/recipe-master
composer install
```

### Development Tools

The theme includes development tools for code quality:

```bash
# Run PHP CodeSniffer to check WordPress coding standards
composer run phpcs

# Fix coding standards issues automatically
composer run phpcbf
```

## Features

### Custom Post Type

The theme registers a `recipe` custom post type with the following ACF fields:

- Recipe Details (cooking time, servings, calories, difficulty)
- Ingredients (repeater field)
- Instructions (repeater field with optional images)
- Additional Information (tips)

### Taxonomies

Two taxonomies are registered:

- `meal_type` (hierarchical) - For categorizing recipes (breakfast, lunch, dinner, etc.)
- `recipe_tag` (non-hierarchical) - For tagging recipes

### Import Tool

An admin page is provided for importing recipes from external APIs. It supports:

- Importing recipes from JSON endpoints
- Update or skip existing recipes
- Automatic downloading of featured images
- Setting taxonomies and ACF fields

## File Structure

```
recipe-master/
├── assets/
│   ├── css/
│   │   ├── main.css                # Main theme styles
│   │   ├── admin.css               # Admin interface styles
│   │   └── recipe/
│   │       └── single-recipe.css   # Styles for single recipe display
│   └── js/
│       ├── admin.js                # Admin functionality
│       ├── navigation.js           # Main navigation functionality
│       └── recipe/
│           └── single-recipe.js    # JS for single recipe page
├── inc/                            # PHP classes organized by namespace
│   ├── Core/
│   │   ├── RecipeMaster.php        # Main theme class
│   │   ├── Setup.php               # Theme setup and initialization
│   │   └── Template.php            # Template handling functions
│   ├── PostTypes/
│   │   └── Recipe.php              # Recipe post type and taxonomy registration
│   ├── Admin/
│   │   └── RecipeImporter.php      # Recipe import functionality
│   └── ACF/
│       └── FieldGroups.php         # ACF field group definitions
├── templates/                      # Template parts
│   ├── single-recipe.php           # Single recipe template
│   └── archive-recipe.php          # Recipe archive template
├── vendor/                         # Composer dependencies
├── archive-recipe.php              # Main recipe archive template
├── taxonomy-meal_type.php          # Meal type taxonomy template
├── taxonomy-recipe_tag.php         # Recipe tag taxonomy template
├── front-page.php                  # Front page template
├── index.php                       # Main index template
├── header.php                      # Header template
├── footer.php                      # Footer template
├── functions.php                   # Theme functions and initialization
├── style.css                       # Theme metadata
├── screenshot.png                  # Theme screenshot
├── composer.json                   # Composer configuration
└── README.md                       # Theme documentation
```

## Key Files and Their Purpose

### Core Files

- **functions.php**: Initializes the theme, loads autoloader, and sets up core components
- **style.css**: Contains theme metadata (name, version, description)
- **index.php**: Main template file used as a fallback
- **front-page.php**: Template for the site's front page with featured recipes
- **archive-recipe.php**: Main wrapper for recipe archives that loads template parts

### inc/ Directory

The `inc/` directory contains PHP classes organized by namespace:

#### Core/

- **RecipeMaster.php**: Main theme class that initializes components and defines constants
- **Setup.php**: Handles theme setup including registering menus, widget areas, and theme features
- **Template.php**: Contains template helper functions for displaying recipes and other content

#### PostTypes/

- **Recipe.php**: Registers the recipe custom post type and related taxonomies (meal_type, recipe_tag)

#### Admin/

- **RecipeImporter.php**: Provides admin interface and functionality for importing recipes from external APIs

#### ACF/

- **FieldGroups.php**: Defines all ACF field groups used in the theme

### Templates

- **templates/single-recipe.php**: Detailed template for displaying a single recipe
- **templates/archive-recipe.php**: Template for displaying recipe archives with pagination

### Assets

- **assets/css/main.css**: Main stylesheet for the theme
- **assets/css/admin.css**: Styles for admin interfaces
- **assets/js/navigation.js**: Handles responsive navigation menu functionality
- **assets/js/admin.js**: JavaScript for admin interfaces
- **assets/js/recipe/single-recipe.js**: JavaScript for single recipe page functionality

## License

This theme is licensed under the GPL v2 or later. 