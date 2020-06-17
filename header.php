<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <?php wp_head(); ?>
</head>
<body>
<div class="navbar-fixed">
    <nav>
        <div class="nav-wrapper bg-primary menu-link">
            <a href="#" data-target="mobile-menu" class="sidenav-trigger">
                <i class="material-icons">menu</i>
            </a>
            <?php 
                wp_nav_menu( 
                    array( 
                        'theme_location' => 'top-menu',
                        'menu_class' => 'menu-top-bar hide-on-med-and-down table-of-contents'  
                    )   
                );
            ?>
            <?php
                wp_nav_menu( 
                    array(
                        'theme_location' => 'mobile-menu',
                        'menu_class' => 'sidenav fullscreen',
                        'menu_id' => 'mobile-menu'
                    )
                );
            ?>
        </div>
    </nav>
</div>
    