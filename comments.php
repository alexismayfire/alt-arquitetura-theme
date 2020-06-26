<?php

if ( post_password_required() ):
	return;
endif;

?>

<?php if ( $comments ) : ?>
<div class="comments" id="comments">
	<div class="comments-header mb-4">
		<h2 class="is-size-4 has-text-weight-semibold comment-reply-title"><?php get_template_part( 'template-parts/comments', 'header' ); ?></h2>
	</div>
	<div>
	<?php wp_list_comments( array( 'walker' => new Alt_Walker_Comment(), 'style' => 'div' ) ); ?>
	</div>
</div>
<?php endif; ?>

<?php get_template_part( 'template-parts/comments', 'form' ); ?>