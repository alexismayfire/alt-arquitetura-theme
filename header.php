<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    
    <?php wp_head(); ?>
</head>
<body>

<?php 
    if ( !is_front_page() ):
        get_template_part( 'template-parts/section', 'header' );
    endif;
?>