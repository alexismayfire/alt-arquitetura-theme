<?php
/**
 * Custom comment walker for this theme.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

if ( ! class_exists( 'Alt_Walker_Comment' ) ) {
	/**
	 * CUSTOM COMMENT WALKER
	 * A custom walker for comments, based on the walker in Twenty Nineteen.
	 */
	class Alt_Walker_Comment extends Walker_Comment {

		/**
		 * Outputs a comment in the HTML5 format.
		 *
		 * @see wp_list_comments()
		 * @see https://developer.wordpress.org/reference/functions/get_comment_author_url/
		 * @see https://developer.wordpress.org/reference/functions/get_comment_author/
		 * @see https://developer.wordpress.org/reference/functions/get_avatar/
		 * @see https://developer.wordpress.org/reference/functions/get_comment_reply_link/
		 * @see https://developer.wordpress.org/reference/functions/get_edit_comment_link/
		 *
		 * @param WP_Comment $comment Comment to display.
		 * @param int        $depth   Depth of the current comment.
		 * @param array      $args    An array of arguments.
		 */
		protected function html5_comment( $comment, $depth, $args ) { ?>
			<div id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
				<article id="div-comment-<?php comment_ID(); ?>" class="comment-body mb-4">
					<footer class="comment-meta mb-2">
						<div class="comment-author vcard">
							<span class="has-text-weight-semibold"><?php echo get_comment_author( $comment ); ?></span>
						<?php if ( is_comment_by_post_author( $comment ) ): ?>
							<span class="tag is-primary ml-2">autor do post</span>
						<?php endif; ?>
						</div><!-- .comment-author -->
						<div class="comment-metadata">
							<span class="is-size-6 has-text-grey">
								<time datetime="<?php comment_time( 'c' ); ?>" title="<?php echo get_comment_datetime_fmt ( $comment, esc_attr ) ?>"><?php echo get_comment_datetime_fmt( $comment, esc_html ); ?></time>
							</span>
							<?php if ( get_edit_comment_link() ): ?>
							<span aria-hidden="true">&bull;</span>
							<a class="comment-edit-link is-size-6 has-text-grey has-text-weight-semibold" href="<?php echo esc_url( get_edit_comment_link() ); ?>">Editar</a>
							<?php endif ?>
						</div>
					</footer>
					<div class="comment-content entry-content">
						<?php comment_text(); ?>
						<?php if ( '0' === $comment->comment_approved ): ?>
						<p class="is-size-6 is-italic has-text-weight-semibold">Seu comentário está aguardando moderação</p>
						<?php endif; ?>
					</div>
					<?php

					$comment_reply_link = get_comment_reply_link(
						array_merge(
							$args,
							array(
								'add_below' => 'div-comment',
								'depth'     => $depth,
								'max_depth' => $args['max_depth'],
								'before'    => '<span class="comment-reply is-size-6 has-text-grey has-text-weight-semibold">',
								'after'     => '</span>',
							)
						)
					);

					if ( $comment_reply_link ): ?>
					<footer class="comment-footer-meta">
						<?php echo $comment_reply_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Link is escaped in https://developer.wordpress.org/reference/functions/get_comment_reply_link/ ?>
					</footer>
					<?php endif; ?>
					<hr class="styled-separator is-style-wide has-background-grey" aria-hidden="true" />
				</article>
			<?php
		}
	}
}
