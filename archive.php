<?php 
get_header(); 
$max_posts = get_option( 'posts_per_page' );
?>

<main class="container" id="blog">
    <section class="section columns is-variable is-6 is-multiline">
        <div class="column is-full blog-header">
            <h1 class="section-title"><?php single_cat_title(); ?></h1>
            <?php echo get_search_form(); ?>
        </div>
        <div class="column is-four-fifths">
            <div class="blog" data-category-id="<?php the_category_ID( true ); ?>">
            <?php 

            $blog_posts = [];
            $i = 0;
                
            if ( have_posts() ):
                $date_fmt = get_option( 'date_format' );
                $cat_base = get_option( 'category_base' );
                $max_posts = get_option( 'posts_per_page' );

                while ( have_posts() ): 
                    the_post();

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
            endif;

            wp_localize_script( 'main', 'posts', $blog_posts );
            
            ?>
            </div>
        </div>
        <div class="column is-one-fifth blog-categories">
        <?php 
            if( is_active_sidebar( 'blog-sidebar' ) ):
                dynamic_sidebar( 'blog-sidebar' );
            endif;
        ?>
        </div>
    </section>
</div>

<?php 
wp_enqueue_script( 'blog' ); 
get_footer(); 
?>