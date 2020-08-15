<?php

$args = array(
  'post_type' => 'attachment',
  'name' => sanitize_title('logo'),
  'posts_per_page' => 1,
  'post_status' => 'inherit',
);
$_header = get_posts( $args );
$header = $_header ? array_pop($_header) : null;
$header ? wp_get_attachment_url($header->ID) : '';

?>

<header class="navbar">
  <div class="container">
    <div class="navbar-brand">
        <a href="/">
        <img class="" src="<?php echo wp_get_attachment_url($header->ID); ?>" />
        </a>
        <span class="navbar-burger burger" data-target="navbarMenuHeroC">
        <span></span>
        <span></span>
        <span></span>
        </span>
    </div>
    <div id="navbarMenuHeroC" class="navbar-menu">
    <?php 
    wp_nav_menu( 
        array( 
            'theme_location' => 'main',
            'menu_class' => 'navbar-items-container',
            'container' => false,
        )   
    );
    
    $locations = get_nav_menu_locations();
    if ( isset( $locations['social-networks'] ) ): 
    ?>
      <ul class="navbar-items-container navbar-social-container">
    <?php
        $menu = get_term( $locations['social-networks'], 'nav_menu' );
        $menu_items = wp_get_nav_menu_items( $menu->term_id );

        foreach( $menu_items as $menu_item ): 
    ?>
        <li class="menu-item menu-item-icon">
          <a target="_blank" href="<?php echo $menu_item->url; ?>">
            <i class="fab fa-lg fa-<?php echo $menu_item->title; ?>"></i>
          </a>
        </li>
    <?php endforeach; ?>
      </ul>
    <?php endif; ?>
    </div>
  </div>
</header>