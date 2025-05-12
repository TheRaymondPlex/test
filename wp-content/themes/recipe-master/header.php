<?php
/**
 * The header for our theme
 *
 * @package RecipeMaster
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <header id="masthead" class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row justify-center items-center">
                <div class="site-branding mb-4 md:mb-0">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="inline-block">
                        <?php 
                        if (has_custom_logo()) {
                            $custom_logo_id = get_theme_mod('custom_logo');
                            $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                            
                            if (is_array($logo)) {
                                $logo_url = $logo[0];
                                $file_ext = pathinfo($logo_url, PATHINFO_EXTENSION);
                                
                                if ($file_ext === 'svg') {
                                    // SVG logo
                                    echo '<img src="' . esc_url($logo_url) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="h-12 w-auto">';
                                } else {
                                    // Regular image logo
                                    echo '<img src="' . esc_url($logo_url) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="h-12 w-auto">';
                                }
                            }
                        } else {
                            // Fallback to site title if no logo is set
                            echo '<span class="text-2xl font-bold text-gray-800">' . esc_html(get_bloginfo('name')) . '</span>';
                        }
                        ?>
                    </a>
                    <?php
                    $recipe_master_description = get_bloginfo('description', 'display');
                    if ($recipe_master_description || is_customize_preview()) :
                    ?>
                        <p class="site-description text-sm text-gray-600 mt-1"><?php echo $recipe_master_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                    <?php endif; ?>
                </div>

                <nav id="site-navigation" class="main-navigation">
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'menu-1',
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                            'menu_class'     => 'flex flex-wrap justify-center gap-6',
                        )
                    );
                    ?>
                </nav>
            </div>
        </div>
    </header>
