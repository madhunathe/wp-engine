<?php
/**
 * Template part for header search.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package doccure
 */
if (!doccure_get_option('display-search-icon')) {
    return;
}
if( doccure_get_option('enable-ajax-search') == true ){
  doccure_ajax_search_form();
} else {
?>
<div class="search-form-wrapper">
    <div class="search-trigger doccure_close">
        <span></span>
        <span></span>
    </div>
    <?php get_search_form(); ?>
</div>
<?php }
