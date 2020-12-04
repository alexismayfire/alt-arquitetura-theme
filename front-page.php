<?php get_header(); ?>

<?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>
<?php get_template_part( 'template-parts/home', 'hero' ); ?>
<main class="container">
    <?php get_template_part( 'template-parts/home', 'services' ); ?>
    <?php get_template_part( 'template-parts/home', 'projects' ); ?>
    <?php get_template_part( 'template-parts/home', 'contact' ); ?>
</main>
<?php endwhile; endif; ?>
<?php get_footer(); ?>