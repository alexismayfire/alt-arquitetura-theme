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