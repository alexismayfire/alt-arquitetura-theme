<section class="section columns is-variable is-6 is-multiline is-relative">
    <div class="column is-full">
    <?php 
        $section_title = explode( ' ', get_field( 'projetos_titulo' ));
        if ( count($section_title) > 1 ): ?>
            <h2 class="section-title"><span class="section-title-prepend"><?php echo array_shift( $section_title ); ?></span><?php echo join( ' ', $section_title ); ?></h2>
        <?php else: ?>
            <h2 class="section-title"><?php the_field( 'projetos_titulo' ); ?></h2>
        <?php endif; ?>
    </div>
    <?php

    $projects = get_field( 'projetos_itens' );
    $featured_projects = [];
    $i = 0;

    if ( $projects ): foreach ( $projects as $post ):
        $terms = get_the_terms( $post->ID, 'segments' );
        $segment = array_shift( $terms );
        $permalink = get_permalink( $post->ID );
        $alternative_featured = get_field( 'projeto_destaque' );
        if ( $alternative_featured ):
            $image = $alternative_featured['sizes']['project-large'];
        else:
            $image = get_the_post_thumbnail_url( $post->ID, 'project-large' );
        endif;

        $featured_projects[] = array(
            'id' => $post->ID,
            'title' => get_the_title(),
            'segment' => $segment->name,
            'image' => $image,
            'slug' => $permalink,
        );

        if ( $i === 0): ?>
            <div class="column is-one-quarter carousel-item">
                <div class="carousel-item-navleft is-hidden-desktop" data-post-id="<?php echo $post->ID; ?>"><i class="fas fa-2x fa-chevron-left"></i></div>
                <div class="carousel-item-content is-hidden-touch" data-post-id="<?php echo $post->ID; ?>">
                    <div class="carousel-item-title"><?php the_title(); ?></div>
                    <span><? echo $segment->name; ?></span>
                </div>
            </div>
        <?php elseif ( $i === 1 ): ?>
        <div class="column is-half-desktop carousel-card">
            <a href="<?php echo $permalink; ?>">
                <img class="carousel-card-image" src="<?php echo $image; ?>" alt="<?php the_title(); ?>" />
            </a>
            <a href="<?php echo $permalink; ?>">
                <div class="carousel-card-content">
                    <h4 class="carousel-card-title"><?php the_title(); ?></h4>
                    <span><? echo $segment->name; ?></span>
                </div>
            </a>
        </div>
        <?php elseif ( $i === 2 ): ?>
        <div class="column is-one-quarter carousel-item">
        <div class="carousel-item-navright is-hidden-desktop" data-post-id="<?php echo $post->ID; ?>"><i class="fas fa-2x fa-chevron-right"></i></div>
            <div class="carousel-item-content is-hidden-touch" data-post-id="<?php echo $post->ID; ?>">
                <div class="carousel-item-title"><?php the_title(); ?></div>
                <span><? echo $segment->name; ?></span>
            </div>
            <a href="/projetos" class="button is-dark">Ver Todos <i class="fas fa-md fa-chevron-right"></i></a>
        </div>
        <?php else: endif; $i++; ?>
    <?php endforeach; wp_reset_postdata(); endif; ?>
    <?php wp_localize_script( 'main', 'featuredProjects', $featured_projects ); ?>
</section>