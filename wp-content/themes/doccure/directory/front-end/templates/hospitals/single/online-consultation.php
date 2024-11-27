<?php
/**
 *
 * The template used for hospital consultation
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id 	= $post->ID;
$name		= doccure_full_name( $post_id );
$name		= !empty( $name ) ? $name : ''; 
$link_id	= doccure_get_linked_profile_id( $post_id ,'post' );


$args = array(
    'post_type' 	=> 'hospitals_team',
    'post_status' 	=> 'publish',
    'meta_query' => array(
        array(
            'key'   => 'hospital_id',
            'value' => $post_id,
        )
    ),
	'fields' => 'ids',
);

$team_authers 		= get_posts( $args );
$comments_per_page 	= 2;

$comments_args	= array(
					'author__in' 	=> $team_authers,
					'post_type'		=> 'healthforum',
					);

$comments		= get_comments($comments_args);
$total_comments = count($comments);
?>
<div class="dc-contentdoctab dc-location-holder tab-pane fade show" id="comments">
	<div class="dc-consultation">
		<?php if (!empty($comments)) { ?>

			<div class="dc-searchresult-head">
				<div class="dc-title"><h4>“<?php echo esc_html( $name );?>” <?php esc_html_e('Answers','doccure');?></h4></div>
			</div>
			<div class="dc-consultation-content">
				<?php 
					wp_list_comments(array(
						'callback' => 'doccure_answer_by_author'
					), $comments);
					if($total_comments > $comments_per_page) {
						doccure_prepare_comments_pagination($total_comments,$comments_per_page);
					}
				?>
			</div>
	
		<?php } else { ?>
			<?php esc_html_e('No consultion found.','doccure');?>
		<?php } ?>
	</div>
</div>