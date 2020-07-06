<?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>

    <img 
        src="<?php the_post_thumbnail_url(); ?>"
        alt="ALT Arquitetura"
    />

    <?php the_content(); ?>

<?php endwhile; else: endif; ?>