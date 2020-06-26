<?php get_header(); ?>

<main class="container">
    <section class="section columns-is-variable is-6 is-multiline pb-0">
        <div class="column is-full">
            <h1 class="project-title"><?php the_title(); ?></h1>
        </div>
        <div class="column is-full">
            <ul class="project-detail">
                <li><span>Cliente</span><strong><?php the_field( 'projeto_cliente');?></strong></li>
                <li><span>Segmento</span><strong><?php $cat = get_the_terms( $post, 'segments' ); echo $cat[0]->name; ?></strong></li>
                <li><span>Área Construída</span><strong><?php echo get_field( 'projeto_area') ? get_field( 'projeto_area' ).' m<sup>2</sup>' : '-' ; ?></strong></li>
                <li><span>Status</span><strong><?php the_field( 'projeto_status');?></strong></li>
            </ul>
        </div>
        <div class="column is-full content">   
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
    <section class="section columns is-variable is-6 is-multiline pt-0" id="contato">
        <div class="column is-full">
            <h2 class="section-title"><span class="section-title-prepend">entre em </span>contato</h2>
        </div>
        <div class="column is-full">
            <p>Queremos conversar sobre os seus projetos.</p>
            <p>Use essa área para tirar dúvidas, enviar questões e comentários.</p>
        </div>
        <div class="column is-full form-wrapper">
            <?php echo do_shortcode( '[contact-form-7 id="98" title="Contato Home"]' ); ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>