<?php get_header(); ?>

<main class="container" id="project">
    <section class="section columns-is-variable is-6 is-multiline is-relative pb-0">
        <div class="column is-full">
            <h1 class="section-title is-size-2 is-size-4-touch"><?php the_title(); ?></h1>
        </div>
        <div class="column is-full">
            <ul class="project-detail">
                <li><span>Cliente</span><strong><?php the_field( 'projeto_cliente' );?></strong></li>
                <li><span>Segmento</span><strong><?php $cat = get_the_terms( $post, 'segments' ); echo $cat[0]->name; ?></strong></li>
                <li><span>Área</span><strong><?php echo get_field( 'projeto_area') ? get_field( 'projeto_area' ).' m<sup>2</sup>' : '-' ; ?></strong></li>
                <li><span>Status</span><strong><?php the_field( 'projeto_status');?></strong></li>
            </ul>
        </div>
        <div class="column is-full content">   
        <?php 
        if ( have_posts() ): 
            while ( have_posts() ): 
                the_post();

                // Loop para pegar os posts relacionados
                $current_id = $post->ID;
                $related_args = array(
                    'post_type' => 'project',
                    'segments' => array( $cat[0]->name ),
                    'posts_per_page' => -1,
                    'post__notin' => array( $post->ID ),
                );
                $related = new WP_Query( $related_args );

                if( $related->have_posts() ) :
                    while ( $related->have_posts() ) :
                        $related->the_post();
                        
                        if ( $post->ID > $current_id ):
                            $prev = get_related_meta( $post->ID );
                        elseif ( $post->ID < $current_id ):
                            $next = get_related_meta( $post->ID );
                            break;
                        endif;
                    endwhile;

                    if ( ! $prev ):
                        $prev_id = $related->posts[$related->post_count - 1]->ID;
                        $prev = get_related_meta( $prev_id );
                    elseif ( ! $next ):
                        $next_id = $related->posts[0]->ID;
                        $next = get_related_meta( $next_id );
                    endif;
                    wp_reset_postdata();
                endif;
                
                the_content();

            endwhile;
        endif;

        $args = array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-projects.php',
            'hierarchical' => 0,
        );
        $pages = get_pages( $args );
        $base_page = 0;
        foreach ( $pages as $page ): 
            $base_page = $page->ID;
        endforeach;

        $footer_content = get_field( 'conteudo_rodape', $base_page );
        if ( $footer_content ):
            if ( strpos( $footer_content, '$titulo' ) ):
                $footer_content = str_replace( '$titulo', get_the_title(), $footer_content );
            endif;

            if ( strpos( $footer_content, '$cliente' ) ):
                $footer_content = str_replace( '$cliente', get_field( 'projeto_cliente' ), $footer_content );
            endif;
            
            if ( strpos( $footer_content, '$segmento_singular' ) ):
                $footer_content = str_replace( '$segmento_singular', strtolower( $cat[0]->name ), $footer_content );
            endif;

            if ( strpos( $footer_content, '$segmento_plural' ) ):
                $footer_content = str_replace( '$segmento_plural', get_field( 'segmento_plural', $cat[0] ), $footer_content );
            endif;

            echo $footer_content;
        
        endif;

        ?>
            <div class="columns is-full is-mobile has-text-centered is-hidden-desktop">
                <div class="column is-half-touch is-relative project-navmobile">
                    <a class="button is-dark project-navmobile-button" href="<?php echo $prev['permalink']; ?>" title="<?php echo $next['title']; ?>"><i class="fas fa-md fa-chevron-left"></i> Anterior</a>
                </div>
                <div class="column is-half-touch is-relative project-navmobile">
                    <a class="button is-dark project-navmobile-button" href="<?php echo $next['permalink']; ?>" title="<?php echo $next['title']; ?>">Próximo <i class="fas fa-md fa-chevron-right"></i></a>
                </div>
            </div>
        </div>
        <a class="project-nav project-nav-left is-hidden-touch is-hidden" href="<?php echo $prev['permalink']; ?>">
            <i class="fas fa-2x fa-chevron-left"></i>
            <div class="project-nav-featured is-relative">
                <img src="<?php echo $prev['img']; ?>" />
                <span class="has-text-weight-semibold"><?php echo $prev['title']; ?></span>
            </div>
        </a>
        <a class="project-nav project-nav-right is-hidden-touch is-hidden" href="<?php echo $next['permalink']; ?>">
            <i class="fas fa-2x fa-chevron-right"></i>
            <div class="project-nav-featured is-relative">
                <img src="<?php echo $next['img']; ?>" />
                <span class="has-text-weight-semibold"><?php echo $next['title']; ?></span>
            </div>
        </a>
    </section>
    <?php 
    $contact_title = get_field( 'contato_titulo', $base_page );
    $contact_content = get_field( 'contato_conteudo', $base_page );
    $contact_shortcode = get_field( 'contato_shortcode', $base_page );
    $contact_id = get_field( 'contato_id', $base_page );
    ?>
    <section class="section columns is-variable is-6 is-multiline pt-8" id="<?php echo $contact_id; ?>">
        <div class="column is-full">
        <?php
        $section_title = explode( ' ', $contact_title );
        if ( count( $section_title ) > 1 ): 
            $featured_title = array_pop( $section_title ); ?>
            <h2 class="section-title"><span class="section-title-prepend"><?php echo join( $section_title, ' ' ); ?></span><?php echo $featured_title ?></h2>
        <?php else: ?>
            <h2 class="section-title"><?php echo array_pop( $section_title ); ?></h2>
        <?php endif; ?>
        </div>
        <div class="column is-full"><?php echo $contact_content; ?></div>
        <div class="column is-full form-wrapper"><?php echo $contact_shortcode; ?></div>
    </section>
</main>

<?php get_footer(); ?>