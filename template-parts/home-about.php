<section class="section columns is-variable is-6 is-multiline" id="<?php the_field( 'quem-somos_id' ); ?>">
    <div class="column is-full is-two-fifths-widescreen">            
    <?php 
    $section_title = explode( ' ', get_field( 'quem-somos_titulo' ) );
    if ( count( $section_title ) > 1 ): ?>
        <h2 class="section-title"><span class="section-title-prepend"><?php echo array_shift( $section_title ); ?></span><?php echo join( ' ', $section_title ); ?></h2>
    <?php else: ?>
        <h2 class="section-title"><?php the_field( 'quem-somos_titulo' ); ?></h2>
    <?php endif; ?>
    <?php the_field('quem-somos_conteudo'); ?>
    </div>
    <?php $members = get_field('equipe'); if ( $members ): ?>
    <div class="column is-full is-three-fifths-widescreen">
        <div class="team">
        <?php foreach( $members as $post ): ?>
            <?php setup_postdata( $post ); ?>
            <div class="team-item has-text-centered-desktop">
                <img src="<?php the_post_thumbnail_url( 'team' ); ?>" alt="<?php the_title(); ?>" class="team-item-image" />
                <div class="team-item-meta">
                    <span class="team-item-name"><?php the_title(); ?></span>
                    <span class="team-item-title"><?php the_content(); ?></span>
                </div>
            </div>
        <?php endforeach; wp_reset_postdata(); ?>
        </div>
    </div>
    <?php endif; ?>
</section>