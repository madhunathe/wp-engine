<?php
/**
 * Template part for header social info.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package doccure
 */
if (!doccure_get_option('display-collapse-sidebar')) {
    return;
}
?>
<!-- Sidebar Navigation -->
<aside class="doccure_aside doccure_aside-desktop">
    <div class="sidebar">
        <?php
        if (is_active_sidebar('header-collapse-sidebar')) {
            dynamic_sidebar('header-collapse-sidebar');
        }
        ?>
    </div>
</aside>
<div class="doccure_aside-overlay aside-trigger-right"></div>
