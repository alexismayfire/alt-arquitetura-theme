<?php get_header(); ?>

<div class="container">
    <?php if (has_post_thumbnail()): ?>
        <!-- TODO: Change alt to use image title, if it has one -->
        <div class="row">
            <img 
                class="responsive-img col s12"
                src="<?php the_post_thumbnail_url( 'blog-large' ); ?>"
                alt="<?php the_title(); ?>"
            />
        </div>
    <?php endif; ?>
    <h1><?php the_title(); ?></h1>
    <?php get_template_part( 'includes/section', 'blogcontent' ); ?>
    <?php wp_link_pages(); ?>
</div>

<?php get_footer(); ?>