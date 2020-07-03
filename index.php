<?php 
get_header(); 
$max_posts = get_option( 'posts_per_page' );
?>

<main class="container">
    <section class="section columns is-variable is-6 is-multiline">
        <div class="column blog-header">
            <h1 class="section-title"><?php single_post_title(); ?></h1>
            <?php echo get_search_form(); ?>
        </div>
        <div class="column is-four-fifths-desktop">
            <div class="blog">
                <?php 
                
                if ( have_posts() ): 
                    while ( have_posts() && $i < $max_posts ): 
                        the_post(); 
                        $i++;
                        get_template_part( 'template-parts/blog', 'card' );
                    endwhile; 
                endif; 
                
                ?>
            </div>
        </div>
        <div class="column is-one-fifth-desktop blog-categories">
        <?php 
            if( is_active_sidebar( 'blog-sidebar' ) ):
                dynamic_sidebar( 'blog-sidebar' );
            endif;
        ?>
        </div>
    </section>
</div>

<?php wp_enqueue_script( 'blog' ); get_footer(); ?>