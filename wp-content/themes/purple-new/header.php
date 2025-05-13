<?php
/**
 * The header for our theme
 *
 * @package Purple
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="header">
    <div class="wrapper header-wrapper">
        <button class="back-button">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/arrow.svg" alt="">
            <span>הקודם</span>
        </button>

        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/logo.svg" alt="purple">
    </div>
</header>

<div class="container">
    <div class="wrapper"> 