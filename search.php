<?php get_header(); ?>

<div class="container">
    <div class="row">
        <div class="section col l9">
            <h1 class="card-title">Resultados da busca para '<?php echo get_search_query(); ?>'</h1>

            <div class="row">
                <?php get_template_part( 'includes/section', 'searchresults' ); ?>
            </div>

            <div class="row">
                <?php
                    global $wp_query;
                    
                    $big = 999999999;
                    $links = paginate_links( array(
                        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                        'format' => '?paged=%#%',
                        'current' => max( 1, get_query_var('paged') ),
                        'total' => $wp_query->max_num_pages,
                        'prev_text' => '<i class="material-icons">chevron_left</i>',
                        'next_text' => '<i class="material-icons">chevron_right</i>',
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
                    endif;
                ?>
            </div>
        </div>
        <div class="section col l3">
            <?php if( is_active_sidebar( 'blog-sidebar' ) ):?>
                <?php get_search_form(); ?>
                <?php dynamic_sidebar( 'blog-sidebar' ); ?>
            <?php endif;?>
        </div>
    </div>
</div>

<?php get_footer(); ?>