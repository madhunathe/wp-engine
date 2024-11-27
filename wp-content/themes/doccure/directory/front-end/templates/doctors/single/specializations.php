<?php
/**
 *
 * The template used for doctors specialiizations
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id = $post->ID;
$specialities	= get_the_term_list( $post->ID, 'specialities', '<ul class="dc-specializationslist"><li><span>', '</span></li><li><span>', '</span></li></ul>' );

$specialities	= !empty( $specialities ) ? $specialities : '';


