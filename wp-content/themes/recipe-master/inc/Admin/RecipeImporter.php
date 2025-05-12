<?php
/**
 * Recipe Importer
 *
 * @package RecipeMaster\Admin
 */

namespace RecipeMaster\Admin;

/**
 * RecipeImporter class for importing recipes from API
 */
class RecipeImporter {
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_admin_page' ] );
        add_action( 'wp_ajax_import_recipes', [ $this, 'ajax_import_recipes' ] );
    }

    /**
     * Add admin menu page for recipe importer
     *
     * @return void
     */
    public function add_admin_page() {
        add_menu_page(
            __( 'Recipe Importer', 'recipe-master' ),
            __( 'Recipe Importer', 'recipe-master' ),
            'manage_options',
            'recipe-importer',
            [ $this, 'render_admin_page' ],
            'dashicons-download',
            30
        );
    }

    /**
     * Render admin page content
     *
     * @return void
     */
    public function render_admin_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            
            <div class="recipe-importer-form">
                <p><b><?php esc_html_e( 'Import recipes from the API. This will create new recipes or update existing ones.', 'recipe-master' ); ?></b></p>
                <p><?php esc_html_e( 'Test data: https://api.jsonbin.io/v3/b/6818869c8561e97a500e1d6f', 'recipe-master' ); ?></p>
                <p><?php esc_html_e( 'Key: $2a$10$3Dkd04bnkw2DjO9V/J5RYuiCzQVm5VMEN7t/f4irG5m899WTFz6WG', 'recipe-master' ); ?></p>

                <div class="import-form">
                    <div class="form-row">
                        <label for="api_url">
                            <?php esc_html_e( 'API URL:', 'recipe-master' ); ?>
                        </label>
                        <input type="url" id="api_url" name="api_url" class="regular-text" 
                               placeholder="https://api.jsonbin.io/v3/b/XXXXX" required>
                        <p class="description">
                            <?php esc_html_e( 'Enter the API URL for the recipe data source.', 'recipe-master' ); ?>
                        </p>
                    </div>
                    
                    <div class="form-row">
                        <label for="api_key">
                            <?php esc_html_e( 'API Key:', 'recipe-master' ); ?>
                        </label>
                        <input type="text" id="api_key" name="api_key" class="regular-text" 
                               placeholder="$2a$10$XXXXX">
                        <p class="description">
                            <?php esc_html_e( 'Enter the API access key if required (e.g., X-Access-Key for JSONBin.io).', 'recipe-master' ); ?>
                        </p>
                    </div>
                </div>
                
                <div class="import-options">
                    <label>
                        <input type="checkbox" id="update_existing" name="update_existing" checked>
                        <?php esc_html_e( 'Update existing recipes if found', 'recipe-master' ); ?>
                    </label>
                </div>
                
                <div class="import-actions">
                    <button id="import-recipes" class="button button-primary">
                        <?php esc_html_e( 'Import Recipes', 'recipe-master' ); ?>
                    </button>
                </div>
            </div>
            
            <div id="import-results" class="import-results" style="display: none;">
                <h2><?php esc_html_e( 'Import Results', 'recipe-master' ); ?></h2>
                <div class="import-progress">
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                    <div class="progress-text">0%</div>
                </div>
                <div class="import-log"></div>
            </div>
        </div>
        <?php
    }

    /**
     * Handle AJAX request to import recipes
     *
     * @return void
     */
    public function ajax_import_recipes() {
        // Check nonce for security
        if ( ! check_ajax_referer( 'recipe_importer_nonce', 'nonce', false ) ) {
            wp_send_json_error( [ 'message' => __( 'Security check failed.', 'recipe-master' ) ] );
        }

        // Check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( [ 'message' => __( 'You do not have permission to perform this action.', 'recipe-master' ) ] );
        }

        // Get API URL
        $api_url = isset( $_POST['api_url'] ) ? esc_url_raw( wp_unslash( $_POST['api_url'] ) ) : '';
        if ( empty( $api_url ) ) {
            wp_send_json_error( [ 'message' => __( 'API URL is required.', 'recipe-master' ) ] );
        }

        // Get API Key (optional)
        $api_key = isset( $_POST['api_key'] ) ? sanitize_text_field( wp_unslash( $_POST['api_key'] ) ) : '';

        // Get update option
        $update_existing = isset( $_POST['update_existing'] ) && 'true' === $_POST['update_existing'];

        // Setup API request
        $args = [
            'timeout' => 60, // Increase timeout for potentially large responses
        ];

        // Add API key as header if provided
        if ( ! empty( $api_key ) ) {
            $args['headers'] = [
                'X-Access-Key' => $api_key,
            ];
        }

        // Fetch data from API
        $response = wp_remote_get( $api_url, $args );
        if ( is_wp_error( $response ) ) {
            wp_send_json_error( [ 'message' => $response->get_error_message() ] );
        }

        // Check response code
        $response_code = wp_remote_retrieve_response_code( $response );
        if ( $response_code !== 200 ) {
            wp_send_json_error( [ 
                'message' => sprintf( 
                    /* translators: %d: HTTP status code */
                    __( 'API returned error code: %d', 'recipe-master' ), 
                    $response_code 
                ) 
            ] );
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( ! $data ) {
            wp_send_json_error( [ 'message' => __( 'Invalid data received from API.', 'recipe-master' ) ] );
        }

        if ( isset( $data['record'] ) ) {
            $data = $data['record'];
        }

        // If data is not an array at this point, try to find a recipes array inside
        if ( ! is_array( $data ) || isset( $data['recipes'] ) ) {
            $data = isset( $data['recipes'] ) ? $data['recipes'] : [ $data ];
        }

        $results = [
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'failed'  => 0,
            'log'     => [],
        ];

        // Process each recipe
        foreach ( $data as $recipe_data ) {
            $result = $this->process_recipe( $recipe_data, $update_existing );
            
            if ( 'created' === $result['status'] ) {
                $results['created']++;
            } elseif ( 'updated' === $result['status'] ) {
                $results['updated']++;
            } elseif ( 'skipped' === $result['status'] ) {
                $results['skipped']++;
            } else {
                $results['failed']++;
            }
            
            $results['log'][] = $result['message'];
        }

        wp_send_json_success( $results );
    }

    /**
     * Process a single recipe from imported data
     *
     * @param array $recipe_data    Recipe data from API.
     * @param bool  $update_existing Whether to update existing recipes.
     * @return array Result status and message.
     */
    private function process_recipe( $recipe_data, $update_existing ) {
        // Check for required fields
        if ( empty( $recipe_data['name'] ) ) {
            return [
                'status'  => 'failed',
                'message' => __( 'Recipe name is missing.', 'recipe-master' ),
            ];
        }

        // Check if recipe already exists
        $existing_recipe = $this->get_existing_recipe( $recipe_data['name'] );
        
        if ( $existing_recipe && ! $update_existing ) {
            return [
                'status'  => 'skipped',
                'message' => sprintf(
                    /* translators: %s: recipe name */
                    __( 'Skipped: Recipe "%s" already exists.', 'recipe-master' ),
                    $recipe_data['name']
                ),
            ];
        }

        // Prepare post data
        $post_data = [
            'post_title'   => sanitize_text_field( $recipe_data['name'] ),
            'post_content' => isset( $recipe_data['description'] ) ? wp_kses_post( $recipe_data['description'] ) : '',
            'post_status'  => 'publish',
            'post_type'    => 'recipe',
        ];

        if ( $existing_recipe ) {
            $post_data['ID'] = $existing_recipe->ID;
            $post_id = wp_update_post( $post_data );
            $action = 'updated';
        } else {
            $post_id = wp_insert_post( $post_data );
            $action = 'created';
        }

        if ( is_wp_error( $post_id ) ) {
            return [
                'status'  => 'failed',
                'message' => sprintf(
                    /* translators: 1: recipe name, 2: error message */
                    __( 'Failed: Could not create/update recipe "%1$s". Error: %2$s', 'recipe-master' ),
                    $recipe_data['name'],
                    $post_id->get_error_message()
                ),
            ];
        }

        // Update featured image if available
        if ( isset( $recipe_data['image'] ) && ! empty( $recipe_data['image'] ) ) {
            $this->set_featured_image( $post_id, $recipe_data['image'] );
        }

        // Update recipe categories if available (using meal_type taxonomy)
        if ( isset( $recipe_data['mealType'] ) && is_array( $recipe_data['mealType'] ) ) {
            $this->set_meal_types( $post_id, $recipe_data['mealType'] );
        } elseif ( isset( $recipe_data['categories'] ) && is_array( $recipe_data['categories'] ) ) {
            $this->set_meal_types( $post_id, $recipe_data['categories'] );
        }

        // Update recipe tags if available
        if ( isset( $recipe_data['tags'] ) && is_array( $recipe_data['tags'] ) ) {
            $this->set_recipe_tags( $post_id, $recipe_data['tags'] );
        }

        // Map API recipe data to local fields
        $mapped_data = [
            'prep_time' => isset( $recipe_data['prepTimeMinutes'] ) ? $recipe_data['prepTimeMinutes'] : null,
            'cooking_time' => isset( $recipe_data['cookTimeMinutes'] ) ? $recipe_data['cookTimeMinutes'] : null,
            'servings' => isset( $recipe_data['servings'] ) ? $recipe_data['servings'] : null,
            'calories' => isset( $recipe_data['caloriesPerServing'] ) ? $recipe_data['caloriesPerServing'] : null,
            'difficulty' => isset( $recipe_data['difficulty'] ) ? $recipe_data['difficulty'] : null,
            'cuisine' => isset( $recipe_data['cuisine'] ) ? $recipe_data['cuisine'] : null,
            'rating' => isset( $recipe_data['rating'] ) ? $recipe_data['rating'] : null,
            'review_count' => isset( $recipe_data['reviewCount'] ) ? $recipe_data['reviewCount'] : 0,
            'ingredients' => isset( $recipe_data['ingredients'] ) ? $recipe_data['ingredients'] : null,
            'instructions' => isset( $recipe_data['instructions'] ) ? $recipe_data['instructions'] : null,
            'tips' => isset( $recipe_data['tips'] ) ? $recipe_data['tips'] : null,
        ];

        // Update ACF fields
        $this->update_acf_fields( $post_id, $mapped_data );

        return [
            'status'  => $action,
            'message' => sprintf(
                /* translators: 1: action (created/updated), 2: recipe name */
                __( 'Success: Recipe "%2$s" %1$s.', 'recipe-master' ),
                $action,
                $recipe_data['name']
            ),
        ];
    }

    /**
     * Check if a recipe already exists by name
     *
     * @param string $recipe_name Recipe name to check.
     * @return WP_Post|null Post object if exists, null otherwise.
     */
    private function get_existing_recipe( $recipe_name ) {
        $args = [
            'post_type'      => 'recipe',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'title'          => $recipe_name,
            'exact'          => true,
        ];

        $query = new \WP_Query( $args );
        
        if ( $query->have_posts() ) {
            return $query->posts[0];
        }
        
        return null;
    }

    /**
     * Get attachment by URL
     *
     * @param string $url Image URL.
     * @return WP_Post|null Post object if exists, null otherwise.
     */
    private function get_attachment_by_url( $url ) {
        // First try to find by exact URL match
        $args = [
            'post_type'      => 'attachment',
            'post_status'    => 'inherit',
            'posts_per_page' => 1,
            'meta_query'     => [
                [
                    'key'     => '_wp_attached_file',
                    'compare' => 'LIKE',
                    'value'   => basename($url),
                ],
            ],
        ];

        $query = new \WP_Query( $args );
        
        if ( $query->have_posts() ) {
            return $query->posts[0];
        }
        
        // If no match by URL, try by title (which is often the basename of the file)
        $filename = basename($url);
        $args = [
            'post_type'      => 'attachment',
            'post_status'    => 'inherit',
            'posts_per_page' => 1,
            'title'          => pathinfo($filename, PATHINFO_FILENAME),
        ];
        
        $query = new \WP_Query( $args );
        
        if ( $query->have_posts() ) {
            return $query->posts[0];
        }
        
        return null;
    }

    /**
     * Set featured image for a recipe
     *
     * @param int    $post_id Post ID.
     * @param string $image_url Image URL.
     * @return void
     */
    private function set_featured_image( $post_id, $image_url ) {
        // Check if post already has a featured image
        $current_thumbnail_id = get_post_thumbnail_id($post_id);
        
        if ($current_thumbnail_id) {
            // Post already has a featured image, check if it's the same image
            $current_attachment = get_post($current_thumbnail_id);
            
            if ($current_attachment) {
                $current_filename = basename(get_attached_file($current_thumbnail_id));
                $new_filename = basename($image_url);
                
                // If filenames are similar, don't update the image
                if (strpos($current_filename, pathinfo($new_filename, PATHINFO_FILENAME)) !== false) {
                    return; // Skip updating featured image
                }
            }
        }
        
        // Download image from URL
        $upload = $this->download_and_upload_image( $image_url );
        
        if ( is_wp_error( $upload ) ) {
            return;
        }
        
        // Set as featured image
        $attachment_id = wp_insert_attachment(
            [
                'post_mime_type' => $upload['type'],
                'post_title'     => sanitize_file_name( basename( $upload['file'] ) ),
                'post_content'   => '',
                'post_status'    => 'inherit',
            ],
            $upload['file'],
            $post_id
        );

        if ( is_wp_error( $attachment_id ) ) {
            return;
        }

        // Generate attachment metadata
        require_once ABSPATH . 'wp-admin/includes/image.php';
        $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
        wp_update_attachment_metadata( $attachment_id, $attachment_data );
        
        // Set as featured image
        set_post_thumbnail( $post_id, $attachment_id );
    }

    /**
     * Download and upload an image to the media library
     *
     * @param string $image_url Image URL.
     * @return array|WP_Error Upload data on success, WP_Error on failure.
     */
    private function download_and_upload_image( $image_url ) {
        // Check if image already exists in media library
        $attachment = $this->get_attachment_by_url( $image_url );
        if ( $attachment ) {
            // Get the attached file path
            $attached_file = get_attached_file( $attachment->ID );
            
            // Check if we should update the image by comparing the remote image with the local one
            $should_update = false;
            
            // Only check for updates if the file exists locally
            if (file_exists($attached_file)) {
                // Download the remote image to a temporary file to compare
                $response = wp_remote_get( $image_url );
                
                if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
                    $remote_image_data = wp_remote_retrieve_body($response);
                    
                    // Get local file content
                    $local_image_data = file_get_contents($attached_file);
                    
                    // Compare file contents using md5 hash
                    $remote_hash = md5($remote_image_data);
                    $local_hash = md5($local_image_data);
                    
                    // If hashes are different, update the image
                    $should_update = ($remote_hash !== $local_hash);
                }
            } else {
                // If the file doesn't exist locally, we should update
                $should_update = true;
            }
            
            // If no update is needed, return the existing attachment
            if (!$should_update) {
                return [
                    'file' => $attached_file,
                    'type' => $attachment->post_mime_type,
                ];
            }
            
            // Otherwise, continue with the update process
        }

        // Download image
        $response = wp_remote_get( $image_url );
        if ( is_wp_error( $response ) ) {
            return $response;
        }

        $image_data = wp_remote_retrieve_body( $response );
        if ( empty( $image_data ) ) {
            return new \WP_Error( 'download_failed', __( 'Failed to download image.', 'recipe-master' ) );
        }

        // Get upload directory
        $upload_dir = wp_upload_dir();
        
        // Generate unique filename
        $filename = wp_unique_filename( $upload_dir['path'], basename( $image_url ) );
        $upload_file = $upload_dir['path'] . '/' . $filename;
        
        // Save image to upload directory
        file_put_contents( $upload_file, $image_data );
        
        // Get mime type
        $wp_filetype = wp_check_filetype( $filename, null );
        
        return [
            'file' => $upload_file,
            'url'  => $upload_dir['url'] . '/' . $filename,
            'type' => $wp_filetype['type'],
        ];
    }

    /**
     * Set meal type taxonomy
     *
     * @param int   $post_id Post ID.
     * @param mixed $meal_types Meal type(s).
     * @return void
     */
    private function set_meal_types( $post_id, $meal_types ) {
        if ( ! is_array( $meal_types ) ) {
            $meal_types = [ $meal_types ];
        }

        $term_ids = [];

        foreach ( $meal_types as $meal_type ) {
            $term = term_exists( $meal_type, 'meal_type' );
            
            if ( ! $term ) {
                $term = wp_insert_term( $meal_type, 'meal_type' );
            }
            
            if ( ! is_wp_error( $term ) ) {
                $term_ids[] = (int) $term['term_id'];
            }
        }

        if ( ! empty( $term_ids ) ) {
            wp_set_object_terms( $post_id, $term_ids, 'meal_type' );
        }
    }

    /**
     * Set recipe tags
     *
     * @param int   $post_id Post ID.
     * @param array $tags    Tags.
     * @return void
     */
    private function set_recipe_tags( $post_id, $tags ) {
        $term_ids = [];

        foreach ( $tags as $tag ) {
            $term = term_exists( $tag, 'recipe_tag' );
            
            if ( ! $term ) {
                $term = wp_insert_term( $tag, 'recipe_tag' );
            }
            
            if ( ! is_wp_error( $term ) ) {
                $term_ids[] = (int) $term['term_id'];
            }
        }

        if ( ! empty( $term_ids ) ) {
            wp_set_object_terms( $post_id, $term_ids, 'recipe_tag' );
        }
    }

    /**
     * Update ACF fields for a recipe
     *
     * @param int   $post_id     Post ID.
     * @param array $recipe_data Recipe data.
     * @return void
     */
    private function update_acf_fields( $post_id, $recipe_data ) {
        if ( ! function_exists( 'update_field' ) ) {
            return;
        }

        // Update prep time
        if ( isset( $recipe_data['prep_time'] ) ) {
            update_field( 'prep_time_minutes', (int) $recipe_data['prep_time'], $post_id );
        }

        // Update cooking time
        if ( isset( $recipe_data['cooking_time'] ) ) {
            // Convert ISO duration to minutes if needed
            $cooking_time = is_numeric( $recipe_data['cooking_time'] ) 
                ? (int) $recipe_data['cooking_time'] 
                : $this->iso8601_to_minutes( $recipe_data['cooking_time'] );
            update_field( 'cooking_time', $cooking_time, $post_id );
        }

        // Update servings
        if ( isset( $recipe_data['servings'] ) ) {
            update_field( 'servings', (int) $recipe_data['servings'], $post_id );
        }

        // Update calories
        if ( isset( $recipe_data['calories'] ) ) {
            update_field( 'calories', (int) $recipe_data['calories'], $post_id );
        }

        // Update difficulty level
        if ( isset( $recipe_data['difficulty'] ) ) {
            update_field( 'difficulty_level', sanitize_text_field( $recipe_data['difficulty'] ), $post_id );
        }
        
        // Update cuisine
        if ( isset( $recipe_data['cuisine'] ) ) {
            update_field( 'cuisine', sanitize_text_field( $recipe_data['cuisine'] ), $post_id );
        }
        
        // Update rating
        if ( isset( $recipe_data['rating'] ) ) {
            update_field( 'rating', (float) $recipe_data['rating'], $post_id );
        }
        
        // Update review count
        if ( isset( $recipe_data['review_count'] ) ) {
            update_field( 'review_count', (int) $recipe_data['review_count'], $post_id );
        }

        // Update ingredients
        if ( isset( $recipe_data['ingredients'] ) && is_array( $recipe_data['ingredients'] ) ) {
            $ingredients = [];
            
            foreach ( $recipe_data['ingredients'] as $ingredient ) {
                if ( is_string( $ingredient ) ) {
                    // Parse text ingredient
                    $parts = $this->parse_ingredient_text( $ingredient );
                    $ingredients[] = [
                        'name'   => $parts['name'],
                        'amount' => $parts['amount'],
                        'unit'   => $parts['unit'],
                    ];
                } else {
                    // Structured ingredient
                    $ingredients[] = [
                        'name'   => isset( $ingredient['name'] ) ? sanitize_text_field( $ingredient['name'] ) : '',
                        'amount' => isset( $ingredient['amount'] ) ? sanitize_text_field( $ingredient['amount'] ) : '',
                        'unit'   => isset( $ingredient['unit'] ) ? sanitize_text_field( $ingredient['unit'] ) : '',
                    ];
                }
            }
            
            update_field( 'ingredients', $ingredients, $post_id );
        }

        // Update instructions
        if ( isset( $recipe_data['instructions'] ) && is_array( $recipe_data['instructions'] ) ) {
            $instructions = [];
            
            foreach ( $recipe_data['instructions'] as $instruction ) {
                if ( is_string( $instruction ) ) {
                    $instructions[] = [
                        'step'  => sanitize_textarea_field( $instruction ),
                        'image' => '',
                    ];
                } else {
                    $instructions[] = [
                        'step'  => isset( $instruction['text'] ) ? sanitize_textarea_field( $instruction['text'] ) : '',
                        'image' => '',
                    ];
                }
            }
            
            update_field( 'instructions', $instructions, $post_id );
        }

        // Update tips
        if ( isset( $recipe_data['tips'] ) ) {
            update_field( 'tips', wp_kses_post( $recipe_data['tips'] ), $post_id );
        }
    }

    /**
     * Parse ingredient text into components
     *
     * @param string $text Ingredient text.
     * @return array Parsed ingredients with name, amount, and unit.
     */
    private function parse_ingredient_text( $text ) {
        $result = [
            'name'   => $text,
            'amount' => '',
            'unit'   => '',
        ];

        // Simple regex to extract amount and unit
        if ( preg_match( '/^([\d\/\.\s]+)\s*([a-zA-Z]+)?\s+(.+)$/', trim( $text ), $matches ) ) {
            $result['amount'] = trim( $matches[1] );
            $result['unit'] = isset( $matches[2] ) ? trim( $matches[2] ) : '';
            $result['name'] = trim( $matches[3] );
        }

        return $result;
    }

    /**
     * Convert ISO 8601 duration to minutes
     *
     * @param string $iso8601 ISO 8601 duration string.
     * @return int Duration in minutes.
     */
    private function iso8601_to_minutes( $iso8601 ) {
        $minutes = 0;
        
        // Extract hours
        if ( preg_match( '/(\d+)H/', $iso8601, $matches ) ) {
            $minutes += (int) $matches[1] * 60;
        }
        
        // Extract minutes
        if ( preg_match( '/(\d+)M/', $iso8601, $matches ) ) {
            $minutes += (int) $matches[1];
        }
        
        return $minutes;
    }
} 