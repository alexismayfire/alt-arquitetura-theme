<?php get_header(); ?>

<?php $comments = get_comments( array( 'post_id' => $post->ID ) ); ?>

<main class="container">
    <section class="section columns pt-1 is-centered is-variable is-6 is-multiline">
    <?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>
        <?php if (has_post_thumbnail()): ?>
        <!-- TODO: Change alt to use image title, if it has one -->
        <div class="column is-full is-two-thirds-widescreen">
            <img src="<?php the_post_thumbnail_url( 'blog-large' ); ?>" alt="<?php the_title(); ?>" />
        </div>
        <?php endif; ?>
        <div class="column is-full is-two-thirds-widescreen blog-post">
            <h1 class="title has-text-primary"><?php the_title(); ?></h1>
            <div class="blog-post-header">
                <p class="is-inline"><?php echo get_the_date(); ?> | <?php echo get_post_author_tag(); ?>
                <p class="has-text-weight-semibold"><?php get_template_part( 'template-parts/categories', 'links' ) ?></p>
            </div>
            <div class="content">
                <?php the_content(); ?>
            </div>
        </div>
        <div class="column is-full is-two-thirds-widescreen blog-comments">
        <?php comments_template(); ?>
        </div>
    <?php endwhile; endif; ?>
    </section>
</main>
<?php get_footer(); ?>