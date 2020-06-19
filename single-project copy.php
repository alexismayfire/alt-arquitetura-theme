<?php get_header(); ?>

<div class="container">
    <div class="row">
        <div class="col s6">
            <?php if (has_post_thumbnail()): ?>
                <!-- TODO: Change alt to use image title, if it has one -->
                <div class="row">
                    <img 
                        class="responsive-img"
                        src="<?php the_post_thumbnail_url( 'blog-large' ); ?>"
                        alt="<?php the_title(); ?>"
                    />
                </div>
            <?php endif; ?>
            <h3 class="header-text"><?php the_title(); ?></h1>
            <?php get_template_part( 'includes/section', 'blogcontent' ); ?>
            <?php wp_link_pages(); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>