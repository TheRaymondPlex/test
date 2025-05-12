<?php
/**
 * Front page template
 *
 * @package RecipeMaster
 */

get_header();

// Query recipes
$args = array(
    'post_type'      => 'recipe',
    'posts_per_page' => 9,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

$recipe_query = new WP_Query( $args );
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold mb-8 font-playfair text-center"><?php echo get_bloginfo('name'); ?></h1>
        
        <?php if ( $recipe_query->have_posts() ) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while ( $recipe_query->have_posts() ) : $recipe_query->the_post(); ?>
                    <div class="bg-white rounded-lg overflow-hidden shadow-md transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                        <a href="<?php the_permalink(); ?>" class="block">
                            <div class="relative h-48 overflow-hidden">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-full object-cover' ) ); ?>
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
            
            <?php wp_reset_postdata(); ?>
            
            <div class="mt-8 text-center">
                <a href="<?php echo esc_url(get_post_type_archive_link('recipe')); ?>" class="inline-flex items-center justify-center rounded-md bg-purple-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors">
                    <?php esc_html_e('View All Recipes', 'recipe-master'); ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </div>
            
        <?php else : ?>
            <div class="text-center py-12">
                <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('No Recipes Found', 'recipe-master'); ?></h2>
                <p class="text-gray-600"><?php esc_html_e('Start creating delicious recipes to see them here.', 'recipe-master'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?> 