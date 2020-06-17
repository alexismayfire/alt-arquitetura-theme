<div class="project-card-small">
    <img src="<?php the_post_thumbnail_url( 'project-small' ); ?>" alt="<?php the_title(); ?>" />
    <div class="card-content">
        <h4 class="card-title"><?php the_title(); ?></h4>
        <a class="card-button bg-primary waves-effect btn" href="<?php the_permalink(); ?>">Ver</a>
    </div>
</div>