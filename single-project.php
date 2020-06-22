<?php get_header(); ?>

<div class="container">
    <section class="section columns-is-variable is-6 is-multiline">
        <div class="column is-full">
            <h1 class="projects-title"><?php the_title(); ?></h1>
        </div>
        <div class="column is-full">
        <?php foreach ( get_attached_media( 'image', $post ) as $img): ?>
            <img src="<?php echo wp_get_attachment_image_url( $img->ID, 'project-gallery' ); ?>"/>
        <?php endforeach; ?>
        </div>
    </section>
</div>

<?php get_footer(); ?>