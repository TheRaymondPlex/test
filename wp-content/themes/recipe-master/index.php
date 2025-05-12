<?php
/**
 * The main template file
 *
 * @package RecipeMaster
 */

get_header();
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <?php if (is_home() && !is_front_page()) : ?>
            <header class="mb-8 text-center">
                <h1 class="text-3xl md:text-4xl font-bold mb-2 font-playfair"><?php single_post_title(); ?></h1>
            </header>
        <?php endif; ?>

        <?php if (have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg overflow-hidden shadow-md transition-transform duration-300 hover:shadow-lg hover:-translate-y-1'); ?>>
                        <a href="<?php the_permalink(); ?>" class="block">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="relative h-48 overflow-hidden">
                                    <?php the_post_thumbnail('medium_large', array('class' => 'w-full h-full object-cover')); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="p-4">
                                <header class="entry-header">
                                    <?php the_title('<h2 class="text-xl font-bold mb-2 text-gray-800 line-clamp-2">', '</h2>'); ?>
                                </header>

                                <div class="text-sm text-gray-600 mb-3">
                                    <?php
                                    echo sprintf(
                                        /* translators: %s: post date */
                                        esc_html__('Posted on %s', 'recipe-master'),
                                        '<time datetime="' . esc_attr(get_the_date('c')) . '">' . esc_html(get_the_date()) . '</time>'
                                    );
                                    ?>
                                </div>

                                <div class="entry-content text-gray-700">
                                    <?php the_excerpt(); ?>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>

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
                <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('No Posts Found', 'recipe-master'); ?></h2>
                <p class="text-gray-600"><?php esc_html_e('It seems we cannot find what you are looking for.', 'recipe-master'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?> 