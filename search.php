<?php 

get_header(); 

global $wp_query;
$blog_posts = [];
$i = 0;

?>

<main class="container" id="blog">
    <section class="section columns is-variable is-6 is-multiline">
        <div class="column blog-header">
            <h1 class="section-title">blog</h1>
            <?php echo get_search_form(); ?>
        </div>
        <div class="column is-four-fifths-desktop">
        <?php if ( $wp_query->have_posts() ): ?>
            <h3 class="is-size-4 mb-4">Resultados (<?php echo $wp_query->found_posts; ?>) da busca para: <span class="has-text-weight-semibold"><?php the_search_query(); ?></span></h3>
            <div class="blog">
                <?php 
                
                $date_fmt = get_option( 'date_format' );
                $cat_base = get_option( 'category_base' );
                $max_posts = get_option( 'posts_per_page' );
                
                while ( $wp_query->have_posts() ):
                    $wp_query->the_post();

                    if ( $i < $max_posts ):
                        get_template_part( 'template-parts/blog', 'card' );
                    endif;

                    $id = $post->ID;
                    $img_id = get_post_thumbnail_id();
                    $feat_img = wp_get_attachment_image_src( $img_id, 'blog-large' );
                    $cats = wp_get_post_categories( $id, array( 'fields' => 'all' ) );
                    $cats_obj = [];

                    foreach ( $cats as $cat ) {
                        $cats_obj[] = array(
                            'name' => $cat->name,
                            'permalink' => $base ? '/'.$base.'/'.$cat->slug : '/'.$cat->slug,
                        );
                    }

                    $excerpt = get_the_excerpt( $id );
                    $excerpt = explode( ' ', $excerpt, 56 );
                    if ( count( $excerpt) === 56 ):
                        array_pop( $excerpt );
                        array_push( $excerpt, "[...]" );
                    endif;


                    $blog_posts[] = array(
                        'id' => $id,
                        'imgSrc' => $feat_img[0],
                        'title' => $post->post_title,
                        'date' => get_the_date( $date_fmt, $id ),
                        'categories' => $cats_obj,
                        'author' => get_author_name( $post->post_author ),
                        'excerpt' => join( " ", $excerpt ),
                        'permalink' => get_the_permalink( $id ),
                    );
                    
                    $i++;
                endwhile; 
                
                wp_localize_script( 'main', 'posts', $blog_posts );
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