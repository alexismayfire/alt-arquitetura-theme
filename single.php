<?php get_header(); ?>

<?php $comments = get_comments( array( 'post_id' => $post->ID ) ); ?>

<div class="container">
    <section class="section columns pt-1 is-centered is-variable is-6 is-multiline">
    <?php if (has_post_thumbnail()): ?>
        <!-- TODO: Change alt to use image title, if it has one -->
        <div class="column is-full is-two-thirds-widescreen">
            <img 
                class="responsive-img col s12"
                src="<?php the_post_thumbnail_url( 'blog-large' ); ?>"
                alt="<?php the_title(); ?>"
            />
        </div>
    <?php endif; ?>
        <div class="column is-full is-two-thirds-widescreen blog-post">
            <h1><?php the_title(); ?></h1>
            <?php echo $post->post_content; ?>
        </div>
        <div class="is-full is-two-thirds-widescreen blog-comments">
        <?php if ( $comments ): ?>
            <h2 class="comments-title">
                <?php
                    printf( _nx( 'One thought on "%2$s"', '%1$s thoughts on "%2$s"', get_comments_number(), 'comments title', 'twentythirteen' ),
                        number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
                ?>
            </h2>
    
            <ol class="comment-list">
                <?php
                    wp_list_comments( array(
                        'style'       => 'ol',
                        'short_ping'  => true,
                        'avatar_size' => 74,
                    ) );
                ?>
            </ol><!-- .comment-list -->
    
            <?php
                // Are there comments to navigate through?
                if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
            ?>
            <nav class="navigation comment-navigation" role="navigation">
                <h1 class="screen-reader-text section-heading"><?php _e( 'Comment navigation', 'twentythirteen' ); ?></h1>
                <div class="nav-previous"><?php previous_comments_link( __( '&amp;larr; Older Comments', 'twentythirteen' ) ); ?></div>
                <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &amp;rarr;', 'twentythirteen' ) ); ?></div>
            </nav><!-- .comment-navigation -->
            <?php endif; // Check for comment navigation ?>
    
            <?php if ( ! comments_open() && get_comments_number() ) : ?>
            <p class="no-comments"><?php _e( 'Comments are closed.' , 'twentythirteen' ); ?></p>
            <?php endif; ?>
    
        <?php endif; // have_comments() ?>
        <?php comment_form(); ?>
        </div>
    </section>
</div>
<?php get_footer(); ?>