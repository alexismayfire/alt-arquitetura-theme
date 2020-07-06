<?php
$categories = get_the_terms( $post->ID, 'category' );
$css_classes = join( " ", array(
    "has-text-primary",
    "has-text-weight-semibold",
));
$i = 0;

if ( $categories && !is_wp_error( $categories ) ): 
    foreach( $categories as $category ): ?>
    <a href="<?php echo get_term_link( $category->slug, 'category' ); ?>" rel="tag" class="<?php echo $css_classes; ?>"><?php echo $category->name; ?></a><?php echo $i < count($categories) - 1 ? ", " : ""; ?>
<?php $i++; endforeach; endif; ?>