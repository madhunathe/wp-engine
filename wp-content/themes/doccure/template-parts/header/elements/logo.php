<?php
/**
 * Template part for header logo.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package doccure
 */
?>
<div class="doccure_logo-wrapper">
    <?php
    // Logo
    doccure_get_site_logo('site-logo');
    if (doccure_get_option('sticky-logo') && doccure_get_option('sticky-header-enable')) {
        doccure_get_site_logo('sticky-logo', false);
    }
    // Info Card
    if (doccure_get_option('display_info_card')) {
        get_template_part('template-parts/header/elements/info-card');
    }
    ?>
</div>
