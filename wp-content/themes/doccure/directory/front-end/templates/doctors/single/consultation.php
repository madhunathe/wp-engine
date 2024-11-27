<?php
/**
 *
 * The template used for doctors consultation
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
$comments_per_page = 10;

$comments_args	= array(
					'author__in' 	=> $link_id,
					'post_type'		=> 'healthforum',
					);

$comments		= get_comments($comments_args);
$total_comments = count($comments);

?>
<div class="dc-comments-holder  " id="comments">
	<div class="dc-consultation">
		<div class="dc-searchresult-head">
			<div class="dc-title"><h4>“<?php echo esc_html( $name );?>” <?php esc_html_e('Answers','doccure');?></h4></div>
		</div>
		<div class="dc-consultation-content">
			<?php if (!empty($comments)) {?>
					<ul>
						<?php 
							wp_list_comments(array(
								'callback' => 'doccure_answer_by_author'
							), $comments);
						?>
					</ul>
					<?php 
					if( $total_comments > $comments_per_page ) {
						doccure_prepare_comments_pagination($total_comments,$comments_per_page);
					}
				} else {
					do_action('doccure_empty_records_html','dc-empty-offered-services dc-emptyholder-sm',esc_html__( 'No online consultation.', 'doccure' ));
				}
			?>
		</div>
	</div>
</div>