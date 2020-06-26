<?php get_header(); ?>

<?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>
<section id="home" class="hero is-fullheight" style="background-image: url(<?php the_post_thumbnail_url(); ?>);">
    <div class="hero-head">
        <?php get_template_part( 'includes/section', 'header' ); ?>
    </div>
    <div class="hero-body">
        <div class="container has-text-centered">
        <?php if ( get_field( 'home-cta_titulo' ) ): ?>
            <h1 class="title home-cta-title"><?php the_field( 'home-cta_titulo' ); ?>
        <?php endif; ?>
        <?php if ( get_field( 'home-cta_tagline' ) ): ?>
            <h2 class="subtitle home-cta-tagline"><?php the_field( 'home-cta_tagline' ); ?></h2>
        <?php endif; ?>
        <?php if ( get_field( 'home-cta_link' ) ): $link = get_field( 'home-cta_link' ); ?>
            <a class="button button-cta is-dark" href="<?php echo esc_url( $link['url'] ); ?>">
                <?php the_field( 'home-cta_link-text' ); ?> <i class="fas fa-md fa-chevron-right"></i>
            </a>
        <?php endif; ?>
        </div>
    </div>
</section>
<main class="container">
    <section class="section columns is-variable is-6 is-multiline" id="<?php the_field( 'quem-somos_id' ); ?>">
        <div class="column is-full is-two-fifths-widescreen">            
        <?php 
            $section_title = explode( ' ', get_field( 'quem-somos_titulo' ));
            if ( count($section_title) > 1 ): ?>
                <h2 class="section-title"><span class="section-title-prepend"><?php echo array_shift( $section_title ); ?></span><?php echo join( ' ', $section_title ); ?></h2>
            <?php else: ?>
                <h2 class="section-title"><?php the_field( 'quem-somos_titulo' ); ?></h2>
            <?php endif; ?>
            <?php the_field('quem-somos_conteudo'); ?>
        </div>
        <?php 
        
        $members = get_field('equipe');
        
        if ( $members ): ?>
            <div class="column is-full is-three-fifths-widescreen">
                <div class="team">
                <?php foreach( $members as $post ): ?>
                    <?php setup_postdata( $post ); ?>
                    <div class="team-item">
                        <img src="<?php the_post_thumbnail_url( 'team' ); ?>" alt="<?php the_title(); ?>" class="team-item-image" />
                        <span class="team-item-name"><?php the_title(); ?></span>
                        <span class="team-item-title"><?php the_content(); ?></span>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>
    </section>
    <?php endwhile; else: endif; ?>
    <section class="section columns is-variable is-6 is-multiline" id="<?php the_field( 'servicos_id' ); ?>">
        <div class="column is-full">
        <?php 
            $section_title = explode( ' ', get_field( 'servicos_titulo' ));
            if ( count($section_title) > 1 ): ?>
                <h2 class="section-title"><span class="section-title-prepend"><?php echo array_shift( $section_title ); ?></span><?php echo join( ' ', $section_title ); ?></h2>
            <?php else: ?>
                <h2 class="section-title"><?php the_field( 'servicos_titulo' ); ?></h2>
            <?php endif; ?>
        </div>
        <?php
        
        $services = get_field('servicos_itens');

        if ( $services ): foreach ( $services as $post ): ?>
            <div class="column is-half service-item">
                <div class="service-item-title">
                    <i class="fas fa-md fa-<?php the_field('servico_icone'); ?>"></i>
                    <h3><?php the_title(); ?></h3>
                </div>
                <div class="service-item-content">
                    <?php echo $post->post_content; ?>
                </div>
            </div>
        <?php endforeach; wp_reset_postdata(); endif; ?>
    </section>
    <section class="section columns is-variable is-6 is-multiline">
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

        $projects = get_field('projetos_itens');
        $i = 0;

        if ( $projects ): foreach ( $projects as $post ):
            $segment = array_shift( get_the_terms( $post->ID, 'segments' ) );
            $permalink = get_permalink( $post->ID );

            if ( $i === 0): ?>
                <div class="column is-hidden-touch is-one-quarter carousel-item">
                    <div class="carousel-item-content" data-post-id="<?php echo $post->ID; ?>">
                        <div class="carousel-item-title"><?php the_title(); ?></div>
                        <span><? echo $segment->name; ?></span>
                    </div>
                </div>
            <?php elseif ( $i === 1 ): ?>
            <div class="column is-half-desktop carousel-card">
                <a href="<?php echo $permalink; ?>">
                    <img class="carousel-card-image" src="<?php the_post_thumbnail_url( 'project-large' ); ?>" alt="<?php the_title(); ?>" />
                    
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
                <div class="carousel-item-content is-hidden-touch " data-post-id="<?php echo $post->ID; ?>">
                    <div class="carousel-item-title"><?php the_title(); ?></div>
                    <span><? echo $segment->name; ?></span>
                </div>
                <a href="/projetos" class="button is-dark">Ver Todos <i class="fas fa-md fa-chevron-right"></i></a>
            </div>
            <?php else: break; endif; $i++; ?>
        <?php endforeach; wp_reset_postdata(); endif; ?>
    </section>
    <section class="section columns is-variable is-6 is-multiline" id="<?php the_field( 'contato_id' ); ?>">
        <div class="column is-full">
        <?php 
        
        $section_title = explode( ' ', get_field( 'contato_titulo' ));
        if ( count( $section_title ) > 1 ): 
            $featured_title = array_pop( $section_title ); ?>
            <h2 class="section-title"><span class="section-title-prepend"><?php echo join( $section_title, ' ' ); ?></span><?php echo $featured_title ?></h2>
        <?php else: ?>
            <h2 class="section-title"><?php the_field( 'contato_titulo' ); ?></h2>
        <?php endif; ?>
        </div>
        <div class="column is-full">
            <?php the_field('contato_conteudo'); ?>
        </div>
        <div class="column is-full form-wrapper">
            <?php the_field('contato_shortcode'); ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>