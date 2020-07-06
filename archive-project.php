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
            <button class="button-filter is-active">Todos</a>
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
            <button class="button-filter" data-segment="<?php echo $taxonomy->term_id; ?>"><?php echo $taxonomy->name; ?></a>
        <?php endforeach; ?>
        </div>
        <div class="column is-full projects">
        <?php if ( have_posts() ): while ( have_posts() && $i < 6 ): the_post(); ?>
            <div class="projects-card" data-id="<?php echo $post->ID; ?>">
                <a href="<?php the_permalink(); ?>">
                    <figure>
                        <?php 
                        $img_id = get_post_thumbnail_id();
                        $feat_img_array = wp_get_attachment_image_src( $img_id, 'full' );
                        $orient = $feat_img_array[1] > $feat_img_array[2] ? 'landscape' : 'portrait';
                        $feat_img = wp_get_attachment_image_src( $img_id, 'project-'.$orient );
                        ?>
                        <img src="<?php echo $feat_img[0]; ?>" alt="<?php the_title(); ?>"/>
                    </figure>
                    <span><?php the_title(); ?></span>
                </a>
            </div>
        <?php $i++; endwhile; else: endif; wp_enqueue_script( 'projects' ); ?>
        </div>
    </section>
</div>

<?php get_footer(); ?>