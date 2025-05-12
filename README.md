# Recipe Master Theme - WordPress Development Task

This repository contains a WordPress theme developed as a test task for a developer position. The theme is designed to showcase recipes with a modern, responsive interface and includes features for recipe management and display.

## 📋 Task Overview

The Recipe Master theme is a custom WordPress theme that includes:

- Custom recipe post type with detailed fields
- Recipe importer tool for importing recipes from external APIs
- Responsive design optimized for all device sizes
- Taxonomy-based organization of recipes
- Pagination and filtering functionality

## 🚀 Installation Instructions

### Prerequisites

- WordPress 6.0+ (Developed and tested on WordPress 6.8.1)
- PHP 8.0+ (Developed and tested using PHP 8.2)
- Advanced Custom Fields PRO plugin

### Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/TheRaymondPlex/test.git
   ```

2. **Install the Theme**
   - Copy the `wp-content/themes/recipe-master` directory to your WordPress installation's `wp-content/themes/` directory
   - Alternatively, zip the `recipe-master` directory and upload it via the WordPress admin panel (Appearance > Themes > Add New > Upload Theme)

3. **Install Required Plugins**
   - Install and activate the Advanced Custom Fields PRO plugin
   - The theme will display a notice if required plugins are missing

4. **Activate the Theme**
   - Go to Appearance > Themes in the WordPress admin panel
   - Find "Recipe Master" and click "Activate"

5. **Set Up Menu Locations**
   - Go to Appearance > Menus
   - Create a menu and assign it to the "Primary" location

6. **Import Sample Recipes (Optional)**
   - Go to Recipe Importer in the admin menu
   - Use the sample API endpoint provided in the importer page
   - Click "Import Recipes" to populate your site with sample recipes

## 🛠️ Development Setup

For development work on this theme:

1. **Install Dependencies**
   ```bash
   cd wp-content/themes/recipe-master
   composer install
   ```

2. **Code Quality Tools**
   ```bash
   # Check WordPress coding standards
   composer run phpcs
   
   # Fix coding standards issues automatically
   composer run phpcbf
   ```

## 🔍 Theme Features

### Custom Post Types and Taxonomies

- **Recipe Post Type**: Custom post type for recipes with detailed metadata
- **Meal Type Taxonomy**: Hierarchical taxonomy (like categories) for meal types
- **Recipe Tag Taxonomy**: Non-hierarchical taxonomy (like tags) for recipe attributes

### Recipe Fields

- Preparation time
- Cooking time
- Servings
- Calories
- Difficulty level
- Ingredients (with amounts and units)
- Step-by-step instructions
- Tips and notes

### Recipe Importer

The theme includes an admin tool for importing recipes from external APIs, with support for:
- JSON data sources
- Updating or skipping existing recipes
- Setting taxonomies and ACF fields automatically

## 📱 Responsive Design

The theme is fully responsive and optimized for:
- Desktop computers
- Tablets
- Mobile phones

## 📂 Project Structure

The main theme directory is located at `wp-content/themes/recipe-master/`. For detailed information about the theme's file structure and components, please refer to the [theme's README file](wp-content/themes/recipe-master/README.md).

## 📄 License

This theme is licensed under the GPL v2 or later.
