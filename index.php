<?php get_header(); ?>

<div class="container">
    <section class="section columns is-variable is-6 is-multiline">
        <div class="column is-full">
            <h1 class="projects-title"><?php single_post_title(); ?></h1>
        </div>
        <div class="column is-four-fifths">
            <div class="blog">
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
            
            else: while ( $wp_query->have_posts() ): $wp_query->the_post(); ?>
                <div class="blog-card">
                    <a href="<?php the_permalink(); ?>">
                        <img class="blog-card-image" src="<?php the_post_thumbnail_url( 'blog-large' ); ?>" alt="<?php the_title(); ?>" />
                        
                    </a>
                    <div class="blog-card-content">
                        <h4 class="blog-card-title"><?php the_title(); ?></h4>
                        <?php the_excerpt(); ?>
                        <a class="button is-dark" href="<?php the_permalink(); ?>">Ler mais</a>
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

<?php get_footer(); ?>