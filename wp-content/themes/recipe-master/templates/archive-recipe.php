<?php
/**
 * The template for displaying recipe archives
 *
 * @package RecipeMaster
 */

get_header();
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <header class="mb-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-2 font-playfair">
                <?php 
                if (is_tax()) {
                    single_term_title();
                } else {
                    post_type_archive_title();
                }
                ?>
            </h1>
            <?php the_archive_description('<div class="text-gray-600 max-w-2xl mx-auto">', '</div>'); ?>
        </header>

        <?php 
        // Get the current page number
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;
        
        // Define the query arguments
        $args = array(
            'post_type' => 'recipe',
            'posts_per_page' => 12,
            'paged' => $paged,
        );
        
        // If we're on a taxonomy page, add the tax query
        if (is_tax()) {
            $term = get_queried_object();
            $args['tax_query'] = array(
                array(
                    'taxonomy' => $term->taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $term->term_id,
                ),
            );
        }
        
        // Create a new query
        $recipes_query = new WP_Query($args);
        
        // Temporarily replace the main query with our custom query for pagination
        $temp_query = $GLOBALS['wp_query'];
        $GLOBALS['wp_query'] = $recipes_query;
        
        if ($recipes_query->have_posts()) : 
        ?>
            <!-- Filter section -->
            <div class="mb-8">
                <?php
                // Recipe Filters
                $meal_types = get_terms([
                    'taxonomy' => 'meal_type',
                    'hide_empty' => true,
                ]);

                if (!empty($meal_types) && !is_wp_error($meal_types)) :
                ?>
                <div class="flex flex-wrap justify-center gap-2 mb-4">
                    <span class="text-gray-700 font-medium py-1 px-2"><?php esc_html_e('Filter by:', 'recipe-master'); ?></span>
                    <?php foreach ($meal_types as $meal_type) : ?>
                        <a href="<?php echo esc_url(get_term_link($meal_type)); ?>" class="inline-flex items-center rounded-full border px-3 py-1 text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 <?php echo is_tax('meal_type', $meal_type->term_id) ? 'bg-purple-600 text-white border-transparent' : 'border-gray-300 bg-white text-gray-800 hover:bg-gray-100'; ?>">
                            <?php echo esc_html($meal_type->name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Recipes grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while ($recipes_query->have_posts()) : $recipes_query->the_post(); ?>
                    <div class="bg-white rounded-lg overflow-hidden shadow-md transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                        <a href="<?php the_permalink(); ?>" class="block">
                            <div class="relative h-48 overflow-hidden">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium_large', array('class' => 'w-full h-full object-cover')); ?>
                                <?php else : ?>
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
                                            <path d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 0 1-3 0 2.704 2.704 0 0 0-3 0 2.704 2.704 0 0 1-3 0 2.704 2.704 0 0 0-3 0 2.701 2.701 0 0 0-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v7h18Z"></path>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                
                                <?php 
                                $rating = get_field('rating');
                                if ($rating) : 
                                ?>
                                <div class="absolute top-2 right-2 bg-black text-white rounded-full px-2 py-1 flex items-center text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" class="text-yellow-400 mr-1">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                                    </svg>
                                    <?php echo number_format((float)$rating, 1); ?>
                                </div>
                                <?php endif; ?>
                                
                                <?php
                                // Meal type badge
                                $meal_types = get_the_terms(get_the_ID(), 'meal_type');
                                if ($meal_types && !is_wp_error($meal_types)) :
                                    $meal_type = reset($meal_types);
                                ?>
                                <div class="absolute top-2 left-2 bg-purple-600 text-white rounded-full px-2 py-1 text-xs font-medium">
                                    <?php echo esc_html($meal_type->name); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="p-4">
                                <h2 class="text-xl font-bold mb-2 text-gray-800 line-clamp-2"><?php the_title(); ?></h2>
                                
                                <div class="flex items-center text-sm text-gray-600">
                                    <?php 
                                    $prep_time = get_field('prep_time_minutes');
                                    $cook_time = get_field('cooking_time');
                                    $total_time = 0;
                                    
                                    if ($prep_time) {
                                        $total_time += intval($prep_time);
                                    }
                                    
                                    if ($cook_time) {
                                        $total_time += intval($cook_time);
                                    }
                                    
                                    if ($total_time > 0) :
                                    ?>
                                    <div class="flex items-center mr-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                        <?php echo $total_time; ?> <?php esc_html_e('min', 'recipe-master'); ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php 
                                    $difficulty = get_field('difficulty_level');
                                    if ($difficulty) :
                                    ?>
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                            <path d="M17 21a1 1 0 0 0 1-1v-5.35c0-.457.316-.844.727-1.041a4 4 0 0 0-2.134-7.589a5 5 0 0 0-9.186 0a4 4 0 0 0-2.134 7.588c.411.198.727.585.727 1.041V20a1 1 0 0 0 1 1Z"></path>
                                            <path d="M6 17h12"></path>
                                        </svg>
                                        <?php echo ucfirst($difficulty); ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                <?php
                echo paginate_links([
                    'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>',
                    'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>',
                    'type' => 'list',
                    'end_size' => 1,
                    'mid_size' => 1,
                ]);
                ?>
            </div>

        <?php else : ?>
            <div class="text-center py-12">
                <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('No Recipes Found', 'recipe-master'); ?></h2>
                <p class="text-gray-600"><?php esc_html_e('Start creating delicious recipes to see them here.', 'recipe-master'); ?></p>
            </div>
        <?php 
        endif; 
        
        // Restore the original main query
        $GLOBALS['wp_query'] = $temp_query;
        
        // Reset post data
        wp_reset_postdata();
        ?>
    </div>
</div>

<?php get_footer(); ?> 