<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package doccure
 */
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}

$comment_count = get_comments_number();
$retina_mult = doccure_get_retina_multiplier();
?>
<div id="comments" class="comments-area">
    <?php if (have_comments()) : ?>
        <h3 class="title"><?php printf(_n('One Comment', '%s Comments', get_comments_number(), 'doccure'), number_format_i18n(get_comments_number())); ?></h3>
        <ol class="comment-list">
            <?php
            wp_list_comments(
                array(
                    'walker' => new doccure_Walker_Comment(),
                    'avatar_size' => 100 * $retina_mult,
                    'style' => 'ol',
                    'short_ping' => true,
                )
            );
            ?>
        </ol>
        <?php
        the_comments_navigation();
    endif;

    // If comments are closed and there are comments, let's leave a little note, shall we?
    if (!comments_open()) :
        ?>
        <p class="no-comments"><?php esc_html_e('Comments are closed.', 'doccure'); ?></p>
    <?php
    endif;

    $required = get_option('require_name_email');
    $aria_required = ($required ? " aria-required='true'" : '');
    $commenter = wp_get_current_commenter();
    $consent = empty($commenter['comment_author_email']) ? '' : ' checked="checked"';
    $comments_args = array(
        'comment_notes_after' => '',
        'submit_button' => '<button name="%1$s" type="submit" id="%2$s" class="%3$s doccure_btn">%4$s <i class="far fa-comments"></i></button>',
        'fields' => apply_filters(
            'doccure_comment_form_fields',
            array(
                'author' => '<div class="doccure-comment-form-input-wrapper"><p class="comment-form-author">' .
                    '<input id="author" placeholder="' . esc_attr__('Name', 'doccure') . '*" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_required . ' />' .
                    '<span class="icon"><i class="fas fa-user"></i></span>' .
                    '</p>',
                'email' => '<p class="comment-form-email">' .
                    '<input id="email" placeholder="' . esc_attr__('Email', 'doccure') . '*" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_required . ' />' .
                    '<span class="icon"><i class="far fa-envelope"></i></span>',
                '</p>',
                'url' => '<p class="comment-form-url">' .
                    '<input id="url" name="url" placeholder="' . esc_attr__('Website', 'doccure') . '" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" />' .
                    '<span class="icon"><i class="fas fa-globe"></i></span>' .
                    '</p></div>',
                'cookies' => '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />' .
                    '<label for="wp-comment-cookies-consent">' . esc_html__('Save my name, email, and website in this browser for the next time I comment.', 'doccure') . '</label></p>',
            )
        ),
        'comment_field' => '<p class="comment-form-comment">' .
            '<textarea id="comment" name="comment" placeholder="' . esc_attr__('Enter your comment here...', 'doccure') . '" cols="45" rows="8" aria-required="true"></textarea>' .
            '</p>',
    );
    comment_form($comments_args);
    ?>
</div>
