<?php get_header(); ?>

<main class="container">
    <section class="section columns-is-variable is-6 is-multiline is-relative pb-0">
        <div class="column is-full">
            <h1 class="section-title is-size-2 is-size-4-touch"><?php the_title(); ?></h1>
        </div>
        <div class="column is-full">
            <ul class="project-detail">
                <li><span>Cliente</span><strong><?php the_field( 'projeto_cliente');?></strong></li>
                <li><span>Segmento</span><strong><?php $cat = get_the_terms( $post, 'segments' ); echo $cat[0]->name; ?></strong></li>
                <li><span>Área Construída</span><strong><?php echo get_field( 'projeto_area') ? get_field( 'projeto_area' ).' m<sup>2</sup>' : '-' ; ?></strong></li>
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
    <section class="section columns is-variable is-6 is-multiline pt-0" id="contato">
        <div class="column is-full">
            <h2 class="section-title"><span class="section-title-prepend">entre em </span>contato</h2>
        </div>
        <div class="column is-full">
            <p>Queremos conversar sobre os seus projetos.</p>
            <p>Use essa área para tirar dúvidas, enviar questões e comentários.</p>
        </div>
        <div class="column is-full form-wrapper">
            <?php echo do_shortcode( '[contact-form-7 id="98" title="Contato Home"]' ); ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>