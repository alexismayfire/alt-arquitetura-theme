<?php
/**
* Template Name: Projetos
*/

get_header(); 

?>

<main class="container">
    <section class="section columns is-variable is-6 is-multiline">
        <div class="column is-half">
            <h1 class="section-title"><?php echo the_title() ?></h1>
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
        wp_localize_script( 'projects', 'categories', $terms );
        
        foreach( $terms as $taxonomy ): ?>
            <button class="button-filter" data-segment="<?php echo $taxonomy->term_id; ?>"><?php echo $taxonomy->name; ?></a>
        <?php endforeach; ?>
        </div>
        <?php 
        $args = array(
            'post_type' => 'project',
            'posts_per_page' => 6,
        );
        $the_query = new WP_Query( $args );
        $i = 0;
        $sizes = array(
            0 => 0,
            1 => 0,
            2 => 0,
        );
        $height = 0;

        if ( $the_query->have_posts() ):
            foreach( $the_query->posts as $post ):
                $img_id = get_post_thumbnail_id();
                $feat_img_array = wp_get_attachment_image_src( $img_id, 'full' );
                $orient = $feat_img_array[1] > $feat_img_array[2] ? 'landscape' : 'portrait';
                $feat_img = wp_get_attachment_image_src( $img_id, 'project-'.$orient );
                $sizes[$i % 3] += $feat_img[2];
                $i++;
            endforeach;
            foreach ( $sizes as $size ):
                if ( $size > $height ):
                    $height = $size;
                endif;
            endforeach;
        endif;

        $i = 0;
        ?>
        <div class="column is-full projects" style="height: <?php echo $height.'px'; ?>;">
        <?php if ( $the_query->have_posts() ): while ( $the_query->have_posts() && $i < 6 ): $the_query->the_post(); ?>
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
</main>

<?php get_footer(); ?>