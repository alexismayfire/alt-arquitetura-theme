<?php 
get_header(); 
$max_posts = get_option( 'posts_per_page' );
?>

<div class="container">
    <section class="section columns is-variable is-6 is-multiline">
        <div class="column is-full blog-header">
            <h1 class="projects-title"><?php single_post_title(); ?></h1>
            <?php echo get_search_form(); ?>
        </div>
        <div class="column is-four-fifths">
            <div class="blog">
            <?php if ( have_posts() ): while ( have_posts() && $i < $max_posts ): the_post(); $i++; ?>
                <div class="blog-card" data-id="<?php $post->ID; ?>">
                    <a href="<?php the_permalink(); ?>">
                        <img class="blog-card-image" src="<?php the_post_thumbnail_url( 'blog-large' ); ?>" alt="<?php the_title(); ?>" />
                    </a>
                    <div class="blog-card-content">
                        <h4 class="blog-card-title"><?php the_title(); ?></h4>
                        <span class="blog-card-meta"><?php echo get_the_date(); ?> | <?php the_terms ( $post->ID, 'category' ); ?></span>
                        <span class="blog-card-author"><?php the_author(); ?></span>
                        
                        <div class="blog-card-excerpt">
                            <?php the_excerpt(); ?>
                            <a class="button is-dark is-uppercase has-text-weight-bold" href="<?php the_permalink(); ?>">Ler mais</i></a>
                        </div>
                    </div>
                </div>
            <?php endwhile; endif; ?>
            </div>
        </div>
        <div class="column is-one-fifth">
        <?php 
            if( is_active_sidebar( 'blog-sidebar' ) ):
                dynamic_sidebar( 'blog-sidebar' );
            endif;
        ?>
        </div>
    </section>
</div>

<?php wp_enqueue_script( 'blog' ); get_footer(); ?>