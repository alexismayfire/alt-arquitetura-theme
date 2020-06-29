<?php get_header(); ?>

<main class="container">
    <section class="section columns is-variable is-6 is-multiline">
        <div class="column is-full blog-header">
            <h1 class="section-title">blog</h1>
            <?php echo get_search_form(); ?>
        </div>
        <div class="column is-four-fifths">
        <?php
        global $wp_query;
        
        $big = 999999999;
        $links = paginate_links( array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => max( 1, get_query_var('paged') ),
            'total' => $wp_query->max_num_pages,
            'prev_text' => '<i class="fas fa-md fa-chevron-left"></i>',
            'next_text' => '<i class="fas fa-md fa-chevron-right"></i>',
            'type' => 'array',
        ) );

        if ( $links ):
            $current = max( 1, get_query_var( 'paged' ) );
            $total_pages = count($links);

            $available_links = array_map( function($val, $key) use ($current, $total_pages) {
                $page = $current > 1 && $current < $total_pages ? $key : $key + 1;

                return $page !== $current
                    ? '<li class="waves-effect">'. $val . '</li>'
                    : '<li class="active"><a href="#!">'. $current . '</a></li>';
            }, $links, array_keys($links) );

            echo '<ul class="pagination">';
            if ( $prev_posts_link = get_previous_posts_link() && 0 == 1):
                echo '<li class="waves-effect">'. $prev_posts_link .'</li>';
            endif;

            echo join( '', $available_links);

            if ( $next_posts_link = get_next_posts_link() && 0 == 1):
                echo '<li class="waves-effect">'. $next_posts_link .'</li>';
            endif;

            echo '</ul>';
        
        elseif ( $wp_query->have_posts() ): ?>
            <h3 class="is-size-4 mb-4">Resultados da busca para: <span class="has-text-weight-semibold"><?php the_search_query(); ?></span></h3>
            <div class="blog">
            <?php while ( $wp_query->have_posts() ): $wp_query->the_post(); ?>
                <div class="blog-card">
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
            <?php endwhile; ?>
            </div>
        <?php else: ?>
            <h3 class="is-size-4 mb-4">Nenhum post encontrado para: <span class="has-text-weight-semibold"><?php the_search_query(); ?></span></h3>
        <?php endif; ?>
        </div>
        <div class="column is-one-fifth">
        <?php 
            if( is_active_sidebar( 'blog-sidebar' ) ):
                dynamic_sidebar( 'blog-sidebar' );
            endif;
        ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>