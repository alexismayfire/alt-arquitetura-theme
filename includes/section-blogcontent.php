<?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>
    <span class="caption-text">
        <?php echo get_the_date(); ?> 
    </span>
    <?php the_content(); ?>

    <?php
        $tags = get_the_tags();
        foreach($tags as $tag):
    ?>
        <a class="chip" href="<?php echo get_tag_link($tag->term_id); ?>">
            <?php echo $tag->name; ?>
        </a>
    <?php endforeach; ?>

    <?php comments_template(); ?>

<?php endwhile; else: endif; ?>