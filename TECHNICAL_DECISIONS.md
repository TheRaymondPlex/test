# Technical Decisions - Recipe Master Theme

This document outlines the key technical decisions made during the development of the Recipe Master theme. It's intended to provide insight into the architectural choices and implementation approaches used.

## Architecture & Organization

### OOP Approach with Namespaces
I chose to build the theme using an object-oriented approach with proper namespaces (`RecipeMaster\Core`, `RecipeMaster\Admin`, etc.) rather than procedural code. This makes the codebase more maintainable, testable, and follows modern PHP best practices.

### PSR-4 Autoloading
The theme uses Composer's PSR-4 autoloading standard rather than manually including files. This eliminates the need for multiple `require`/`include` statements and improves performance.

### Separation of Concerns
I separated the codebase into logical components:
- Core functionality (setup, template handling)
- Post types and taxonomies
- Admin interfaces
- ACF field definitions

This makes it easier to maintain and extend specific parts of the theme without affecting others.

## Performance Considerations

### Custom Queries vs. Main Query
For the recipe archive pages, I implemented custom WP_Query instances instead of modifying the main query with `pre_get_posts`. This approach:
- Gives more precise control over the query parameters
- Allows for easier pagination handling
- Avoids potential conflicts with other plugins

The temporary replacement of the global query for pagination was necessary to maintain compatibility with WordPress core pagination functions.

## Front-End Implementation

### Tailwind CSS
I used Tailwind CSS utility classes instead of custom CSS for several reasons:
- Example website uses it
- Faster development time
- Consistent design system
- Better responsive behavior

This improves page load performance and reduces potential conflicts.

## WordPress Integration

### Template Hierarchy
I leveraged WordPress's template hierarchy by implementing:
- `archive-recipe.php` for recipe archives
- `taxonomy-meal_type.php` and `taxonomy-recipe_tag.php` for taxonomy archives
- `single-recipe.php` for individual recipes

This follows WordPress best practices and ensures proper template loading.

## Admin Experience

### Recipe Importer
I developed a custom admin page for importing recipes that:
- Handles API authentication
- Processes JSON data into WordPress posts
- Maps external data to ACF fields
- Provides progress feedback during import

This significantly improves the content management workflow compared to manual recipe creation.

## Challenges & Solutions

### Pagination Issues
The original pagination implementation was showing incorrect numbers of items and causing 404 errors on the last page. I solved this by:
1. Standardizing posts per page to 12 items
2. Temporarily replacing the global query with custom query
3. Properly restoring the original query after pagination links were generated

### Image Duplication
To prevent duplicate image downloads during imports, I implemented a solution that:
1. Checks if an image with the same filename already exists in the media library
2. Compares file hashes to determine if an update is needed
3. Only downloads new or changed images

This prevents media library bloat while ensuring content stays current.

## Future Improvements

If I had more time, I would consider:
1. Adding caching for external images
2. Implementing a recipe search feature with filters
3. Creating a Gutenberg block editor integration for recipes
4. Adding Front-End and Back-End caching, minifying static files

--- 