<section id="home" class="hero is-fullheight" style="background-image: url(<?php echo get_the_post_thumbnail_url( $post->ID, 'full' ); ?>);">
    <div class="hero-head">
        <?php get_template_part( 'template-parts/section', 'header' ); ?>
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