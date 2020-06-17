<?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>
    <div class="card large">
        <div class="card-image">
            <img 
                src="<?php the_post_thumbnail_url( 'blog-small' ); ?>"
                alt="<?php the_title(); ?>"
            />
            <h3 class="card-title bg-primary"><?php the_title(); ?></h3>
        </div>
        <div class="card-content">
            <?php the_excerpt(); ?>
            <div class="card-action">
                <a href="<?php the_permalink(); ?>" class="waves-effect waves-light btn">
                    Leia mais
                </a>
            </div>
        </div>
    </div>
<?php endwhile; else: ?>
    <p class="caption-text">Sem resultados para '<?php echo get_search_query(); ?>'</p>
<?php endif; ?>