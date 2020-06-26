<?php
$categories = get_the_terms( $post->ID, 'category' );
$css_classes = join( " ", array(
    "has-text-primary",
    "has-text-weight-semibold",
));

if ( $categories && !is_wp_error( $categories ) ): ?>
    <p class="is-inline">
    <?php foreach( $categories as $category ): ?>
        <a href="<?php echo get_term_link( $category->slug, 'category' ); ?>" rel="tag" class="<?php echo $css_classes; ?>"><?php echo $category->name; ?></a>
    <?php endforeach; ?>
    </p>
<?php endif; ?>