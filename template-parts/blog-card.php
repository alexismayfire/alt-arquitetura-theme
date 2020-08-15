<div class="blog-card" data-id="<?php $post->ID; ?>">
    <a href="<?php the_permalink(); ?>">
        <img class="blog-card-image" src="<?php the_post_thumbnail_url( 'blog-large' ); ?>" alt="<?php the_title(); ?>" />
    </a>
    <div class="blog-card-content">
        <a class="is-block" href="<?php the_permalink(); ?>">
            <h4 class="blog-card-title"><?php the_title(); ?></h4>
        </a>
        <span><?php echo get_the_date(); ?></span> | <?php echo get_post_author_tag(); ?>
        <span class="is-block has-text-weight-semibold mb-4"><?php the_terms ( $post->ID, 'category' ); ?></span>
        <div class="blog-card-excerpt">
            <?php the_excerpt(); ?>
            <a class="button is-dark is-uppercase has-text-weight-bold" href="<?php the_permalink(); ?>">Ler mais</i></a>
        </div>
    </div>
</div>