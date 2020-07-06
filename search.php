<?php get_header(); ?>

<main class="container">
    <section class="section columns is-variable is-6 is-multiline">
        <div class="column blog-header">
            <h1 class="section-title">blog</h1>
            <?php echo get_search_form(); ?>
        </div>
        <div class="column is-four-fifths-desktop">
        <?php global $wp_query; if ( $wp_query->have_posts() ): ?>
            <h3 class="is-size-4 mb-4">Resultados da busca para: <span class="has-text-weight-semibold"><?php the_search_query(); ?></span></h3>
            <div class="blog">
                <?php 
                    
                if ( $wp_query->have_posts() ): 
                    while ( $wp_query->have_posts() ): 
                        $wp_query->the_post();
                        get_template_part( 'template-parts/blog', 'card' );
                    endwhile; 
                endif; 
                
                ?>
            </div>
        <?php else: ?>
            <h3 class="is-size-4 mb-4">Nenhum post encontrado para: <span class="has-text-weight-semibold"><?php the_search_query(); ?></span></h3>
        <?php endif; ?>
        </div>
        <div class="column is-one-fifth-desktop blog-categories">
        <?php 
            if( is_active_sidebar( 'blog-sidebar' ) ):
                dynamic_sidebar( 'blog-sidebar' );
            endif;
        ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>