<?php

define( 'THEME_VERSION', '1.0.1');

require_once get_template_directory() . '/core/post-types.php';
require_once get_template_directory() . '/core/rest-api.php';
require_once get_template_directory() . '/core/scripts.php';

// Theme options
add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'widgets' );
add_theme_support( 'title-tag' );
add_theme_support(
    'html5',
    array(
        'search-form',
        'comment-form',
        'comment-list',
        'script',
        'style',
    )
);

// Menus
register_nav_menus( 
    array(
        'top-menu' => 'Menu Desktop',
        'mobile-menu' => 'Menu Mobile',
    )
);

// Media
add_image_size( 'team', 160, 235, true );
add_image_size( 'blog-large', 1000, 400, true );
add_image_size( 'blog-small', 300, 200, true );
add_image_size( 'project-gallery', 1500, 600, true );
add_image_size( 'project-landscape', 700, 500, false );
add_image_size( 'project-portrait', 500, 700, false );
add_image_size( 'project-large', 430, 330, true );
add_image_size( 'project-small', 300, 150, true );

function my_custom_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'project-large' => __( 'Projeto Home' ),
    ) );
}
add_filter( 'image_size_names_choose', 'my_custom_sizes' );

function filter_site_upload_size_limit( $size ) {
    return 5 * 1024 * 1024;
}
add_filter( 'upload_size_limit', 'filter_site_upload_size_limit', 20 );

// Sidebars
function custom_sidebars() {
    register_sidebar( array(
        'name' => 'Blog Sidebar',
        'id' => 'blog-sidebar',
        'before_title' => '<h4 class="card-title">',
        'after_title' => '</h4>'
    ) );
}
add_action( 'widgets_init', 'custom_sidebars' );

// Plugins
add_filter( 'wpcf7_autop_or_not', '__return_false' );

add_action( 'wp_head', function() { ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-170483133-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-170483133-1');
    </script>
<?php } );

// Load template tags
require_once get_template_directory() . '/template-tags.php';

// Comments Custom Walker
require_once get_template_directory() . '/class-alt-walker-comment.php';

?>