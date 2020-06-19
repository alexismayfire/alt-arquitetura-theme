<?php 

if ( have_posts() ): while ( have_posts() ): get_header(); the_post(); 
$hero_class_values = array(
    'background-image' => 'url(\''.get_the_post_thumbnail_url().'\')',
    'background-repeat' => 'no-repeat',
    'background-size' => 'cover',
    'background-attachment' => 'fixed',
    'filter' => 'brightness(0.5)',
);
array_walk($hero_class_values, function( $val, $key ) use( &$res) {
    $res .= "$key:$val;";
});

?>

<div class="full-width">
    <div class="project-hero-carousel" style="<?php echo $res; ?>"></div>
    <section class="hero project-hero-header is-fullheight">
        <div class="hero-head">
            <?php get_template_part( 'includes/section', 'header' ); ?>
        </div>
        <div class="hero-body">
            <div class="previous">
                <i class="fas fa-2x fa-chevron-left"></i>
            </div>
            <div class="next">
                <i class="fas fa-2x fa-chevron-right"></i>
            </div>
        </div>
        <div class="hero-footer">
            <div class="container has-text-centered">
                <h1 class="project-title"><?php the_title(); ?></h1>
            </div>
        </div>
    </section>
    <section class="section hero project-hero-content">
        <div class="container">
            <div class="columns">
                <div class="column is-half">
                    <?php the_content(); ?>
                </div>
                <div class="column is-half">
                <?php 
                    foreach( get_attached_media( 'image', $post ) as $img ): ?>
                    <img src="<?php echo wp_get_attachment_image_url( $img->ID, 'project-gallery' ); ?>"/>
                <?php endforeach;?>
                </div>
            </div>
        </div>
    </section>
</div>

<?php endwhile; endif; get_footer(); ?>