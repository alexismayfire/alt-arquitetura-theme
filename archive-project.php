<?php get_header(); ?>

<div class="container">
    <section class="section columns is-variable is-6 is-multiline">
        <div class="column is-half">
            <h1 class="projects-title"><?php 
                $post_type = get_post_type_object( get_post_type( get_the_ID() ) );
                echo $post_type->label;
            ?></h1>
        </div>
        <div class="column is-half projects-categories">
            <span>Filtrar por:</span>
            <a class="button-filter is-active">Todos</a>
        <?php
        
        $cat_args = array(
            'order_by' => 'term_id',
            'order' => 'ASC',
            'hide_empty' => true,
        );

        $terms = get_terms( 'segments', $cat_args );
        // $posts = array();
        $i = 0;
        
        wp_localize_script( 'projects', 'categories', $terms );
        
        foreach( $terms as $taxonomy ): ?>
            <a class="button-filter"><?php echo $taxonomy->name; ?></a>
        <?php endforeach; ?>
        </div>
        <div class="column is-full projects">
        <?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>
            <div class="projects-card">
                <a href="<?php the_permalink(); ?>">
                    <figure>
                        <img src="<?php echo the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>"/>
                    </figure>
                </a>
            </div>
        <?php endwhile; else: endif; wp_enqueue_script( 'projects' ); ?>
        </div>
    </section>
</div>

<?php get_footer(); ?>