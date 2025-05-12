<?php
/**
 * The template for displaying the footer
 *
 * @package RecipeMaster
 */

?>
    <footer id="colophon" class="bg-white border-t border-gray-200 mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="flex flex-col items-center">
                <?php if (has_custom_logo()) : ?>
                    <div class="mb-4">
                        <?php
                        $custom_logo_id = get_theme_mod('custom_logo');
                        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                        
                        if (is_array($logo)) {
                            $logo_url = $logo[0];
                            echo '<a href="' . esc_url(home_url('/')) . '" class="inline-block">';
                            echo '<img src="' . esc_url($logo_url) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="h-8 w-auto">';
                            echo '</a>';
                        }
                        ?>
                    </div>
                <?php endif; ?>
                
                <div class="site-info text-center text-sm text-gray-600">
                    <p class="mb-2">
                        <?php
                        /* translators: 1: Theme name, 2: Theme author. */
                        printf(esc_html__('%1$s by %2$s.', 'recipe-master'), 'Recipe Master', '<a href="mailto:theraymondplex@gmail.com" class="text-purple-600 hover:text-purple-800">Igor Novak</a>');
                        ?>
                    </p>
                    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('Built specially for PURPLE. All rights reserved.', 'recipe-master'); ?></p>
                </div>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html> 