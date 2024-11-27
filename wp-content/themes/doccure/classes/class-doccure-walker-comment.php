<?php
/**
 * Custom comment walker for this theme.
 *
 * @package WordPress
 * @subpackage doccure
 * @since 1.0.0
 */
if ( ! class_exists( 'doccure_Walker_Comment' ) ) {
	/**
	 * Core walker class used to create an HTML list of comments.
	 *
	 * @since 2.7.0
	 *
	 * @see Walker
	 */
	class doccure_Walker_Comment extends Walker_Comment {
		/**
		 * Outputs a comment in the HTML5 format.
		 *
		 * @since 3.6.0
		 *
		 * @see wp_list_comments()
		 *
		 * @param WP_Comment $comment Comment to display.
		 * @param int        $depth   Depth of the current comment.
		 * @param array      $args    An array of arguments.
		 */
		protected function html5_comment( $comment, $depth, $args ) {
			$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
			$commenter = wp_get_current_commenter();
			if ( $commenter['comment_author_email'] ) {
				$moderation_note = esc_html__( 'Your comment is awaiting moderation.', 'doccure' );
			} else {
				$moderation_note = esc_html__( 'Your comment is awaiting moderation. This is a preview, your comment will be visible after it has been approved.', 'doccure' );
			}
			$comment_author = get_comment_author( $comment );
			?>
			<<?php echo esc_html( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
				<div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
					<div class="comment-avatar">
						<?php
						if ( 0 !== $args['avatar_size'] ) {
							echo get_avatar( $comment, $args['avatar_size'] );
						}
						?>
					</div>
					<div class="comment-content">
						<div class="comment-meta">
							<h5 class="comment-author"><?php echo esc_html( $comment_author ); ?></h5>
							<span class="comment-date">
								<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
									<i class="far fa-clock"></i>
									<time datetime="<?php comment_time( 'c' ); ?>">
										<?php
										/* translators: 1: Comment date, 2: Comment time. */
										printf( esc_html__( '%1$s', 'doccure' ), esc_html( get_comment_date( '', $comment ) ));
										?>
									</time>
								</a>
							</span>
						</div>
						<?php comment_text(); ?>
						<?php
						comment_reply_link(
							array_merge(
								$args,
								array(
									'add_below' => 'div-comment',
									'depth'     => $depth,
									'max_depth' => $args['max_depth'],
									'before'    => '<div class="reply">',
									'after'     => '</div>',
								)
							)
						);
						?>
					</div>
				</div><!-- .comment-body -->
			<?php
		}
	}
}
