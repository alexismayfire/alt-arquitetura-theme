<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title><?php 
        $name = get_bloginfo( 'name' );
        $after = is_front_page() ? get_bloginfo( 'description' ) : wp_title( '|', false, 'left' );
    
        echo $name . $after;
    ?></title>
    
    <?php wp_head(); ?>
</head>
<body>

<?php 
    if ( !is_front_page() ):
        get_template_part( 'includes/section', 'header' );
    endif;
?>