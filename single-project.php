<?php get_header(); ?>

<div class="container">
    <section class="section columns-is-variable is-6 is-multiline">
        <div class="column is-full">
            <h1 class="projects-title"><?php the_title(); ?></h1>
        </div>
        <div class="column is-full">
            <ul class="project-detail">
                <li>Cliente <strong><?php the_field( 'projeto_cliente');?></strong></li>
                <li>Segmento <strong><?php the_field( 'projeto_segmento');?></strong></li>
                <li>Área Construída <strong><?php echo get_field( 'projeto_area') ? get_field( 'projeto_area' ).' m<sup>2</sup>' : '-' ; ?></strong></li>
                <li>Status <strong><?php the_field( 'projeto_status');?></strong></li>
            </ul>
        </div>
        <div class="column is-full">   
        <?php 
        if ( have_posts() ): 
            while ( have_posts() ): 
                the_post();
                the_content();
            endwhile;
        endif;
        ?>
        </div>
    </section>
</div>

<?php get_footer(); ?>