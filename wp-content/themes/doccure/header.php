<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package doccure
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
    <link href="https://fonts.googleapis.com/css2?family=Encode+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
</head>
<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php get_template_part('template-parts/preloader/content'); ?>

<div id="page" class="site">
    <!-- Page Template Before Header --->
    <?php echo doccure_get_the_page_template('page_template_before_header', 'enable_page_template_before_header'); ?>

    <?php get_template_part('template-parts/header/content'); ?>

    <!-- Page Template After Header --->
    <?php echo doccure_get_the_page_template('page_template_after_header', 'enable_page_template_after_header'); ?>

    <div id="content" class="site-content">

        <?php get_template_part('template-parts/subheader/content'); ?>


<?php 

?>
                                                    