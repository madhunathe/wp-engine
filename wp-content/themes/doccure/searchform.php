<?php
/**
 * Template for displaying search form
 *
 * @package WordPress
 * @version 1.0.0
 */
$search_result_ptypes = !empty(doccure_get_option('search-result-cpt')) ? implode(',', doccure_get_option('search-result-cpt')) : 'post';
?>
<form id="<?php echo esc_attr(uniqid('searchform-')) ?>" class="search-form"
      action="<?php print esc_url(home_url('/')); ?>" method="get">
    <input type="search" placeholder="<?php echo esc_attr__('Search...', 'doccure') ?>"
           id="<?php echo esc_attr(uniqid('search-form-')) ?>" value="<?php echo esc_attr(get_search_query()); ?>"
           name="s" required>
    <input type="hidden" name="post_type" value="<?php echo esc_attr($search_result_ptypes); ?>"/>
    <button type="submit"><i class="fas fa-search"></i></button>
</form>
