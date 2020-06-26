<?php 

if ( comments_open() || pings_open() ):
	comment_form(
		array(
			'fields' => array(
				html_comment_field( 'author', 'Nome', 'text' ),
				html_comment_field( 'email', 'Email', 'email' ),
				html_comment_field( 'wp-comment-cookies-consent', 'Salvar meus dados nesse dispositivo', 'checkbox' ),
			),
			'comment_field' => html_comment_field( 'comment', 'Comentário', 'textarea' ),
			'comment_notes_before' => '<div class="mb-4">Não se preocupe, seu email não será publicado</div>',
			'class_form' => 'wpcf7-form section-inner thin max-percentage',
			'title_reply_before' => '<h3 id="reply-title" class="is-size-5 has-text-weight-semibold comment-reply-title">',
			'title_reply_after' => '</h3>',
			'cancel_reply_before' => '<div class="is-inline ml-4">',
			'cancel_reply_after' => '</div>',
			'cancel_reply_link' => '(cancelar)',
			'submit_button' => '<button type="submit" id="%2$s" class="wpcf7-form-control wpcf7-submit button is-dark is-pulled-right">Comentar <i class="fas fa-md fa-paper-plane"></i></button>',
			// TODO: check if is logged-in, then remove is-one-third-widescreen
			'submit_field' => '<div class="column is-full is-one-third-widescreen">%1$s %2$s</div>'
		)
	);
endif; 

?>