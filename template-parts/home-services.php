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