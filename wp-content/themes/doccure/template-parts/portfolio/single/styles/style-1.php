<?php
/**
 * Template part for displaying portfolio details.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package doccure
 */
$portfolio_terms = get_the_terms(get_the_ID(), 'portfolio-category');
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('doccure_post-details-inner'); ?>>
    <div class="entry-content">
        <?php the_content(); ?>
    </div>
</article>

