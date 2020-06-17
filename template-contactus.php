<?php 

/*
Template Name: Contact Us
*/

?>

<?php get_header(); ?>

<div class="container">
    <h1><?php the_title(); ?></h1>
    
    <div class="row">
        <div class="col s6">
            <?php get_template_part( 'includes/section', 'content' ); ?>
        </div>
        <div class="col s6">
            <p>This is the contact page!</p>
        </div>
    </div>
   
</div>

<?php get_footer(); ?>