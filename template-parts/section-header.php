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
                'theme_location' => 'top-menu',
                'menu_class' => 'navbar-items-container',
                'container' => false,
            )   
        );
    ?>
    <ul class="navbar-items-container navbar-social-container">
      <li class="menu-item menu-item-icon">
        <a target="_blank" href="https://instagram.com/alt_arquitetura">
          <i class="fab fa-lg fa-instagram"></i>
        </a>
      </li>
      <li class="menu-item menu-item-icon">
        <a target="_blank" href="https://pinterest.com/alt_arquitetura">
          <i class="fab fa-lg fa-pinterest"></i>
        </a>
      </li>
      <li class="menu-item menu-item-icon">
        <a target="_blank" href="https://www.facebook.com/alt_arquitetura-103873547635710">
          <i class="fab fa-lg fa-facebook"></i>
        </a>
      </li>
      <li class="menu-item menu-item-icon">
        <a target="_blank" href="https://www.linkedin.com/company/alt-arquitetura">
          <i class="fab fa-lg fa-linkedin"></i>
        </a>
      </li>
      <li class="menu-item menu-item-icon">
        <a target="_blank" href="https://www.behance.net/altarquitetura">
          <i class="fab fa-lg fa-behance"></i>
        </a>
      </li>
    </ul>
    </div>
  </div>
</header>