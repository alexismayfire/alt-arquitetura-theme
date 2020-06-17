<?php
    $choices = array('cardsmall', 'cardsmall-wrapper', 'cardbig');
    $open_div = true;
    
    while ( $loop->have_posts() ) : $loop->the_post();
        $part = array_pop($choices);
        $used_part = $part;
        if( $open_div ): 
?>
<div class="project-carousel-item">
<?php
        endif;
        if( $part === 'cardsmall-wrapper' ):
            $part = 'cardsmall';
            $used_part = 'smallwrapper';
?>
    <div class="project-carousel-smallwrapper">
<?php
        endif;

        $open_div = false;
        get_template_part( 'includes/projects', $part );

        if( $used_part === 'cardsmall' ):
?>
    </div>
<?php
        endif;

        if( count($choices) === 0 ): 
            $choices = array('cardsmall', 'cardsmall-wrapper', 'cardbig');
            $open_div = true;
?>
</div>
<?php
        endif; 
    endwhile; 
?>