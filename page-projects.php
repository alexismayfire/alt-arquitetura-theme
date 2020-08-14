<?php
/**
* Template Name: Projetos
*/

get_header(); 

$cat_args = array(
    'order_by' => 'term_id',
    'order' => 'ASC',
    'hide_empty' => true,
);

$terms = get_terms( 'segments', $cat_args );        
wp_localize_script( 'main', 'categories', $terms );

?>

<main class="container" id="projects">
    <section class="section columns is-variable is-6 is-multiline">
        <div class="column is-half">
            <h1 class="section-title"><?php echo the_title() ?></h1>
        </div>
        <div class="column is-half projects-categories">
            <span>Filtrar por:</span>
            <button class="button-filter is-active is-hidden-touch">Todos</button>
        <?php foreach( $terms as $taxonomy ): ?>
            <button class="button-filter is-hidden-touch" data-segment="<?php echo $taxonomy->term_id; ?>"><?php echo $taxonomy->name; ?></button>
        <?php endforeach; ?>
            <div class="select is-hidden-desktop">
                <select name="categories">
                    <option value="">Todos</option>
                <?php foreach( $terms as $taxonomy ): ?>
                    <option value="<?php echo $taxonomy->term_id; ?>"><?php echo $taxonomy->name; ?></option>
                <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?php 
        $args = array(
            'post_type' => 'project',
            'posts_per_page' => -1,
        );
        $the_query = new WP_Query( $args );
        $i = 0;
        $sizes = array(
            0 => 0,
            1 => 0,
            2 => 0,
        );
        $height = 0;

        if ( $the_query->have_posts() && $i < 6 ):
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
        <?php 
        $projects = [];
        if ( $the_query->have_posts() ): 
            while ( $the_query->have_posts() ): 
                $the_query->the_post();

                $title = $post->post_title;
                // TODO: match segments to previous query of posts that belongs to this term?
                $segments = [];
                foreach( get_the_terms( $post->ID, 'segments' ) as $segment ):
                    $segments[] = $segment->term_id;
                endforeach;
                $permalink = get_the_permalink( $post->ID );
                $img_id = get_post_thumbnail_id();
                $feat_img_array = wp_get_attachment_image_src( $img_id, 'full' );
                $orient = $feat_img_array[1] > $feat_img_array[2] ? 'landscape' : 'portrait';
                $feat_img = wp_get_attachment_image_src( $img_id, 'project-'.$orient );

                $projects[] = array(
                    'id' => $post->ID,
                    'title' => $title,
                    'segment' => $segments,
                    'permalink' => $permalink,
                    'imgSrc' => $feat_img[0],
                    'imgWidth' => $feat_img[1],
                    'imgHeight' => $feat_img[2],
                );

                if ( $i < 6 ):
        ?>
            <div class="projects-card" data-id="<?php echo $post->ID; ?>">
                <a href="<?php echo $permalink; ?>">
                    <figure>
                        <img src="<?php echo $feat_img[0]; ?>" alt="<?php echo $title; ?>"/>
                    </figure>
                    <span><?php echo $title; ?></span>
                </a>
            </div>
        <?php 
                endif;
                $i++;
            endwhile; 
        endif;
        wp_localize_script( 'main', 'projects', $projects );
        ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>